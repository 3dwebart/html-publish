<?php
/**
* Description of BaseDAO
* @author bugnote@funhansoft.com
* @date 2013-08-07
* @copyright (c)funhansoft.com
* @license http://funhansoft.com/license
* @version 0.2.1
*/
class Viewer {

    const DEFAULT_PAGE = 'main';
    const SKIN_DIR = './View';
    private $language = '';

    private $htmlJavaScript;
    private $htmlHeader;
    private $htmlTitle = '';
    private $htmlKeyword = ''; //검색엔진에 노출
    private $htmlDescription = ''; //검색엔진에 노출
    private $responseType = 'HTML';
    private $controllParam;
    private $pageBody; // HTML 이외의 출력 값
    private $cacheTTL = 0; //cache TTL 1hour
    private $cfg;
    private $print=''; //결과 값
    private $viewBeanData = array(); //스킨에서 사용할 변수

    /**
    * @brief
    **/
    function __construct(){
        $this->cfg = $cfg = Config::getConfig();
        if(isset($this->cfg['html'])){
            $this->htmlTitle = $this->cfg['html']['title'];
            $this->htmlDescription = $this->cfg['html']['description'];
            $this->htmlKeyword = $this->cfg['html']['keywords'];
        }
        if(isset($this->cfg['cmd'])){
            $this->cacheTTL = $this->cfg['cmd']['cache'];
        }
    }

    public function __set($name, $value) {
            $this->$name = $value;
    }

    public function initParam($controllParam,$view){
        $this->controllParam['controller'] = $controllParam;
        $this->controllParam['view']  = $view;
    }

    public function getSkin() {
        $ext = $this->cfg['filename']['viewer_exc'];
        if($this->cfg['cmd']['debug']==true) {$ext = $this->cfg['filename']['viewer_exc'];}
        $skindir = self::SKIN_DIR;

        $this->language = Utils::getCookiePlain("Language");
        if($this->language == '' || $this->language == 'kr' || $this->language == 'ko'){
            Utils::setCookie('Language', 'ko', 86400*30);   // 유효기간 30일
        }
        return $skindir .'/'.$this->controllParam['controller'] .'/'. $this->controllParam['view'] . $ext;
    }

    public function hasSkin() {
        return file_exists($this->getSkin());
    }

    public function setJavaScript($js){
            $this->htmlJavaScript = $js;
    }

    public function getJavaScript(){
        return $this->htmlJavaScript;
    }

    public function setHTMLTitle($title){
            $this->htmlTitle = $title;
    }

    public function getHTMLTitle(){
        return $this->htmlTitle;
    }

    public function setHTMLKeyword($val){
            $this->htmlKeyword = $val;
    }

    public function getHTMLKeyword(){
            return $this->htmlKeyword;
    }

    public function setHTMLDescription($val){
            $this->htmlDescription = $val;
    }

    public function getHTMLDescription(){
            return $this->htmlDescription;
    }

    public function setHTMLHead($head){
            $this->htmlHeader = $head;
    }

    public function getViewBeanData(){
            return $this->viewBeanData;
    }

    public function setViewBeanData($skindata){
            $this->viewBeanData = $skindata;
    }

    public function setPageBody($body){
        $this->pageBody = $body;
    }

    public function getResponseType(){
        return $this->responseType;
    }

    public function setResponseType($type){
            $this->responseType = $type;
    }

    public function getCacheTTL(){
        return $this->cacheTTL;
    }

    public function setCacheTTL($ttl){
            $this->cacheTTL = $ttl;
    }

    public function getHTMLHeader($css=''){
            $str = '<!DOCTYPE html><html><head>';
            $str .= '<title>'.$this->htmlTitle.'</title><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">';
            $str .= '<meta name="viewport" content="initial-scale=1.0,user-scalable=no,maximum-scale=1,width=device-width" />';             
            $str .= '<meta name="format-detection" content="telephone=no">';            
            $str .= '<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">';
            $str .= '<meta name="description" content="'.$this->htmlDescription.'">';
            $str .= '<meta name="keywords" content="'.$this->htmlKeyword.'">';
            //$str .= '<meta name="author" content="Powered by FunHanSoft">'; //제거하지 마세요^^
            $str .= '<!--[if lt IE 7]><script type="text/javascript" src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->';
//            $str .= '<script type="text/javascript" charset="utf-8" src="//code.jquery.com/jquery-1.11.3.min.js"></script>';
            $str .= '<script type="text/javascript" charset="utf-8" src="'.$this->cfg['url']['static'].'/assets/js/jquery-1.11.3.min.js"></script>';
            $str .= '<script src="'.$this->cfg['url']['static'].'/assets/js/bootstrap.min.js"></script>';
            $str .= '<script type="text/javascript" charset="utf-8" src="'.$this->cfg['url']['static'].'/assets/js/ie10-viewport-bug-workaround.js"></script>'; //IE10 viewport hack for Surface/desktop Windows 8 bug
            $str .= '<script type="text/javascript" charset="utf-8" src="'.$this->cfg['url']['static'].'/assets/js/vendor/holder.js"></script>';
            // language
            $this->language = Utils::getCookiePlain("Language");
            if($this->language == '' || $this->language == 'kr' ){
                Utils::setCookie('Language', 'ko', 86400*30);   // 유효기간 30일
                $this->language = 'ko';
            }
            $str .= '<script src="'.$this->cfg['url']['site'].'/language/main/js-'.$this->language.'"></script>';

			//상단메뉴에 bootstrap-hover-dropdown 기능을 위한 부분
			$str .= '<script src="'.$this->cfg['url']['static'].'/assets/js/bootstrap-hover-dropdown.min.js"></script>';
			$str .= '<script>';
			$str .= '$(document).ready(function() {';
			$str .= '$(\'.js-activated\').dropdownHover().dropdown();';
			$str .= '});';
			$str .= '</script>';

            if($this->htmlHeader){
                    $str .= $this->htmlHeader;
            }
            if($this->htmlJavaScript){
                    $str .= '<script type="text/javascript">';
                    $str .= $this->htmlJavaScript;
                    $str .= '</script>';
            }
            if($css) $str .= '<link rel="stylesheet" href="'.$this->cfg['url']['static'].'/assets/css/'.$css.'.min.css" type="text/css">';
            // favicon
            $str .= '<link rel="shortcut icon" type="image/x-icon" href="'.$this->cfg['url']['static'].'/assets/img/favicon.ico">';
            // comodo
            // $str .= '<script language="javascript" src="https://secure.comodo.net/trustlogo/javascript/trustlogo.js" type="text/javascript"></script>';
            $str .= '</head>';
            return $str;
    }

