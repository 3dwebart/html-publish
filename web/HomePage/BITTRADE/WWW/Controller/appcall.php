<?php
/**
* Bitcoin Web Trade Solution
* @description FunHanSoft Co.,Ltd (프로그램 수정 및 사용은 자유롭게 가능하나, 재배포를 엄격히 금지합니다.)
* @date 2015-11-19
* @copyright (c)Funhansoft.com
* @license http://funhansoft.com/solutionlicense/bittrade
* @version 0.0.1
*/
class Controller_appcall extends BaseController{


    function __construct($conjson=NULL){
        parent::__construct($conjson);
    }
    
    // 주소 유효성 확인
    function validbtcaddress(){

        $PluginBitcoin = new PluginBitcoin();
        $ValidateAddress = $PluginBitcoin->getRPCValidateAddress($this->parameters['address']);
        $result = $ValidateAddress;
		$account = $PluginBitcoin->getRPCAccountByAddress($this->parameters['address']);
        $isinner = false;
		if($account){
			$isinner = true;
		}

        return json_encode(array('result'=>$result,'isinner'=>$isinner, 'account'=>$account));
    }
}