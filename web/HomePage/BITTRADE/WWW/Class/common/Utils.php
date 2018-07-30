<?php
/**
* Description of Utils
* @author bugnote@funhansoft.com
* @date 2013-08-07
* @copyright (c)funhansoft.com
* @license http://funhansoft.com/license
* @version 0.2.2
*/
final class Utils{

    private function __construct() {
    }
    public static function jsonEncode($value){
        return json_encode($value,JSON_HEX_APOS);
    }
    public static function jsonDecode($value,$isarray=false){
        $res = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', stripslashes($value));


        return json_decode(stripslashes($res),$isarray);
    }
    /**
     * Generate link.
     * @param string $mode target page
     * @param array $params page parameters
     */
    public static function createLink($mode, array $params = array()) {
        //$params = array_merge(array('mode' => $mode), $params);
        // TODO add support for Apache's module rewrite
        return '/'.$mode.'/' .http_build_query($params);
    }

    /**
     * Format date.
     * @param DateTime $date date to be formatted
     * @return string formatted date
     */
    public static function formatDate(DateTime $date = null) {
        if ($date === null) {
            return '';
        }
        return $date->format('Y-m-d');
    }

    /**
     * Format date and time.
     * @param DateTime $date date to be formatted
     * @return string formatted date and time
     */
    public static function formatDateTime(DateTime $date = null) {
        if ($date === null) {
            return '';
        }
        return $date->format('Y-m-d H:i');
    }

    /**
     * Redirect to the given page.
     * @param type $mode target page
     * @param array $params page parameters
     */
    public static function redirect($mode, array $params = array()) {
        if($mode==Utils::getUrlParam('mode',ResError::no)){
            return;
        }
        if($mode=='/') $returnurl = '/';
        else $returnurl = self::createLink($mode, $params);
        header('Content-Type: text/html; charset=utf-8');
        header('Expires: 0');
        header('Cache-Control: no-store, no-cache, must-revalidate'); // HTTP/1.1
        header('Cache-Control: pre-check=0, post-check=0, max-age=0'); // HTTP/1.1
        header('Pragma: no-cache'); // HTTP/1.0
        header('Location: ' . $returnurl);
        die();
    }

    public static function getBaseUrl(){
        $baseurl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') ? 'https://' : 'http://';
        $baseurl .= ($_SERVER['SERVER_PORT'] != '80') ? $_SERVER['HTTP_HOST'].':'.$_SERVER['SERVER_PORT'] : $_SERVER['HTTP_HOST'];
        return $baseurl;

    }

    public static function nl2br($str){
        return preg_replace('/\\\\r\\\\n|\\\\r|\\\\n/','<br />',$str);
    }

    public static function getGET() {
        return @@$_GET;
    }

    public static function getPOST() {
        return @@$_POST;
    }

    /**
     * Get value of the URL param.
     * @return string parameter value
     * @throws NotFound if the param is not found in the URL
     */
    public static function getUrlParam($name,$noerror=0,$orgindata=0) {

        $returnv = '';
        if($noerror){
            $returnv = @@$_GET[$name];
        }else{
            if (!array_key_exists($name, @$_GET) || @$_GET[$name]=='null') {
                throw new NotParam('POST parameter "' . $name . '" not found.',ResError::paramEmptyPost);
            }
            if(is_string(@$_GET[$name])) $returnv = trim(@$_GET[$name]);
            else $returnv = @$_GET[$name];
        }
        if(!$orgindata){
            $returnv =str_replace("'", "\"", $returnv);
            $returnv = self::jsAddSlashes($returnv);
        }
        return $returnv;
    }

    public static function getPostParam($name,$noerror=0,$orgindata=0) {
        $returnv = '';
        if($noerror){
            $returnv = @$_POST[$name];
        }else{
            if (!array_key_exists($name, $_POST) || $_POST[$name]=='null') {
                throw new NotParam('POST parameter "' . $name . '" not found.',ResError::paramEmptyPost);
            }
            if(is_string($_POST[$name])) $returnv = trim($_POST[$name]);
            else $returnv = $_POST[$name];
        }
        if(!$orgindata){
            $returnv =str_replace("'", "\"", $returnv);
            $returnv = self::jsAddSlashes($returnv);
        }
        return $returnv;
    }
    
