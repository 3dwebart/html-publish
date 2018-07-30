<?php
class TradeExchange extends BaseModelBase{

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

    /*
     * 구매하기전에 내 발란스를 체크한다.
     * @param url param
     */
    public function getExecute($param){
        set_time_limit(5);
        
        //초기에 모니터링을 위해 저장
       // $this->setMoniterLogFile(1,$param);
        

        $returnarr = array('result'=>0,'token'=>'');
        $result = array('result'=>0); //db
        
        // 중복 방지 토큰 체크
        $token_result = parent::checkToken(false);
        if($token_result<0){
            return array(
                "result" => $token_result,
                "success"=>false, "token"=>"",
                "error"=>$this->res->lang->validator->tokenTime);
        }
        
        $token = Utils::createToken();
        
        /******************************
         *  회원 검사
         ****************************/
        $member = json_decode($this->getMemberDataFromRedis(),TRUE);       
        if(!isset($member['mb_id']) || !$member['mb_id']){
            return array(
                "result" => -401,
                "success"=>false, "token"=>$token,
                "error"=>$this->res->lang->logincheck->fail);
        }

        $returnarr['token'] = $token;

        /******************************
         *  파라미터 검사
         ****************************/
        if( !isset($param['od_action']) || !isset($param['od_market_price']) ||
                !isset($param['od_total_cost']) || !isset($param['od_temp_coin']) ||
                !isset($param['channel']) || !isset($param['currency']) ){
            
            $errorcode = -5002;
            $this->setLogFile($errorcode,$param);
            
            return array(
                "result" => $errorcode,
                "success"=>false, "token"=>$token,
                "error"=>$this->res->lang->trade->inputRequired);
        }
        
        /******************************
         *  파라미터 값 검사
         ****************************/
        if( (float)$param['od_market_price'] == 0 || (float)$param['od_total_cost'] == 0
                || (float)$param['od_temp_coin'] == 0 ||
                !$param['channel'] || !$param['currency'] ){
            
            $errorcode = -5003;
            $this->setLogFile($errorcode,$param);
            
            return array(
                "result" => $errorcode,
                "success"=>false, "token"=>$token,
                "error"=>$this->res->lang->trade->inputRequired);
        }

        $param['mb_no'] = $member['mb_no'];
        $od_action    = $param['od_action'];// 페이지 구분값
        
        $channel = strtoupper($param['channel']);
        //파라미터 변조 확인
        $currency = $param['currency'];
        if($param['channel'] != 'krw_'.$currency){
            
            $errorcode = -5013;
            $this->setLogFile($errorcode,$param);
            
            return array( "result" => $errorcode, "success"=>false, "token"=>$token, "error"=>$this->res->lang->trade->unknowMarket);
        }
        
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
        
        /******************************
        *  DB LOCK CHECK
        ****************************/
        foreach($master as $key => $value) {
            $lock_data_result = array();
            $ex_key = explode("_", $key);
            $ctype = strtolower($ex_key[1]);
            if($channel == $key){
                $this->remotedb = null;
                $this->remotedbSlave = null;
                $lock_data_result = $this->execLists(
                    array(
                        'sql'=>$this->res->ctrl->sql_lock,
                        'mapkeys'=>$this->res->ctrl->mapkeys_lock
                    ),array());

                if(isset($lock_data_result[0]['update_cnt'])){
                    for($i=0;$i<count($lock_data_result);$i++){
                        $lock_data = $lock_data_result[$i];

                        if(isset($lock_data[$i]['update_cnt']) == 0){
                            $cs_mb_nos = json_decode($lock_data['cs_mb_nos'], true);
                            for($k=0;$k<count($cs_mb_nos);$k++){
                                if((int)$member['mb_no'] == (int)$cs_mb_nos[$k]){
                                    return array(
                                        "result" => -999,
                                        "success"=>false,
                                        "token"=>$token,
                                        "error"=>str_replace("{ctype}",strtoupper($ctype),$this->res->lang->trade->dblock)
                                    );
                                    exit;
                                }
                            }
                        }
                    }
                }
            }else{
                $this->remotedb = null;
                $this->remotedbSlave = null;
                $lock_data_result = $this->execRemoteLists(
                    $this->res->ctrl->database->$ctype,
                    array(
                        'sql'=>$this->res->ctrl->sql_lock,
                        'mapkeys'=>$this->res->ctrl->mapkeys_lock
                    ),array());

                if(isset($lock_data_result[0]['update_cnt'])){
                    for($i=0;$i<count($lock_data_result);$i++){
                        $lock_data = $lock_data_result[$i];

                        if(isset($lock_data[$i]['update_cnt']) == 0){
                            $cs_mb_nos = json_decode($lock_data['cs_mb_nos'], true);
                            for($k=0;$k<count($cs_mb_nos);$k++){
                                if((int)$member['mb_no'] == (int)$cs_mb_nos[$k]){
                                    return array(
                                        "result" => -999,
                                        "success"=>false,
                                        "token"=>$token,
                                        "error"=>str_replace("{ctype}",strtoupper($ctype),$this->res->lang->trade->dblock)
                                    );
                                    exit;
                                }
                            }
                        }
                    }
                }
            }
        }

        if(!$is_search_channel){
            
            $errorcode = -5004;
            $this->setLogFile($errorcode,$param);
                        
            return array( "result" => $errorcode, "success"=>false, "token"=>$token, "error"=>$this->res->lang->trade->unknowMarket);
        }
        
        if($mastermarket && ( $mastermarket['itUse'] == 'P' || $mastermarket['itUse'] == 'R' )  ){
                        
            $errorcode = -5005;
            $this->setLogFile($errorcode,$param);
            
            return array( "result" => $errorcode, "success"=>false, "token"=>$token, "error"=>$this->res->lang->trade->marketfrozen);
        }
        
        /******************************
         *  현재 걸려있는 주문 갯수 검사
         ****************************/
        $order_data = $this->execLists(array(
            'sql'=>$this->res->ctrl->sql_order_sum,
            'mapkeys'=>$this->res->ctrl->mapkeys_order_sum,
            'rowlimit'=>1
        ),array());
        
        $order_limit = $this->res->config['trade']['order_count'];
        
        if($order_data[0]['order_count'] >= $order_limit){
            
            $errorcode = -5006;
            $this->setLogFile($errorcode,$param);
                        
            return array( "result" => $errorcode, "success"=>false, "token"=>$token, "error"=>str_replace("{0}",$order_limit,$this->res->lang->trade->orderlimit) );
        }
        
        $od_market_price = (float)$param['od_market_price'];
        $od_total_cost  = (float)$param['od_total_cost'];
        $od_amount = (float)$param['od_temp_coin'];

        /******************************
         *  호가 단위 및 수수료
         ****************************/
        $fee = JsonConfig::get('configtradefee');
        $tradetypekey = $channel;
        $trade_krw_min_limit = 100;
        $trade_coin_min_amount = 0.0001;
        $call_unit_krw = 100;
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
        // 거래 KRW 최소값 체크 
        if($od_total_cost < $trade_krw_min_limit){     
            return array(
                "result" => -5016,
                "success"=>false, "token"=>$token,
                "error"=>str_replace("{0}",$trade_krw_min_limit,$this->res->lang->trade->minimumbtcmarketcost));
        }
        // 거래 코인 최소 수량
        if( $od_amount < (float)$trade_coin_min_amount){
            return array(
            "result" => -5015,
            "success"=> false, "token"=>$token,
            "error"=>str_replace("{0}",$trade_coin_min_amount,$this->res->lang->trade->minimumbtccost));
        }

        if($od_action == 'buy'){ 
        }else if($od_action == 'sell'){
        }else{
            $errorcode = -5009;
            $this->setLogFile($errorcode,$param);
            
            return array(
                "result" => $errorcode,
                "success"=>false, "token"=>$token,
                "error"=>$this->res->lang->trade->inputRequired);
        }


        // 원화 호가단위 체크
        $remainder = (int)($od_market_price % $call_unit_krw);
        if($remainder > 0){
            return array(
                "result" => -5008,
                "success"=>false, "token"=>$token,
                "error"=>str_replace("{0}",$call_unit_krw,$this->res->lang->trade->btcmarketcostunit));
        }

        /***************************************
        //발란스 체크
        ***************************************/
        // 2018-09-09  아래 함수의 방식은 쿠키 방식이라 브라우저(사파리)특성을 타서 setBalanceUpdate() 함수로 변경
        //$balances = $this->_getBalanceSum('ordersum');
        $balances = $this->_getBalance($member['mb_no']);
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
        $param['od_receipt_coin'] = 0;
        $param['od_receipt_dt'] = '';

        // 수수료 셋팅
        $param['od_fee_rate'] = 0;
        $param['od_receipt_fee'] = 0;
        
        
        /*********************************
         * 위변조 검사
         ********************************/
        $tmp_total = round($od_market_price * $od_amount);
        $param_total = $od_total_cost;
        
        if (  round($param_total)  != round($tmp_total)  ){
            return array(
                "result" => -501,
                "success"=>false,
                "token"=>$token,
                "error"=>'Total KRW Error.');
        }
            
        /*********************************
         * 내가 사려고 할 때 - KRW
         ********************************/
        $resultcomplet = ''; //체결여부 'PART':부분 'OK':완료
        if($od_action =='buy'){
            //주문총액과 내 발란스 비교
            if( $balance_krw_poss == 0 || !isset($param['od_total_cost']) || $balance_krw_poss < $od_total_cost){
                return array(
                    "result" => -502,
                    "success"=>false,
                    "token"=>$token,
                    "error"=>$this->res->lang->trade->lowkrwbalance);
            }
            
            /*********************************
            * balance update
            ********************************/
            $balance_result = $this->balanceUpdateRetry($od_action, $od_total_cost, $member);
            
            if($balance_result < 0){
                return array(
                    "result" => -555,
                    "success"=>false,
                    "token"=>$token,
                    "error"=>$this->res->lang->trade->balancefail);
                exit;
            }
        /*********************************
         * 내가 팔려고 할 때 - COIN
         ********************************/
        }else if($od_action == 'sell'){
            //주문KRW과 내 발란스 비교
            if($balance_coin_poss == 0 || $od_amount == 0 || $balance_coin_poss < $od_amount){
                return array(
                    "result" => -503,
                    "success"=>false,
                    "token"=>$token,
                    "error"=>$this->res->lang->trade->lowkrwbalance);
            }
            
            /*********************************
            * balance update
            ********************************/
            $balance_result = $this->balanceUpdateRetry($od_action, $od_amount, $member);
            
            if($balance_result < 0){
                return array(
                    "result" => -555,
                    "success"=>false,
                    "token"=>$token,
                    "error"=>$this->res->lang->trade->balancefail);
                exit;
            }
        }
        
        /*********************************
         * 레디스 발란스 업데이트
         *********************************/
        $session_expire = (int)$this->res->config['session']['cache_expire'] * 60;
        $balancekey = 'MB'.$member['mb_no'].'-balance';
        $tmpbalancedata = $this->getRedisData($balancekey);
        $jsonbalancedata = json_encode($balances);
        if($tmpbalancedata != $jsonbalancedata){
            $this->delRedisData($balancekey);
            $this->setRedisData($balancekey,$jsonbalancedata,$session_expire);
        }

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


//        $is_possible_buysell = $this->_getIsPossMarketTrade($od_action,(float)$param['od_market_price'],$param['channel']);
//        
//        //메모리 서버 접속실패
//        if($is_possible_buysell == -22){
//            return array(
//                "result" => -22,
//                "success"=>false,
//				"token"=>$token,
//                "error"=>$this->res->lang->trade->tradesigndberror);
//        }
        $is_possible_buysell = 1;
        if($is_possible_buysell==1 || $is_possible_buysell==0){
            $param['od_pay_status'] = $resultcomplet = 'WAIT';
        }else{
            $param['od_pay_status'] = $resultcomplet = 'REQ';
        }
        
        // 거래량
        $param['od_fee_rate'] = $this->toConvert($od_amount, 8);

        //값 등록
        //lock현상으로 등록이 안될 경우.
        for($att=0;$att<3;$att++){
            $result = $this->execUpdate(array(),$param);
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
                    $param['od_pay_status'] = 'CAN';
                    $result = $this->execUpdate(array(),$param);

                    return array(
                    "result" => -9999,
                    "success"=>false,
					"token"=>$token,
                    "error"=>$this->res->lang->tradeapi->exception);
                }
            }
        }

        $returnarr['result'] = $result['result'];
        $returnarr['complete'] = $resultcomplet; //체결완료 여부
        //echo "test execRemoteUpdate";
    
        //$this->setMoniterLogFile(2,$param);
        return $returnarr;
    }

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
        
        if( !isset($marketdata[0]['od_market_price']) || !$marketdata[0]['od_market_price']){
            return 0; //초기값이 없음
        }
        

        if($mytype=='buy'){
            if((float)$marketdata[0]["od_market_price"] <= (float)$ordercost){
                return 1;
            }
        }else if($mytype=='sell'){
            if((float)$marketdata[0]["od_market_price"] >= (float)$ordercost){
                return 1;
            }
        }

        return -1;
    }

    private function setLogFile($errorcode,$param){
        $log = new Logging();
        $log->lfile('../WebApp/Debug/TradeException-'.$param['currency'].'-'.date('Ymd',time()).'.txt');
        $log->lwrite(__CLASS__ . "[".$errorcode."]:".' '.json_encode($param).' - '.Utils::getClientIP());
        $log->lclose();
    }
    
    private function setMoniterLogFile($errorcode,$param){
        $log = new Logging();
        $log->lfile('../WebApp/Debug/TradeMoniterLog-'.$param['currency'].'-'.date('Ymd',time()).'.txt');
        $log->lwrite(__CLASS__ . "[".$errorcode."]:".' '.json_encode($param).' - '.Utils::getClientIP());
        $log->lclose();
    }
    
    public function _getBalance($mb_no)
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
    
    private function balanceUpdateRetry($od_action, $amount, $member){
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
                        'mb_no'=>$member['mb_no']
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
                        'mb_no'=>$member['mb_no']
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


    function __destruct(){

    }
}
