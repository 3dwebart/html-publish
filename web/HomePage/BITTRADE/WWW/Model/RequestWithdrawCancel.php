<?php
class RequestWithdrawCancel extends BaseModelBase{


    function __construct($dbconfname=null) {
        parent::__construct($dbconfname);

    }


    /*
     * 출금요청 취소부분
     * @param url param
     */
    public function getExecute($param){

        // 파라미터 통체크 - 필수입력 값이 입력되지 않았습니다
        if(!$param){
            return array(
                "result"=>-5911,
                "success"=>false,
                "error"=>$this->res->lang->validator->required );
        }

        // redis member info get
        $member = json_decode($this->getMemberDataFromRedis('sum'));
        if(!isset($member->mb_id) || !$member->mb_id){
            return array(
                "result" => -5912,
                "success"=>false,
                "error"=>$this->res->lang->logincheck->notLogin);
        }

        // 취소 처리
        $request_withdraw_cancel_result = $this->execUpdate(
            array(
                'sql'=>$this->res->ctrl->sql,
                'mapkeys'=>$this->res->ctrl->mapkeys,
                'rowlimit'=>1
            )
            , array('od_pay_status'=>$param['od_pay_status']
                ,'od_id'=>$param['od_id'])
        );

        return array($request_withdraw_cancel_result);
    }


    function __destruct(){

    }

}
