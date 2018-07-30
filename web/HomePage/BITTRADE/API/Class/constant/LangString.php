<?php
/**
* Description of BaseDAO
* @author bugnote@funhansoft.com
* @date 2013-08-07
* @copyright (c)funhansoft.com
* @license http://funhansoft.com/license
* @version 0.2.1
*/




class LangString {

    const DEFINED_DIR = '/lang/';
    private $cfgfolder;
    private $jsonlangs;

    function __construct() {
        $this->cfgfolder = Config::getConfig('folder');
        $filelang = file_get_contents($this->getDefinedLanguage());
        $this->jsonlangs = $filelang;
    }

    private function getDefinedLanguage() {
        $path = $this->cfgfolder['jsondefine2'].self::DEFINED_DIR;
        $lang = '';
        $lang = Utils::getCookiePlain("Language");
        switch ($lang){
            case "en":
                return $path . 'en.json';
                break;
            case "zh":
                return $path . 'zh.json';
                break;
            default:
                return $path . 'ko.json';
                break;
        }
    }

    public function __toString() {
        return (string) $this->jsonlangs;
    }

}

?>
