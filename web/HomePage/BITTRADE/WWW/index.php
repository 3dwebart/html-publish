<?php

final class Index {
    private $controller;
    private $commonClasses;
    /**
     * System config.
     */
    public function init() {

        date_default_timezone_set('Asia/Seoul');
//         error reporting - all errors for development (ensure you have display_errors = On in your php.ini file)
        if($_SERVER['REMOTE_ADDR']=='127.0.0.1' || $_SERVER['REMOTE_ADDR']=='119.197.22.176' || $_SERVER['REMOTE_ADDR']=='121.173.110.67' ||  $_SERVER['REMOTE_ADDR']=='175.193.165.157'){
            error_reporting(E_ALL | E_STRICT);
            
        }else{
            error_reporting(0); 
        }
        
        $this->initCommonClass();

        ini_set("display_errors", "1"); //"1"은 디폴트
        ini_set("session.use_trans_sid", 0);    // PHPSESSID를 자동으로 넘기지 않음
        ini_set("url_rewriter.tags",""); // 링크에 PHPSESSID가 따라다니는것을 무력화함

        mb_internal_encoding('UTF-8');
        set_exception_handler(array($this, 'handleException'));
        spl_autoload_register(array($this, 'loadClass'));
    }

    /**
     * Run the application
     */
    public function run() {
        $this->controller = new ControllerLoader();
        $this->controller->runPage();
    }

    /**
     * Exception handler
     */
    public function handleException($ex) {
        $extra = array('message' => $ex->getMessage());
        if ($ex instanceof NotFound) {
            header('HTTP/1.0 404 Not Found');
            //$this->runPage('/404', $extra);
        } else {
            // TODO log exception
            @header('HTTP/1.1 500 Internal Server Error');
            //$this->runPage('/500', $extra);
        }
    }

    /**
     * Class loader.
     */
    private function loadModelClass($name) {
        $filenamedao = "./Model/" . $name . ".php";
//        $filenamebasic = "./Model/DBBasic.php";
        if (is_readable($filenamedao)) {
            require_once $filenamedao;
//        }else if(is_readable($filenamebasic)){
//            require_once $filenamebasic;
        }else{
            die('Class "' . $name . '" not found.');
        }
    }
    
    private function initCommonClass(){
        $this->commonClasses = array(
            'Config'        	   => './Class/Config.class.php',
            'LangString'        => './Class/constant/LangString.php',
            'Page'              => './Class/constant/Page.php',
            'JsonConfig'        => './Class/constant/JsonConfig.php',
            'NotFound'      	   => './Class/exception/NotFound.php',
            'NotParam'      	   => './Class/exception/NotParam.php',
            'ExceptionParam'    => './Class/exception/ExceptionParam.php',
            'DBException'       => './Class/exception/DBException.php',
            'RPCException'      => './Class/exception/RPCException.php',
            'Utils'         	=> './Class/common/Utils.php',
            'Session'           => './Class/common/Session.php',
            'Thumbnailer'       => './Class/common/Thumbnailer.php',
            'MCrypt'            => './Class/common/MCrypt.php',
            'Logging'           => './Class/common/Logging.php',
            'FileCache'         => './Class/common/FileCache.php',
            'StringChecker'     => './Class/common/StringChecker.php',
            'Viewer'            => './Class/common/Viewer.php',
            'Language'         	=> './Class/common/Language.php',
            'BaseModelBase'     => './Class/common/BaseModelBase.php',
            'ControllerLoader'	=> '../WebApp/Engine/WWW/LICENSE4',
            'BaseController'    => '../WebApp/Engine/WWW/LICENSE4',
            'Credis_Client'     => './Plugin/Credis/Client.php',
            'jsonRPCClient'     => './Plugin/jsonrpcphp/includes/jsonRPCClient.php',
            'PluginBitcoin'     => './Plugin/PluginBitcoin.php'
        );

        if((float)phpversion() < 7.0){
            $this->commonClasses['ResError'] = './Class/constant/ResError5.php';
        }else{
            $this->commonClasses['ResError'] = './Class/constant/ResError.php';
        }
    }

    public function loadClass($name) {

        if (!array_key_exists($name, $this->commonClasses)) {
            $this->loadModelClass($name);
            //die('Class "' . $name . '" not found.');
        }else{
            if($name=='ControllerLoader' || $name=='BaseController' ){
              ini_set('screwim.enable', true);
            }else{
              ini_set('screwim.enable', false);
            }
            require_once $this->commonClasses[$name];
        }
    }

}

// -------------------------------------
// 서버 점검중 , 관리자들만 접속 가능, 서버아이피 반드시 필요
// -------------------------------------
//if($_SERVER['REMOTE_ADDR']!='000.000.000.000' ){
//    include 'View/main/servercheck.php';
//    exit;
//}

$index = new Index();
$index->init();
$index->run();
