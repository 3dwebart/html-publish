<?php
class TradeOrderReg extends BaseModelBase{

    private $tradeserv;

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
    
    
    private $gHeader;
    public function getHeader($header)
    {
       $this->gHeader = $header;
    }
    
    public function getExecute($param){
        set_time_limit(5);                

       
        //초기에 모니터링을 위해 저장
        //$this->setMoniterLogFile(1,$param);
        
        $returnarr = array('result'=>'success', 'errorcode'=>0, 'orderid'=>0);
        $result = array('result'=>0); //db
           
        /***********************************************************************************
         * HTTP HEADER 값을 체크한다.
        ***********************************************************************************/
         // Header의 Authorization을 체크한다.
        if(!isset($this->gHeader['Authorization'])) {
            return array(
               "result" => 'fail',
               "errorcode"=>-1001,
               "error"=>$this->res->lang->validator->required);           
        }
                
        // Header의 Timestamp를 체크한다.
        if(!isset($this->gHeader['Timestamp'])) {
            return array(
               "result" => 'fail',
               "errorcode"=> -1002,
               "error"=>$this->res->lang->validator->required);               
        }
        /***********************************************************************************/
        
        
        /***********************************************************************************
        * API 호출 파라미터를 체크한다
        ***********************************************************************************/
        if( !isset($param['apikey']) || !isset($param['currency']) || !isset($param['action']) || 
            !isset($param['price']) || !isset($param['amount'])) {
            
            $errorcode = -2001;
            $this->setLogFile($errorcode,$param);
            return array(
               "result" => 'fail',
               "errorcode"=> $errorcode,
               "error"=>$this->res->lang->validator->required);
        } 
        /***********************************************************************************/

        
        /***********************************************************************************
         * API 호출 파라미터 값을 체크한다
        ***********************************************************************************/
        if( !$param['apikey'] || !$param['currency'] || !$param['action'] || 
            (float)$param['price'] == 0 || (float)$param['amount'] == 0){
            
            $errorcode = -2101;
            $this->setLogFile($errorcode,$param);            
            return array(
                "result" => 'fail',
                "errorcode"=> $errorcode,
                "error"=>$this->res->lang->validator->required);
        }      
        /***********************************************************************************/
        

        /***********************************************************************************
         *  API Key, Authorization 값으로 회원 체크 및 유효한 API 인지 체크
        ***********************************************************************************/
        $akey = $param['apikey'];
        $auth = $this->gHeader['Authorization'];
        $timestamp = $this->gHeader['Timestamp'];
                    
        // 레디스에 키가 있는지 체크
        $redis_value = $this->getRedisAPIKey('API-'.$akey);
        if( $redis_value ) {
            $mb_info = json_decode($redis_value, true);
            $reMemInfo['mb_no'] = $mb_info['mb_no'];
            $reMemInfo['mb_id'] = $mb_info['mb_id'];
            $reMemInfo['mk_secret'] = $mb_info['mk_secret'];
        } else {
            //key가 없으면, 파라미터 akey값을 비교하여 회원 정보를 가져온다.
            $reMemInfo = $this->getMemberInfo($akey);
            if(!$reMemInfo || $reMemInfo['result'] < 1) {
                return array(
                   "result" => 'fail',
                   "errorcode"=> -3001,                
                   // ADD Ment
                   "error"=>$this->res->lang->validator->required);            
            }
            
            // 레디스 키값 생성
            $this->setRedisAPIKey($akey, $reMemInfo);
        }
              
        // apikey값이 존재하는 회원이면 회원의 securetkey와  header의 authorization값을 비교
        if(!$this->checkAuthorization($reMemInfo['mk_secret'], $timestamp, $auth)) {
             return array(
               "result" => 'fail',
               "errorcode"=> -3002,
                // ADD Ment
               "error"=>$this->res->lang->validator->required);
        }      
        /***********************************************************************************/
        
        
        $param['mb_no'] = $reMemInfo['mb_no'];  
        $param['mb_id'] = $reMemInfo['mb_id'];
        $member['mb_id'] = $param['mb_id'];
               
        $od_action  = $param['action'];// sell, buy 구분값
        $currency   = $param['currency'];
        $channel    = strtoupper('krw_'.$currency);
        
        //사용할 remotedb 생성
        $remotedb = $this->res->ctrl->database->$currency;
        //currency에 맞춰서 테이블 변경
        $cSql = str_replace("{currency}", $currency, $this->res->ctrl->sql);
        
 
        /***********************************************************************************
         *  유효한 마켓 검사
        ***********************************************************************************/
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
            
            $errorcode = -3101;
            $this->setLogFile($errorcode,$param);
                        
            return array( 
                "result" => 'fail', 
                "errorcode"=>$errorcode, 
                "error"=>$this->res->lang->trade->unknowMarket);
        }
        
