<?php

/*
 * 실행 후 바로 로그 아웃
 */
final class Index {

    private $controller;
    private $commonClasses;

    /**
     * System config.
     */
    public function init() {

        date_default_timezone_set('Asia/Seoul');
 //       date_default_timezone_set('Asia/Singapore');
        // error reporting - all errors for development (ensure you have display_errors = On in your php.ini file)
        if($_SERVER['REMOTE_ADDR']=='127.0.0.1' || $_SERVER['REMOTE_ADDR']=='119.197.22.176' || $_SERVER['REMOTE_ADDR']=='121.173.110.67' ){
            error_reporting(E_ALL | E_STRICT);
        }else{
            error_reporting(E_STRICT);
        }
        
        $this->initCommonClass();
        
        ini_set("display_errors", "1"); //"1"은 디폴트
        ini_set("session.use_trans_sid", 0);    // PHPSESSID를 자동으로 넘기지 않음
        ini_set("url_rewriter.tags",""); // 링크에 PHPSESSID가 따라다니는것을 무력화함
        ini_set("session.cache_expire", 14400); // 세션 캐쉬 보관시간 (분)
        ini_set("session.gc_maxlifetime", 864000); // session data의 gabage collection 존재 기간을 지정 (초)
        ini_set('max_execution_time', 300);
        mb_internal_encoding('UTF-8');
//        set_exception_handler(array($this, 'handleException'));
        spl_autoload_register(array($this, 'loadClass'));

    }

    /**
     * Run the application
     */
    public function run() {
        $this->controller = new ControllerLoader();
        $this->controller->runPage($this->controller->getPage());
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
            header('HTTP/1.1 500 Internal Server Error');
            //$this->runPage('/500', $extra);
        }
    }

    /**
     * Class loader.
     */
    private function loadClassDAO($name) {
        $filename = "./Class/dao/" . $name . ".php";
        if (is_readable($filename)) {
            require_once $filename;
        }else{
            die('Class "' . $name . '" not found.');
        }
    }
    
    private function initCommonClass(){
        $this->commonClasses = array(
            'Config'        	 => './Class/Config.class.php',
            'ResString'        	 => './Class/constant/ResString.php',
            'NotFound'      	 => './Class/exception/NotFound.php',
            'NotParam'      	=> './Class/exception/NotParam.php',
            'ExceptionParam'    => './Class/exception/ExceptionParam.php',
            'Session'           => './Class/common/Session.php',
            'DBException'       => './Class/exception/DBException.php',
            'Utils'         	=> './Class/common/Utils.php',
            'Thumbnailer'       => './Class/common/Thumbnailer.php',
            'MCrypt'            => './Class/common/MCrypt.php',
            'Logging'           => './Class/common/Logging.php',
            'StringChecker'     => './Class/common/StringChecker.php',
            'Viewer'            => './Class/common/Viewer.php',
            'BaseDAO'           => '../WebApp/Engine/ADMIN/LICENSE4',
            'ControllerBase'	=> '../WebApp/Engine/ADMIN/LICENSE4',
            'ControllerLoader'  => '../WebApp/Engine/ADMIN/LICENSE4',
            'Credis_Client'     => './Plugin/Credis/Client.php',
            'jsonRPCClient'     => './Plugin/jsonrpcphp/includes/jsonRPCClient.php',
            'PHPExcel'          => './Plugin/PHPExcel/Classes/PHPExcel.php'
        );

        if((float)phpversion() < 7.0){
            $this->commonClasses['ResError'] = './Class/constant/ResError5.php';
        }else{
            $this->commonClasses['ResError'] = './Class/constant/ResError.php';
        }
    }

    public function loadClass($name) {
        
        if (!array_key_exists($name, $this->commonClasses)) {
            $this->loadClassDAO($name);
            //die('Class "' . $name . '" not found.');
        }else{
            if($name=='BaseDAO' || $name=='ControllerBase' || $name=='ControllerLoader' ){
              ini_set('screwim.enable', true);
            }else{
              ini_set('screwim.enable', false);
            }

            require_once $this->commonClasses[$name];
        }
    }

    

    public function checkLoginToken(){
        Session::startSession();
        try{
            $pri = Utils::getUrlParam('pri');
            $mbId = ($pri)?base64_decode($pri):'';
            $token = Utils::getUrlParam('token');
        }catch(Exception $e){
            $pri = '';
            $mbId = '';
            $token = '';
        }



        if(!Session::getSession('admin_mb_id') || Session::getSession('login_ip') != Utils::getClientIP() )
        {
            $oauthResult = $this->checkConfigIpAddress();
            if($oauthResult){
                Session::setSession('admin_mb_id','system');
                Session::setSession('admin_mb_name',Utils::getClientIP());
                Session::setSession('login_ip',Utils::getClientIP());
            }
        }

    }

    // config cron 허용 아이피 체크
    private function checkConfigIpAddress(){
        $oauthResult = false;
        $this->cfg = Config::getConfig();
        $cfgIpValue = $this->cfg['cron']['allowip'];
        if(!isset($cfgIpValue)){
            return false;
        }
        $cfgIpValueArr = explode(',', $cfgIpValue);
        for($i=0; $i<count($cfgIpValueArr); $i++){
            if($_SERVER['REMOTE_ADDR']==$cfgIpValueArr[$i]){
                return true;
            }
        }
        return false;
    }

    public function logout(){
        Session::startSession();
        Session::delSession('admin_mb_id');
        Session::delSession('admin_mb_name');
        Session::delSession('admin_mb_auth');
        Session::delSession('login_ip');
        Session::destorySession();
//        Utils::redirect('/');
    }

}



$index = new Index();
$index->init();
$index->checkLoginToken();
$index->run();
//$index->logout();
