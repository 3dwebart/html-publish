<?php
class EmailConfirm extends BaseModelBase{

    private $member;
    private $oMcrypt;

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

        if( !isset($param['action']) || !isset($param['type']) ){
            return array(
                "result" => -5010,
                "success"=>false,
                "error"=>$this->res->lang->logincheck->validator);
        }
        $type = strtoupper($param['type']);

        $action = $param['action'];
        
        $this->member = json_decode($this->getMemberDataFromRedis(),TRUE);
        /*****************************************************
        * 이메일 보내기
        ****************************************************/
        if($action=='sendemail'){
           return $this->actionTypeSendEmail($type,$param);

        /*****************************************************
        * 이메일을 통한 인증 처리
        ****************************************************/
        }else if($action=='confirmemail'){
            return $this->actionTypeConfirm($type,$param);
        }
        return array('result'=>0);
    }

    private function actionTypeSendEmail($type,$param){
         /*****************************************************
            * 회원가입 인증메일 재요청
            * 가입 인증 메일 유효시간 오버로 인한 재요청 ( 유효시간 : 가입시간 + 3H )
            * 링크 URL로 회원 확인 및 정보 업데이트 처리 - 토큰 JOIN 재삽입
            ****************************************************/
            if($type == 'REJOIN'){
                if( !isset($param['mb_id'])  ){
                    return array(
                        "result" => -5011,
                        "success"=>false,
                        "error"=>$this->res->lang->logincheck->validator);
                }

                $mbkey = array();
                $mb_id = base64_decode($param['mb_id']);

                $mb_info = $this->execLists(
                    array(
                        'sql'=>$this->res->ctrl->sql,
                        'mapkeys'=>$this->res->ctrl->mapkeys,
                        'rowlimit'=>1
                    )
                    , array('mb_id'=>$mb_id)
                );

                if(isset($mb_info[0])){
                    $mbkey = $mb_info[0];
                    $mb_info = $mb_info[0];
                }

                // id값, level값 체크 후 리턴
                if(!isset($mbkey['mb_id'])){
                    return array(
                        "result" => -6101,
                        "success"=>false,
                        "error"=>$this->res->lang->logincheck->referer);
                }else if(($mbkey['mb_level']) > 0){
                    return array(
                        "result" => -6102,
                        "success"=>false,
                        "error"=>$this->res->lang->logincheck->trueAuth);
                }

                // web_member_email_key 데이터 삽입 처리
                $mk_type    = "JOIN";
                $mk_explain = "Email Re-verification";

                $mb_email_key_result = $this->execUpdate(
//                return $this->execUpdate(
                    array(
                        'sql'=>$this->res->ctrl->sql_regist_email_key,
                        'mapkeys'=>$this->res->ctrl->mapkeys_regist_email_key,
                        'rowlimit'=>1
                    )
                    , array('mb_no'=>$mbkey['mb_no'],
                        'mb_id'=>$mb_id,
                        'mk_type'=>$mk_type,
                        'mk_explain'=>$mk_explain,
                        'md5_mb_id'=>time().$mb_id)
                );

                // email_key의 데이터 삽입이 에러났다면
                if( (int)$mb_email_key_result < 0 ){
                    return array(
                        "result" => -6103,
                        "success"=>false,
                        "error"=>$this->res->lang->response->err21);
                }

                // 새로 삽입된 email_key 정보 획득
                $mb_key_info = $this->execLists(
                    array(
                        'sql'=>$this->res->ctrl->sql_get_email_key,
                        'mapkeys'=>$this->res->ctrl->mapkeys_get_email_key,
                        'rowlimit'=>1
                    )
                    , array('type'=>$mk_type, 'mb_no'=>$mbkey['mb_no'])
                );

                if(isset($mb_key_info[0])){
                    $mbkey = $mb_key_info[0];
                }

                if(!isset($mbkey['mk_email_key'])){
                    return array(
                        "result" => -6104,
                        "success"=>false,
                        "error"=>$this->res->lang->logincheck->referer);
                }

                $mb_email_key = $mbkey['mk_email_key'];
                $mb_en_id = $param['mb_id']; //base64 로 인코딩된 데이터
                $send_confirm_url = '/returnemail/confirm/action-confirmemail/type-prove/id-'.$mb_en_id.'/mbkey-'.$mb_email_key;

                $email_subject          = '';
                $email_type_country     = '';

                if($mb_info['contry_code']=='KR'){
                    $email_subject       = '회원가입 재인증';
                    $email_type_country  = 'request_regist_email_kr';
                }else if($mb_info['contry_code']=='CN'){
                    $email_subject       = '회원가입 재인증메일입니다.';
                    $email_type_country  = 'request_regist_email_cn';
                }else{
                    $email_subject       = 'Re-Verification';
                    $email_type_country  = 'request_regist_email_en';
                }

                $body = $this->getHTMLEmailBody($email_type_country,array(
                    'name'=>$mb_info['mb_name'],
                    'confirm_url'=>$send_confirm_url)
                );
                $mailresult = $this->mailer($mb_id, $email_subject, $body, 1, "", "", "");

                if( (int)$mailresult <= 0 ){
                    return array(
                        "result" => -6105,
                        "success"=>false,
                        "error"=>$this->res->lang->validator->notsendmail);
                }

                return array("result" => $mailresult,
                    "mailsend" => $mailresult);

            /*****************************************************
            * 비밀번호 복구 - from email
            * 비밀번호 분실로 인한 복구 요청
            * 링크 URL로 회원 확인 및 정보 업데이트 처리 - token PWD 삽입 후 메일링
            ****************************************************/
            }else if($type == 'PWD'){
                if( !isset($param['mb_id'])  ){
                    return array(
                        "result" => -5011,
                        "success"=>false,
                        "error"=>$this->res->lang->logincheck->validator);
                }

                $mbkey = array();
                $mb_id = base64_decode($param['mb_id']);

                $mb_info = $this->execLists(
                    array(
                        'sql'=>$this->res->ctrl->sql,
                        'mapkeys'=>$this->res->ctrl->mapkeys,
                        'rowlimit'=>1
                    )
                    , array('mb_id'=>$mb_id)
                );

                if(isset($mb_info[0])){
                    $mbkey = $mb_info[0];
                    $mb_info = $mb_info[0];
                }

                // id값, level값 체크 후 리턴
                if(!isset($mbkey['mb_id'])){
                    return array(
                        "result" => -6201,
                        "success"=>false,
                        "error"=>$this->res->lang->logincheck->referer);
                }

                // web_member_email_key 데이터 삽입 처리
                $mk_type    = "PWD";
                $mk_explain = "Password Recovery";
                $mb_email_key_result = $this->execUpdate(
                    array(
                        'sql'=>$this->res->ctrl->sql_regist_email_key,
                        'mapkeys'=>$this->res->ctrl->mapkeys_regist_email_key,
                        'rowlimit'=>1
                    )
                    , array('mb_no'=>$mbkey['mb_no'],
                        'mb_id'=>$mb_id,
                        'mk_type'=>$mk_type,
                        'mk_explain'=>$mk_explain,
                        'md5_mb_id'=>time().$mb_id)
                );

                // email_key의 데이터 삽입이 에러났다면
                if( (int)$mb_email_key_result < 0 ){
                    return array(
                        "result" => -6203,
                        "success"=>false,
                        "error"=>$this->res->lang->response->err21);
                }

                // 새로 삽입된 email_key 정보 획득
                $mb_key_info = $this->execLists(
                    array(
                        'sql'=>$this->res->ctrl->sql_get_email_key,
                        'mapkeys'=>$this->res->ctrl->mapkeys_get_email_key,
                        'rowlimit'=>1
                    )
                    , array('type'=>$mk_type, 'mb_no'=>$mbkey['mb_no'])
                );

                if(isset($mb_key_info[0])){
                    $mbkey = $mb_key_info[0];
                }

                if(!isset($mbkey['mk_email_key'])){
                    return array(
                        "result" => -6104,
                        "success"=>false,
                        "error"=>$this->res->lang->logincheck->referer);
                }

                $mb_email_key = $mbkey['mk_email_key'];
                $mb_en_id = $param['mb_id']; //base64 로 인코딩된 데이터
                $send_confirm_url = '/returnemail/confirmpwd/action-confirmemail/type-pwd/id-'.$mb_en_id.'/mbkey-'.$mb_email_key;

                $email_subject    = '';
                $email_type_country  = '';

                // contry_code => 디비 오타 있음 주의
                if($mb_info['contry_code']=='KR'){
                    $email_subject       = '비밀번호 초기화';
                    $email_type_country  = 'request_pwd_email_kr';
                }else if($mb_info['contry_code']=='CN'){
                    $email_subject       = '비밀번호 복구요청입니다.';
                    $email_type_country  = 'request_pwd_email_cn';
                }else{
                    $email_subject       = 'Reset Password';
                    $email_type_country  = 'request_pwd_email_en';
                }

                $body = $this->getHTMLEmailBody($email_type_country,array(
                    'name'=>$mb_info['mb_name'],
                    'confirm_url'=>$send_confirm_url
                ));
                $mailresult = $this->mailer($mb_id, $email_subject, $body, 1, "", "", "");

                if( (int)$mailresult <= 0 ){
                    return array(
                        "result" => -6105,
                        "success"=>false,
                        "error"=>$this->res->lang->validator->notsendmail);
                }

                return array("result" => $mailresult,
                    "mailsend" => $mailresult);
            /*****************************************************
            * API키 사용
            ****************************************************/
            }else if($type == 'APIENABLE'){
                
                if(!isset($this->member['mb_id']) || !$this->member['mb_id']){
                    return array(
                        "result" => -5006,
                        "success"=>false,
                        "error"=>$this->res->lang->logincheck->fail);
                }

                if($this->member['mb_api_use'] == 'Y'){
                    return array(
                        "result" => -1,
                        "success"=>false,
                        "error"=>'aready api enabled');
                }

                $mbkey = array();

                // web_member_email_key 데이터 삽입 처리
                $mk_type    = "API";
                $mk_explain = "API Access Confirmation";
                
                $mb_email_key_result = $this->execUpdate(
                    array(
                        'sql'=>$this->res->ctrl->sql_regist_email_key,
                        'mapkeys'=>$this->res->ctrl->mapkeys_regist_email_key,
                        'rowlimit'=>1
                    )
                    , array('mb_no'=>$this->member['mb_no'],
                        'mb_id'=>$this->member['mb_id'],
                        'mk_type'=>$mk_type,
                        'mk_explain'=>$mk_explain,
                        'md5_mb_id'=>time().$this->member['mb_id'])
                );

                // email_key의 데이터 삽입이 에러났다면
                if( (int)$mb_email_key_result < 0 ){
                    return array(
                        "result" => -6203,
                        "success"=>false,
                        "error"=>$this->res->lang->response->err21);
                }
                

                // 새로 삽입된 email_key 정보 획득
                $mb_key_info = $this->execLists(
                    array(
                        'sql'=>$this->res->ctrl->sql_get_email_key,
                        'mapkeys'=>$this->res->ctrl->mapkeys_get_email_key,
                        'rowlimit'=>1
                    )
                    , array('type'=>$mk_type, 'mb_no'=>$this->member['mb_no'])
                );

                if(isset($mb_key_info[0])){
                    $mbkey = $mb_key_info[0];
                }

                if(!isset($mbkey['mk_email_key'])){
                    return array(
                        "result" => -6104,
                        "success"=>false,
                        "error"=>$this->res->lang->logincheck->referer);
                }

                $mb_email_key = $mbkey['mk_email_key'];
                $mb_en_id = base64_encode($this->member['mb_id']);
                $send_confirm_url = '/api/apikey/action-confirmemail/type-apienable/id-'.$mb_en_id.'/mbkey-'.$mb_email_key;
                
                // contry_code => 디비 오타 있음 주의
                if($this->member['contry_code']=='KR'){
                    $email_subject       = 'API 설정 인증';
                    $email_type_country  = 'request_apienable_email_kr';
                }else if($this->member['contry_code']=='CN'){
                    $email_subject       = 'API 설정 인증';
                    $email_type_country  = 'request_apienable_email_cn';
                }else{
                    $email_subject       = 'API Setting Verification';
                    $email_type_country  = 'request_apienable_email_en';
                }

                $body = $this->getHTMLEmailBody($email_type_country,array(
                    'name'=>$this->member['mb_name'],
                    'confirm_url'=>$send_confirm_url
                ));

                $mailresult = $this->mailer($this->member['mb_id'], $email_subject, $body, 1, "", "", "");

                if( (int)$mailresult <= 0 ){
                    return array(
                        "result" => -6105,
                        "success"=>false,
                        "error"=>$this->res->lang->validator->notsendmail);
                }
                
                
                return array("result" => $mailresult,
                    "mailsend" => $mailresult);
            }
    }
    
    private function actionTypeConfirm($type,$param){
        // 디비는 JOIN, 사용자가 인증하러 올경우 PROVE가되니 변경
        if($type == 'PROVE'){
            //가입확인 --->
            $mb_type = 'JOIN';
            //mbkey - 인증키 체크
            if( !isset($param['id']) || !isset($param['mbkey']) ){
                return array(
                    "result" => -5101,
                    "success"=>false,
                    "error"=>$this->res->lang->logincheck->referer);
            }
            // 인증시 인증키가 mbkey로 정의
            $mk_email_key = ($param['mbkey']);
            $mb_id = base64_decode($param['id']);

            // 파라미터로 데이터 조회
            $mb_key_info = $this->execLists(
                array(
                    'sql'=>$this->res->ctrl->sql,
                    'mapkeys'=>$this->res->ctrl->mapkeys,
                    'rowlimit'=>1
                )
                , array('type'=>$mb_type, 'mb_id'=>$mb_id)
            );

            $mbkey = array();
            if(isset($mb_key_info[0])){
                $mbkey = $mb_key_info[0];
            }

            // mb_id 유효한지 체크
            if(!isset($mbkey['mb_id'])){
                return array(
                    "result" => -5101,
                    "success"=>false,
                    "error"=>$this->res->lang->logincheck->referer);
            // 인증된 사용자라면 '인증된 사용자 입니다.'
            }else if(($mbkey['mk_confirm_yn'])=="Y"){
                return array(
                    "result" => -5102,
                    "success"=>false,
                    "error"=>$this->res->lang->logincheck->trueAuth);
            // 유효 키값 체크   -- 올바른 경로로 이동하세요
            }else if( ($mbkey['mk_email_key'])!=$mk_email_key ){
                return array(
                    "result" => -5101,
                    "success"=>false,
                    "error"=>$this->res->lang->logincheck->referer);
            // 유효시간 체크 -- 유효시간이 지났습니다. 재 인증 요청을 진행하세요.
            }else if( strtotime($mbkey['mk_expire_dt']) < time() ){
                return array(
                    "result" => -5103,
                    "success"=>false,
                    "error"=>$this->res->lang->logincheck->expireDt);
            }
            // 사용자 유효 체크

            // 유저 값 체크 - 비트코인 주소 있는지 여부
            $mb_info_result = $this->execLists(
                array(
                    'sql'=>$this->res->ctrl->sql_get_member,
                    'mapkeys'=>$this->res->ctrl->mapkeys_get_member,
                    'rowlimit'=>1
                )
                , array(
                    'mb_id'=>$mb_id)
            );
            $mb_info = array();
            if(isset($mb_info_result[0])){
                $mb_info = $mb_info_result[0];
            }
            // mb_id 유효한지 체크
            if(!isset($mb_info['mb_id'])){
                return array(
                    "result" => -5101,
                    "success"=>false,
                    "error"=>$this->res->lang->logincheck->referer);
            }

            $mk_confirm_ip = Utils::getClientIP();
            $mk_confirm_yn = 'Y';
            $prove_update_result = $this->execLists(
                array(
                    'sql'=>$this->res->ctrl->sql_prove_update,
                    'mapkeys'=>$this->res->ctrl->mapkeys_prove_update,
                    'rowlimit'=>1
                )
                , array('mk_confirm_yn'=>$mk_confirm_yn,
                    'mk_confirm_ip'=>$mk_confirm_ip,
                    'mk_etc'=>'',
                    'type'=>$mb_type,
                    'mb_id'=>$mb_id)
            );

            if( (int)$prove_update_result < 0 ){
                return array(
                    "result" => -5104,
                    "success"=>false,
                    "error"=>$this->res->lang->response->err21);
            }

            return array("result"=>1);

        
        /*****************************************************
        * 가입된 회원 비밀번호 복구 요청 - 비밀번호 변경 
        ****************************************************/
        }else if($type == 'PWD'){

            if( !isset($param['mb_pwd']) ){
                return array(
                    "result" => -5101,
                    "success"=>false,
                    "error"=>$this->res->lang->logincheck->referer);
            }else if( !isset($param['mbkey']) ){
                return array(
                    "result" => -5102,
                    "success"=>false,
                    "error"=>$this->res->lang->logincheck->referer);
            }else if( !isset($param['mb_id']) ){
                return array(
                    "result" => -5103,
                    "success"=>false,
                    "error"=>$this->res->lang->logincheck->referer);
            }

            // 인증시 인증키가 mbkey로 정의
            $mk_email_key = ($param['mbkey']);
            $mb_id  = base64_decode($param['mb_id']);
            $mb_pwd = ($param['mb_pwd']);

            // 정보 조회
            $mb_key_info = $this->execLists(
                array(
                    'sql'=>$this->res->ctrl->sql,
                    'mapkeys'=>$this->res->ctrl->mapkeys,
                    'rowlimit'=>1
                )
                , array('type'=>$type, 'mb_id'=>$mb_id)
            );

            $mb_info = array();
            if(isset($mb_key_info[0])){
                $mb_info = $mb_key_info[0];
            }

            // mb_id 유효한지 체크 (tbl-web_member)
            if( !isset($mb_info['mb_id']) ){
                return array(
                    "result" => -5104,
                    "success"=>false,
                    "error"=>$this->res->lang->logincheck->referer);
            }else if(($mb_info['mk_confirm_yn'])=="Y"){
                return array(
                    "result" => -5105,
                    "success"=>false,
                    "error"=>$this->res->lang->logincheck->trueAuth);
            // 유효 키값 체크   -- 올바른 경로로 이동하세요
            }else if( ($mb_info['mk_email_key'])!=$mk_email_key ){
                return array(
                    "result" => -5106,
                    "success"=>false,
                    "error"=>$this->res->lang->logincheck->referer);
            // 유효시간 체크 -- 유효시간이 지났습니다. 재 인증 요청을 진행하세요.
            }else if( strtotime($mb_info['mk_expire_dt']) < time() ){
                return array(
                    "result" => -5107,
                    "success"=>false,
                    "error"=>$this->res->lang->logincheck->expireDt);
            }

            $mb_no = $mb_info['mb_no'];
            $mk_no = $mb_info['mk_no'];
            $mk_confirm_ip = Utils::getClientIP();
            $mk_confirm_yn = 'Y';



            //비밀번호 다시 한번 더 암호화, 사이트 임의의 키
            $enkey1 = 'siterand';
            $enkey2 = 'siteafter';
            if(isset($this->res->config['encode'])){
                $enkey1 = $this->res->config['encode']['passwd1'];
                $enkey2 = $this->res->config['encode']['passwd2'];
            }



            $mb_pwd = hash('sha512',$enkey1.$mb_pwd,false).hash('sha512', $mb_id.$enkey2 , false);
            $mb_pwd = substr($mb_pwd,0,250);


            $update_result = $this->execUpdate(
                array(
                    'sql'=>$this->res->ctrl->sql_update,
                    'mapkeys'=>$this->res->ctrl->mapkeys_update,
                    'rowlimit'=>1
                )
                , array(
                    'mb_encPwd'=>$mb_pwd,
                    'mb_pwd'=>$mb_pwd,
                    'mb_no'=>$mb_no)
            );

            if( $update_result < 0 ){
                return array(
                    "result" => -5108,
                    "success"=>false,
                    "error"=>$this->res->lang->logincheck->referer);
            }

            $email_update_result = $this->execUpdate(
                array(
                    'sql'=>$this->res->ctrl->sql_email_key_update,
                    'mapkeys'=>$this->res->ctrl->mapkeys_email_key_update,
                    'rowlimit'=>1
                )
                , array('mk_confirm_yn'=>'Y',
                    'mk_confirm_ip'=>$mk_confirm_ip,
                    'mk_no'=>$mk_no)
            );

            if( (int)$email_update_result < 0 ){
                return array(
                    "result" => -5109,
                    "success"=>false,
                    "error"=>$this->res->lang->response->err21);
            }
            return array("result"=>1);
        /*****************************************************
        * API 사용여부 인증
        ****************************************************/
        }else if($type == 'APIENABLE'){
            $mb_type = 'API';
            if(!isset($this->member['mb_id']) || !$this->member['mb_id']){
                return array(
                    "result" => -5006,
                    "success"=>false,
                    "error"=>$this->res->lang->logincheck->fail);
            }
            
            if( !isset($param['id']) || !isset($param['mbkey']) ){
                return array(
                    "result" => -5101,
                    "success"=>false,
                    "error"=>$this->res->lang->logincheck->referer);
            }

            // 인증시 인증키가 mbkey로 정의
            $mk_email_key = ($param['mbkey']);
            $mb_id = base64_decode($param['id']);

            // 파라미터로 데이터 조회
            $mb_key_info = $this->execLists(
                array(
                    'sql'=>$this->res->ctrl->sql,
                    'mapkeys'=>$this->res->ctrl->mapkeys,
                    'rowlimit'=>1
                )
                , array('type'=>$mb_type, 'mb_id'=>$mb_id)
            );

            $mbkey = array();
            if(isset($mb_key_info[0])){
                $mbkey = $mb_key_info[0];
            }

            // mb_id 유효한지 체크
            if(!isset($mbkey['mb_id'])){
                return array(
                    "result" => -5101,
                    "success"=>false,
                    "error"=>$this->res->lang->logincheck->referer);
            // 인증된 사용자라면
            }else if(($mbkey['mk_confirm_yn'])=="Y"){
                //redis 회원정보 갱신
                $this->member['mb_api_use'] = 'Y';
                $this->setUserSession(json_encode($this->member));
                
                return array(
                    "result" => -5102,
                    "success"=>false,
                    "error"=>$this->res->lang->logincheck->trueAuth);
            // 유효 키값 체크   -- 올바른 경로로 이동하세요
            }else if( ($mbkey['mk_email_key'])!=$mk_email_key ){
                return array(
                    "result" => -5101,
                    "success"=>false,
                    "error"=>$this->res->lang->logincheck->referer);
            // 유효시간 체크 -- 유효시간이 지났습니다. 재 인증 요청을 진행하세요.
            }else if( strtotime($mbkey['mk_expire_dt']) < time() ){
                return array(
                    "result" => -5103,
                    "success"=>false,
                    "error"=>$this->res->lang->logincheck->expireDt);
            }
            // 사용자 유효 체크

            // 유저 값 체크 - 비트코인 주소 있는지 여부
            $mb_info_result = $this->execLists(
                array(
                    'sql'=>$this->res->ctrl->sql_get_member,
                    'mapkeys'=>$this->res->ctrl->mapkeys_get_member,
                    'rowlimit'=>1
                )
                , array(
                    'mb_id'=>$mb_id)
            );
            $mb_info = array();
            if(isset($mb_info_result[0])){
                $mb_info = $mb_info_result[0];
            }
            // mb_id 유효한지 체크
            if(!isset($mb_info['mb_id'])){
                return array(
                    "result" => -5101,
                    "success"=>false,
                    "error"=>$this->res->lang->logincheck->referer);
            }

            // 유효시간, 인증키 유효하다면
            $mk_confirm_ip = Utils::getClientIP();
            $mk_confirm_yn = 'Y';

            $prove_update_result = $this->execLists(
                array(
                    'sql'=>$this->res->ctrl->sql_prove_update,
                    'mapkeys'=>$this->res->ctrl->mapkeys_prove_update,
                    'rowlimit'=>1
                )
                , array('mk_confirm_yn'=>$mk_confirm_yn,
                    'mk_confirm_ip'=>$mk_confirm_ip,
                    'mk_etc'=>'',
                    'type'=>$mb_type,
                    'mb_id'=>$mb_id)
            );

            if( (int)$prove_update_result < 0 ){
                return array(
                    "result" => -5104,
                    "success"=>false,
                    "error"=>$this->res->lang->response->err21);
            }
            
            //redis 회원정보 갱신
            $this->member['mb_api_use'] = 'Y';
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

    private function getHTMLEmailBody($skin_html_file,$user=array('name'=>'','confirm_url'=>'')){
        $logo_url = '/desktop/img/common/logo_email_email.png';

        $html = file_get_contents('../WebApp/ViewHTML/'.$skin_html_file.'.html');
        $html = str_replace("{site_name}", $this->res->config['html']['title'],$html);
        $html = str_replace("{user_name}", $user['name'],$html);
        $html = str_replace("{logo_url}", $this->res->config['url']['static'].$logo_url,$html);
        $html = str_replace("{confirm_url}", $this->res->config['url']['site'].$user['confirm_url'],$html);
        return $html;
    }

    function __destruct(){

    }



}
