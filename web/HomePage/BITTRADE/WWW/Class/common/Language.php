<?php
/**
* Description of Language
* @author bugnote@funhansoft.com
* @date 2013-08-07
* @copyright (c)funhansoft.com
* @license http://funhansoft.com/license
* @version 0.2.2
*/
final class Language{

    protected $res;
    protected $langString;
    protected $conjson;

    private function __construct() {
    }
    /*
     ** @brief 언어 코드 유무 체크 및 매핑
     * @return string
     */
    public static function langConvert($language, $key){

        $value = '';
        try {
            if(!$language || !property_exists($language, $key)){
                echo '?lang.'.$key;
                return;
            }
            if(!isset($language->$key)){
                echo '?lang.'.$key;
                return;
            }
            $value = $language->$key;
            if(strpos($value, '\"')){
                $value = str_replace('\"', '\'', $value);
            }
        }catch (Exception $e) {
            $log = new Logging();
            $log->lfile('../WebApp/Debug/Language.txt');
            $log->lwrite("not found key : {$key}\n");
            $log->lclose();
            echo '?lang.'.$key;
            return;
        }
        echo $value;
        return;
    }
}