        if($mastermarket && ( $mastermarket['itUse'] == 'P' || $mastermarket['itUse'] == 'R' ) ){
                        
            $errorcode = -3102;
            $this->setLogFile($errorcode,$param);
            
            return array( 
                "result" => 'fail', 
                "errorcode"=>$errorcode, 
                "error"=>$this->res->lang->trade->marketfrozen);
        }
        /***********************************************************************************/

        
        $od_market_price    = (float)$param['price'];
        $od_amount          = (float)$param['amount'];
        $od_total_cost      =  (float)$od_amount *  (float)$od_market_price;
        $param['totalcost'] = round($od_total_cost);
     
        /***********************************************************************************
         *  호가 단위 및 수수료
        ***********************************************************************************/
        $fee = JsonConfig::get('configtradefee');
        $tradetypekey = $channel;
        $trade_krw_min_limit = 1000.0;
        $trade_coin_min_amount = 0.0001;
        $call_unit_krw = 500.0;
        $call_unit_coin = 0.0001;
        $trade_maker_fee = 0.0;
        $trade_tracker_fee = 0.0;
        if( $fee && isset($fee[$tradetypekey]) && 
            isset($fee[$tradetypekey]['cfOrderMinKrw']) && 
            isset($fee[$tradetypekey]['cfOrderMinCoin']) && 
            isset($fee[$tradetypekey]['cfCallUnitKrw']) && 
            isset($fee[$tradetypekey]['cfCallUnitCoin']) && 
            isset($fee[$tradetypekey]['cfTrMarketmakerFee']) && 
            isset($fee[$tradetypekey]['cfTrTrackerFee']) ){
            $trade_krw_min_limit = $fee[$tradetypekey]['cfOrderMinKrw'];
            $trade_coin_min_amount = $fee[$tradetypekey]['cfOrderMinCoin'];
            $call_unit_krw = $fee[$tradetypekey]['cfCallUnitKrw'];
            $call_unit_coin = $fee[$tradetypekey]['cfCallUnitCoin'];
            $trade_maker_fee = $fee[$tradetypekey]['cfTrMarketmakerFee'];
            $trade_tracker_fee = $fee[$tradetypekey]['cfTrTrackerFee'];
        }

        //  BTC 최소 거래량 체크 
        if($od_action == 'buy'){
            if( $od_amount < (float)$trade_coin_min_amount){
                return array(
                "result" => 'fail',
                "errorcode"=> -3201,
                "error"=>str_replace("{0}",$trade_coin_min_amount, $this->res->lang->trade->minimumbtcbuycost));
            }
        }else if($od_action == 'sell'){
            if( $od_amount < (float)$trade_coin_min_amount){
                return array(
                "result" => 'fail',
                "errorcode"=> -3202, 
                "error"=>str_replace("{0}",$trade_coin_min_amount, $this->res->lang->trade->minimumbtccost));
            }
        }else{
            $errorcode = -2201;
            $this->setLogFile($errorcode,$param);
            
            return array(
                "result" => 'fail',
                "errorcode"=>$errorcode,
                "error"=>$this->res->lang->validator->required);
        }
           
        if($od_total_cost < $trade_coin_min_amount){
            return array(
                "result" => 'fail',
                "errorcode" => -3203, 
                "error"=>str_replace("{0}",$trade_coin_min_amount,$this->res->lang->trade->minimumbtcmarketcost));
        }

