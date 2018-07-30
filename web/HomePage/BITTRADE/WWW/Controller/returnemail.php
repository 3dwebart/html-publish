<?php
/**
* Bitcoin Web Trade Solution
* @description FunHanSoft Co.,Ltd (프로그램 수정 및 사용은 자유롭게 가능하나, 재배포를 엄격히 금지합니다.)
* @date 2015-11-19
* @copyright (c)Funhansoft.com
* @license http://funhansoft.com/solutionlicense/bittrade
* @version 0.0.1
*/
class Controller_returnemail extends BaseController{

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



    // 회원가입 후 이메일 인증 페이지
    function confirm(){
        if($this->member && isset($this->member['mb_no']) && $this->member['mb_no']){
            Utils::redirect('/');
        }
        $viewdata = array('title'=>$this->res->lang->viewReturnemailConfirm->title,
            'lang'=>$this->res->lang->viewReturnemailConfirm, 'langcommon'=>$this->res->lang->common);
        $this->viewer->setHTMLTitle($viewdata['title'].'|'.$this->viewer->getHTMLTitle());
        $this->viewer->setJavaScript($this->jsonObject);
        $this->viewer->setViewBeanData($viewdata);
       return $this->viewer;
    }

    // 비밀번호 분실 후 찾기 인증 페이지
    function confirmpwd(){
        if($this->member && isset($this->member['mb_no']) && $this->member['mb_no']){
            Utils::redirect('/');
        }
        $viewdata = array('title'=>$this->res->lang->viewReturnemailConfirmpwd->title,
            'lang'=>$this->res->lang->viewReturnemailConfirmpwd, 'langcommon'=>$this->res->lang->common);
        $this->viewer->setHTMLTitle($viewdata['title'].'|'.$this->viewer->getHTMLTitle());
        $this->viewer->setJavaScript($this->jsonObject);
        $this->viewer->setViewBeanData($viewdata);
        return $this->viewer;
    }

    // 출금요청 인증 페이지
    function confirmwithdraw(){
        if(!$this->member || !isset($this->member['mb_no']) || !$this->member['mb_no']){
            Utils::redirect('account/signin/return-returnemail/confirmwithdraw');
        }
        $token = Utils::createToken();
        $viewdata = array('title'=>$this->res->lang->viewReturnemailConfirmwithdrawal->title, 'token'=>$token,
            'lang'=>$this->res->lang->viewReturnemailConfirmwithdraw, 'langcommon'=>$this->res->lang->common);
        $this->viewer->setHTMLTitle($viewdata['title'].'|'.$this->viewer->getHTMLTitle());
        $this->viewer->setJavaScript($this->jsonObject);
        $this->viewer->setViewBeanData($viewdata);
        return $this->viewer;
    }
}