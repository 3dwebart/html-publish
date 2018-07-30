<?php      
/**
* Description of WebMemberLoginHisDAO
* @description Funhansoft PHP auto templet
* @date 2013-09-25
* @copyright (c)funhansoft.com
* @license http://funhansoft.com/license
* @version 0.2.3
*/
        class WebMemberLoginHisDAOExt extends BaseDAO{

            protected $db;
            protected $dbSlave;

            function __construct() {
                parent::__construct();
            }



            public function setUpdateTempBlock($lh_no,$lhBlockYn){

                parent::setResult(-1);
                if(!$this->db) $this->db=parent::getDatabase(); if($this->db){

                    $sql = "UPDATE web_member_login_his SET
                                lh_block_yn=? WHERE lh_no=?";

                    if($this->db) $stmt = $this->db->prepare($sql);
                    if($stmt){
                        $j=1;
                        $stmt->bindValue( $j++, $lhBlockYn, PDO::PARAM_STR);
                        $stmt->bindValue( $j++, (int)$lh_no, PDO::PARAM_STR);
                        $stmt->execute();

                        if($stmt->rowCount()==1) parent::setResult(1);
                        else parent::setResult(0);
                    }else{
                        parent::setResult(ResError::dbPrepare);
                    }
                }else{
                    parent::setResult(ResError::dbConn);
                }
                return parent::getResult();
            }

            function __destruct(){
                $this->db = NULL;
                $this->dto = NULL;
            }
        }