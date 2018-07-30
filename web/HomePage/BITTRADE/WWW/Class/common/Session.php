<?php
/**
* Description of BaseDAO
* @author bugnote@funhansoft.com
* @date 2013-08-07
* @copyright (c)funhansoft.com
* @license http://funhansoft.com/license
* @version 0.2.1
*/
final class Session{

    private static $isStart = false;
    private function __construct() {

    }

    public static function startSession(){
        if(self::$isStart == true){
            return;
        }

        if(class_exists('Config')){
            $cfg = Config::getConfig('session');
        
            session_save_path($cfg['save_path']);

            if (isset($SESSION_CACHE_LIMITER))
                @session_cache_limiter($SESSION_CACHE_LIMITER);
            else
                @session_cache_limiter("no-cache, must-revalidate");


            ini_set("session.cache_expire", $cfg['cache_expire']); // 세션 캐쉬 보관시간 (분)
            ini_set("session.gc_maxlifetime",$cfg['gc_maxlifetime'] ); // session data의 garbage collection 존재 기간을 지정 (초)
            ini_set("session.gc_probability", 1); // session.gc_probability는 session.gc_divisor와 연계하여 gc(쓰레기 수거) 루틴의 시작 확률을 관리합니다. 기본값은 1입니다. 자세한 내용은 session.gc_divisor를 참고하십시오.
            //ini_set("session.gc_divisor", 100); // session.gc_divisor는 session.gc_probability와 결합하여 각 세션 초기화 시에 gc(쓰레기 수거) 프로세스를 시작할 확률을 정의합니다. 확률은 gc_probability/gc_divisor를 사용하여 계산합니다. 즉, 1/100은 각 요청시에 GC 프로세스를 시작할 확률이 1%입니다. session.gc_divisor의 기본값은 100입니다.

            session_set_cookie_params(0, '/');
            ini_set("session.cookie_domain", $cfg['cookie_domain']);
        }
        
        if(function_exists('session_status')){
            if (session_status() === PHP_SESSION_NONE){
                @session_start();
                self::$isStart = true;
            }
        }else{
            @session_start();
            self::$isStart = true;
        }
    }
    
    // 세션변수값 얻음
    public static function getSessionId(){
        return session_id();
    }
    // 세션변수 생성
    public static function setSession($name, $value){
        if (PHP_VERSION < '5.3.0')
            session_register($name);
        // PHP 버전별 차이를 없애기 위한 방법
        $_SESSION["$name"] = $value;
    }


    // 세션변수값 얻음
    public static function getSession($name){
        if(!isset($_SESSION)) return null;
        if (!array_key_exists($name, $_SESSION)) {
            return null;
        }else{
            return $_SESSION[$name];
        }
    }
    
    // 세션변수 생성
    public static function delSession($name){
        if (PHP_VERSION < '5.3.0')
            session_unregister($name);
        // PHP 버전별 차이를 없애기 위한 방법
        unset($_SESSION["$name"]);
    }
    
    public static function destorySession(){
        echo("<script>location.replace(tmpProtocol + '//' + tmpHost);</script>"); 
        session_destroy();
    }
    
    
    
    
}