        // 1코인당 주문가격과 해당 코인의 호가단위를 비교하여, 주문가격이 호가단위로 들어오지 않을 경우
        // 에러 출력
        $remainder = (int)(($od_market_price) % ($call_unit_krw));       
        if($remainder > 0){
            return array(
                "result" => 'fail',
                "errorcode"=> -3204, 
                "error"=>str_replace("{0}",$call_unit_krw,$this->res->lang->trade->btcmarketcostunit));
        }        
        
        /***********************************************************************************
        * 발란스 체크
        ***********************************************************************************/
        //$this->_getBalanceSum($param['mb_no'], 'API');        
        $balances = $this->setBalanceUpdate($param['mb_no']);
        if(!$balances){
            $mbsearchkey = 'MB'.$member['mb_no'];
            $balancekey = $mbsearchkey . '-balance';
            $balancedata = $this->getRedisData($balancekey);
            $balances = json_decode($balancedata, true);
        }
        

        $balance_krw_poss = 0.0000;
        $balance_coin_poss = 0.0000;
        
        for($t=0;$t<count($balances);$t++){
            $tmp = $balances[$t];
            if($od_action =='buy'){
                if(isset($tmp['po_type']) && $tmp['po_type'] == 'krw'){
                    
		    // 사용가능한 KRW
                    $balance_krw_poss = round((float)$tmp['poss']);
                    

                    // 사용중 원화 UPDATE
                    $balances[$t]['on_trade'] = $this->toConvert($tmp['on_trade'] + $od_total_cost, 8);
                    $balances[$t]['poss'] = $this->toConvert($tmp['poss'] - $od_total_cost, 8);
                }
            }else if($od_action == 'sell'){
                if(isset($tmp['po_type']) && $tmp['po_type'] == $currency){
                    // 사용가능한 COIN
                    $balance_coin_poss = (float)$tmp['poss'];

                    // 사용중 코인 UPDATE
                    $balances[$t]['on_trade'] = $this->toConvert($tmp['on_trade'] + $od_amount, 8);
                    $balances[$t]['poss'] = $this->toConvert($tmp['poss'] - $od_amount, 8);
                }
            }
        }
        
        //파라미터로 받은 값 예외처리
        $param['rcoin'] = 0;
        $param['rdate'] = '';

        // 수수료 셋팅
        $param['feerate'] = 0;
        $param['rfee'] = 0;
        
        
        /***********************************************************************************
         * 내가 사려고 할 때 - BTC
        ***********************************************************************************/
        $resultcomplet = ''; //체결여부 'PART':부분 'OK':완료
        if($od_action =='buy'){  

            if( $balance_krw_poss == 0 || !isset($param['totalcost']) || $balance_krw_poss < $od_total_cost){
                return array(
                    "result" => 'fail',
                    "errorcode"=> -4001,
                    "error"=>$this->res->lang->trade->lowkrwbalance);
            } 
            
            /*********************************
            * balance update
            ********************************/
            $balance_result = $this->balanceUpdateRetry($od_action, $od_total_cost, $param['mb_no']);
            
            if($balance_result < 0){
                return array(
                    "result" => 'fail',
                    "errorcode"=>-5555,
                    "error"=>$this->res->lang->trade->balancefail);
                exit;
            }
            
        /***********************************************************************************
         * 내가 팔려고 할 때 - COIN
        ***********************************************************************************/
        }else if($od_action == 'sell'){
            //주문BTC과 내 발란스 비교         
            if($balance_coin_poss == 0 || $od_amount == 0 || $balance_coin_poss < $od_amount){
                return array(
                "result" => 'fail',
                "errorcode"=> -4002,
	        "error"=>$this->res->lang->trade->lowkrwbalance);
            }
            
            /*********************************
            * balance update
            ********************************/
            $balance_result = $this->balanceUpdateRetry($od_action, $od_amount, $param['mb_no'], $currency);
            
            if($balance_result < 0){
                return array(
                    "result" => 'fail',
                    "errorcode"=>-5555,
                    "error"=>$this->res->lang->trade->balancefail);
                exit;
            }
        }
        