    public static function getPostHTMLParam($name,$noerror=0) {
        if($noerror){
            $result = @$_POST[$name].'';
        }else{
            if (!array_key_exists($name, $_POST) || $_POST[$name]=='null') {
                throw new NotParam('POST html parameter "' . $name . '" not found.',ResError::paramEmptyPost);
            }
            $result = $_POST[$name];
        }
        return Utils::getHTMLParam($result,$noerror);
        
    }

    public static function getHTMLParam($result,$noerror=0) {


        // http://htmlpurifier.org/
        // Standards-Compliant HTML Filtering
        // Safe  : HTML Purifier defeats XSS with an audited whitelist
        // Clean : HTML Purifier ensures standards-compliant output
        // Open  : HTML Purifier is open-source and highly customizable
        $f = file('./Plugin/htmlpurifier/safeiframe.txt');
        $domains = array();
        foreach($f as $domain){
            // 첫행이 # 이면 주석 처리
            if (!preg_match("/^#/", $domain)) {
                $domain = trim($domain);
                if ($domain)
                    array_push($domains, $domain);
            }
        }
        //내 도메인도 추가
        array_push($domains, $_SERVER['HTTP_HOST'].'/');
        $safeiframe = implode('|', $domains);

//        include_once('./Plugin/htmlpurifier/HTMLPurifier.standalone.php');
        include_once('./Plugin/htmlpurifier-4.9.2/HTMLPurifier.auto.php');
        $config = HTMLPurifier_Config::createDefault();
        //디렉토리에 CSS, HTML, URI 디렉토리 등을 만든다.
        $config->set('Cache.SerializerPath', './../WebApp/Debug');
        $config->set('HTML.SafeEmbed', true);
        $config->set('HTML.SafeObject', true);
        $config->set('HTML.SafeIframe', true);
        $config->set('URI.SafeIframeRegexp','%^(https?:)?//('.$safeiframe.')%');
        $config->set('Attr.AllowedFrameTargets', array('_blank'));
        $purifier = new HTMLPurifier($config);
        $result = preg_replace('/\r\n|\r|\n/','',$purifier->purify($result));
        $result = stripslashes($result);
        if(is_string($result)){
            $result =str_replace("'", "\"", $result);
            return addslashes(trim($result));
        }
        else return $result;
    }


    public static function getFileParam($name,$noerror=0) {
        if($noerror) return @$_FILES[$name];
        else{
            if (!array_key_exists($name, $_FILES)) {
                throw new NotParam('FILE parameter "' . $name . '" not found.',ResError::paramEmptyFile);
            }
            return $_FILES[$name];
        }
    }

    public static function getDateNow() {
        return date("Y-m-d H:i:s",time());
    }

    public static function getAddMonth($month) {
        return date("Y-m-d H:i:s",strtotime("+{$month} month"));
    }
    public static function getAddDay($day,$timeymd='') {
        if($timeymd){
            return date("Y-m-d H:i:s",strtotime("{$timeymd} +{$day} day"));
        }else{
            return date("Y-m-d H:i:s",strtotime("+{$day} day"));
        }
    }
    // 쿠키변수 생성
    public static function setCookie($name, $value, $sec=1200,$domain=null)
    {
        setcookie(md5($name), base64_encode($value), time() + $sec, '/',$domain);
    }


    // 쿠키변수값 얻음
    public static function getCookie($name)
    {
        $cookie = md5($name);
        if (array_key_exists($cookie, $_COOKIE))
            return base64_decode($_COOKIE[md5($name)]);
        else
            return "";
    }

    public static function getCookiePlain($name)
    {
        if (array_key_exists($name, $_COOKIE))
            return base64_decode($_COOKIE[$name]);
        else
            return "";
    }

    /**
     * Capitalize the first letter of the given string
     * @param string $string string to be capitalized
     * @return string capitalized string
     */
    public static function capitalize($string) {
        return ucfirst(mb_strtolower($string));
    }

    public static function jsAddSlashes($str) {
        $pattern = array(
            "/\\\\/"  , "/\n/"    , "/\r/"    , "/\"/"    ,
            "/\'/"    , "/&/"     , "/</"     , "/>/"
        );
        $replace = array(
            "\\\\\\\\", "\\n"     , "\\r"     , "\\\""    ,
             "´"      , "\\x26"   , "\\x3C"   , "\\x3E"
        );
        return preg_replace($pattern, $replace, $str);
    }

    /**
     * Escape the given string
     * @param string $string string to be escaped
     * @return string escaped string
     */
    public static function escape($string) {
        return htmlspecialchars($string, ENT_QUOTES);
    }

