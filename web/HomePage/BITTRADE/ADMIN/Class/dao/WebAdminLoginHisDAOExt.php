<?php      
/**
* Description of WebAdminLoginHisDAO
* @description Funhansoft PHP auto templet
* @date 2013-09-03
* @copyright (c)funhansoft.com
* @license http://funhansoft.com/license
* @version 0.2.1
*/
        class WebAdminLoginHisDAOExt extends BaseDAO{

            protected $db;
            protected $dbSlave;

            function __construct() {
                parent::__construct();
                
            }
            
            public function isBlockIp($lh_reg_ip){
                if(!$this->dbSlave) $this->dbSlave=parent::getDatabaseSlave(); if($this->dbSlave){
                    try{
                        $sql = "SELECT 	lh_no
                                FROM web_admin_login_his WHERE lh_reg_ip=? and lh_ip_block=1  limit 1";

                        if($this->dbSlave) $stmt = $this->dbSlave->prepare($sql);
                        if($stmt){
                            $stmt->bindValue( 1, $lh_reg_ip, PDO::PARAM_STR);
                            $stmt->execute();
                            list($lhno) = $stmt->fetch();
                            if($stmt->rowCount()==1) parent::setResult(1);
                            else parent::setResult(0);
                        }else{
                            parent::setResult(ResError::dbPrepare);
                        }
                    }catch(PDOException $e){ parent::setResult(ResError::dbPrepare);}
                }else{
                    parent::setResult(ResError::dbConn);
                }
                return parent::getResult();
            }
            
            public function setUpdateIpBlock($lh_no,$isBlock=1){
                parent::setResult(-1);
                if(!$this->db) $this->db=parent::getDatabase(); if($this->db){

                    $sql = "UPDATE web_admin_login_his SET 
				lh_ip_block=? WHERE lh_no=?";

                    if($this->db) $stmt = $this->db->prepare($sql);
                    if($stmt){
                        $j=1;
			$stmt->bindValue( $j++, $isBlock, PDO::PARAM_STR);
			$stmt->bindValue( $j++, (int)$lh_no, PDO::PARAM_INT);
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