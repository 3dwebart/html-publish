<?php
error_reporting(E_ALL);

ini_set("display_errors", 1);

class LoginBasic extends BaseModelBase{

    private $member;
    private $oMcrypt;
    private $block_block_count = 5; //10회 비번 틀릴경우 차단.

    function __construct($dbconfname=null) {
        parent::__construct($dbconfname);
    }
    /*
     * 조회 후 없으면 삽입, 있으면 수정
     * @param url param
     */
    public function getExecute($param){

//        if($_SERVER['HTTP_USER_AGENT'] == "Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.1; Trident/5.0)"){
//                return array(
//        "result" => -10998,
//        "success"=>false,
//        "error"=>"공격용으로 사용된 브라우저로 확인되었습니다. 정상이신 분인데 이 메시지가 보이신다면 고객센터로 연락주세요."
//                        );
//        }

        if(!$param){
            return array(
                "result" => -5009,
                "success"=>false,
                "error"=>$this->res->lang->logincheck->validator );
        }

        //공격이 들어올 경우 이 값 변경 요망 /view/signin.php에서도 같이 변경
        if(!isset($param['token']) || $param['token']!='ad1fdb06f59c6b9d2f0034c7ac0ff43d'){
                return array(
                "result" => -10999,
                "success"=>false,
                "error"=>"올바르지 않는 요청입니다."
            );
        }
        //$this->setMoniterLogFile(1,array('mb_no'=>$this->member['mb_no'],'start loginbase'=>'start'));
        //$this->setMoniterLogFile(1,array('mb_no'=>17,'begin start loginbase'=>'start'));

        if( !isset($param['encId']) || !isset($param['encPwd']) ){
            return array(
                "result" => -5010,
                "success"=>false,
                "error"=>$this->res->lang->logincheck->validator);
        }

        // mb_id base 64 encode -> decode 재정의
        // 20160806
        $param['mb_id'] = base64_decode($param['encId']);
        //$this->setMoniterLogFile(2,array('mb_no'=>$this->member['mb_no'],'start loginbase'=>'start'));
        //오늘차단 아이피 조회
        $blockrow = $this->execLists(
            array(
                'sql'=>$this->res->ctrl->sql_block_check,
                'mapkeys'=>$this->res->ctrl->mapkeys_block_check,
                'rowlimit'=>1
            )
            , array('query'=>Utils::getClientIP()));
        if(isset($blockrow[0]) && isset($blockrow[0]['result']) && $blockrow[0]['result']>0 ){
            return array(
                "result" => -999,
                "success"=>false,
                "error"=>$this->res->lang->logincheck->todayBlocked);
        }
        
        //$this->setMoniterLogFile(3,array('mb_no'=>$this->member['mb_no'],'start loginbase'=>'start'));
        //reCAPTCHA
        $this->initRedisServer($this->res->config['redis']['host'],
                $this->res->config['redis']['port'],null,
                $this->res->config['redis']['db_tmp']);

        $key = Utils::getClientIP();
        //$key = $param['mb_id'];
        $warn_count = $this->getWarnCountRedis($key);

        if($warn_count > 0){
//            $postdata = http_build_query(
//                    array('secret'=>$this->res->config['recaptcha']['secret'],
//                        'remoteip'=>  Utils::getClientIP(),
//                        'response'=>$param['g-recaptcha-response'])
//                    );
//            $opts = array(
//                'http'=>array(
//                'method'=>"POST",
//                'header'=>"Accept-language: ".$_SERVER['HTTP_ACCEPT_LANGUAGE']."\r\n" .
//                    "Content-Type: application/x-www-form-urlencoded; charset=UTF-8\r\n",
//                    'content'=>$postdata
//                )
//            );
//
//            $context = stream_context_create($opts);
//            $rescov = file_get_contents($this->res->ctrl->recaptchaurl,false, $context);
//            $resjson = json_decode($rescov,true);
//
//            if( !isset($resjson['success']) || !$resjson['success'] ){
//                return array(
//                    "result" => -801,
//                    "success"=>false,
//                    "error"=>$this->res->lang->validator->captcha,
//                    "recaptcha"=>true);
//            }
        }
        
        //회원확인
        $rows = $this->execLists(array(),$param);
        if(isset($rows[0]))  $this->member = $rows[0];
        else{
            $this->member['result'] = -1;
        }

        if(!isset($this->member['mb_id'])){
            $this->member['mb_id'] = $param['mb_id'];
        }

        if( (int)$this->member['result']<1 ){
            //올바른 회원인지 확인 후 이용하세요.
            return $this->getInsertLoginLog(
                array("result" => -90,
                "success"=>false,
                "error"=>$this->res->lang->logincheck->notMember)
                );
        }

        $enkey1 = 'siterand';
        $enkey2 = 'siteafter';
        if(isset($this->res->config['encode'])){
            $enkey1 = $this->res->config['encode']['passwd1'];
            $enkey2 = $this->res->config['encode']['passwd2'];
        }
        
        //비밀번호 다시 한번 더 암호화, siteafter는 사이트 임의의 키
        $mb_pwd = hash('sha512',$enkey1.$param["encPwd"],false).hash('sha512', $this->member['mb_id'].$enkey2 , false);
        $mb_pwd = substr($mb_pwd,0,250);
        
        //미인증된 회원입니다. (레벨)
        if((int)$this->member['mb_level']<1){
            //아직 미인증된 회원입니다.
            return $this->getInsertLoginLog(
                array(
                    "result" => -800,
                    "success"=>false,
                    "error"=>$this->res->lang->logincheck->notAuth)
            );
        }

        
        if($this->member['mb_pwd']!=$mb_pwd){
//        if($this->member['mb_pwd']!=hash('sha256',$param["mb_id"].$mb_pwd)){
            //비밀번호가 올바르지 않습니다.
            return $this->getInsertLoginLog(
                    array("result" => -190,
                    "success"=>false,
                    "error"=>$this->res->lang->logincheck->checkPwd )
                );
        }


        $resultstatument = array("result" =>-1,
            "success"=>false,
            "error"=>"error"
        );
        //$this->setMoniterLogFile(5,array('mb_no'=>$this->member['mb_no'],'start loginbase'=>'start'));
        // 로그인 전 포인트 재연산 프로시져 호출
        /*$call_proc = $this->execRemoteUpdate(
            $this->res->ctrl->database->point,
            array(
                'sql'=>$this->res->ctrl->sql_call_proc,
                'mapkeys'=>$this->res->ctrl->mapkeys_call_proc,
                'rowlimit'=>1
            )
            , array('mb_no'=>$this->member['mb_no'])
        );*/
        
        // otp login 사용여부 체크
        $otp_login = 'N';
        $otp_use_info = $this->execLists(
            array(
                "sql"=>$this->res->ctrl->sql_otp_use_info,
                "mapkeys"=>$this->res->ctrl->mapkeys_otp_use_info
            ),array(
                "mb_no"=>$this->member['mb_no']
            )
        );
        if(isset($otp_use_info[0]) && isset($otp_use_info[0]['wo_login_yn']) && $otp_use_info[0]['wo_login_yn']=='Y'){
            $otp_login = 'Y';
        }

        //$this->setMoniterLogFile(6,array('mb_no'=>$this->member['mb_no'],'start loginbase'=>'start'));
               
        
        // psc 20180614 
        $master = JsonConfig::get('exchangemarket');
        //$this->setMoniterLogFile(8,array('master'=>'data','masterData loginbase'=>'start'));
        foreach ($master as $key => $value) {
          
          $tmp = explode('_', $key);
          $currency = strtolower($tmp[1]);

          //$cSql = str_replace("{currency}", $currency, $this->res->ctrl->sql_buy_sum);
          $remotedb = $this->res->ctrl->database->$currency;    
          //$db_params_sum = array('mb_no'=>$this->member['mb_no']);
         
          // 코인별 거래 매수 금액 누적 

          $sumbuy = $this->execRemoteLists(
            $remotedb,
            array(
                'sql'=>$this->res->ctrl->sql_buy_sum,
                'mapkeys'=>$this->res->ctrl->mapkeys_buy_sum,    
                'rowlimit'=>1                  
                ),
             array(
                'mb_no'=>$this->member['mb_no']
            )
          );
            
          //$this->setMoniterLogFile(8,$sumbuy);
          if($sumbuy[0]['result'] == 0) {
              $sum_sell_price =  0 ;
              $sum_buy_price  =  0 ;
          }else{
              $sum_sell_price = $sumbuy[0]['ret_sell_price'];
              $sum_buy_price  = $sumbuy[0]['ret_buy_price'];
          }
          
          //$this->setMoniterLogFile(9,array('sumbuy'=>'data','sum_buy_price'=>$sum_buy_price,'sum_sell_price'=>$sum_sell_price));
           
          if(0 != $sum_buy_price || 0 != $sum_sell_price) {
            $db_params_acc = array(
                          'mb_no'=>$this->member['mb_no'],
                          'po_type'=>$currency,
                          'po_sell_price'=>$sum_sell_price,
                          'po_buy_price'=>$sum_buy_price);
            $this->execRemoteUpdate(
                  $this->res->ctrl->database->point, 
                  array(
                      'sql'=>$this->res->ctrl->sql_point_accumulate,
                      'mapkeys'=>$this->res->ctrl->mapkeys_point_accumulate                      
                  )
                  ,$db_params_acc

           );
          }
   
        }
        
        
        
        /*******************************************
         * 로그인 처리 & 메모리에 로드
         *******************************************/
        if($this->member['mb_otp_use'] == 'Y' && $otp_login=='Y'){
            $this->setUserSession(true);
           
            //otp가 맞는지 확인 후 다시 로그인
            return array("result" =>100,
                "success"=>false,
                "error"=>$this->res->lang->logincheck->authFail,
                "otp"=>true
            );
        }else{
            $this->setUserSession();
       
            //request
            return $this->getInsertLoginLog(array("result" =>1,
                "success"=>true,
                "error"=>$this->res->lang->logincheck->logined
            ));
        }
        
      
    }
        
