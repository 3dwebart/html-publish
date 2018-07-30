<?php
/**
* Bitcoin Web Trade Solution
* @description FunHanSoft Co.,Ltd (프로그램 수정 및 사용은 자유롭게 가능하나, 재배포를 엄격히 금지합니다.)
* @date 2015-11-19
* @copyright (c)Funhansoft.com
* @license http://funhansoft.com/solutionlicense/bittrade
* @version 0.0.1
*/
class Controller_language extends BaseController{


    function __construct($conjson=NULL){
        parent::__construct($conjson);
    }

    function main(){

        $language = $this->parameters['js'];
        ob_start();

        if($language=='en'){
            require_once '../WebApp/Defined/lang/js-en.json';
        }else if($language=='zh'){
            require_once '../WebApp/Defined/lang/js-zh.json';
        }else{
            require_once '../WebApp/Defined/lang/js-ko.json';
        }
        $ob_content = ob_get_clean();
        ob_end_clean();

        echo 'var lang = '.($ob_content).';';
    }
}