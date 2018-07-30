<?php
/**
* Description of BaseDAO
* @author admin@bugnote.net
* @date 2013-08-07
* @copyright (c)funhansoft.com
* @license http://funhansoft.com/license
* @version 0.2.3
*/
class Viewer {

    const DEFAULT_PAGE = 'main';
    const SKIN_DIR = './View/';
    
    private $htmlJavaScript;
    private $htmlHeader;
    private $htmlTitle;
    private $htmlKeyword; //검색엔진에 노출
    private $htmlDescription; //검색엔진에 노출
    private $responseType = 'HTML';
    private $controllParam;
    private $pageBody; // HTML 이외의 출력 값
    private $cacheTTL = 3600; //cache TTL 1hour
    private $cfg;
    private $print=''; //결과 값
    
    /**
    * @brief
    **/
    function __construct(){
        $this->cfg = $cfg = Config::getConfig();
        $this->htmlTitle = $this->cfg['html']['title'];
        $this->htmlDescription = $this->cfg['html']['description'];
        $this->htmlKeyword = $this->cfg['html']['keywords'];
        $this->cacheTTL = $this->cfg['cmd']['cache'];
    }

    public function __set($name, $value) {
            $this->$name = $value;
    }

    public function initParam($controllParam){
        $this->controllParam = $controllParam;
    }
    
    public function getSkin() {
        $ext = '.html';
        if($this->cfg['cmd']['debug']==true) $ext = '.html';
        return self::SKIN_DIR .$this->controllParam['controller'] .'/'. $this->controllParam['view'] . '.html';
    }

    public function hasSkin() {
        return file_exists($this->getSkin());
    }
    
    public function setJavaScript($js){
            $this->htmlJavaScript = $js;
    }
    
    public function setJavaScriptAddJsonObj($js){
            $this->htmlJavaScript = 'var jsonObject = \''.$js.'\';';
    }

    public function getJavaScript(){
        return $this->htmlJavaScript;
    }

    public function setHTMLTitle($title){
            $this->htmlTitle = $title;
    }
    
    public function setHTMLTitleAdd($title){
            $this->htmlTitle = $this->htmlTitle .' - '. $title;
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
            $str .= '<meta name="description" content="'.$this->htmlDescription.'">';
            $str .= '<meta name="keywords" content="'.$this->htmlKeyword.'">';
            $str .= '<meta name="author" content="Powered by web.bugnote.net">'; //제거하지 마세요^^
            if($this->htmlHeader){
                    $str .= $this->htmlHeader;
            }
            if($this->htmlJavaScript){
                    $str .= '<script type="text/javascript">';
                    $str .= $this->htmlJavaScript;
                    $str .= '</script>';
            }
            $str .= '</head>';
            return $str;
    }

    public function getHTMLFooter(){
            $str = "</html>";
            return $str;
    }
    
    

    public function runNotFoungPage($page,$msg=''){
        $str = "<body><div>";
        $str .= "<h1>404 Not Found</h1>";
        $str .= "<p>The page that you have requested could not be found.</p>";
        $str .= "<p>요청하신 페이지를 찾을 수 없습니다.</p>";
        $str .= $msg;
        $str .= "</div></body>";
        echo $str;
    }

    public function runErrorPage($page,$msg=''){
        echo $this->getHTMLHeader('infopage');
        $str = "<body><div>";
        $str .= "<h1>500 Server Error</h1>";
        $str .= "<p>서버에 오류가 발생하여 페이지를 표시할 수 없습니다.</p>";
        $str .= "</div></body>";
        echo $str;
        echo $this->getHTMLFooter();
        throw new NotFound('Error Page "' . $page . '" not found '.$msg,-500);
    }


    public function runJSONPage(){
        if(is_string($this->pageBody)){
            $this->print = $this->pageBody;
        }else{
            $this->print = Utils::jsonEncode($this->pageBody);
        }
    }


    public function runHTMLPage(){
        $ob_content = '';
        if ($this->hasSkin()) {
            $ob_content .= $this->getHTMLHeader();
            $ob_content .= file_get_contents($this->getSkin());
            //echo preg_replace('/[\s]+/', ' ', $outScript);
            $ob_content .= $this->getHTMLFooter();

            $ob_content = preg_replace('/[\s]+|<!--.*?-->|\/\*.*?\*\/|(?<!\S)\/\/\s*[^\r\n]*|<\/head>.*<head>/ms', ' ', $ob_content);
            $ob_content = preg_replace('/;[\s]+/ms', ';', $ob_content); //js 공백제거
            $ob_content = preg_replace('/(>)([\s]+)(<)/ms', '><', $ob_content);

            $ob_content = preg_replace('/\$link\{css_root\}/ms', $this->cfg['url']['css_root'], $ob_content);
            $ob_content = preg_replace('/\$link\{css\}/ms', $this->cfg['url']['css'], $ob_content);
            $ob_content = preg_replace('/\$link\{js\}/ms', $this->cfg['url']['js'], $ob_content);
            $ob_content = preg_replace('/\$link\{script\}/ms', $this->cfg['url']['script'], $ob_content);

            $this->print = $ob_content;
        }
    }

    public function __toString() {
        return $this->print;
    }


    public function getPageMenu(){
        $resultArray['result'] = ResError::no;
        $resultArray['user'] =  array("id"=>"","name"=>"adminmenu");
        $filecontent = file_get_contents('../WebApp/Defined/menu/adminpage.json');
        $conjson = json_decode($filecontent, true);

        $categorySubKey = '';
        $resultArray['category']    = array();
        $resultArray['categorySub'] = array();

        for($m=0;$m<count($conjson['category']);$m++){

            $categoryValue = $conjson['category'][$m]['name'];
            $resultArray['category'][$m] = $categoryValue;
            $categorySub_count = count($conjson['category'][$m]['sub']);
            $resultArray['categorySub'][$m] = array();

            for($m2=0;$m2<count($conjson['category'][$m]['sub']);$m2++){

                $categorySubKey = key($conjson['category'][$m]['sub'][$m2]);
                $resultArray['categorySub'][$m][$categorySubKey] =  $conjson['category'][$m]['sub'][$m2][$categorySubKey];
            }
        }
        return $resultArray;
    }

}