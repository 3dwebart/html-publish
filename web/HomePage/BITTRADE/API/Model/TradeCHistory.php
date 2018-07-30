<?php
class TradeCHistory extends BaseModelBase{

    private $tradeserv;
    

    function __construct($dbconfname=null) {		
        parent::__construct($dbconfname);

    }
    
    private $gHeader;
    public function getHeader($header)
    {
       $this->gHeader = $header;      
    }
    
    public function getExecute($param){
         
        /*
        // Header의 Authorization을 체크한다.
        if(!isset($this->gHeader['Authorization'])) {
            return array(
               "result" => -5100,
               "success"=>false,
               "error"=>$this->res->lang->validator->required);           
        }
        
        // Header의 Timestamp를 체크한다.
        if(!isset($this->gHeader['Timestamp'])) {
            return array(
               "result" => -5100,
               "success"=>false,
               "error"=>$this->res->lang->validator->required);           
        }
                
        // API 호출 파라미터를 체크한다
        if( !isset($param['apikey']) || !isset($param['currency']) ) {
            return array(
               "result" => -5100,
               "success"=>false,
               "error"=>$this->res->lang->validator->required);
        }            
        
        
        $akey = $param['apikey'];
        $auth = $this->gHeader['Authorization'];
        $timestamp = $this->gHeader['Timestamp'];
            
        // 파라미터 akey값을 비교하여 회원 정보를 가져온다.
        $reMemInfo = $this->getMemberInfo($akey);
        if(!$reMemInfo || $reMemInfo['result'] < 1) {
            return array(
               "result" => -5100,
               "success"=>false,
               "error"=>$this->res->lang->validator->required);            
        } 
       
        // apikey값이 존재하는 회원이면 회원의 securetkey와  header의 authorization값을 비교
        if(!$this->checkAuthorization($reMemInfo['mk_secret'], $timestamp, $auth)) {
             return array(
               "result" => -5100,
               "success"=>false,
               "error"=>$this->res->lang->validator->required);
        }
         * */
        
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
                
        if( !isset($param['apikey']) || !isset($param['currency']) || !isset($param['count'])){
            $errorcode = -2001;        
            return array(
               "result" => 'fail',
               "errorcode"=> $errorcode,
               "error"=>$this->res->lang->validator->required);
        }        
        /***********************************************************************************/
        
        /***********************************************************************************
         * API 호출 파라미터 값을 체크한다
        ***********************************************************************************/
        if( !$param['apikey'] || !$param['currency'] || !$param['count']){
            
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
        
                
        $mb_no = $reMemInfo['mb_no'];         
        $currency = $param['currency'];
        $count = $param['count'];
        $remotedb = $this->res->ctrl->database->$currency;
        $cSql = str_replace("{currency}", $currency, $this->res->ctrl->sql);

        $res =  $this->execRemoteLists(
                    $remotedb, 
                    array(
                        'sql'=>$cSql,
                        'mapkeys'=>$this->res->ctrl->mapkeys,
                        'rowlimit'=>$count
                    )
                    , array('mb_no'=>(int)$mb_no)
                );  
        
        //return $res;         
        $returnarr = array('result'=>'success', 'errorcode'=>0, 'count'=>$res[0]['result'] ,'data'=>$res);
        return $returnarr;
    }
     
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
	  
    
    function __destruct(){

    }
}
