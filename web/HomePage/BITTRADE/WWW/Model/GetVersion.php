<?php
class GetVersion extends BaseModelBase{

    private $member;

    function __construct($dbconfname=null) {
        parent::__construct($dbconfname);
    }
    /*
     * 실행
     * @param url param
     */
    public function getExecute($param){

        if(!$param){
            return array(
                "result" => -5009,
                "success"=>false,
                "error"=>$this->res->lang->logincheck->validator );
        }
        
        $rows = $this->execLists(array(),$param);
        if(isset($rows[0]))  $this->member = $rows[0];
        else {
              return array(
                        "result" => -5011,
                        "success"=>false,
                        "error"=>$this->res->lang->logincheck->validator);
              
        }
        
        $ret_check_version = (int)str_replace('.','',$this->member['check_version']);
        $param_check_version = (int)str_replace('.','',$param['check_version']);
        
        if($param_check_version < $ret_check_version){
              return array(
                        "result" => $this->member['check_address'],
                        "success"=>true,
                        );
        }else{
              return array(
                        "result" => $this->member['live_address'],
                        "success"=>true,
                        );
        }
        
    }
   
    
    function __destruct(){

    }



}
