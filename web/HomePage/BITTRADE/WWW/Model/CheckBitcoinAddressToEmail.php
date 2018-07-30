<?php
class CheckBitcoinAddressToEmail extends BaseModelBase{

    function __construct($dbconfname=null) {
        parent::__construct($dbconfname);

    }
    /*
     * 실행
     * @param url param
     */
    // 비트코인 출금시 받는 곳 주소 이메일일경우 체크 
    public function getExecute($param){

        // 필수입력 값이 입력되지 않았습니다
        if( !isset($param['address'])){
            return array(
                "result" => -5301,
                "success"=>false,
                "error"=>$this->res->lang->validator->required);
        }
        $send_mb_id = base64_decode($param['address']);

        $sql_mb_info = $this->execlists(
            array(
                'sql'=>$this->res->ctrl->sql,
                'mapkeys'=>$this->res->ctrl->mapkeys,
                'rowlimit'=>1
            ),
            array(
                'mb_id'=>$send_mb_id
            )
        );
        $mb_info = array("result"=>0, "mb_bit_wallet"=>"");
        if( isset($sql_mb_info[0]) && $sql_mb_info[0]['result'] > 0 ){
            $mb_info = $sql_mb_info[0];
        }
        return $mb_info;
    }


    function __destruct(){

    }

}
