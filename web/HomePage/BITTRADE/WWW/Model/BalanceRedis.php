<?php
class BalanceRedis extends BaseModelBase{

    private $member;

    function __construct($dbconfname=null) {
        parent::__construct($dbconfname);
    }
    /*
     * 실행
     * @param url param
     */
    public function getExecute($param){
        $loginstatus = 0;
        
        if(isset($param['cmd']) && $param['cmd']=='sum' && isset($param['market'])){
        // 레디스 갱신전 프로시저 통해서 포인트 재연산
            $call_proc = $this->execUpdate(array(
                        'sql'=>$this->res->ctrl->sql_call_proc,
                        'mapkeys'=>$this->res->ctrl->mapkeys_call_proc,
                        'rowlimit'=>1
                    ), $param);
        }
        
        if(!isset($param['ac']) || !$param['ac']){
            $param['ac'] = 'websum';
        }
        
        

        //redis 서버
        $this->initRedisServer($this->res->config['redis']['host'],
                $this->res->config['redis']['port'],null,
                $this->res->config['redis']['db_member']);
        $session_expire = ((int)$this->res->config['session']['cache_expire'] * 60);
        $session_key = Utils::getCookie($this->res->config['session']['sskey']);
        $tmpmember = $this->getRedisData($session_key);
        if(!$tmpmember){
            return array("result" => -998,
                    "success"=>false,
                    "error"=>$this->res->lang->logincheck->expire);
        }
        $redismember = json_decode($tmpmember,TRUE);
        $resultmember = $redismember;
        $mbsearchkey = '';
        
        //다른 사용자키로 쿠키 공격을 대비
        if($session_key){
            $loginstatus = 1;
            $tmp = @explode("-", $session_key);
            $mbsearchkey = $tmp[0];
            if(isset($redismember['login_ip']) && $redismember['login_ip'] != Utils::getClientIP()){
                $loginstatus = -10; //다른 곳에서 로그인
                Utils::setCookie($this->res->config['session']['sskey'],"",0);
            }
        }
        if($loginstatus != 1){
            return array("result" => -999,
                    "success"=>false,
                    "error"=>$this->res->lang->logincheck->expire);
        }
        
        
        //balance
        if(isset($param['ismem']) && $param['ismem']=='y'){
            $balancekey = $mbsearchkey . '-balance';
            $balancedata = $this->getRedisData($balancekey);
            $balancedata = json_decode($balancedata, true);
        }else{
            $balancedata = $this->execLists(array(),$param);

            //balance update
            if($mbsearchkey){
                $balancekey = $mbsearchkey . '-balance';
                $tmpbalancedata = $this->getRedisData($balancekey);
                $jsonbalancedata = json_encode($balancedata);
                if($tmpbalancedata != $jsonbalancedata){
                    $this->delRedisData($balancekey);
                    $this->setRedisData($balancekey,$jsonbalancedata,$session_expire);
                }
            }
        }
   
        $tmp = 0;
        if($redismember){
            if(isset($redismember['login_ip']) && $redismember['login_ip'] != Utils::getClientIP()){
                $loginstatus = -10; //다른 곳에서 로그인
            }
        }
        $this->setSesstionTimeExtension();
        $tmp = 1;
        
        // 현재가를 얻어온다
        
        $this->initRedisServer($this->res->config['redis']['host'],
        $this->res->config['redis']['port'],null,
        $this->res->config['redis']['db_ticker']);
            
        $redis_key = 'ticker-main-master';
         
        $currentprice = $this->redis->hGetAll($redis_key);
        if(!$currentprice){
            return array("result" => -998,
                    "success"=>false,
                    "error"=>$this->res->lang->logincheck->expire);
        }
        
        //$param['mb_no'] = $this->member['mb_no'];
        //$this->setMoniterLogFile(11,$currentprice);
        // 코인별 거래 매수 금액 읽기 
              
        $buylists = $this->execLists(
            array(
                    'sql'=>$this->res->ctrl->sql_select_buy_sum,
                    'mapkeys'=>$this->res->ctrl->mapkeys_select_buy_sum                      
                ),
            array()
        );
        
        //$this->setMoniterLogFile(1,$buylists);
        // 평균평가액 기타 등등을 계산한다.
        $total_asset_held = 0;
        $total_buy_price = 0;
        $total_sell_price = 0;
        
        $total_evaluation_price = 0;
        // 총 코인 보유수
        $total_coin_held = 0;
        // 총 평가 손익률 
        $total_profit_rate = 0;
        
        foreach ($balancedata as $value){
            //$this->setMoniterLogFile(2,$value);
            
            if("krw" == $value['po_type']){
                   $asset_info[$value['po_type']] = array('po_type'=>$value['po_type'],'total'=>$value['total']);
                   $total_asset_held = $total_asset_held + $value['total'];
                   continue;
            }
                
            if($buylists[0]['result'] == 0) continue;
            
            foreach($buylists as $buy){
      
                //$this->setMoniterLogFile(3,$buy);           
                if($value['po_type'] != $buy['po_type']) continue;
            
                
                $cSql = str_replace("{currency}", $value['po_type'], $this->res->ctrl->sql_select_uncomplete_count);
                $po_type = $value['po_type'];
                $remotedb = $this->res->ctrl->database->$po_type;    
          
                $this->setMoniterLogFile(11,array('sql'=>$cSql));
                
                
                $ubcomplete_data = $this->execRemoteLists(
                    $remotedb,
                    array(
                            'sql'=>$cSql,
                            'mapkeys'=>$this->res->ctrl->mapkeys_select_uncomplete_count                      
                        ),
                    array()
                );
                 
                //평균매수가
                $average_buy_price = ($buy['po_buy_price']-$buy['po_sell_price'])/ $value['total'];
                
                $this->setMoniterLogFile(12,$ubcomplete_data);
                
                $jsondata = json_decode($currentprice["krw_".$value['po_type']], true);
                //$this->setMoniterLogFile(3,$jsondata);      
                //평가금액 
                $evaluation_price = $jsondata['last']* $value['total'];
                // 총평가 금액
                $total_evaluation_price = $total_evaluation_price + $evaluation_price;
                // 수익율 
                $profit_rate = (($average_buy_price*$value['total'])/$evaluation_price)*100;
                //$this->setMoniterLogFile(1,array('po_type'=>$value['po_type'],'mb_no'=> $this->member['mb_no'], 'profit_rate'=>$profit_rate,'average_buy_price'=>$average_buy_price,'total'=>$value['total'],'evaluation_price'=>$evaluation_price));
                // 총 보유 자산
                $total_asset_held = $total_asset_held + $evaluation_price;
                // 총 매수 금액
                $total_buy_price = $total_buy_price + ($buy['po_buy_price'] - $buy['po_sell_price']);
                //$total_sell_price = $total_sell_price + $buy['po_sell_price'];
                //총 코인 보유수
                $total_coin_held = $total_coin_held + $value['total'];
                        
                $asset_info[$value['po_type']] = array('po_type'=>$value['po_type'],'po_point'=>$buy['po_buy_price'] - $buy['po_sell_price'],'total'=>$value['total'],'last'=>$jsondata['last']
                        ,'average_buy_price'=>$average_buy_price,'evaluation_price'=>$evaluation_price,'profit_rate'=>$profit_rate,'uncomplete_count'=>$ubcomplete_data[0]['totalcount']);
             }
         }
         
        // 총평균매수가
        if(0 != $total_coin_held){
            $total_average_buy_price = ($total_buy_price)/ $total_coin_held;
        
            $total_profit_rate = (($total_average_buy_price*$total_coin_held)/$total_evaluation_price)*100;
        
            // 총평가 손익
            $total_profit = $total_buy_price - $total_evaluation_price;
        }
        //총 보유자산
        $asset_info['krw']['total_asset_held'] = $total_asset_held;
        //총매수 금액
        $asset_info['krw']['total_buy_price'] = $total_buy_price;
        //총 평가 금액
        $asset_info['krw']['total_evaluation_price'] = $total_evaluation_price;
                
        $asset_info['krw']['total_profit'] = $total_profit;
        //총 평가 수익률
        $asset_info['krw']['total_profit_rate'] = $total_profit_rate;
                
        $this->setMoniterLogFile(4,$asset_info);
        
        return array('success'=> true,'member'=>  base64_encode(json_encode($resultmember)),'balance'=>  base64_encode(json_encode($balancedata)),'loginstatus'=>$loginstatus ,'tmp'=>$tmp,'asset_info'=>base64_encode(json_encode($asset_info)));
        
        //return array('success'=> true,'member'=>  base64_encode(json_encode($resultmember)),'balance'=>  json_encode($balancedata),'loginstatus'=>$loginstatus ,'tmp'=>$tmp);
    }
       
    
    private function setLogFile($errorcode,$param){
        $log = new Logging();
        $log->lfile('../WebApp/Debug/BalanceRedis_Exception-'.'-'.date('Ymd',time()).'.txt');
        $log->lwrite(__CLASS__ . "[".$errorcode."]:".' '.json_encode($param).' - '.Utils::getClientIP());
        $log->lclose();
    }
    
    private function setMoniterLogFile($errorcode,$param){
        $log = new Logging();
        $log->lfile('../WebApp/Debug/BalanceRedis_MoniterLog-'.'-'.date('Ymd',time()).'.txt');
        $log->lwrite(__CLASS__ . "[".$errorcode."]:".' '.json_encode($param).' - '.Utils::getClientIP());
        $log->lclose();
    }
    
    private function setSesstionTimeExtension(){
        $session_expire = ((int)$this->res->config['session']['cache_expire'] * 60);
        
        $get_cookie = Utils::getCookie($this->res->config['session']['sskey']);
        
        if($get_cookie){
            Utils::setCookie($this->res->config['session']['sskey'],  $get_cookie , $session_expire);
            $mb_key_tmp = explode('-', $get_cookie);
            $get_balance_key = $mb_key_tmp[0].'-balance';
            $this->initRedisServer($this->res->config['redis']['host'],$this->res->config['redis']['port'],null,$this->res->config['redis']['db_member']);
            $this->redis->expire($get_cookie,$session_expire);
            $this->redis->expire($get_balance_key,$session_expire);
        }
                
    }



    function __destruct(){

    }



}
