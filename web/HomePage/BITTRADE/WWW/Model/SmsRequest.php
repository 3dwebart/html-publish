<?php
class SmsRequest extends BaseModelBase{

    private $member;

    function __construct($dbconfname=null) {
        parent::__construct($dbconfname);

    }
    /*
     * 실행
     * @param url param
     */
    // 회원가입에서 SMS 인증 번호 요청
    public function getExecute($param){

        // 중요 파라미터 타입, 회원아이디, 모바일 번호
        if( !isset($param['type']) || !isset($param['mbid']) || !isset($param['mbhp']) ){
            return array(
                "result" => -5001,
                "success" => false,
                "error" => $this->res->lang->validator->required );
        }

        $param['type'] = strtoupper($param['type']);
        $param['mbid'] = base64_decode($param['mbid']);
        $param['mbid'] = preg_replace("/\s+/", "", $param['mbid']);
        $param['mbhp'] = base64_decode($param['mbhp']);
        $param['mbhp'] = preg_replace("/\s+/", "", $param['mbhp']);

        // 휴대폰 번호 길이 체크
        if(strlen((int)$param['mbhp'])<9 ){
            return array(
                "result"=>-5002,
                "success"=>false,
                "error"=>$this->res->lang->validator->mobile );
        }

        if($param['type']==='PROVE'){
            // 회원가입 SMS 요청 10분 LOCK
            return $this->_sendSMS($param);
        }
        
        return array('result'=>-1);
    }