     private function setLogFile($errorcode,$param){
        $log = new Logging();
        $log->lfile('../WebApp/Debug/LoginException-'.$param['mb_no'].'-'.date('Ymd',time()).'.txt');
        $log->lwrite(__CLASS__ . "[".$errorcode."]:".' '.json_encode($param).' - '.Utils::getClientIP());
        $log->lclose();
    }
    
    private function setMoniterLogFile($errorcode,$param){
        $log = new Logging();
        $log->lfile('../WebApp/Debug/LoginMoniterLog-'.date('Ymd',time()).'.txt');
        $log->lwrite(__CLASS__ . "[".$errorcode."]:".' '.json_encode($param).' - '.Utils::getClientIP());
        $log->lclose();
    }

    private function setUserSession($otp2ch=false){

       
        Utils::setCookie("account",  json_encode(array(
            "mb_id"=>$this->member['mb_id'],
            "mb_name"=>$this->member['mb_name'],
            "mb_level"=>$this->member['mb_level']
        )));
        $login_agent_key =  md5($_SERVER['HTTP_USER_AGENT']);
        $mbsearchkey = 'MB'.$this->member['mb_no'];
        $session_key =  $mbsearchkey. '-'. md5(Utils::getClientIP().'-' . $login_agent_key .'-' . time());
        
        
        
        $session_expire = ((int)$this->res->config['session']['cache_expire'] * 60);
        
        if($otp2ch){
            $session_expire = 10 * 60; //10분
            Utils::setCookie('otp',  $session_key , $session_expire);
        }else{
            Utils::setCookie($this->res->config['session']['sskey'],  $session_key , $session_expire);
        }
        
        // OTP 사용용도
        $otp_use_info = $this->execLists(
            array(
                'sql'=>$this->res->ctrl->sql_otp_use_info,
                'mapkeys'=>$this->res->ctrl->mapkeys_otp_use_info
            ),array('mb_no'=>$this->member['mb_no'])
        );
        
        $userdata = json_encode(array(
            "login_ip"=>Utils::getClientIP(),
            "mb_no"=>$this->member['mb_no'],
            "mb_id"=>$this->member['mb_id'],
            "mb_name"=>$this->member['mb_name'],
            "mb_first_name"=>$this->member['mb_first_name'],
            "mb_last_name"=>$this->member['mb_last_name'],
            "mb_level"=>$this->member['mb_level'],
            "mb_volume_level"=>$this->member['mb_volume_level'],
            "mb_api_use"=>$this->member['mb_api_use'],
            "mb_otp_use"=>$this->member['mb_otp_use'],    
            "contry_code"=>$this->member['contry_code'],
            "mb_certificate"=>$this->member['mb_certificate'],
            "mb_hp"=>$this->member['mb_hp'],
            "mb_volume"=>$this->member['mb_volume'],
            "otp_login"=>(isset($otp_use_info[0]['wo_login_yn']))?$otp_use_info[0]['wo_login_yn']:'N',
            "otp_withdraw"=>(isset($otp_use_info[0]['wo_withdraw_yn']))?$otp_use_info[0]['wo_withdraw_yn']:'N'
            
        ));
        
        //balance
        $balances = $this->execRemoteLists(
            $this->res->ctrl->database->point,
            array(
                'sql'=>$this->res->ctrl->sql_balance,
                'mapkeys'=>$this->res->ctrl->mapkeys_balance
            ),array('mb_no'=>$this->member['mb_no'])
        );
        
        $balancedata = json_encode($balances);
        
        //redis 서버
        $this->initRedisServer($this->res->config['redis']['host'],
                $this->res->config['redis']['port'],null,
                $this->res->config['redis']['db_member']);
        
        //구글 otp로그인 시 임시 키로 저장
        if($otp2ch) {
            $session_key = 'otp'.$session_key;
        }
         
        //세션정보 삽입
        if($this->redis->exists($session_key)){
            //기존의 세션 삭제
            $this->redis->del($session_key);
        }
        $this->setRedisData($session_key,$userdata,$session_expire);
        //회원 발란스 정보 삽입
        $balancekey = $mbsearchkey . '-balance';
        if($this->redis->exists($balancekey)){
            //기존의 세션 삭제
            $this->redis->del($balancekey);
        }
        $this->setRedisData($balancekey,$balancedata,$session_expire);    
    }


