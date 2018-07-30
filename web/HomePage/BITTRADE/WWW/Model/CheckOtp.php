<?php
class CheckOtp extends BaseModelBase{

    private $member;

    function __construct($dbconfname=null) {
        parent::__construct($dbconfname);
    }
    /*
     * 실행
     * @param url param
     */
    public function getExecute($param){
        
        if(!$param){
            return array(
                "result" => -5009,
                "success"=>false,
                "error"=>$this->res->lang->logincheck->validator );
        }
        
        $rows = $this->execLists(array(),$param);
        if(isset($rows[0]))  $this->member = $rows[0];
        
        require_once './Plugin/PHPGangsta/GoogleAuthenticator.php';
        $ga = new PHPGangsta_GoogleAuthenticator();
        $oMcrypt = new MCrypt();

        if( !$this->member['mb_otp_key'] || $this->member['mb_otp_use']!='Y' ){
            return array(
               "result" => -5011,
               "success"=>false,
               "error"=>$this->res->lang->logincheck->validator);
        }else{
            $secret = trim($oMcrypt->decryptTrim($this->member['mb_otp_key']));
        }
        
        $oneCode = $ga->getCode($secret);
        $checkResult = $ga->verifyCode($secret, $oneCode, 2);    // 2 = 2*30sec clock tolerance , 
        if ($checkResult) {
            if($param['g_otp'] == $oneCode){
                return array("result"=>1,"success"=>true);
            }else{
                return array("result" => -5194,
                    "success"=>false,
                    "error"=>$this->res->lang->logincheck->googleAuthenticatorFail);
            }
        }else{
            return array("result" => -194,
                "success"=>false,
                "error"=>$this->res->lang->logincheck->googleAuthenticatorFail);
        }
    }

    
    
    function __destruct(){

    }



}