    public static function unescape($string) {
        $trans = get_html_translation_table(HTML_ENTITIES);
        $trans = array_flip($trans);
        return addslashes(strtr($string, $trans));
    }
    /**
     * 외부에 POST방식으로 전달
     * @param string $url string to be escaped
     * @param string $postdata string to be sended
     * @param string $url
     * @return string escaped string
     */
    public static function doPostRequest($url, $postdata, $files = null)
    {
        $data = "";
        $boundary = "---------------------".substr(md5(rand(0,32000)), 0, 10);

        //Collect Postdata
        foreach($postdata as $key => $val)
        {
            $data .= "--$boundary\n";
            $data .= "Content-Disposition: form-data; name=\"".$key."\"\n\n".$val."\n";
        }
        $data .= "--$boundary\n";

        //Collect Filedata
        if($files!=null){
            foreach($files as $key => $file)
            {
                $fileContents = file_get_contents($file['tmp_name']);

                $data .= "Content-Disposition: form-data; name=\"{$key}\"; filename=\"{$file['name']}\"\n";
                $data .= "Content-Type: image/jpeg\n";
                $data .= "Content-Transfer-Encoding: binary\n\n";
                $data .= $fileContents."\n";
                $data .= "--$boundary--\n";
            }
        }

        $params = array('http' => array(
            'method' => 'POST',
            'header' => 'Content-Type: multipart/form-data; boundary='.$boundary,
            'content' => $data
            ));

        $ctx = stream_context_create($params);
        $fp = fopen($url, 'rb', false, $ctx);

        if (!$fp) {
            throw new Exception("Problem with $url, $php_errormsg");
        }

        $response = @stream_get_contents($fp);
        if ($response === false) {
            throw new Exception("Problem reading data from $url, $php_errormsg");
        }
        return $response;
    }


    public static function getClientIP(){


        if(@array_key_exists('HTTP_X_REAL_IP', $_SERVER) && !empty($_SERVER['HTTP_X_REAL_IP']) ) {
            if ($_SERVER['SERVER_ADDR'] == $_SERVER['REMOTE_ADDR']) {
                return $_SERVER['HTTP_X_REAL_IP'];
            }else{
                return $_SERVER['REMOTE_ADDR'];
            }
        }else if( @array_key_exists('HTTP_X_FORWARDED_FOR', $_SERVER) && !empty($_SERVER['HTTP_X_FORWARDED_FOR']) ) {
            if (strpos($_SERVER['HTTP_X_FORWARDED_FOR'], ',')>0) {
                $addr = explode(",",$_SERVER['HTTP_X_FORWARDED_FOR']);
                return trim($addr[0]);
            } else {
                return $_SERVER['HTTP_X_FORWARDED_FOR'];
            }

        }else {
            return $_SERVER['REMOTE_ADDR'];
        }
    }

    //한글자르기
    public static function strcutUtf8($str, $len, $checkmb=false, $tail='..'){
        preg_match_all('/[\xEA-\xED][\x80-\xFF]{2}|./', $str, $match);

        $m = $match[0];
        $slen = strlen($str); // length of source string
        $tlen = strlen($tail); // length of tail string
        $mlen = count($m); // length of matched characters

        if ($slen <= $len) return $str;
        if (!$checkmb && $mlen <= $len) return $str;

        $ret = array();
        $count = 0;

        for ($i=0; $i < $len; $i++) {
            $count += ($checkmb && strlen($m[$i]) > 1)?2:1;

            if ($count + $tlen > $len) break;
            $ret[] = $m[$i];
        }
        return join('', $ret).$tail;
    }
    /*
     * 자동로그인 토큰
     */
    public static function getAppLoginToken($member){
        return md5($member->mbNo.$member->mbKey).'-'.md5($member->mbNo.$member->mbPwd).'-'.md5(Utils::getClientIP() );
    }

    /*
     ** @brief 토큰 생성
     * @return string
     */
    public static function createToken(){
        Session::startSession();
        $ss_token = Session::getSessionId().time();
        Session::setSession("token", $ss_token);
        return $ss_token;
    }

    /*
     * 이더리움 헥사
     */
    public static function bigHexToBigDec($hex)
	{
		$dec = '0';
		$len = strlen($hex);
		for ($i = 1; $i <= $len; $i++) {
			$dec = bcadd($dec, bcmul(strval(hexdec($hex[$i - 1])), bcpow('16', strval($len - $i))));
		}

		return $dec;
	}

}
