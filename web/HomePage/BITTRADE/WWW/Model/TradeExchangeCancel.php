<?php
class TradeExchangeCancel extends BaseModelBase{

    function __construct($dbconfname=null) {
        parent::__construct($dbconfname);

    }
    
    private function initTradeServer($serverinfo=null){
        if(!$serverinfo){
            $serverinfo = '127.0.0.1';
        }
        try {
            $this->tradeserv = new jsonRPCClient('http://'.$serverinfo, false);
        }
        catch (Exception $e) {
            new RPCException("<p>Trade Server error! ".$e);
        }
    }

    // 거래소 판/구매 물량 취소 이벤트
    // 151026
    public function getExecute($param){

        if( !isset($param['od_id'])  || !isset($param['od_pay_status']) || !isset($param['currency'])  ){
            return array(
                "result" => -5100,
                "success"=>false,
                "error"=>$this->res->lang->validator->required);
        }

        // redis member info get
        $member = json_decode($this->getMemberDataFromRedis());
        if(!isset($member->mb_id) || !$member->mb_id){
            return array(
                "result" => -5001,
                "success"=>false,
                "error"=>$this->res->lang->logincheck->notLogin);
        }
        
        $channel = strtoupper('krw_'.$param['currency']);

        /******************************
         *  유효한 마켓 검사
         ****************************/
        $master = JsonConfig::get('exchangemarket');
        
        $mastermarket = null;
        
        $is_search_channel = false;
        foreach($master as $key => $value) {
            if($channel == $key){
                $is_search_channel = true;
                $mastermarket = $value;
                break;
            }
        }

        if(!$is_search_channel){
            $errorcode = -5004;
            $this->setLogFile($errorcode,$param);    
            return array( "result" => $errorcode, "success"=>false, "error"=>$this->res->lang->trade->unknowMarket);
        }
        
        if($mastermarket && ( $mastermarket['itUse'] == 'P' || $mastermarket['itUse'] == 'R' ) ){
                        
            $errorcode = -5005;
            $this->setLogFile($errorcode,$param);
            
            return array( "result" => $errorcode, "success"=>false,  "error"=>$this->res->lang->trade->marketfrozen);
        }
        
        
        
        
        if(isset($resjson['error']) && $resjson['error']){
            return array(
                    "result" => -998,
                    "success"=>false,
                    "error"=>$this->res->lang->tradeapi->exception);
        }else{
            // 취소할 정보 select
            /*$trade_cancel_data_result = $this->execLists(
                array(
                    'sql'=>$this->res->ctrl->sql_cancel_list,
                    'mapkeys'=>$this->res->ctrl->mapkeys_cancel_list,
                    'rowlimit'=>1
                )
                , array('od_id'=>$param['od_id'])
            );
            $trade_cancel_data = $trade_cancel_data_result[0];*/
            
            // 취소 처리
            $trade_cancel_result = $this->execUpdate(
                array(
                    'sql'=>$this->res->ctrl->sql,
                    'mapkeys'=>$this->res->ctrl->mapkeys,
                    'rowlimit'=>1
                )
                , array('od_pay_status'=>$param['od_pay_status']
                    ,'od_id'=>$param['od_id'])
            );

            /**********************************
            * RPC
            *********************************/

            try{
                $this->initTradeServer($mastermarket['itServerSignIp'].':'.$mastermarket['itServerSignPort']);
            } catch (RPCException $ex) {
                $param['od_pay_status'] = 'CAN';
                return array(
                        "result" => -9998,
                        "success"=>false,
                        "token"=>$token,
                        "error"=>$this->res->lang->tradeapi->exception);
                exit;
            }
            

            /***************************************
            //내 발란스
            ***************************************/
            if( (int)$trade_cancel_result['result'] >= 0 ){
                $rcpres = $this->tradeserv->tradePointSyncStart([$member->mb_no],$param['od_id']);
                $resjson = json_decode($rcpres,TRUE);
                $trade_cancel_result['result'] = $resjson;
            }
            /*$balances = $this->_getBalance($member->mb_no);
            $remainder = $trade_cancel_data['od_temp_coin'] - $trade_cancel_data['od_receipt_coin'];
            
            for($t=0;$t<count($balances);$t++){
                $tmp = $balances[$t];
                if($trade_cancel_data['od_action'] =='buy'){
                    if(isset($tmp['po_type']) && $tmp['po_type'] == 'krw'){
                        // 사용중 원화 UPDATE
                        $balances[$t]['on_trade'] = $this->toConvert($tmp['on_trade'] - ($trade_cancel_data['od_market_price'] * $remainder), 8);
                        $balances[$t]['poss'] = $this->toConvert($tmp['poss'] + ($trade_cancel_data['od_market_price'] * $remainder), 8);
                        
                        // balance update
                        $this->execRemoteUpdate(
                            $this->res->ctrl->database->balance,
                            array(
                                'sql'=>$this->res->ctrl->sql_balance_update_krw,
                                'mapkeys'=>$this->res->ctrl->mapkeys_balance_update_krw
                            ),
                            array(
                                'po_on_trade'=>$this->toConvert($trade_cancel_data['od_market_price'] * $remainder, 8),
                                'mb_no'=>$member->mb_no
                            )
                        );
                    }
                }else if($trade_cancel_data['od_action'] == 'sell'){
                    if(isset($tmp['po_type']) && $tmp['po_type'] == $param['currency']){
                        // 사용중 코인 UPDATE
                        $balances[$t]['on_trade'] = $this->toConvert($remainder, 8);
                        $balances[$t]['poss'] = $this->toConvert($tmp['poss'] + $remainder, 8);
                        
                        // balance update
                        $this->execRemoteUpdate(
                            $this->res->ctrl->database->balance,
                            array(
                                'sql'=>$this->res->ctrl->sql_balance_update,
                                'mapkeys'=>$this->res->ctrl->mapkeys_balance_update
                            ),
                            array(
                                'po_on_trade'=>$this->toConvert($remainder, 8),
                                'mb_no'=>$member->mb_no
                            )
                        );
                    }
                }
            }*/
            
            /*********************************
            * 레디스 발란스 업데이트
            *********************************/
            /*$session_expire = (int)$this->res->config['session']['cache_expire'] * 60;
            $balancekey = 'MB'.$member->mb_no.'-balance';
            $tmpbalancedata = $this->getRedisData($balancekey);
            $jsonbalancedata = json_encode($balances);
            if($tmpbalancedata != $jsonbalancedata){
                $this->delRedisData($balancekey);
                $this->setRedisData($balancekey,$jsonbalancedata,$session_expire);
            }*/

        }
        
//        $balance = $this->_getBalanceSum($channel);

        //notice
        $this->_setTradeEventNoti($param);
        
        return $trade_cancel_result;
    }