        /*********************************
         * 레디스 발란스 업데이트
         *********************************/
        $session_expire = (int)$this->res->config['session']['cache_expire'] * 60;
        $balancekey = 'MB'.$param['mb_no'].'-balance';
        $tmpbalancedata = $this->getRedisData($balancekey);
        $jsonbalancedata = json_encode($balances);
        if($tmpbalancedata != $jsonbalancedata){
            $this->delRedisData($balancekey);
            $this->setRedisData($balancekey,$jsonbalancedata,$session_expire);
        }

        /***********************************************************************************
         * RPC
        ***********************************************************************************/

        try{
            $this->initTradeServer($mastermarket['itServerSignIp'].':'.$mastermarket['itServerSignPort']);
        } catch (RPCException $ex) {
            $param['status'] = 'CAN';
            return array(
                    "result" => 'fail',
                    "errorcode"=> -9998,
                    "error"=>$this->res->lang->tradeapi->exception);
            exit;
        }


        $is_possible_buysell = 1;
        if($is_possible_buysell==1 || $is_possible_buysell==0){
            $param['status'] = $resultcomplet = 'WAIT';
        }else{
            $param['status'] = $resultcomplet = 'REQ';
        }
        
        // 거래량
        $param['feerate'] = $this->toConvert($od_amount, 8);

        //값 등록
        //lock현상으로 등록이 안될 경우.
        for($att=0;$att<3;$att++){
            $result = $this->execRemoteUpdate(
                    $remotedb, 
                    array(
                        'sql'=>$cSql,
                        'mapkeys'=>$this->res->ctrl->mapkeys,
                        'rowlimit'=>10
                    )
                    , $param);
            
            if((int)$result['result']==0){
                sleep(1);
            }else{
                $att=100;
            }
        }

        if( (int)$result['result'] > 0 ){
            if($is_possible_buysell > -1 ){

                $rcpres = $this->tradeserv->tradeStart($result['result']);
                $resjson = json_decode($rcpres,TRUE);
                if(isset($resjson['error']) && $resjson['error']){
                    $param['status'] = 'CAN';
                    $result = $this->execRemoteUpdate(
                            $remotedb, 
                            array(
                                'sql'=>$cSql,
                                'mapkeys'=>$this->res->ctrl->mapkeys,
                                'rowlimit'=>10
                            )
                            , $param);

                    return array(
                    "result" => 'fail',
                    "errorcode"=> -9999,                 
                    "error"=>$this->res->lang->tradeapi->exception);
                }
            }
        }
  
	//$returnarr['result'] = $result['result'];
        //$returnarr['complete'] = $resultcomplet; //체결완료 여부
        
        $returnarr['result'] = 'success';
        $returnarr['errorcode'] = 0;
        $returnarr['orderid'] = $result['result'];
      
        // 2018-02-20
        // 마켓 메이킹에서 주문 후 OrderArrange()를 수행하여, TradeModerOrderCancel.php를 사용하는데
        // TradeModerOrderCancel에서 아래 tradePointSyncStart를 사용하므로 이곳에서는 사용할 필요 없음
        // tradePointSyncStart를 사용하는 곳은 TradeModerOrderCancel, TradeAllOrderCancel에서만 사용하면됨
        // 단, 마켓 메이킹이 아닌 주문취소를 별도로 수행할 경우에는 사용해야 함
        
        /*if( (int)$returnarr['errorcode'] == 0 ){
            $rcpres = $this->tradeserv->tradePointSyncStart(json_encode([$param['mb_no']]),$returnarr['orderid']);
            $resjson = json_decode($rcpres,TRUE);
            $trade_cancel_result['result'] = $resjson;
        }*/
         
            
       //$this->_getBalanceSum($param['mb_no'], 'API');
       
        //내 발란스 redis update
        $this->_setBalanceEventNoti($member['mb_id']);
        
