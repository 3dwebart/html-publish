<?php
class TradeOrderCancel extends BaseModelBase{

        
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
    

    // 거래소 판/구매 물량 취소 이벤트
    // 151026
    public function getExecute($param){

        //초기에 모니터링을 위해 저장
        //$this->setMoniterLogFile(1,$param);
        
             
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
                
        if( !isset($param['apikey']) || !isset($param['orderid'])  || 
            !isset($param['status']) || !isset($param['currency']) ){
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
        if( !$param['apikey'] || !$param['currency'] || 
            !$param['orderid'] || !$param['status']){
            
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
                    
        // 파라미터 akey값을 비교하여 회원 정보를 가져온다.
        $reMemInfo = $this->getMemberInfo($akey);
        if(!$reMemInfo || $reMemInfo['result'] < 1) {
            return array(
               "result" => 'fail',
               "errorcode"=> -3001,                
               // ADD Ment
               "error"=>$this->res->lang->validator->required);            
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
        $currency = $param['currency'];
        //사용할 remotedb 생성
        $remotedb = $this->res->ctrl->database->$currency;
        //currency에 맞춰서 테이블 변경
        $cSql = str_replace("{currency}", $currency, $this->res->ctrl->sql);
         
        //+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
        
        /******************************
         *  유효한 마켓 검사
         ****************************/
        $channel = strtoupper('krw_'.$param['currency']);
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
            return array( "result" => $errorcode, "success"=>false, "error"=>$this->res->lang->trade->unknowMarket);
        }
        
        if($mastermarket && ($mastermarket['itUse'] == 'P' || $mastermarket['itUse'] == 'R') ){
                        
            $errorcode = -3102;
            $this->setLogFile($errorcode,$param);
            
            return array( "result" => $errorcode, "success"=>false,  "error"=>$this->res->lang->trade->marketfrozen);
        }
        
        try{
            $this->initTradeServer($mastermarket['itServerSignIp'].':'.$mastermarket['itServerSignPort']);        
        } catch (RPCException $ex) {
            $param['status'] = 'CAN';
            return array(
                    "result" => -9998,
                    "success"=>false,                 
                    "error"=>$this->res->lang->tradeapi->exception);
            exit;
        }
                
        if(isset($resjson['error']) && $resjson['error']){
            return array(
                    "result" => -998,
                    "success"=>false,
                    "error"=>$this->res->lang->tradeapi->exception);
        }else{
            // 취소 처리
            $trade_cancel_result = $this->execRemoteUpdate($remotedb, 
                array(
                    'sql'=>$cSql,
                    'mapkeys'=>$this->res->ctrl->mapkeys,
                    'rowlimit'=>1
                )
                , array('status'=>$param['status']
                    ,'orderid'=>$param['orderid']
                    ,'mb_no'=>$param['mb_no']
                    )
            );
            
            /***************************************
            //내 발란스
            ***************************************/     
            // 2018-02-20
            // 마켓 메이킹에서 주문 후 OrderArrange()를 수행하여, TradeModerOrderCancel.php를 사용하는데
            // TradeModerOrderCancel에서 아래 tradePointSyncStart를 사용하므로 이곳에서는 사용할 필요 없음
            // tradePointSyncStart를 사용하는 곳은 TradeModerOrderCancel, TradeAllOrderCancel에서만 사용하면됨
            // 단, 마켓 메이킹이 아닌 주문취소를 별도로 수행할 경우에는 사용해야 함
            /*
            if( (int)$trade_cancel_result['result'] >= 0 ){            
                $rcpres = $this->tradeserv->tradePointSyncStart(json_encode([$param['mb_no']]),$param['orderid']);
                $resjson = json_decode($rcpres,TRUE);
                $trade_cancel_result['result'] = $resjson;
            }            
            */
        }
        //+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//        
         
       
        //$balance = $this->_getBalanceSum($param);
        $balances = $this->setBalanceUpdate($param['mb_no']);
        
        //notice
        $this->_setTradeEventNoti($param);
    
        $re = $trade_cancel_result['result'];
        
        if($re >= 0)
            $returnarr = array('result'=>'success', 'errorcode'=>0, 'error'=>"");
        else
            $returnarr = array('result'=>'fail', 'errorcode'=>-5001, 'error'=>"Please check your order number.");
         
        return $returnarr;
       
    }

    

    private function _getBalanceSum(){
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
        $json = file_get_contents($this->res->config['url']['site'].'/getbalance/server/cmd-sum/ac-ordercan', false, $context);
        $balancedata = json_decode($json,TRUE);
        if(isset($balancedata['balance'])){
            return $balancedata['balance'];
        }
        
        return array();
    }

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
    
    private function _setTradeEventNoti($param){
        if($param['currency']){
            $channel = 'krw_'.$param['currency'];
        }
        $this->initRedisServer($this->res->config['redis_trade']['host'],
        $this->res->config['redis_trade']['port'],null,
        $this->res->config['redis_trade']['db_ticker']);
        $this->setRedisData('trading-complet-event-'.$channel,1);
    } 
    
    private function setLogFile($errorcode,$param){
        $log = new Logging();
        $log->lfile('../WebApp/Debug/TradeException-API-CANCEL-'.$param['currency'].'-'.Utils::formatDateTime().'-'.'.txt');
        $log->lwrite(__CLASS__ . "[".$errorcode."]:".' '.json_encode($param).' - '.Utils::getClientIP());
        $log->lclose();
    }
    
    private function setMoniterLogFile($errorcode,$param){
        $log = new Logging();
        $log->lfile('../WebApp/Debug/TradeMoniterLog-API-CANCEL-'.$param['currency'].'-'.Utils::formatDateTime().'-'.'txt');
        $log->lwrite(__CLASS__ . "[".$errorcode."]:".' '.json_encode($param).' - '.Utils::getClientIP());
        $log->lclose();
    }
    
    private function setBalanceUpdate($mb_no)
    {
        $loginstatus = 0;
   
        $mbsearchkey = '';
        $mbsearchkey = 'MB'.$mb_no;
        $session_expire = ((int)$this->res->config['session']['cache_expire'] * 60);
   
        //balance
        $balancedata = $this->execRemoteLists(
                $this->res->ctrl->database->balance,
                array(
                        'sql'=>$this->res->ctrl->sql_balanceupdate,
                        'mapkeys'=>$this->res->ctrl->mapkeys_balanceupdate                      
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
    
    
    function __destruct(){

    }
}