    private function _getBalanceSum($market){
        $cookies = '';
        if (isset($_COOKIE)) {
            foreach ($_COOKIE as $name => $value) {
                $name = htmlspecialchars($name);
                $value = htmlspecialchars($value);
                $cookies .= "$name=$value; ";
            }
        }
        $opts = array(
            'http'=>array(
              'method'=>"GET",
              'header'=>"Accept-language: en\r\n" .
                        "Cookie: '.$cookies.'"
            )
        );
        $context = stream_context_create($opts);
        $json = file_get_contents($this->res->config['url']['site'].'/getbalance/server/market-'.$market.'ac-ordercan', false, $context);
        $balancedata = json_decode($json,TRUE);
        if(isset($balancedata['balance'])){
            return $balancedata['balance'];
        }
        
        return array();
    }
    
    private function _getBalance($mb_no){
        $loginstatus = 0;
   
        $mbsearchkey = '';
        $mbsearchkey = 'MB'.$mb_no;
        $session_expire = ((int)$this->res->config['session']['cache_expire'] * 60);
         
        //balance
        $balancedata = $this->execRemoteLists(
                $this->res->ctrl->database->balance,
                array(
                        'sql'=>$this->res->ctrl->sql_balance,
                        'mapkeys'=>$this->res->ctrl->mapkeys_balance
                    ),
                array('mb_no'=>$mb_no)
            );
 
        
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
        
        return $balancedata;
    }
    
    private function _setTradeEventNoti($param){
        $channel = '';
        if($param['currency']){
            $channel = 'krw_'.$param['currency'];
        }
        $this->initRedisTradeServer($this->res->config['redis_trade']['host'],
        $this->res->config['redis_trade']['port'],null,
        $this->res->config['redis_trade']['db_ticker']);
        $this->setRedisTradeData('trading-complet-event-'.$channel,1);
    }
    
    // 자바스크립트 toFixed와 같은역활
    private function toConvert($number, $decimals){
        return number_format($number, $decimals, ".", "");
    }
    

    function __destruct(){

    }
}
