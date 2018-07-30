<?php
/**
* Bitcoin Web Trade Solution
* @description FunHanSoft Co.,Ltd (프로그램 수정 및 사용은 자유롭게 가능하나, 재배포를 엄격히 금지합니다.)
* @date 2015-11-19
* @copyright (c)Funhansoft.com
* @license http://funhansoft.com/solutionlicense/bittrade
* @version 0.0.1
*/
class Controller_about extends BaseController{

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

    function terms(){
        $viewdata = array('title'=>$this->res->lang->viewAboutTerms->title,
            'lang'=>$this->res->lang->viewAboutTerms, 'langcommon'=>$this->res->lang->common);
        $this->viewer->setHTMLTitle($viewdata['title'].'|'.$this->viewer->getHTMLTitle());
        $this->viewer->setJavaScript($this->jsonObject);
        $this->viewer->setViewBeanData($viewdata);
        return $this->viewer;
    }

    function privacy(){
        $viewdata = array('title'=>$this->res->lang->viewAboutPrivacy->title,
            'lang'=>$this->res->lang->viewAboutPrivacy, 'langcommon'=>$this->res->lang->common);
        $this->viewer->setHTMLTitle($viewdata['title'].'|'.$this->viewer->getHTMLTitle());
        $this->viewer->setJavaScript($this->jsonObject);
        $this->viewer->setViewBeanData($viewdata);
        return $this->viewer;
    }

    
    function mbleave(){
        $viewdata = array('title'=>$this->res->lang->viewAboutMbleave->title,
            'lang'=>$this->res->lang->viewAboutMbleave, 'langcommon'=>$this->res->lang->common);
        $this->viewer->setHTMLTitle($viewdata['title'].'|'.$this->viewer->getHTMLTitle());
        $this->viewer->setJavaScript($this->jsonObject);
        $this->viewer->setViewBeanData($viewdata);
        return $this->viewer;
    }



}