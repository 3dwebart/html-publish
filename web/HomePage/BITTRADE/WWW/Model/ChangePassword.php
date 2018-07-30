<?php
class ChangePassword extends BaseModelBase{

    function __construct($dbconfname=null) {
        parent::__construct($dbconfname);

    }
    /*
     * 회원가입 모델
     * @param url param
     */
    public function getExecute($param){
        
        
        $enkey1 = 'siterand';
        $enkey2 = 'siteafter';
        if(isset($this->res->config['encode'])){
            $enkey1 = $this->res->config['encode']['passwd1'];
            $enkey2 = $this->res->config['encode']['passwd2'];
        }
        

        $member = json_decode($this->getMemberDataFromRedis(),TRUE); 
        
        $mb_id              = $member['mb_id'];
        $mb_pwd             = $param['mb_pwd'];
        $mb_new_encPwd      = $param['mb_new_encPwd'];

        //비밀번호 다시 한번 더 암호화, siteafter는 사이트 임의의 키
        $mb_pwd = hash('sha512',$enkey1.$mb_pwd,false).hash('sha512', $mb_id.$enkey2 , false);
        $param['mb_pwd'] = substr($mb_pwd,0,250);

        //비밀번호 다시 한번 더 암호화, siteafter는 사이트 임의의 키
        $mb_new_encPwd = hash('sha512',$enkey1.$mb_new_encPwd,false).hash('sha512', $mb_id.$enkey2 , false);
        $param['mb_new_encPwd'] = substr($mb_new_encPwd,0,250);

        // 비밀번호변경
        $mb_update_result = $this->execUpdate( array(),$param);

        return $mb_update_result;
    }



    function __destruct(){

    }

}
