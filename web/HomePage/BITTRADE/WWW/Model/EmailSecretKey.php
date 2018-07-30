<?php
class EmailSecretKey extends BaseModelBase{

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
        if($action=='SEMDEMAIL'){
           return $this->actionTypeSendEmail($type,$param);
        }

        return array('result'=>0);
    }

    private function actionTypeSendEmail($type,$param){
  
        /*****************************************************
        * API키 사용
        ****************************************************/
        if($type == 'SECRETKEY'){

            if(!isset($this->member['mb_id']) || !$this->member['mb_id']){
                return array(
                    "result" => -5006,
                    "success"=>false,
                    "error"=>$this->res->lang->logincheck->fail);
            }

            if($this->member['mb_api_use'] != 'Y'){
                return array(
                    "result" => -1,
                    "success"=>false,
                    "error"=>'aready api enabled');
            }

            // 새로 삽입된 secret key 정보 획득
            $mb_key_info = $this->execLists(
                array(
                    'sql'=>$this->res->ctrl->sql,
                    'mapkeys'=>$this->res->ctrl->mapkeys,
                    'rowlimit'=>1
                )
                , array('mb_id'=>$this->member['mb_id'])
            );

            $mbkeyInfo = $mb_key_info[0];


            if(!isset($mbkeyInfo['mk_secret'])){
                return array(
                    "result" => -6104,
                    "success"=>false,
                    "error"=>$this->res->lang->logincheck->referer);
            }

            $mb_secret_key = $mbkeyInfo['mk_secret'];
            $mb_en_id = base64_encode($this->member['mb_id']);
            
            // contry_code => 디비 오타 있음 주의
            if($this->member['contry_code']=='KR'){
                $email_subject       = 'API Secret Key';
                $email_type_country  = 'show_secretkey_kr';
            }else{
                $email_subject       = 'API Secret Key';
                $email_type_country  = 'show_secretkey_en';
            }

            $body = $this->getHTMLEmailBody($email_type_country, $mb_secret_key, array(
                'name'=>$this->member['mb_name'],                    
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
        
    private function getHTMLEmailBody($skin_html_file, $mb_secret_key, $user=array('name'=>'','confirm_url'=>'')){
        $logo_url = '/assets/img/common/logo_email.png';
        $html = file_get_contents('../WebApp/ViewHTML/'.$skin_html_file.'.html');
        $html = str_replace("{site_name}", $this->res->config['html']['title'],$html);
        $html = str_replace("{user_name}", $user['name'],$html);
        $html = str_replace("{logo_url}", $this->res->config['url']['static'].$logo_url,$html);       
        $html = str_replace("{secret_key}", $mb_secret_key, $html);
        return $html;
    }


    function __destruct(){

    }



}
