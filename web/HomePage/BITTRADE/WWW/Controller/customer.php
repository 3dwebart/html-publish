<?php
/**
* Bitcoin Web Trade Solution
* @description FunHanSoft Co.,Ltd (프로그램 수정 및 사용은 자유롭게 가능하나, 재배포를 엄격히 금지합니다.)
* @date 2015-11-19
* @copyright (c)Funhansoft.com
* @license http://funhansoft.com/solutionlicense/bittrade
* @version 0.0.1
*/
class Controller_customer extends BaseController{

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

    // 고객센터 > 메인
    function main(){
        $viewdata = array('title'=>$this->res->lang->viewCsNotice->title,
            'lang'=>$this->res->lang->viewCsNotice, 'langcommon'=>$this->res->lang->common);
        $this->viewer->setHTMLTitle($viewdata['title'].'|'.$this->viewer->getHTMLTitle());
        $this->viewer->setJavaScript($this->jsonObject);
        $this->viewer->setViewBeanData($viewdata);
        return $this->viewer;
    }

    // 고객센터 > 1:1문의리스트
    function questionlist(){
        if(!$this->member || !isset($this->member['mb_no']) || !$this->member['mb_no']){
            Utils::redirect('account/signin/return-customer/questionlist');
        }
        $viewdata = array('title'=>$this->res->lang->viewCsQnalist->title,
            'lang'=>$this->res->lang->viewCsQnalist, 'langcommon'=>$this->res->lang->common);
        $this->viewer->setHTMLTitle($viewdata['title'].'|'.$this->viewer->getHTMLTitle());
        $this->viewer->setJavaScript($this->jsonObject);
        $this->viewer->setViewBeanData($viewdata);
        return $this->viewer;
    }

    // 고객센터 > 1:1문의
    function question(){
        if(!$this->member || !isset($this->member['mb_no']) || !$this->member['mb_no']){
            Utils::redirect('account/signin/return-customer/question');
        }
        $viewdata = array('title'=>$this->res->lang->viewCsQna->title,'mb_name'=>$this->member['mb_name'],'mb_id'=>$this->member['mb_id'],
            'lang'=>$this->res->lang->viewCsQna, 'langcommon'=>$this->res->lang->common);
        $this->viewer->setHTMLTitle($viewdata['title'].'|'.$this->viewer->getHTMLTitle());
        $this->viewer->setJavaScript($this->jsonObject);
        $this->viewer->setViewBeanData($viewdata);
        return $this->viewer;
    }

    // 고객센터 > 이용안내
    function guide(){
        $viewdata = array('title'=>$this->res->lang->viewCsGuide->title,
            'lang'=>$this->res->lang->viewCsGuide, 'langcommon'=>$this->res->lang->common);
        $this->viewer->setHTMLTitle($viewdata['title'].'|'.$this->viewer->getHTMLTitle());
        $this->viewer->setJavaScript($this->jsonObject);
        $this->viewer->setViewBeanData($viewdata);
        return $this->viewer;
    }
	
	// 고객센터 > FAQ
	function faq() {
		$viewdata = array('title'=>$this->res->lang->viewCsNotice->title,
			'lang'=>$this->res->lang->viewCsNotice, 'langcommon'=>$this->res->lang->common);
		$this->viewer->setHTMLTitle($viewdata['title'].'|'.$this->viewer->getHTMLTitle());
		$this->viewer->setJavaScript($this->jsonObject);
		$this->viewer->setViewBeanData($viewdata);
		return $this->viewer;
	}

}