<?php
/**
* Bitcoin Web Trade Solution
* @description FunHanSoft Co.,Ltd (프로그램 수정 및 사용은 자유롭게 가능하나, 재배포를 엄격히 금지합니다.)
* @date 2015-11-19
* @copyright (c)Funhansoft.com
* @license http://funhansoft.com/solutionlicense/bittrade
* @version 0.0.1
*/


class Controller_account extends BaseController{

    private $member =NULL;
    private $jsonObject = '';

    function __construct($conjson=NULL){
        parent::__construct($conjson);
        $this->setDefaultInitJsonObject();
    }
    
    private function setDefaultInitJsonObject($return=null){
        if(!$return){
            $return = array(
                'link'=>base64_encode((json_encode($this->res->config['url']))),
                'client'=>base64_encode((json_encode($this->res->config['client'])))
            );
        }
        $mb = $this->getMemberDataFromRedis();
        $this->member = json_decode($mb,TRUE);
        if ($mb) {
            $return['member'] = base64_encode($mb);
        }
        $this->jsonObject = 'var jsonObject='.json_encode($return );
    }

    function signup(){
        if($this->member && isset($this->member['mb_no']) && $this->member['mb_no']){
            Utils::redirect('/');
        }
        $is_sms_signup_use = $this->res->config['sms']['is_sms_signup_use'];
        if(!isset($is_sms_signup_use)){   // 없으면 기본값
            $is_sms_signup_use = false;
        }

        $viewdata = array('title'=>$this->res->lang->viewAccountSignup->title,'captcha_sitekey'=>$this->res->config['recaptcha']['sitekey'],
            'is_sms_signup_use'=>$is_sms_signup_use,
            'lang'=>$this->res->lang->viewAccountSignup, 'langcommon'=>$this->res->lang->common);
        $this->viewer->setHTMLTitle($viewdata['title'].'|'.$this->viewer->getHTMLTitle());
        $this->viewer->setJavaScript($this->jsonObject);
        $this->viewer->setViewBeanData($viewdata);

        return $this->viewer;
    }

    function signin(){
        if($this->member && isset($this->member['mb_no']) && $this->member['mb_no']){
            Utils::redirect('/');
        }
        
        
         
        $key = Utils::getClientIP();
        $warncount = $this->getWarnCountRedis($key);
        $viewdata = array('title'=>$this->res->lang->viewAccountSignin->title,'captcha_sitekey'=>$this->res->config['recaptcha']['sitekey'],
            'warncount'=>$warncount,
            'master'=>$master,
            'lang'=>$this->res->lang->viewAccountSignin, 'langcommon'=>$this->res->lang->common);
        $this->viewer->setHTMLTitle($viewdata['title'].'|'.$this->viewer->getHTMLTitle());
        $this->viewer->setJavaScript($this->jsonObject);
        $this->viewer->setViewBeanData($viewdata);
 
        
        return $this->viewer;
    }
    
    function signin2ch(){
        if($this->member && isset($this->member['mb_no']) && $this->member['mb_no']){
            Utils::redirect('/');
        }
        
        $otpsession = $this->getOtpSessionRedis();
        if(!$otpsession){
            Utils::redirect('account/signin');
        }
        $viewdata = array('title'=>$this->res->lang->viewAccountSignin->title,'captcha_sitekey'=>$this->res->config['recaptcha']['sitekey'],
            'gkey'=>$otpsession,
            'lang'=>$this->res->lang->viewAccountSignin, 'langcommon'=>$this->res->lang->common);
        $this->viewer->setHTMLTitle($viewdata['title'].'|'.$this->viewer->getHTMLTitle());
        $this->viewer->setJavaScript($this->jsonObject);
        $this->viewer->setViewBeanData($viewdata);
        return $this->viewer;
    }
    