    // 회원 모바일 인증 SMS 요청 10분 LOCK
    private function _sendSMS($param){

        // 파라미터에 시간을 보내서 +1분
        // 현재시간과 비교하여 오래된거면 넘긴다.
        if( ($param['reqdt']+60) < time()){
            return array(
                "result"=>-5031,
                "success"=>false,
                "error"=>$this->res->lang->module->smsnotvalidator );
        }

        $code = rand(111111,999999);  // 인증번호 랜덤
        $sms_reg_ip = Utils::getClientIP();     // IP

        $param['dialcode'] = base64_decode($param['dialcode']);

        // 앞자리 0 없을 경우 0 붙여주기
        if(substr($param['mbhp'], 0, 1)!='0'){
            $param['mbhp'] = '0'.$param['mbhp'];
        }
        // 국제 발송용 번호로 변경 - 인증키 삽입 부분의 번호는 무조건 국제 번호로 삽입
        $param['dialcode'] = str_replace('+', '', $param['dialcode']);
        $param['dialcode'] = str_replace(' ', '', $param['dialcode']);
        // 국제 번호
        $mb_hp = $param['dialcode'].substr($param['mbhp'], 1, strlen($param['mbhp']));

        // SMS 발송 제한을 위한 체크 (하루 5번 체크시간 기준-1day) - 모바일 번호 기준
        $sql_count_result = $this->execLists(
            array(
                'sql'=>$this->res->ctrl->sql_count_result,
                'mapkeys'=>$this->res->ctrl->mapkeys_count_result,
                'rowlimit'=>1
            )
            , array('sms_hp'=>$mb_hp)
        );

        if( isset($sql_count_result[0]) && ($sql_count_result[0]['result'] >= (int)$this->res->config['sms']['sms_send_limit']) ){
            return array(
                "result"=>-5033,
                "success"=>false,
                "error"=>$this->res->lang->module->smsvalidatorover );
        }

        // SMS 발송 제한을 위한 체크 (하루 5번 체크시간 기준-1day) - ip 기준
        $sql_ip_count_result = $this->execLists(
            array(
                'sql'=>$this->res->ctrl->sql_ip_count_result,
                'mapkeys'=>$this->res->ctrl->mapkeys_ip_count_result,
                'rowlimit'=>1
            )
            , array('reg_ip'=>$sms_reg_ip)
        );

        if( isset($sql_ip_count_result[0]) && ($sql_ip_count_result[0]['result'] >= (int)$this->res->config['sms']['sms_send_limit']) ){
            return array(
                "result"=>-5034,
                "success"=>false,
                "error"=>$this->res->lang->module->smsvalidatorover );
        }


        // 최근 내역 리스트 (있다면 유효시간 체크 10분)
        // 151023 sms_type 제거
        $sql_is_send_list = $this->execLists(
            array(
                'sql'=>$this->res->ctrl->sql_is_send_list,
                'mapkeys'=>$this->res->ctrl->mapkeys_is_send_list,
                'rowlimit'=>1
            )
            , array('sms_hp'=>$mb_hp,
                    'sms_status'=>'REQ')
        );


        // 유효시간 체크 10분이상 되었으면
        if( isset($sql_is_send_list[0]['reg_dt']) && strtotime($sql_is_send_list[0]['reg_dt'].' +10 MINUTE') > time() ){
            return array(
                "result"=>-5035,
                "success"=>false,
                "error"=>$this->res->lang->module->smsvalidator);
        }

        // SMS 인증키값 삽입 - (국제 번호로 삽입, 중복 방지를 위해서)
        $sms_certify_insert_result = $this->execUpdate(
            array(
                'sql'=>$this->res->ctrl->sql_insert_sms_certify,
                'mapkeys'=>$this->res->ctrl->mapkeys_insert_sms_certify,
                'rowlimit'=>1
            )
            , array('sms_id'=>$param['mbid'],
                'sms_hp'=>$mb_hp,
                'sms_certify'=>$code,
                'sms_type'=>$param['type'],
                'reg_ip'=>$sms_reg_ip)
        );


        // sms 인증키값 삽입 유무 체크
        if($sms_certify_insert_result==0){
            return array(
                "result" => -5036,
                "success" => false,
                "error" => $this->res->lang->module->smssendfail);
        }

        // config SMS provider
        $sms_provider = $this->res->config['sms']['sms_provider'];
        if(!isset($sms_provider)){
            return array(
                "result" => -5037,
                "success" => false,
                "error" => $this->res->lang->module->smssendfail); // 고객센터로 문의 변경처리요망
        }

        // 모바일 인증 - SMS 보낼 내용 셋팅
        $sms_msg = $this->res->config['sms']['site_name'].' 고객센터입니다. '.'모바일 인증번호 ['.$code.']를 입력해 주세요.';

        // sms 전송 처리 (web_sms_seder 로 삽입, 테이블 트리거로 provider에 따라 분기 처리)
        $sms_sender_result = $this->execUpdate(
            array(
                'sql'=>$this->res->ctrl->sql_insert_sms_sender,
                'mapkeys'=>$this->res->ctrl->mapkeys_insert_sms_sender,
                'rowlimit'=>1
            )
            , array(
                'mb_id'=>$param['mbid']
                ,'ss_is_mms'=>'N'
                ,'ss_is_inter'=>''
                ,'ss_tel_code'=>$param['dialcode']
                ,'ss_tel_num'=>$param['mbhp']
                ,'ss_content'=>$sms_msg
                ,'ss_page_type'=>'PROVE'
                ,'ss_provider'=>$sms_provider
            )
        );

        // sms 전송 실패시
        if($sms_sender_result['result']<=0){

            // 전송 실패시 - 자동 취소 처리 (단 앞에서 체크하던 카운트는 올라간다.)
            $sql_update_cancel = $this->execUpdate(
                array(
                    'sql'=>$this->res->ctrl->sql_update_sms_cancel,
                    'mapkeys'=>$this->res->ctrl->mapkeys_update_sms_cancel,
                    'rowlimit'=>1
                )
                , array('new_sms_status'=>'CAN',
                        'sms_hp'=>$param['mbhp'],
                        'sms_type'=>$param['type'],
                        'old_sms_status'=>'REQ')
            );
            return array(
                "result" => -5037,
                "success" => false,
                "error" => $this->res->lang->module->smssendfail);
        }else{
            return array("result"=>1);
        }
    }


    function __destruct(){

    }

}
