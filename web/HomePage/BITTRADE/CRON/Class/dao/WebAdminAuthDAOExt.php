<?php      
/**
* Description of WebAdminAuthDAO
* @description Funhansoft PHP auto templet
* @date 2013-09-04
* @copyright (c)funhansoft.com
* @license http://funhansoft.com/license
* @version 0.2.1
*/
        class WebAdminAuthDAOExt extends BaseDAO{

            protected $db;
            protected $dbSlave;

            function __construct() {
                parent::__construct();
                
            }

            public function getViewMenuByMbId($mb_id,$menu){
                $dto = new WebAdminAuthDTO();
                if(!$this->dbSlave) $this->dbSlave=parent::getDatabaseSlave(); if($this->dbSlave){
                    try{
                        $sql = "SELECT 	au_no,
				mb_id,
				au_menu,
				au_auth 
                                FROM web_admin_auth WHERE mb_id=? and au_menu=? limit 1";

                        if($this->dbSlave) $stmt = $this->dbSlave->prepare($sql);
                        if($stmt){
                            $stmt->bindValue( 1, $mb_id, PDO::PARAM_STR);
                            $stmt->bindValue( 2, $menu, PDO::PARAM_STR);
                            $stmt->execute();
                            list(
				$dto->auNo,
				$dto->mbId,
				$dto->auMenu,
				$dto->auAuth) = $stmt->fetch();
                            if($stmt->rowCount()==1) parent::setResult(1);
                            else parent::setResult(0);
                        }else{
                            parent::setResult(ResError::dbPrepare);
                        }
                    }catch(PDOException $e){ parent::setResult(ResError::dbPrepare);}
                }else{
                    parent::setResult(ResError::dbConn);
                }
                $dto->result = parent::getResult();
                return $dto;
            }


            function __destruct(){
                $this->db = NULL;
                $this->dto = NULL;
            }
        }