    private function getOtpSessionRedis(){
        $key = 'otp'.Utils::getCookie('otp');
        $redis = new Credis_Client($this->res->config['redis']['host'],$this->res->config['redis']['port'],null,'',$this->res->config['redis']['db_member']);
        if($redis->exists($key)){
            return $redis->get($key);
        }
        return;
    }
    
    private function getWarnCountRedis($key){
        $redis = new Credis_Client($this->res->config['redis']['host'],$this->res->config['redis']['port'],null,'',$this->res->config['redis']['db_tmp']);
        if($redis->exists($key)){
            return (int)$redis->get($key);
        }
        return 0;
    }

    function signout(){
        $this->delMemberDataFromRedis();
    }

    // 회원 가입 인증 메일 재요청
    function signrequest(){
        if($this->member && isset($this->member['mb_no']) && $this->member['mb_no']){
            Utils::redirect('/');
        }
        $viewdata = array('title'=>$this->res->lang->viewAccountSignrequest->title,
            'lang'=>$this->res->lang->viewAccountSignrequest, 'langcommon'=>$this->res->lang->common);
        $this->viewer->setHTMLTitle($viewdata['title'].'|'.$this->viewer->getHTMLTitle());
        $this->viewer->setJavaScript($this->jsonObject);
        $this->viewer->setViewBeanData($viewdata);
        return $this->viewer;
    }

    // 비밀번호 복구
    function signrequestpwd(){
        if($this->member && isset($this->member['mb_no']) && $this->member['mb_no']){
            Utils::redirect('/');
        }
        $viewdata = array('title'=>$this->res->lang->viewAccountSignrequestpwd->title,
            'lang'=>$this->res->lang->viewAccountSignrequestpwd, 'langcommon'=>$this->res->lang->common);
        $this->viewer->setHTMLTitle($viewdata['title'].'|'.$this->viewer->getHTMLTitle());
        $this->viewer->setJavaScript($this->jsonObject);
        $this->viewer->setViewBeanData($viewdata);
        return $this->viewer;
    }

    function signedit(){
        if(!$this->member || !isset($this->member['mb_no']) || !$this->member['mb_no']){
            Utils::redirect('account/signin/return-account/signedit');
        }    
        $viewdata = array('title'=>$this->res->lang->viewAccountSignedit->title,
            'lang'=>$this->res->lang->viewAccountSignedit, 'langcommon'=>$this->res->lang->common,'member'=>$this->member );
        $this->viewer->setHTMLTitle($viewdata['title'].'|'.$this->viewer->getHTMLTitle());
        $this->viewer->setJavaScript($this->jsonObject);
        $this->viewer->setViewBeanData($viewdata);
        return $this->viewer;
    }

    function authcheck(){
        return $this->viewer;
    }

    function authfail(){
        return $this->viewer;
    }

    // 인증센터
    function verificationcenter(){
        if(!$this->member || !isset($this->member['mb_no']) || !$this->member['mb_no']){
            Utils::redirect('account/signin/return-account/verificationcenter');
        }
        $master = JsonConfig::get('exchangemarket');
        
        $viewdata = array('title'=>$this->res->lang->viewAccountVerificationcenter->title,
            'lang'=>$this->res->lang->viewAccountVerificationcenter, 'langcommon'=>$this->res->lang->common,
            'master'=>$master);
        $this->viewer->setHTMLTitle($viewdata['title'].'|'.$this->viewer->getHTMLTitle());
        $this->viewer->setJavaScript($this->jsonObject);
        $this->viewer->setViewBeanData($viewdata);
        return $this->viewer;
    }

