<?php
class BalanceRedisFromServer extends BaseModelBase{

    private $member;

    function __construct($dbconfname=null) {
        parent::__construct($dbconfname);
    }
    /*
     * 실행
     * @param url param
     */
    public function getExecute($param){
        $loginstatus = 0;
        
        if(isset($param['cmd']) && $param['cmd']=='sum'){
        // 레디스 갱신전 프로시저 통해서 포인트 재연산
            $call_proc = $this->execUpdate(array(
                        'sql'=>$this->res->ctrl->sql_call_proc,
                        'mapkeys'=>$this->res->ctrl->mapkeys_call_proc,
                        'rowlimit'=>1
                    ), $param);
        }
        
        if(!isset($param['ac']) || !$param['ac']){
            $param['ac'] = 'websum';
        }

        //redis 서버
        $this->initRedisServer($this->res->config['redis']['host'],
                $this->res->config['redis']['port'],null,
                $this->res->config['redis']['db_member']);
        $session_expire = ((int)$this->res->config['session']['cache_expire'] * 60);
        $session_key = Utils::getCookie($this->res->config['session']['sskey']);
        $tmpmember = $this->getRedisData($session_key);
        if(!$tmpmember){
            return array("result" => -998,
                    "success"=>false,
                    "error"=>$this->res->lang->logincheck->expire);
        }
        $redismember = json_decode($tmpmember,TRUE);        
        
        $resultmember = $redismember;

        $mbsearchkey = '';
        
        if($session_key){
            $loginstatus = 1;
            $tmp = @explode("-", $session_key);
            $mbsearchkey = $tmp[0];
        }
        
        
        
        //balance
        $balancedata = $this->execLists(array(),$param);
        
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
        $this->setSesstionTimeExtension();
        return array('success'=> true,'member'=>  $resultmember,'balance'=>  $balancedata,'loginstatus'=>$loginstatus );
    }
    
    
    private function setSesstionTimeExtension(){
        $session_expire = ((int)$this->res->config['session']['cache_expire'] * 60);
        
        $get_cookie = Utils::getCookie($this->res->config['session']['sskey']);
        
        if($get_cookie){
            Utils::setCookie($this->res->config['session']['sskey'],  $get_cookie , $session_expire);
            $mb_key_tmp = explode('-', $get_cookie);
            $get_balance_key = $mb_key_tmp[0].'-balance';
            $this->initRedisServer($this->res->config['redis']['host'],$this->res->config['redis']['port'],null,$this->res->config['redis']['db_member']);
            $this->redis->expire($get_cookie,$session_expire);
            $this->redis->expire($get_balance_key,$session_expire);
        }
                
    }


    function __destruct(){

    }



}
