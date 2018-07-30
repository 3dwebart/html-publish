<?php
class NameCheck extends BaseModelBase{
    
    private $member;

    function __construct($dbconfname=null) {
        parent::__construct($dbconfname);

    }
    /*
     * 실행
     * @param url param
     */
    public function getExecute($param){

        // 필수입력 값이 입력되지 않았습니다
        if( !isset($param['mbname']) || !isset($param['mbbirth']) || !isset($param['mbgender']) || !isset($param['mbhp']) ){
            return array(
                "result" => -5301,
                "success"=>false,
                "error"=>$this->res->lang->validator->required);
        }

        $mb_name        = base64_decode($param['mbname']);
        $mb_name        = urldecode($mb_name);
        $mb_birth       = base64_decode($param['mbbirth']);
        $mb_gender      = base64_decode($param['mbgender']);
        $mb_hp          = base64_decode($param['mbhp']);
        $mb_level       = 2;

        $sql_get_mb_info = $this->execLists(
            array(
                'sql'=>$this->res->ctrl->sql_get_mb_info,
                'mapkeys'=>$this->res->ctrl->mapkeys_get_mb_info,
                'rowlimit'=>1
            ),array()
        );

        // 회원 정보 확인
        $mb_info = array();
        if( isset($sql_get_mb_info[0]) ){
            $mb_info = $sql_get_mb_info[0];
        }

        if( !isset($mb_info['mb_no']) ){
            return array(
                "result" => -5302,
                "success"=>false,
                 "error"=>$this->res->lang->logincheck->notMember);
        }

        if( $mb_info['mb_level']>1){
            $mb_level = $mb_info['mb_level'];
        }

        // 회원 정보 업데이트
        $sql_set_mb_info = $this->execUpdate(
            array(
                'sql'=>$this->res->ctrl->sql_set_mb_info,
                'mapkeys'=>$this->res->ctrl->mapkeys_set_mb_info,
                'rowlimit'=>1
            ),
            array(
                'mb_name'=>$mb_name,
                'mb_birth'=>$mb_birth,
                'mb_gender'=>$mb_gender,
                'mb_level'=>$mb_level
            )
        );

        $mb_verification_result = array();
        if(isset($sql_set_mb_info['result']) && $sql_set_mb_info['result']>0){
            // 인증 데이터 삽입
            $mb_verification_result = $this->execUpdate(
            array(
                'sql'=>$this->res->ctrl->sql_verification_regist,
                'mapkeys'=>$this->res->ctrl->mapkeys_verification_regist,
                'rowlimit'=>1
            )
            , array(
                'mb_cur_level'=>$mb_info['mb_level']
                ,'mb_req_level'=>$mb_level
                ,'mb_prove_method'=>'NiceID'
                ,'admin_confirm'=>'A'
                ,'admin_memo'=>'본인인증'.'<br/>'.$mb_hp
                )
            );
        }else{
            return array(
                "result" => -5303,
                "success"=>false,
                "error"=>$this->res->lang->account->levelRequestFail);
        }

        $this->member = json_decode($this->getMemberDataFromRedis(),TRUE);
            
        if(!isset($this->member['mb_no']) || !$this->member['mb_no']){
            return array(
                "result" => -5006,
                "success"=>false,
                "error"=>$this->res->lang->logincheck->fail);
        }

        $this->member['mb_level'] = 2;

        $this->setUserSession(json_encode($this->member));

        return array("result" => $mb_verification_result);
    }



    private function setUserSession($userdata){

        $session_key = Utils::getCookie($this->res->config['session']['sskey']);
        $session_expire = ((int)$this->res->config['session']['cache_expire'] * 60);
    
        //redis 서버
        $this->initRedisServer($this->res->config['redis']['host'],
                $this->res->config['redis']['port'],null,
                $this->res->config['redis']['db_member']);
        
        //세션정보 삽입
        if($this->redis->exists($session_key)){
            //기존의 세션 삭제
            $this->redis->del($session_key);
        }
        $this->setRedisData($session_key,$userdata,$session_expire); 
    }



    function __destruct(){

    }

}
