<?php
class EmailDuplication extends BaseModelBase{

    function __construct($dbconfname=null) {
        parent::__construct($dbconfname);

    }
    /*
     * 실행
     * @param url param
     */
    public function getExecute($param){

        if( !isset($param['query']) ){
            return array(
                "result" => -5201,
                "success"=>false,
                "error"=>$this->res->lang->validator->required);
        }
        $param['query'] = base64_decode($param['query']);

        $sql_email_duplicate = $this->execLists(
            array(
                'sql'=>$this->res->ctrl->sql,
                'mapkeys'=>$this->res->ctrl->mapkeys,
                'rowlimit'=>1
            ),array(
                'mb_id'=>$param['query'],
            )
        );
        return array("result" => $sql_email_duplicate);
    }



    function __destruct(){

    }

}
