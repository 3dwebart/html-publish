<?php
class Login2ch extends BaseModelBase{

    private $member;
    private $block_block_count = 5; //10회 비번 틀릴경우 차단.

    function __construct($dbconfname=null) {
        parent::__construct($dbconfname);
    }
    /*
     * 조회 후 없으면 삽입, 있으면 수정
     * @param url param
     */
    public function getExecute($param){

        

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

        $this->initRedisServer($this->res->config['redis']['host'],
                $this->res->config['redis']['port'],null,
                $this->res->config['redis']['db_member']);

        

        if( !isset($param['encId']) || !isset($param['encPwd']) || !isset($param['ac_type'])  ){
            return array(
                "result" => -5010,
                "success"=>false,
                "error"=>$this->res->lang->logincheck->validator);
        }

        $param['mb_id'] = base64_decode($param['encId']);

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
        
        
        if($this->member['mb_pwd']!=$mb_pwd){
            //비밀번호가 올바르지 않습니다.
            return $this->getInsertLoginLog(
                    array("result" => -190,
                    "success"=>false,
                    "error"=>$this->res->lang->logincheck->checkPwd )
                );
        }

        require_once './Plugin/PHPGangsta/GoogleAuthenticator.php';
        $ga = new PHPGangsta_GoogleAuthenticator();
//        $secret = $ga->createSecret();
        $oMcrypt = new MCrypt();
        
        $check_type = $param['ac_type'];
        if( !$this->member['mb_otp_key'] || $this->member['mb_otp_use']!='Y' ){
            $secret = $param['g_key'];
            if(!$secret || strlen($secret) < 16){
                 return array(
                "result" => -5011,
                "success"=>false,
                "error"=>$this->res->lang->logincheck->validator);
            }            
        }else{
            $secret = trim($oMcrypt->decryptTrim($this->member['mb_otp_key']));
        }       
        
        if($check_type == 'regist'){
            Session::startSession();
            $secret = Session::getSession('otp_secret');
        }
        
        
        $oneCode = $ga->getCode($secret);
        $checkResult = $ga->verifyCode($secret, $oneCode, 2);    // 2 = 2*30sec clock tolerance , 
        if ($checkResult) {
            if($param['g_otp'] == $oneCode){
                
                if($check_type == 'login'){
                    return $this->getInsertLoginLog(array("result"=>1,"error"=>""),true); //change session
                /***********************************
                 * 신규 등록
                 **********************************/
                }else if($check_type == 'regist' || $check_type == 'delete'){
                    $this->member = json_decode($this->getMemberDataFromRedis(),TRUE);
                    if( !isset($this->member['mb_id']) || !(int)$this->member['mb_no']  ){
                    return array(
                        "result" => -5011,
                        "success"=>false,
                        "error"=>$this->res->lang->logincheck->validator);
                    }
                    
                    
                    $param['mb_otp_use'] = 'Y';
                    if($check_type == 'delete'){
                        $errormsg = 'Two Factor Authentication Disabled';
                        $param['mb_otp_use'] = 'N';
                        $param['g_key'] = '-';
                    }else{
                        $errormsg = 'Two Factor Authentication Enabled';
                        $param['g_key'] = $oMcrypt->encrypt($secret);
                    }
                    
                    $result = $this->execUpdate(
                    array(
                        'sql'=>$this->res->ctrl->sql_otp_regist,
                        'mapkeys'=>$this->res->ctrl->mapkeys_otp_regist),
                        $param
                    );
                    
                    // OTP 사용용도
                    $otp_use_info = $this->execLists(
                        array(
                            'sql'=>$this->res->ctrl->sql_otp_use_info,
                            'mapkeys'=>$this->res->ctrl->mapkeys_otp_use_info
                        ),
                        $param
                    );
                    
                    if(!isset($otp_use_info[0]['wo_no'])){
                        $otp_use_type = $this->execUpdate(
                        array(
                            'sql'=>$this->res->ctrl->sql_otp_use_type,
                            'mapkeys'=>$this->res->ctrl->mapkeys_otp_use_type),
                            $param
                        );
                    }
                       
                    if( isset($result['success']) && (string)$result['success']=="false"){
                        return array(
                            "result" => -5421,
                            "success"=>false,
                            "error"=>$this->res->lang->account->otpRegistFail . ' (PARAM error)',
                            "errorform"=>$result['error']);
                    }else if( isset($result['result']) && $result['result']<1 ) {
                        return array(
                            "result" => -5422,
                            "success"=>false,
                            "error"=>$this->res->lang->account->otpRegistFail);
                    }
                    
                    
                    //redis 회원정보 갱신
                    $this->member['mb_otp_use'] = $param['mb_otp_use'];
                    $this->setUserSession();
                    
                    
                    return $this->getInsertLoginLog(array("result"=>1,"error"=>$errormsg),false); 
                }
                
                
            }
            
            return $this->getInsertLoginLog(
                array("result" => -5194,
                "success"=>false,
                "error"=>$this->res->lang->logincheck->googleAuthenticatorFail)
            );
                    
            
        } else {
//            echo 'FAILED';
            //키가 올바르지 않습니다.
            return $this->getInsertLoginLog(
                array("result" => -194,
                "success"=>false,
                "error"=>$this->res->lang->logincheck->googleAuthenticatorFail)
            );
        }
        
        return $this->getInsertLoginLog(
                array("result" => -195,
                "success"=>false,
                "error"=>$this->res->lang->logincheck->googleAuthenticatorFail)
            );
       
    }
    