    private function getInsertLoginLog(array $rescode){

        //redis 서버
        $this->initRedisServer($this->res->config['redis']['host'],
                $this->res->config['redis']['port'],null,
                $this->res->config['redis']['db_tmp']);

        $is_block = 'N';
        $clientIP = Utils::getClientIP();
        //$key = $this->member['mb_id'];
        $key = $clientIP;
        $warn_count = $this->getWarnCountRedis($key) + 1;

        if($rescode['result'] == '-190'){
            if($warn_count>-1){
                $rescode['error'] = $rescode['error'];
                if($warn_count > 1) {
                    if($this->block_block_count-$warn_count-1 == 0){
                        $rescode['error'] = $this->res->lang->logincheck->todayBlocked;
                    }else{
                        $rescode['error'] = $rescode['error'].'<br /> '.($this->block_block_count-$warn_count-1).'번이상 틀릴경우 차단됩니다.';
                    }
                }
                $rescode['recaptcha'] = true;
            }
            if( $warn_count >= ($this->block_block_count - 1) ){
                $is_block = 'Y';
            }
        }

        if((int)$rescode['result'] < 0){
            $this->setWarnCountRedis($key);
        }else{
//            $rescode['request_key'] = $this->member['mb_key'];
            if($warn_count>0){
                $this-> delWarnCountRedis($key);
            }
        }

        $result = $this->execUpdate(
            array(
                'sql'=>$this->res->ctrl->sql_loginsert,
                'mapkeys'=>$this->res->ctrl->mapkeys_loginsert),
            array(
                "lh_type"=>'LOGIN'
                ,"mb_no"=>(isset($this->member['mb_no']))?$this->member['mb_no']:'0'
                ,"mb_id"=>(isset($this->member['mb_id']))?$this->member['mb_id']:'guest'
                ,"mb_key"=>(isset($this->member['mb_key']))?$this->member['mb_key']:''
                ,"lh_result_code"=>$rescode['result']
                ,"lh_result_msg"=>$rescode['error']
                ,"lh_agent"=>($_SERVER['HTTP_USER_AGENT'])?$_SERVER['HTTP_USER_AGENT']:""
                ,"lh_block_yn"=>$is_block
                ,"lh_block_force_yn"=>'N'
//                ,"lh_reg_ip"=>$key)
                ,"lh_reg_ip"=>$clientIP)
            );
        $volumedata = $this->execRemoteLists(
                $this->res->ctrl->database->point,
                array(
                        'sql'=>$this->res->ctrl->sql_sum_volume,
                        'mapkeys'=>$this->res->ctrl->mapkeys_sum_volume                      
                    ),
                array('mb_no'=>(isset($this->member['mb_no']))?$this->member['mb_no']:'0')
            );
        
        $ta_volume = $volumedata[0]['volume'];
        
        $this->execUpdate(
            array(
                'sql'=>$this->res->ctrl->sql_insert_volume,
                'mapkeys'=>$this->res->ctrl->mapkeys_insert_volume),
            array(
                "mb_no"=>(isset($this->member['mb_no']))?$this->member['mb_no']:'0'
                ,"ta_volume"=>$ta_volume)
            );
        
        $this->execUpdate(
            array(
                'sql'=>$this->res->ctrl->sql_update_volume,
                'mapkeys'=>$this->res->ctrl->mapkeys_update_volume),
            array(
                "mb_volume"=>$ta_volume
                ,"mb_no"=>(isset($this->member['mb_no']))?$this->member['mb_no']:'0')
            );
        return $rescode;
    }

    private function setWarnCountRedis($key){
        if(!$this->redis) $this->redis = new Credis_Client($this->res->config['redis']['host'],$this->res->config['redis']['port'],null,'',$this->res->config['redis']['db_tmp']);
        if(!$this->redis->exists($key)){
            $this->redis->set($key,0);
            $this->redis->expire($key, 120); //2분
        }else{
            $this->redis->set($key, ((int)$this->redis->get($key)+1));
        }
    }
    
    private function getWarnCountRedis($key){
        if(!$this->redis) $this->redis = new Credis_Client($this->res->config['redis']['host'],$this->res->config['redis']['port'],null,'',$this->res->config['redis']['db_tmp']);
        if($this->redis->exists($key)){
            return (int)$this->redis->get($key);
        }
        return 0;
    }

    private function delWarnCountRedis($key){
        
        if(!$this->redis) $this->redis = new Credis_Client($this->res->config['redis']['host'],$this->res->config['redis']['port'],null,'',$this->res->config['redis']['db_tmp']);
        if($this->redis->exists($key)){
            $this->redis->del($key);
        }

    }


    function __destruct(){

    }
}