        //$this->setMoniterLogFile(2,$param);
        return $returnarr;
    }

    //현재 사용 안함 2017-08-21 KIMJH
    //즉시 판/구매가 가능한지 여부(1:체결가능 0:호가메모리 or 디비에러 -1:체결불가)
    private function _getIsPossMarketTrade($mytype,$ordercost,$channel){

        //내가 살때
        if($mytype=='buy'){
            $marketdata = $this->execLists(array(
                'sql'=>$this->res->ctrl->sql_sell_view,
                'mapkeys'=>array(),
                'rowlimit'=>1
            ), array());
        //내가 팔때
        }else if($mytype=='sell'){
            $marketdata = $this->execLists(array(
                'sql'=>$this->res->ctrl->sql_buy_view,
                'mapkeys'=>array(),
                'rowlimit'=>1
            ), array());
        }
       

       
        if(!$marketdata || !isset($marketdata[0])){
            return -22; //DB에러
        }
        
        if( !isset($marketdata[0]['price']) || !$marketdata[0]['price']){
            return 0; //초기값이 없음
        }
        

        if($mytype=='buy'){
            if((float)$marketdata[0]["price"] <= (float)$ordercost){
                return 1;
            }
        }else if($mytype=='sell'){
            if((float)$marketdata[0]["price"] >= (float)$ordercost){
                return 1;
            }
        }

        return -1;
    }

    
    //합산
    private function _getBalanceSum($mb_no, $ac){
        
        /*
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
        $json = file_get_contents($this->res->config['url']['site'].'/getbalance/server/cmd-sum/ac-'.$ac, false, $context);
        $balancedata = json_decode($json,TRUE);
        if(isset($balancedata['balance'])){
            return $balancedata['balance'];
        }
        return array();
        */

        $rebalance = $this->execRemoteUpdate(
                    $this->res->ctrl->database->balance, 
                    array(
                        'sql'=>$this->res->ctrl->sql_balancesum,
                        'mapkeys'=>$this->res->ctrl->mapkeys_balancesum                      
                    )
                    , array('mb_no'=>$mb_no,
                            'ac'=>$ac)
                );
        
        return $rebalance;
    }
    

    private function _setBalanceEventNoti($mb_id){
        //내 발란스 이벤트 처리
        $this->initRedisServer($this->res->config['redis']['host'],
            $this->res->config['redis']['port'],null,
            $this->res->config['redis']['db_member_noti']);
        $data = $this->getRedisData($mb_id);
        if(!$data){
            $this->setRedisData($mb_id,"1",3600);
        }
    }
    
    private function setLogFile($errorcode,$param){
        $log = new Logging();
        $log->lfile('../WebApp/Debug/TradeException-API-ORDER-'.$param['currency'].'-'.Utils::formatDateTime().'.txt');
        $log->lwrite(__CLASS__ . "[".$errorcode."]:".' '.json_encode($param).' - '.Utils::getClientIP());
        $log->lclose();
    }
    
    private function setMoniterLogFile($errorcode,$param){
        $log = new Logging();
        $log->lfile('../WebApp/Debug/TradeMoniterLog-API-ORDER-'.$param['currency'].'-'.Utils::formatDateTime().'.txt');
        $log->lwrite(__CLASS__ . "[".$errorcode."]:".' '.json_encode($param).' - '.Utils::getClientIP());
        $log->lclose();
    }

