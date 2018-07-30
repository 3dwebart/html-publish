<?php
class SignUp extends BaseModelBase{

    private $member;

    function __construct($dbconfname=null) {
        parent::__construct($dbconfname);

    }
    /*
     * 회원가입 모델
     * @param url param
     */
    public function getExecute($param){

        // 회원가입시 SMS 인증 여부
        $is_sms_signup_use = isset($this->res->config['sms']['is_sms_signup_use'])?$this->res->config['sms']['is_sms_signup_use']:false;
        if(!isset($is_sms_signup_use)){   // 없으면 기본값
            $is_sms_signup_use = false;
        }

        //reCAPTCHA
//        if( !isset($param['g-recaptcha-response']) || !$param['g-recaptcha-response'] ){
//            return array("result" => -800,
//                    "success"=>false,
//                    "error"=>$this->res->lang->validator->captcha);
//        }
//
//        $postdata = http_build_query(
//                array('secret'=>$this->res->config['recaptcha']['secret'],
//                    'remoteip'=>  Utils::getClientIP(),
//                    'response'=>$param['g-recaptcha-response'])
//                );
//
//        $opts = array(
//            'http'=>array(
//            'method'=>"POST",
//            'header'=>"Accept-language: ".$_SERVER['HTTP_ACCEPT_LANGUAGE']."\r\n" .
//                "Content-Type: application/x-www-form-urlencoded; charset=UTF-8\r\n",
//            'content'=>$postdata
//            )
//        );
//
//        $context = stream_context_create($opts);
//        $rescov = file_get_contents($this->res->ctrl->recaptchaurl,false, $context);
//        $resjson = json_decode($rescov,true);
//
//        if( !isset($resjson['success']) || !$resjson['success'] ){
//            return array("result" => -801,
//                    "success"=>false,
//                    "error"=>$this->res->lang->validator->captcha);
//        }

        // 필수입력 값이 입력되지 않았습니다
        if( !isset($param['mb_id']) || !isset($param['mb_pwd']) || !isset($param['mb_pwd_re'])
                || !isset($param['mb_last_name']) || !isset($param['mb_first_name']) /* || !isset($param['country_code']) */
                /* || !isset($param['mb_country_dial_code']) */ ) {
            return array(
                "result" => -5101,
                "success"=>false,
                "error"=>$this->res->lang->validator->required);
        }

        // SMS 인증 여부에 따른 파라미터 체크
        if($is_sms_signup_use){
            if( !isset($param['mb_hp']) || !isset($param['mb_cert_number']) ){
            return array(
                "result" => -5101,
                "success"=>false,
                "error"=>$this->res->lang->validator->required);
            }
        }


        $mb_id              = base64_decode($param['mb_id']);
        $mb_pwd             = $param['mb_pwd'];
        $mb_pwd_re          = $param['mb_pwd_re'];
        $mb_last_name       = base64_decode($param['mb_last_name']);
        $mb_last_name       = urldecode($mb_last_name);
        $mb_first_name      = base64_decode($param['mb_first_name']);
        $mb_first_name      = urldecode($mb_first_name);
        $mb_hp              = null;
        $mb_cert_number     = null;
        if($is_sms_signup_use){
            $mb_hp              = base64_decode($param['mb_hp']);
            $mb_cert_number     = base64_decode($param['mb_cert_number']);
        }
        //$mb_country_code    = $param['country_code'];
		$mb_country_code    = '';
        //$mb_country_dial_code    = base64_decode($param['mb_country_dial_code']);
		$mb_country_dial_code    = '';

		$mb_zip_code = base64_decode($param['mb_zip_code']);
		$mb_address = urldecode(base64_decode($param['mb_address']));
		$mb_detail_address = urldecode(base64_decode($param['mb_detail_address']));
		
        //비밀번호 다시 한번 더 암호화, siteafter는 사이트 임의의 키
        $enkey1 = 'siterand';
        $enkey2 = 'siteafter';
        if(isset($this->res->config['encode'])){
            $enkey1 = $this->res->config['encode']['passwd1'];
            $enkey2 = $this->res->config['encode']['passwd2'];
        }
        
        
        $mb_pwd = hash('sha512', $enkey1.$mb_pwd , false).hash('sha512', $mb_id.$enkey2 , false);
        $mb_enPwd = substr($mb_pwd,0,250);
        

        // 국가에 따른 - 성,이름 표기 변경
		/*
        if($mb_country_code=='KR' || $mb_country_code=='KP' || $mb_country_code=='CN' || $mb_country_code=='JP'){
            $mb_name            = $mb_last_name.$mb_first_name;
        }else{
            $mb_name            = $mb_first_name.' '.$mb_last_name;
        }
		 */
		$mb_name            = $mb_last_name.$mb_first_name;

        // SMS 인증 여부에 따른 조건 체크 - 인증번호 유효성
        if($is_sms_signup_use){

            $mb_dialcode_hp = $mb_hp;
            $mb_dialcode_remove_str = '';
            if(substr($mb_hp, 0, 1)=='0'){
                $mb_dialcode_remove_str = str_replace('+', '', $mb_country_dial_code);
                $mb_dialcode_hp = $mb_dialcode_remove_str.substr($mb_hp, 1, strlen($mb_hp));
            }else{
                $mb_dialcode_remove_str = str_replace('+', '', $mb_country_dial_code);
                $mb_dialcode_hp = $mb_dialcode_remove_str.$mb_hp;
            }
            // 인증번호 유효성 체크 시작
            $mb_get_sms_certify = $this->execLists(
                array(
                    'sql'=>$this->res->ctrl->sql_get_sms_certify,
                    'mapkeys'=>$this->res->ctrl->mapkeys_get_sms_certify,
                    'rowlimit'=>1
                ),
                array(
                    'sms_hp'=>$mb_dialcode_hp)
            );

            $mb_sms_key = array();
            if( isset($mb_get_sms_certify[0]) ){
                $mb_sms_key = $mb_get_sms_certify[0];
            }

            if( !isset($mb_sms_key['sms_certify']) || $mb_sms_key['sms_certify']!=$mb_cert_number ){
                return array(
                    "result" => -5102,
                    "success"=>false,
                    "error"=>$this->res->lang->validator->notcertified);
            }
        }

        // 회원 가입
        $mb_resigt_result = $this->execUpdate(
            array(
                'sql'=>$this->res->ctrl->sql_regist,
                'mapkeys'=>$this->res->ctrl->mapkeys_regist,
                'rowlimit'=>1
            )
            , array(
                'mb_id'=>$mb_id,
                'mb_nick'=>'',
                'mb_name'=>$mb_name,
                'mb_last_name'=>$mb_last_name,
                'mb_first_name'=>$mb_first_name,
                'mb_hp'=>$mb_hp,
                'mb_pwd'=>$mb_enPwd,
                'mb_key'=>$mb_enPwd,
                'mb_country_dial_code'=>$mb_country_dial_code,
                'contry_code'=>$mb_country_code,
				'mb_zip_code'=>$mb_zip_code,
				'mb_address'=>$mb_address,
				'mb_detail_address'=>$mb_detail_address,
			)
        );

        // 회원 등록 실패
        if( isset($mb_resigt_result['success']) && (string)$mb_resigt_result['success']=="false"){
            $errormsg = $this->res->lang->model->err21;
            if( isset($mb_resigt_result['error']) ){
                if(isset($mb_resigt_result['error'][0]['message'])){
                    $errormsg = $mb_resigt_result['error'][0]['message'];
                }
            }
            return array(
                "result" => -5103,
                "success"=>false,
                "error"=>$this->res->lang->account->joinFail);
        }else if( isset($mb_resigt_result['result']) && $mb_resigt_result['result']<1 ) {
            return array(
                "result" => -5104,
                "success"=>false,
                "error"=>$this->res->lang->account->joinFail);
        }

        // SMS 인증 여부에 따른 조건 체크 - 인증번호 유효성
        // SMS 인증 내역 삽입
        if($is_sms_signup_use){
            // 삽입 결과 result == 회원번호
            $mb_no = $mb_resigt_result['result'];

            $sql_sms_certification = $this->execUpdate(
                array(
                    'sql'=>$this->res->ctrl->sql_sms_certification,
                    'mapkeys'=>$this->res->ctrl->mapkeys_sms_certification,
                    'rowlimit'=>1
                )
                , array(
                    'mb_no'=>$mb_no
                    ,'mb_id'=>$mb_id
                    ,'mb_name'=>$mb_name
                    ,'mb_hp'=>$mb_hp
                    ,'contry_code'=>$mb_country_code
                    ,'mb_country_dial_code'=>$mb_country_dial_code
                )
            );
        }

        // 인증을 위한 email key 정보
        $mb_get_email_key = $this->execLists(
                array(
                    'sql'=>$this->res->ctrl->sql_get_email_key,
                    'mapkeys'=>$this->res->ctrl->mapkeys_get_email_key,
                    'rowlimit'=>1
                ),
                array(
                    'type'=>'JOIN',
                    'mb_id'=>$mb_id)
        );

        $mb_email_info = array();

        if( isset($mb_get_email_key[0]) ){
            $mb_email_info = $mb_get_email_key[0];
        }

        // email key 유효 체크
        if( !isset($mb_email_info['mb_id']) ){
            return array(
                "result" => -5111,
                "success"=>false,
                "error"=>$this->res->lang->account->sendJoinEmailFail);
        }else if( !isset($mb_email_info['mk_email_key']) ){
            return array(
                "result" => -5112,
                "success"=>false,
                "error"=>$this->res->lang->account->sendJoinEmailFail);
        }

        // mailling
        $mk_email_key = $mb_email_info['mk_email_key'];
        $send_confirm_url = '/returnemail/confirm/action-confirmemail/type-prove/id-'.$param['mb_id'].'/mbkey-'.$mk_email_key;

        $email_subject    = '';
        $email_type_country  = '';
/*
        if($mb_country_code=='KR'){
            $email_subject       = '회원가입인증';
            $email_type_country  = 'regist_email_kr';
        }else if($mb_country_code=='CN'){
            $email_subject       = 'Email Verification';
            $email_type_country  = 'regist_email_zh';
        }else{
            $email_subject       = 'Verification';
            $email_type_country  = 'regist_email_en';
        }
*/		
        $email_subject       = '회원가입인증';
        $email_type_country  = 'regist_email_kr';

        $body = $this->getHTMLEmailBody($email_type_country,array(
            'name'=>$mb_name,
            'confirm_url'=>$send_confirm_url)
        );
        $mailresult = $this->mailer($mb_id, $email_subject, $body, 1, "", "", "");

        if((int)$mailresult <= 0 ){
            return array(
                "result" => -5113,
                "success"=>false,
                "error"=>$this->res->lang->account->sendJoinEmailFail);
        }
        $mbRegistResult = 0;
        if((int)$mb_resigt_result['result']>0){
            $mbRegistResult = 1;
        }

        return array("result" => $mbRegistResult,
            "mailsend" => $mailresult);

    }



    function __destruct(){

    }



    // mailling
    private function getHTMLEmailBody($skin_html_file,$user=array('name'=>'','confirm_url'=>'')){
        $logo_url = '/assets/img/common/logo_email.png';

        $html = file_get_contents('../WebApp/ViewHTML/'.$skin_html_file.'.html');
        $html = str_replace("{site_name}", $this->res->config['html']['title'],$html);
        $html = str_replace("{user_name}", $user['name'],$html);
        $html = str_replace("{logo_url}", $this->res->config['url']['static'].$logo_url,$html);
        $html = str_replace("{confirm_url}", $this->res->config['url']['site'].$user['confirm_url'],$html);
        return $html;
    }
}
