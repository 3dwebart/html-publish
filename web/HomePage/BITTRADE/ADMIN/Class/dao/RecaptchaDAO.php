<?php
/**
* Description of RecaptchaDAO
* @description Funhansoft PHP auto templet
* @date 2016-08-06
* @copyright (c)funhansoft.com
* @license http://funhansoft.com/license
* @version 4.0.0
*/
class RecaptchaDAO extends BaseDAO{

    private $db;
    private $dbSlave;

    function __construct() {
        parent::__construct();
        $this->cfg = Config::getConfig();
    }

    function recaptcha($value){
        $postdata = http_build_query(
            array('secret'=>$this->cfg['recaptcha']['secret'],
                'remoteip'=>Utils::getClientIP(),
                'response'=>$value)
            );
        $opts = array(
            'http'=>array(
            'method'=>"POST",
            'header'=>"Accept-language: ".$_SERVER['HTTP_ACCEPT_LANGUAGE']."\r\n" .
                "Content-Type: application/x-www-form-urlencoded; charset=UTF-8\r\n",
            'content'=>$postdata
            )
        );
        $recaptchaurl = 'https://www.google.com/recaptcha/api/siteverify';
        $context = stream_context_create($opts);
        $rescov = file_get_contents($recaptchaurl, false, $context);
        $resjson = json_decode($rescov,true);

        if( !isset($resjson['success']) || !$resjson['success'] ){
            parent::setResult(ResError::captcha);
            $resjson['success']= false;
        }
        return $resjson;
    }



    function __destruct(){

    }
}