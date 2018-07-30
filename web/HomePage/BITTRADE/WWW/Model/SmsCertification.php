<?php
class SmsCertification extends BaseModelBase{

    private $member;

    function __construct($dbconfname=null) {
        parent::__construct($dbconfname);

    }
    /*
     * 실행
     * @param url param
     */
    // 인증센터 모바일 인증
    public function getExecute($param){

        // 중요파라미터
        // 회원 모바일 번호, 인증번호, 국가코드, 국가코드 번호
        if( !isset($param['mb_hp']) || !isset($param['mb_cert_number']) || !isset($param['country']) || !isset($param['mb_country_dial_code'])){
            return array(
                "result" => -5501,
                "success" => false,
                "error" => $this->res->lang->validator->required );
        }

        $param['mb_hp']                 = base64_decode($param['mb_hp']);
        $param['mb_cert_number']        = base64_decode($param['mb_cert_number']);
        $param['country']               = base64_decode($param['country']);
        $param['mb_country_dial_code']  = base64_decode($param['mb_country_dial_code']);
        $mb_hp                          = $param['mb_hp'];

        // 휴대폰 번호 길이 체크
        if(strlen((int)$param['mb_hp'])<9 ){
            return array(
                "result"=>-5502,
                "success"=>false,
                "error"=>$this->res->lang->validator->mobile );
        }

        // mbhp 국제발송용 번호로 변경
        if(substr($param['mb_hp'], 0, 1)=='0'){
            $mb_hp = (str_replace('+', '', $param['mb_country_dial_code'])).substr($param['mb_hp'], 1, strlen($param['mb_hp']));
        }else{
            $mb_hp = (str_replace('+', '', $param['mb_country_dial_code'])).$param['mb_hp'];
        }

        // 인증번호 유효성 체크 시작
        $mb_get_sms_certify = $this->execLists(
            array(
                'sql'=>$this->res->ctrl->sql_get_sms_certify,
                'mapkeys'=>$this->res->ctrl->mapkeys_get_sms_certify,
                'rowlimit'=>1
            ),
            array(
                'sms_hp'=>$mb_hp)
        );

        $mb_sms_key = array();
        if( isset($mb_get_sms_certify[0]) ){
            $mb_sms_key = $mb_get_sms_certify[0];
        }

        // 인증번호 유효성 체크
        if( !isset($mb_sms_key['sms_certify']) || $mb_sms_key['sms_certify']!==$param['mb_cert_number'] ){
            return array(
                "result" => -5503,
                "success"=>false,
                "error"=>$this->res->lang->validator->notcertified);
        }

        // redis member info get
        $member = json_decode($this->getMemberDataFromRedis('sum'));
        if(!isset($member->mb_id) || !$member->mb_id){
            return array(
                "result" => -5504,
                "success"=>false,
                "error"=>$this->res->lang->logincheck->notLogin);
        }

        // contry_code 
        $sql_regist = $this->execUpdate(
            array(
                'sql'=>$this->res->ctrl->sql_regist,
                'mapkeys'=>$this->res->ctrl->mapkeys_regist,
                'rowlimit'=>1
            )
            , array(
                'mb_name'=>$member->mb_name
                ,'mb_hp'=>$param['mb_hp']
                ,'contry_code'=>$param['country']
                ,'mb_country_dial_code'=>$param['mb_country_dial_code']
            )
        );

        // 인증 결과 삽입 체크
        if($sql_regist['result']<0){
            return array(
                "result" => -5505,
                "success" => false,
                "error" => $this->res->lang->account->levelRequestFail);
        }else{
            $this->member = json_decode($this->getMemberDataFromRedis(),TRUE);
            
            if(!isset($this->member['mb_no']) || !$this->member['mb_no']){
                return array(
                    "result" => -5006,
                    "success"=>false,
                    "error"=>$this->res->lang->logincheck->fail);
            }
            
            $this->member['mb_hp'] = $param['mb_hp'];
        
            $this->setUserSession(json_encode($this->member));
            return array("result"=>1);
        }
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
