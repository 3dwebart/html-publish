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
        $path = $cfgfolder['jsondefine'].self::DEFINED_DIR.$key.'.json';
        if(file_exists($path)){
            $file = file_get_contents($path);
            $jsonarr = (array)json_decode($file, true);
        }
        return $jsonarr;
    }

}