//    // 메일링 - 거래 체결
//    private function getHTMLEmailBody($skin_html_file,$user=array('name'=>'','link_url'=>'')){
//        $html = file_get_contents('../WebApp/ViewHTML/'.$skin_html_file.'.html');
//        $html = str_replace("{user_name}", $user['name'],$html);
//        $html = str_replace("{site_name}", $this->res->config['html']['title'],$html);
//        $html = str_replace("{trade_type_desc}", $user['trade_type_desc'],$html);
//        $html = str_replace("{trade_type}", $user['trade_type'],$html);
//        $html = str_replace("{od_temp_value}", $user['od_temp_value'],$html);
//        $html = str_replace("{od_market_price}", $user['od_market_price'],$html);
//        $html = str_replace("{od_total_cost}", $user['od_total_cost'],$html);
//        $html = str_replace("{link_url}", $this->res->config['url']['site'].$user['link_url'],$html);
//        return $html;
//    }

    // 2017-08-22 KIMJH
    // api key  와 header authorization값으로 처리
    
    public function getMemberInfo($apikey)
    {     
        $res = $this->execLists(
                array(
                   'sql'=>$this->res->ctrl->sql_apikey,
                   'mapkeys'=>$this->res->ctrl->mapkeys_apikey,
                   'rowlimit'=>1
                )
               , array('apikey'=>$apikey)
           );
       if($res &&  count($res) == 1){
           return $res[0];
       }
       return null;
    }
    
    public function checkAuthorization($secretkey, $timestamp, $authorization) {
  
        $temp = $timestamp.$secretkey;
        $hash = hash('sha512', $temp);
   
        
        if($hash === $authorization)
            return TRUE;
        return FALSE;
    }
    
    public function setBalanceUpdate($mb_no)
    {
        $loginstatus = 0;
   
        $mbsearchkey = '';
        $mbsearchkey = 'MB'.$mb_no;
        $session_expire = ((int)$this->res->config['session']['cache_expire'] * 60);
         
        //초기화
        $this->remotedb = null;
        $this->remotedbSlave = null;
        $balancedata = $this->execRemoteLists(
            $this->res->ctrl->database->balance,
            array(
                    'sql'=>$this->res->ctrl->sql_balanceupdate,
                    'mapkeys'=>$this->res->ctrl->mapkeys_balanceupdate                      
                ),
            array('mb_no'=>$mb_no)
        );
 
        //잔액을 못 가져올 경우
        if(isset($balancedata[0]['result']) && $balancedata[0]['result'] < 1){
            return null;
        }
        
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
    
    public function getRedisAPIKey($key)
    {
        $this->initRedisServer($this->res->config['redis']['host'],
            $this->res->config['redis']['port'],null,
            $this->res->config['redis']['db_member']);
        
        return $this->getRedisData($key);
    }
    
    public function setRedisAPIKey($key, $mb_info)
    {
        $api_key = 'API-' . $key;
        $json_data = json_encode($mb_info);
        $session_expire = ((int)$this->res->config['session']['cache_expire'] * 60);
        
        $this->initRedisServer($this->res->config['redis']['host'],
            $this->res->config['redis']['port'],null,
            $this->res->config['redis']['db_member']);
        
        $this->setRedisData($api_key,$json_data,$session_expire);
    }
    
    private function balanceUpdateRetry($od_action, $amount, $mb_no, $po_type=''){
        $resultCode = -1;
        
        for($i=0;$i<3;$i++){
            if($od_action == 'buy'){
                $result = $this->execRemoteUpdate(
                    $this->res->ctrl->database->balance,
                    array(
                        'sql'=>$this->res->ctrl->sql_balance_update_krw,
                        'mapkeys'=>$this->res->ctrl->mapkeys_balance_update_krw
                    ),
                    array(
                        'po_on_trade'=>$this->toConvert($amount, 8),
                        'mb_no'=>$mb_no
                    )
                );
            }else if($od_action == 'sell'){
                $result = $this->execRemoteUpdate(
                    $this->res->ctrl->database->balance,
                    array(
                        'sql'=>$this->res->ctrl->sql_balance_update,
                        'mapkeys'=>$this->res->ctrl->mapkeys_balance_update
                    ),
                    array(
                        'po_on_trade'=>$this->toConvert($amount, 8),
                        'mb_no'=>$mb_no,
                        'po_type'=>$po_type
                    )
                );
            }
            if((int)$result['result']==0){
                sleep(1);
            }else{
                $resultCode = 1;
                $i = 99;
            }
        }
        
        return $resultCode;
    }
    
    // 자바스크립트 toFixed와 같은역활
    private function toConvert($number, $decimals){
        return number_format($number, $decimals, ".", "");
    }

    function __destruct(){

    }
}
