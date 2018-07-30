<?php
/**
* Description of JsonConfig
* @author bugnote@funhansoft.com
* @date 2013-08-07
* @copyright (c)funhansoft.com
* @license http://funhansoft.com/license
* @version 0.2.2
*/

final class JsonConfig{

    const DEFINED_DIR = '/config/';

    public static function get($key) {
        $jsonarr = array();
        $cfgfolder = Config::getConfig('folder');

        // 2017-08-29 KIMJH
        // 기존 jsondefine값이  WebAPP/DefineAPI를 가르치기 때문에
        // 웹상에서 운영되는 WebAPP/Define쪽에서 사용하는  json파일과 달라서
        // 다른 파일(동일한 이름)을 사용하는 경우가 생겨서 config.api.ini에서 기존 jsondefine은
        // 두고 새롭게 jsondefine2 항목을 만들어 WebAPP/Define 을 가르키도록 함
        $path = $cfgfolder['jsondefine2'].self::DEFINED_DIR.$key.'.json';     
        if(file_exists($path)){
            $file = file_get_contents($path);
            $jsonarr = (array)json_decode($file, true);
        }
        return $jsonarr;
    }

}