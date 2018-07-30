<?php
/**
* Bitcoin Web Trade Solution
* @description FunHanSoft Co.,Ltd (프로그램 수정 및 사용은 자유롭게 가능하나, 재배포를 엄격히 금지합니다.)
* @date 2015-11-19
* @copyright (c)Funhansoft.com
* @license http://funhansoft.com/solutionlicense/bittrade
* @version 0.0.1
*/
class Controller_tradingview extends BaseController{

	/**
	* @brief 구조
	* @date 2015-06-11
	**/
	function __construct($conjson=NULL){
        parent::__construct($conjson);
    }

    /*******************************************************************
     * === json파일이 있을 경우 메소드는 아래의 파일에서 정의 ==== 
     * 경로 :../Defined/controller/클래스이름.json
     * 설명 : View페이지가 필요 없고 DB 쿼리 위주의 rest api를 작성할 경우 사용
     *        받는 파라미터 해당 파일의 mapkeys 참고
     ******************************************************************/
}