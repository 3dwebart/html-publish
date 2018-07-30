<?php
/**
* Bitcoin Web Trade Solution
* @description FunHanSoft Co.,Ltd (프로그램 수정 및 사용은 자유롭게 가능하나, 재배포를 엄격히 금지합니다.)
* @date 2015-11-19
* @copyright (c)Funhansoft.com
* @license http://funhansoft.com/solutionlicense/bittrade
* @version 0.0.1
*/
class Controller_Image extends BaseController{
    /**
    * @brief
    **/
    function __construct(){
        parent::__construct();
    }
    /*
     * QR코드 이미지 로드
     */
    public function wallet() {
        
            
        $addr = $this->parameters['q'];
        $address = base64_decode($addr);
        $filepath = $address.'.png';
        
        require_once './Plugin/Wallet/BitcoinRPC.php';
        $walletRPC = new BitcoinRPC();

        if(!$walletRPC->isQRFile($filepath)){
            $walletRPC->createQRImg($address);
        }
        header("Content-type: image/png");
        echo readfile($walletRPC->getAddressQRdir($address).''.$filepath);
//        echo $walletRPC->getAddressQRdir($address).''.$filepath;
        exit();
    }

    public function captcha(){
        $captcha = new PluginCaptchaDAO();
        // OPTIONAL Change configuration...
        //$captcha->wordsFile = 'words/es.php';
        //$captcha->session_var = 'secretword';
        //$captcha->imageFormat = 'png';
        //$captcha->lineWidth = 3;
        //$captcha->scale = 3; $captcha->blur = true;
        //$captcha->resourcesPath = "/var/cool-php-captcha/resources";

        // OPTIONAL Simple autodetect language example
        /*
        if (!empty($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
            $langs = array('en', 'es');
            $lang  = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
            if (in_array($lang, $langs)) {
                $captcha->wordsFile = "words/$lang.php";
            }
        }
        */

        // Image generation
        $captcha->CreateImage();
        exit;

    }



}