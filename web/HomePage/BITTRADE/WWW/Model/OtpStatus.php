<?php
class OtpStatus extends BaseModelBase{

    private $member;

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
        if(isset($param['g_otp1']) && $param['g_otp1'] != ''){
            $param['g_otp_status'] = $param['g_otp1'];
        }else if(isset($param['g_otp2']) && $param['g_otp2'] != ''){
            $param['g_otp_status'] = $param['g_otp2'];
        }else{
            $param['g_otp_status'] = '';
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

        if( !isset($param['status_type'])  ){
            return array(
                "result" => -5010,
                "success"=>false,
                "error"=>$this->res->lang->logincheck->validator);
        }
        
        //회원확인
        $param['mb_id'] = base64_decode($param['encId']);
        
        $rows = $this->execLists(array(),$param);
        if(isset($rows[0]))  $this->member = $rows[0];
        else{
            $this->member['result'] = -1;
        }

        if(!isset($this->member['mb_id'])){
            $this->member['mb_id'] = $param['mb_id'];
        }

        require_once './Plugin/PHPGangsta/GoogleAuthenticator.php';
        $ga = new PHPGangsta_GoogleAuthenticator();
        $oMcrypt = new MCrypt();
        
        $check_type = $param['status_type'];
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

        $oneCode = $ga->getCode($secret);
        $checkResult = $ga->verifyCode($secret, $oneCode, 2);    // 2 = 2*30sec clock tolerance , 
        if ($checkResult) {
            if($param['g_otp_status'] == $oneCode){
                
                if($check_type == 'delete'){
                    $this->member = json_decode($this->getMemberDataFromRedis(),TRUE);
                    if( !isset($this->member['mb_id']) || !(int)$this->member['mb_no']  ){
                    return array(
                        "result" => -5011,
                        "success"=>false,
                        "error"=>$this->res->lang->logincheck->validator);
                    }

                    $errormsg = 'Two Factor Authentication Disabled';
                    $param['mb_otp_use'] = 'N';
                    $param['g_key'] = '-';
                    
                    $result = $this->execUpdate(
                    array(
                        'sql'=>$this->res->ctrl->sql_otp_delete,
                        'mapkeys'=>$this->res->ctrl->mapkeys_otp_delete),
                        $param
                    );
                       
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
                    $this->member['mb_otp_use'] = 'N';
                    $this->setUserSession();
                    
                    return array("result"=>1,
                        "success"=>true); 
                }else{
                    $this->member = json_decode($this->getMemberDataFromRedis(),TRUE);
                    if( !isset($this->member['mb_id']) || !(int)$this->member['mb_no']  ){
                    return array(
                        "result" => -5011,
                        "success"=>false,
                        "error"=>$this->res->lang->logincheck->validator);
                    }
                    
                    $result = $this->execUpdate(
                        array(
                            'sql'=>$this->res->ctrl->sql_otp_update,
                            'mapkeys'=>$this->res->ctrl->mapkeys_otp_update
                        ),
                        array(
                            'wo_login_yn'=>$param['otp_login'],
                            'wo_withdraw_yn'=>$param['otp_withdraw']
                        )
                    );
                    
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
                    $this->member['otp_login'] = $param['otp_login'];
                    $this->member['otp_withdraw'] = $param['otp_withdraw'];
                    $this->setUserSession();
                    
                    return array("result"=>1,
                        "success"=>true); 
                }
                
                
            }
            
            return array("result" => -5194,
                "success"=>false,
                "error"=>$this->res->lang->logincheck->googleAuthenticatorFail);

        } else {
//            echo 'FAILED';
            //키가 올바르지 않습니다.
            return array("result" => -194,
                "success"=>false,
                "error"=>$this->res->lang->logincheck->googleAuthenticatorFail);
        }
        return array("result" => -195,
            "success"=>false,
            "error"=>$this->res->lang->logincheck->googleAuthenticatorFail);
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

    private function setUserSession(){

        $session_key = Utils::getCookie($this->res->config['session']['sskey']);
        $session_expire = ((int)$this->res->config['session']['cache_expire'] * 60);
    
        //redis 서버
        $this->initRedisServer($this->res->config['redis']['host'],
                $this->res->config['redis']['port'],null,
                $this->res->config['redis']['db_member']);

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
            "otp_login"=>$this->member['otp_login'],
            "otp_withdraw"=>$this->member['otp_withdraw']
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
