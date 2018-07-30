<?php
class OAuth extends BaseModelBase{

    private $oMCrypt;
    private $getmember;
    private $accessToken;
    private $refreshToken;
    
    function __construct($dbconfname) {
        parent::__construct($dbconfname);
    }
    
    

    public function getExecute($param){
        
        if(!$param){
            return array( 
                "result" => -5009,
                "success"=>false,
                "error"=>urlencode($this->res->lang->logincheck->validator) );
        }
        
        if(isset($_POST['client_id'])){
            try{
                $_POST['client_id'] = base64_decode($_POST['client_id']);
            } catch (Exception $ex) {
                return array("result" => -590,
                    "success"=>false,
                    "error"=>urlencode($this->res->lang->oauth->notClient."-base"));
            }
        }else{
            return array( 
                "result" => -5010,
                "success"=>false,
                "error"=>urlencode($this->res->lang->logincheck->validator) );
        }
        
        //올바른 클라이언트인지
        $resultclient = $this->checkClient($_POST);
        if(isset($resultclient['result']) && (int)$resultclient['result']<1) return $resultclient;
        
        //유저키가 생성된 시간이 유효한지 확인 && 회원확인
        $resultuser = $this->checkUser($_POST);
        if(isset($resultuser['result']) && (int)$resultuser['result']<1) return $resultuser;
        
        
        //토큰발급
        $resulttoken = $this->makeAccessToken($_POST);
        if(isset($resulttoken['result']) && (int)$resulttoken['result']<0) return $resulttoken;
        
//        //REFRESH 토큰발급
        $resultretoken = $this->makeRefreshToken($_POST);
        if(isset($resultretoken['result']) && (int)$resultretoken['result']<0) return $resultretoken;
        
        return array('result'=>1,'success'=>'true','error'=>'','access_token'=>$this->accessToken,'refresh_token'=>$this->refreshToken,'expire'=>(int)$this->res->config['oauth']['ac_token_expire']);
    }
    
    private function checkClient($param){
        //클라이언트 확인
        
        $rows = $this->execLists(array(),$param);
        if(isset($rows[0]))  $client = $rows[0];
        else{
            $client['result'] = -1;
        }
        if( (int)$client['result']<1 ){
            //올바른 클라이언트인지 확인 후 이용하세요.
            return array("result" => -90,
                    "success"=>false,
                    "error"=>urlencode($this->res->lang->oauth->notClient));
        }
        if($client['client_secret']!=$param['client_secret']){
            return array("result" => -91,
                    "success"=>false,
                    "error"=>urlencode($this->res->lang->oauth->checkSecretKey));
        }
        
        return array("result" => 1,'success'=>'true','error'=>'');
    }
    
    private function checkUser($param){
        
        //회원확인
        $this->oMCrypt = new MCrypt();
        $code = trim($this->oMCrypt->decryptTrim($param['code']));
        $this->getmember = (array)json_decode(stripslashes($code) , true ); //true면 array
        $json_errors = json_last_error();
        $error =  $json_errors;
        
        //회원정보키 생성 시간먼저 확인 접두사 T00
        $mktime = preg_replace("/T00/", "", $this->getmember['mb_time']);
        if(isset($this->getmember['mb_time']) && $this->getmember['mb_time']){
            if(time() > ((int)$mktime+120)){ //발급시간이 2분이 넘었으면 error
                return array("result" => -25,
                    "success"=>false,
                    "error"=>urlencode($this->res->lang->oauth->validatorCode)  );
            }
        }else{
            return array("result" => -061,
                    "success"=>false,
                    "error"=>urlencode($this->res->lang->oauth->validatorUrl)  );
        }

        
        $user = $this->execLists(array(
                    'sql'=>$this->res->ctrl->sql_user,
                    'mapkeys'=>$this->res->ctrl->mapkeys_user,
                    'rowlimit'=>1
                    ), array('mb_id'=>$this->getmember['mb_id']) );
        if(!isset($user[0]) || (int)$user[0]['result']<1){
            
            return array("result" => -96,
                    "success"=>false,
                    "error"=>urlencode($this->res->lang->oauth->authUserFail) );
        }
        if($user[0]['mb_key']!=$this->getmember['mb_key']){
            return array("result" => -97,
                    "success"=>false,
                    "error"=>  urlencode($this->res->lang->oauth->authUserFail));
        }
        return array("result" => 1,'success'=>'true','error'=>'');
    }
    
    private function makeAccessToken($param){

        //유효한 토큰이 있는지 확인
        $tokenkey = md5($this->getmember['mb_id']);
        if($this->getRedisData($tokenkey)){
            $this->accessToken = $tokenkey.'/'.$this->getRedisData($tokenkey);
            return array('result'=>0,'success'=>true); //이미발급되어 있음.
        }else{
            $accessToken = hash('sha256',$this->getmember['mb_id'].$this->getmember['mb_time'].Utils::getClientIP().$param['client_id'].$param['client_secret']);
            $this->accessToken = $tokenkey.'/'.$accessToken;
            $param['access_token'] = $this->accessToken;
            $param['mb_id'] = $this->getmember['mb_id'];
            $result =  $this->execUpdate(array(
                        'sql'=>$this->res->ctrl->sql_acinsert,
                        'mapkeys'=>$this->res->ctrl->mapkeys_acinsert
                        ),$param);
            if($result['result']<1) {
                $result['success'] = false;
            }else{
                $result['success'] = true;
                $this->initRedisServer($this->res->config['redis']['host'],$this->res->config['redis']['port'],null,$this->res->config['redis']['db_token']);
                $this->setRedisData($tokenkey,$accessToken,(int)$this->res->config['oauth']['ac_token_expire']);
            }
        }
 
        return $result;
    }
    
    private function makeRefreshToken($param){
        
        //DB에 유효한 값이 있는지 확인
        $rows = $this->execLists(array(
                    'sql'=>$this->res->ctrl->sql_is_refreshtoken,
                    'mapkeys'=>$this->res->ctrl->mapkeys_is_refreshtoken,
                    'rowlimit'=>1
                    ), array('client_id'=>$param['client_id'],'mb_id'=>$this->getmember['mb_id']));
        if(!isset($rows[0]) || (int)$rows[0]['result']<1){
            //토큰발급
            $this->refreshToken = hash('sha256','R'.$this->getmember['mb_id'].Utils::getClientIP(). time().  mt_rand());
            $param['refresh_token'] = $this->refreshToken;
            $param['mb_id'] = $this->getmember['mb_id'];
            $result =  $this->execUpdate(array(
                        'sql'=>$this->res->ctrl->sql_reinsert,
                        'mapkeys'=>$this->res->ctrl->mapkeys_reinsert
                        ),$param);
            if($result['result']<1) {
                $result['success'] = false;
            }else{
                $result['success'] = true;
            }
        //이미 발급되어 있음.
        }else{
            $this->refreshToken = $rows[0]['refresh_token'];
            return array('result'=>0,'success'=>true);
        }
        
        return $result;
    }
    
    function __destruct(){
        
    }
}
