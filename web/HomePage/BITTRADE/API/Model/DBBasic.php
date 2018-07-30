<?php
class DBBasic extends BaseModelBase{

    function __construct($dbconfname=null) {
        parent::__construct($dbconfname);
    }
    public function getLists($paramPage,$param=''){
        return $this->execLists(array(),$param,$paramPage);
    }
    /*************
     * 수정 / 삽입
     ***********/
    public function getUpdate($param){
        return $this->execUpdate(array(),$param);
    }
    
    function __destruct(){
        
    }
}
