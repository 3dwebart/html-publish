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
            $str = '<!doctype html><html><head>';
            $str .= '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">';
            $str .= '<title>'.$this->htmlTitle.'</title>';
            $str .= '<meta name="viewport" content="width=device-width, initial-scale=1">';
            $str .= '<meta http-equiv="X-UA-Compatible" content="IE=edge">';
            $str .= '<meta name="description" content="'.$this->htmlDescription.'">';
            $str .= '<meta name="keywords" content="'.$this->htmlKeyword.'">';
            $str .= '<link rel="shortcut icon" type="image/x-icon" href="'.$this->cfg['url']['static'].'/favicon.ico">';
            $str .= '<script type="text/javascript" charset="utf-8" src="'.$this->cfg['url']['static'].'/script/dist/jquery.min.js"></script>';
            
            // language
            $this->language = Utils::getCookiePlain("Language");
            if($this->language == '' || $this->language == 'kr' ){
                Utils::setCookie('Language', 'ko', 86400*30);   // 유효기간 30일
                $this->language = 'ko';
            }
            $str .= '<script src="'.$this->cfg['url']['site'].'/language/main/js-'.$this->language.'"></script>';

            if($this->htmlHeader){
                    $str .= $this->htmlHeader;
            }
            if($this->htmlJavaScript){
                    $str .= '<script type="text/javascript">';
                    $str .= $this->htmlJavaScript;
                    $str .= '</script>';
            }
            // favicon
            
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
        echo $this->getHTMLHeader('infopage');
        $str = "{\"response\":404,\"msg\":\"The page that you have requested could not be found.\"}";
        echo $str;
        echo $this->getHTMLFooter();
        throw new NotFound('404 Page "' . $page . '" not found '.$msg,-404);
    }

    public function runErrorPage($page,$msg=''){
        echo $this->getHTMLHeader('infopage');
        $str = "{\"response\":500,\"msg\":\"Server Error\"}";
        echo $str;
        echo $this->getHTMLFooter();
        throw new NotFound('Error Page "' . $page . '" 500 msg  '.$msg,-500);
    }

    public function runErrorRequest($page,$msg=''){
        echo $this->getHTMLHeader('infopage');
        $str = "{\"response\":401,\"msg\":\"Request Error Page\"}";
        echo $str;
        echo $this->getHTMLFooter();
        throw new NotFound('Error Page "' . $page . '" 400 msg '.$msg,-400);
    }

    public function runJSONPage(){

        if($this->pageBody){
            if(is_string($this->pageBody)){
                $this->print = $this->pageBody;
            }else{
                $this->print = Utils::jsonEncode($this->pageBody); //'쿼테이션
            }
        }else{
            
        }
    }

    public function runHTMLPage(){
        $view = array(); //스킨에서 사용할 변수

        if ($this->hasSkin()) {
            ob_start();

            $this->viewBeanData['url'] = $this->cfg['url'];
            $view = $this->viewBeanData;

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