    private function changeOtpSessionRedis($session_key,$session_expire){
        $key = 'otp'.$session_key;
        $this->initRedisServer($this->res->config['redis']['host'],
                $this->res->config['redis']['port'],null,
                $this->res->config['redis']['db_member']);
        
        if($this->redis->exists($key)){
            $predata = $this->getRedisData($key);
            $this->redis->del($key); //기존의 세션 삭제
            $this->setRedisData($session_key,$predata,$session_expire);
        }
    }
    private function deleteOtpSessionRedis($session_key){
        $key = 'otp'.$session_key;
        $this->initRedisServer($this->res->config['redis']['host'],
                $this->res->config['redis']['port'],null,
                $this->res->config['redis']['db_member']);
        
        if($this->redis->exists($key)){
            $this->redis->del($key); //기존의 세션 삭제
        }
    }

    private function changeSesstion($rescode){
        $session_key = Utils::getCookie('otp');
        if((int)$rescode['result'] > 0){
            $session_expire = ((int)$this->res->config['session']['cache_expire'] * 60);
            Utils::setCookie('otp','',0);
            Utils::setCookie($this->res->config['session']['sskey'],  $session_key , $session_expire);
            //redis값 변경
            $this->changeOtpSessionRedis($session_key,$session_expire);
        }else{
            Utils::setCookie('otp','',0);
            $this->deleteOtpSessionRedis($session_key);
        }
    }

    private function getInsertLoginLog(array $rescode,$is_change_session=false){
        
        if($is_change_session){
            $this->changeSesstion($rescode);
        }
        
        
        if($rescode['result'] < 0)
            $resultMsg = 'Google Authenticator failed';
        else 
            $resultMsg = 'Google Authenticator OK';
        

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
                ,"lh_result_msg"=>$resultMsg
                ,"lh_agent"=>($_SERVER['HTTP_USER_AGENT'])?$_SERVER['HTTP_USER_AGENT']:""
                ,"lh_block_yn"=>'N'
                ,"lh_block_force_yn"=>'N'
                ,"lh_reg_ip"=>Utils::getClientIP() )
            );
        return $rescode;
    }

    private function setUserSession(){

        $session_key = Utils::getCookie($this->res->config['session']['sskey']);
        $session_expire = ((int)$this->res->config['session']['cache_expire'] * 60);
    
        //redis 서버
        $this->initRedisServer($this->res->config['redis']['host'],
                $this->res->config['redis']['port'],null,
                $this->res->config['redis']['db_member']);

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

        //세션정보 삽입
        if($this->redis->exists($session_key)){
            //기존의 세션 삭제
            $this->redis->del($session_key);
        }
        $this->setRedisData($session_key,$userdata,$session_expire); 
    }

    private function setWarnCountRedis($key){
        if(!$this->redis) $this->redis = new Credis_Client($this->res->config['redis']['host'],$this->res->config['redis']['port']);
        if(!$this->redis->exists($key)){
//            $this->redis->rpush($key, array('fdsfsdfsd-fdshirewtiou-fdsfsdfsd-fdshirewtiou','',''));
            $this->redis->set($key,1);
            $this->redis->expire($key, 120); //2분
        }else{
            $this->redis->set($key, ((int)$this->redis->get($key)+1));
        }
    }

    private function getWarnCountRedis($key){
        if(!$this->redis) $this->redis = new Credis_Client($this->res->config['redis']['host'],$this->res->config['redis']['port']);
        if($this->redis->exists($key)){
            return (int)$this->redis->get($key);
        }
        return 0;
    }

    private function delWarnCountRedis($key){
        if(!$this->redis) $this->redis = new Credis_Client($this->res->config['redis']['host'],$this->res->config['redis']['port']);
        if($this->redis->exists($key)){
            $this->redis->del($key);
        }

    }


    function __destruct(){

    }
}
