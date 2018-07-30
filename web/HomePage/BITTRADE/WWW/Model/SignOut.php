<?php
class SignOut extends BaseModelBase{
    private $member;

    function __construct($dbconfname=null) {
        parent::__construct($dbconfname);
    }
    
    public function getExecute($param){
        
        $res = $this->execUpdate(array() ,$param);
        $this->delMemberDataFromRedis();
        return $res;
    }

    
    private function delMemberDataFromRedis(){
        $this->initRedisServer($this->res->config['redis']['host'],
                $this->res->config['redis']['port'],null,
                $this->res->config['redis']['db_member']);

        $sessionkey = Utils::getCookie($this->res->config['session']['sskey']);
        $this->delRedisData($sessionkey);
        Utils::setCookie($this->res->config['session']['sskey'],  "",0);
        Utils::setCookie("access_token", "",0);
        Utils::setCookie("refresh_token", "",0);
        Utils::setCookie("account",  "",0);
    }


    function __destruct(){

    }

}