    // 인증 센터 요청 모바일 폼
    function verificationformmobile(){
        if(!$this->member || !isset($this->member['mb_no']) || !$this->member['mb_no']){
            Utils::redirect('account/signin/return-account/verificationformmobile');
        }
        if($this->member['mb_hp']){
            Utils::redirect('account/verificationcenter');
        }

        $viewdata = array('title'=>$this->res->lang->viewAccountVerificationformmobile->title,
            'lang'=>$this->res->lang->viewAccountVerificationformmobile, 'langcommon'=>$this->res->lang->common);
        $this->viewer->setHTMLTitle($viewdata['title'].'|'.$this->viewer->getHTMLTitle());
        $this->viewer->setJavaScript($this->jsonObject);
        $this->viewer->setViewBeanData($viewdata);
        return $this->viewer;
    }

    // 인증 센터 요청 폼
    function verificationform(){
        if(!$this->member || !isset($this->member['mb_no']) || !$this->member['mb_no']){
            Utils::redirect('account/signin/return-account/verificationform');
        }
        // 파일 업로드 제한 - 2개 제한크기/2 - 내려주는 파일 크기 각 폼의 개수에 맞게
        // config 는 mb 내려주는건 byte
        $upload_file_size_limit = $this->res->config['ftp']['upload_file_size_limit'];
        if(!isset($upload_file_size_limit)){
            $upload_file_size_limit = (int)((10/2)*1024)*1024;
        }else{
            $upload_file_size_limit = (int)(($upload_file_size_limit/2)*1024)*1024;
        }

        $viewdata = array('title'=>$this->res->lang->viewAccountVerificationform->title,
            'filesizelimit'=>$upload_file_size_limit,
            'lang'=>$this->res->lang->viewAccountVerificationform, 'langcommon'=>$this->res->lang->common);
        $this->viewer->setHTMLTitle($viewdata['title'].'|'.$this->viewer->getHTMLTitle());
        $this->viewer->setJavaScript($this->jsonObject);
        $this->viewer->setViewBeanData($viewdata);
        return $this->viewer;
    }

    function connectioninfo(){
        if(!$this->member || !isset($this->member['mb_no']) || !$this->member['mb_no']){
            Utils::redirect('account/signin/return-account/connectioninfo');
        }
        $viewdata = array('title'=>$this->res->lang->viewAccountConnectioninfo->title,
            'lang'=>$this->res->lang->viewAccountConnectioninfo,'langlogincheck'=>$this->res->lang->logincheck, 'langcommon'=>$this->res->lang->common);
        $this->viewer->setHTMLTitle($viewdata['title'].'|'.$this->viewer->getHTMLTitle());
        $this->viewer->setJavaScript($this->jsonObject);
        $this->viewer->setViewBeanData($viewdata);
        return $this->viewer;
    }
    
     function otp(){
        if(!$this->member || !isset($this->member['mb_no']) || !$this->member['mb_no']){
            Utils::redirect('account/signin/return-account/otp');
        }
        
        $secret = '';
        $qrCodeUrl = '';
        
        if(!isset($this->member['mb_otp_use']) || $this->member['mb_otp_use'] == 'N'){
            require_once './Plugin/PHPGangsta/GoogleAuthenticator.php';
            $ga = new PHPGangsta_GoogleAuthenticator();
            
            Session::startSession();
            $secret = $ga->createSecret();
            Session::setSession('otp_secret', $secret);

            $qrCodeUrl = $ga->getQRCodeGoogleUrl($this->res->config['otp']['sitehead'].$this->member['mb_id'].$this->res->config['otp']['sitetail'], $secret,$this->res->config['otp']['title']);
        }
        
        $viewdata = array('title'=>$this->res->lang->viewAccountOtp->title,
            'lang'=>$this->res->lang->viewAccountOtp, 'langcommon'=>$this->res->lang->common,'member'=>$this->member,
            'otp'=>array('key'=>$secret,'qrurl'=>$qrCodeUrl) );
        $this->viewer->setHTMLTitle($viewdata['title'].'|'.$this->viewer->getHTMLTitle());
        $this->viewer->setJavaScript($this->jsonObject);
        $this->viewer->setViewBeanData($viewdata);
        return $this->viewer;
    }

}
