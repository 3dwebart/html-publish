<?php
/*
 * 실행 후 바로 로그 아웃
 */
final class Index {

    private $controller;

    /**
     * System config.
     */
    public function init() {

        date_default_timezone_set('Asia/Seoul');
        // error reporting - all errors for development (ensure you have display_errors = On in your php.ini file)
        if($_SERVER && isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR']=='127.0.0.1'){
            error_reporting(E_ALL | E_STRICT);
        }else{
            error_reporting(E_STRICT);
        }
        ini_set("display_errors", "1"); //"1"은 디폴트
        ini_set("session.use_trans_sid", 0);    // PHPSESSID를 자동으로 넘기지 않음
        ini_set("url_rewriter.tags",""); // 링크에 PHPSESSID가 따라다니는것을 무력화함
        ini_set("session.cache_expire", 14400); // 세션 캐쉬 보관시간 (분)
        ini_set("session.gc_maxlifetime", 864000); // session data의 gabage collection 존재 기간을 지정 (초)
        ini_set('max_execution_time', 300);
        mb_internal_encoding('UTF-8');
        set_exception_handler(array($this, 'handleException'));
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
    public function handleException(Exception $ex) {
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

    public function loadClass($name) {
        $classes = array(
            'Config'        	=> './Class/Config.class.php',
            'ResError'         => './Class/constant/ResError.php',
            'ResString'        	=> './Class/constant/ResString.php',
            'NotFound'      	=> './Class/exception/NotFound.php',
            'NotParam'      	=> './Class/exception/NotParam.php',
            'ExceptionParam'    => './Class/exception/ExceptionParam.php',
            'DBException'       => './Class/exception/DBException.php',
            'Utils'         	=> './Class/common/Utils.php',
            'Session'           => './Class/common/Session.php',
            'Thumbnailer'       => './Class/common/Thumbnailer.php',
            'MCrypt'            => './Class/common/MCrypt.php',
            'Logging'           => './Class/common/Logging.php',
            'StringChecker'     => './Class/common/StringChecker.php',
            'Viewer'            => './Class/common/Viewer.php',
            'BaseDAO'           => '../WebApp/Engine/ADMIN/LICENSE2',
            'ControllerBase'	=> '../WebApp/Engine/ADMIN/LICENSE2',
            'ControllerLoader'  => '../WebApp/Engine/ADMIN/LICENSE2',
            'Credis_Client'     => './Plugin/Credis/Client.php',
            'jsonRPCClient'     => './Plugin/jsonrpcphp/includes/jsonRPCClient.php'
        );


        if (!array_key_exists($name, $classes)) {
            $this->loadClassDAO($name);
            //die('Class "' . $name . '" not found.');
        }else{
            require_once $classes[$name];
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


        if($pri){

            if(!Session::getSession('admin_mb_id') || Session::getSession('login_ip') != Utils::getClientIP() )
            {
                $memberdao = new WebAdminMemberDAO();
                $member = $memberdao->getViewByMbId($mbId);
                $oauthResult = $this->checkConfigIpAddress();
//                if($oauthResult || $token == Utils::getAppLoginToken($member)){
                if($oauthResult){
                    Session::setSession('admin_mb_id',$member->mbId);
                    Session::setSession('admin_mb_name',$member->mbName);
                    Session::setSession('login_ip',Utils::getClientIP());
                    echo "oauth ok\n";
                }
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
        Utils::redirect('/');
    }

}

$index = new Index();
$index->init();
$index->checkLoginToken();
$index->run();
$index->logout();
