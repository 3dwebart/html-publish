<?php
/**
* Bitcoin Web Trade Solution
* @description FunHanSoft Co.,Ltd (프로그램 수정 및 사용은 자유롭게 가능하나, 재배포를 엄격히 금지합니다.)
* @date 2015-11-19
* @copyright (c)Funhansoft.com
* @license http://funhansoft.com/solutionlicense/bittrade
* @version 0.0.1
*/
class Controller_page extends BaseController{

    function main(){
        $viewdata = array('title'=>'','jsondata'=>array());
        
        $this->viewer->setHTMLTitle($viewdata['title'].'');
        $this->viewer->setViewBeanData($viewdata);
        return $this->viewer;
    }

    function e404(){
        $viewdata = array('title'=>'404','langcommon'=>$this->res->lang->common,'jsondata'=>array());
        
        $this->viewer->setHTMLTitle($this->viewer->getHTMLTitle().'|404');
        $this->viewer->setViewBeanData($viewdata);
        return $this->viewer;
    }
    
    function e403(){
        $viewdata = array('title'=>'403','langcommon'=>$this->res->lang->common,'jsondata'=>array());
        
        $this->viewer->setHTMLTitle($this->viewer->getHTMLTitle().'|403');
        $this->viewer->setViewBeanData($viewdata);
        return $this->viewer;
    }
    
    function e500(){
        $viewdata = array('title'=>'500','langcommon'=>$this->res->lang->common,'jsondata'=>array());
        
        $this->viewer->setHTMLTitle($this->viewer->getHTMLTitle().'|500');
        $this->viewer->setViewBeanData($viewdata);
        return $this->viewer;
    }
}