    public function getHTMLFooter(){
//            $str = $this->getHTMLSiteAnalytics();
            $str = "</html>";
            return $str;
    }

    private function getHTMLSiteAnalytics(){
        $str = "<script>";
        $str .= "</script>";
        return $str;
    }

    public function runNotFoungPage($page,$msg=''){
//        echo $this->getHTMLHeader('infopage');
//        $str = "<body><div>";
//        $str .= "<h1>404 Not Found</h1>";
//        $str .= "<p>The page that you have requested could not be found.</p>";
//        $str .= "<p>요청하신 페이지를 찾을 수 없습니다.</p>";
//        $str .= $msg;
//        $str .= "</div></body>";
//        echo $str;
//        echo $this->getHTMLFooter();
//        throw new NotFound('404 Page "' . $page . '" not found '.$msg,-404);
        Utils::redirect('page/e404');
    }

    public function runErrorPage($page,$msg=''){
//        echo $this->getHTMLHeader('infopage');
//        $str = "<body><div>";
//        $str .= "<h1>500 Server Error</h1>";
//        $str .= "<p>서버에 오류가 발생하여 페이지를 표시할 수 없습니다.</p>";
//        $str .= $msg;
//        $str .= "</div></body>";
//        echo $str;
//        echo $this->getHTMLFooter();
//        throw new NotFound('Error Page "' . $page . '" 500 msg  '.$msg,-500);
        Utils::redirect('page/e500');
    }

    public function runErrorRequest($page,$msg=''){
        echo $this->getHTMLHeader('infopage');
        $str = "<body><div>";
        $str .= "<h1>Request Error Page</h1>";
        $str .= "<p>잘못된 페이지 요청입니다.</p><p>올바른 경로로 접근하셨는지 확인해 주세요.</p>";
        $str .= $msg;
        $str .= "</div></body>";
        echo $str;
        echo $this->getHTMLFooter();
        throw new NotFound('Error Page "' . $page . '" 400 msg '.$msg,-400);
    }

    public function runJSONPage(){
        if(is_string($this->pageBody)){
            $this->print = $this->pageBody;
        }else{
            $this->print = Utils::jsonEncode($this->pageBody); //'쿼테이션
        }
    }

    public function runHTMLPage(){
        $view = array(); //스킨에서 사용할 변수

        if ($this->hasSkin()) {
            ob_start();

            $this->viewBeanData['url'] = $this->cfg['url'];
            $view = $this->viewBeanData;
            $view['ver'] = $this->cfg['solution']['version'];

            echo $this->getHTMLHeader();

            require_once  $this->getSkin(); //변수들 치환

            echo $this->getHTMLFooter();

            $ob_content = ob_get_clean();
            ob_end_clean();

            $ob_content = preg_replace('/<\/head>.*<head>|<\/body>.*<body>/ms', ' ', $ob_content);

            //.min.html 컴프레서 사용으로 주석
            $ob_content = preg_replace('/[\s]+|<!--[^\[].*?-->|\/\*.*?\*\/|(?<!\S)\/\/\s*[^\r\n]*|<\/head>.*<head>/ms', ' ', $ob_content);
            $ob_content = preg_replace('/;[\s]+/ms', ';', $ob_content); //js 공백제거
//            $ob_content = preg_replace('/(>)([\s][\s]+)(<)/ms', '><', $ob_content); //2개이상 공백

            //[^\.min]\.css

//            $ob_content = preg_replace('/\$link\{static\}/ms', isset($this->cfg['url']['static'])?$this->cfg['url']['static']:'', $ob_content);

            $this->print = $ob_content;
        }

    }

    public function __toString() {
        return $this->print;
    }

}