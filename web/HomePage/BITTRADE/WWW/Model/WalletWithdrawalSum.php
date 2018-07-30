<?php
class WalletWithdrawalSum extends BaseModelBase{
    private $member;

    function __construct($dbconfname=null) {
        parent::__construct($dbconfname);
    }
    
    public function getExecute($param){
        $result = $this->execRemoteLists($this->res->ctrl->database->wallet,
            array(
                'sql'=>$this->res->ctrl->sql,
                'mapkeys'=>$this->res->ctrl->mapkeys,
                'rowlimit'=>1
            )
            , array(
                'po_type'=>$param['po_type'],
                'day'=>(int)$param['day']
            )
        );
        return $result;
    }

    function __destruct(){

    }

}
