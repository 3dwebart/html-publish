<?php      
/**
* Description of WebAdminAuthDAO
* @description Funhansoft PHP auto templet
* @date 2013-09-04
* @copyright (c)funhansoft.com
* @license http://funhansoft.com/license
* @version 0.2.1
*/
        class WebAdminAuthDAO extends WebAdminAuthDAOExt{

            function __construct() {
                parent::__construct();
            }

            public function getViewById($au_no){
                $dto = new WebAdminAuthDTO();
                if(!$this->dbSlave) $this->dbSlave=parent::getDatabaseSlave(); if($this->dbSlave){
                    try{
                        $sql = "SELECT 	au_no,
				mb_id,
				au_menu,
				au_auth 
                                FROM web_admin_auth WHERE au_no=?  limit 1";

                        if($this->dbSlave) $stmt = $this->dbSlave->prepare($sql);
                        if($stmt){
                            $stmt->bindValue( 1, $au_no, PDO::PARAM_STR);
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

            public function getListCount($field='',$value='',$svdf='',$svdt=''){
                $dto = new CommonListDTO();
                if(!$this->dbSlave) $this->dbSlave=parent::getDatabaseSlave(); if($this->dbSlave){
                    try{
                        $sql_add = '';
                        if($value){
                            $sql_add = " WHERE {$field} LIKE CONCAT('%',?,'%')";
                            if($field=='au_no'){ $sql_add = " WHERE {$field}=?"; }
                        }
                        $sql = "SELECT count(*)  FROM web_admin_auth{$sql_add}";

                        if($this->dbSlave) $stmt = $this->dbSlave->prepare($sql);
                        if($stmt){
                            if($value)
                                $stmt->bindValue( 1, $value, PDO::PARAM_STR);
                            
                            $stmt->execute();
                            list($dto->totalCount) =  $stmt->fetch();
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
                $dto->totalPage = ceil((int)$dto->totalCount / parent::getListLimitRow() );
                $dto->limitRow =  (int)parent::getListLimitRow();
                
                return $dto;
            }

            public function getList($field='',$value='',$svdf='',$svdt=''){
                $dto = new WebAdminAuthDTO();
                $dtoarray = array();          
                if(!$this->dbSlave) $this->dbSlave=parent::getDatabaseSlave(); if($this->dbSlave){
                    try{
                        $sql_add = '';
                        if($value){
                            $sql_add = " WHERE {$field} LIKE CONCAT('%',?,'%')";
                            if($field=='au_no'){ $sql_add = " WHERE {$field}=?"; }
                        }
                        $sql = "SELECT 	au_no,
				mb_id,
				au_menu,
				au_auth 
                                FROM web_admin_auth{$sql_add} ORDER BY au_no DESC LIMIT ?,?";

                        if($this->dbSlave) $stmt = $this->dbSlave->prepare($sql);
                        if($stmt){
                            $j=1;
                            if($value)
                                $stmt->bindValue( $j++, $value, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, parent::getListLimitStart(), PDO::PARAM_INT);
                            $stmt->bindValue( $j++, parent::getListLimitRow(), PDO::PARAM_INT);
                            $stmt->execute();

                            $i = 0;
                            while(list(
				$dto->auNo,
				$dto->mbId,
				$dto->auMenu,
				$dto->auAuth) = $stmt->fetch()) {
                                    $dtoarray[$i] = new WebAdminAuthDTO();
                                    parent::setResult($i+1);
                                    $dtoarray[$i]->auNo = $dto->auNo;
					$dtoarray[$i]->mbId = $dto->mbId;
					$dtoarray[$i]->auMenu = $dto->auMenu;
					$dtoarray[$i]->auAuth = $dto->auAuth;  
                                    $i++;
                            }

                            if($i==0) parent::setResult(0);
                        }
                    }catch(PDOException $e){ parent::setResult(ResError::dbPrepare);}
                }else{
                    parent::setResult(ResError::dbConn);
                }
                $dto = null;
                return $dtoarray;
            }
            
            

            public function getListByPri($pri){
                $dto = new WebAdminAuthDTO();
                $dtoarray = array();
                if(!$this->dbSlave) $this->dbSlave=parent::getDatabaseSlave(); if($this->dbSlave){
                    try{
                         $sql = "SELECT 	au_no,
				mb_id,
				au_menu,
				au_auth 
                            FROM web_admin_auth WHERE au_no LIKE CONCAT('%',?,'%') ORDER BY au_no DESC LIMIT ?,?";

                        if($this->dbSlave) $stmt = $this->dbSlave->prepare($sql);
                        if($stmt){
                            $j=1;
                            $stmt->bindValue( $j++, $pri, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, parent::getListLimitStart(), PDO::PARAM_INT);
                            $stmt->bindValue( $j++, parent::getListLimitRow(), PDO::PARAM_INT);
                            $stmt->execute();

                            $i = 0;
                            while(list(
				$dto->auNo,
				$dto->mbId,
				$dto->auMenu,
				$dto->auAuth) = $stmt->fetch()) {
                                $dtoarray[$i] = new WebAdminAuthDTO();
                                parent::setResult($i+1);
                                $dtoarray[$i]->auNo = $dto->auNo;
					$dtoarray[$i]->mbId = $dto->mbId;
					$dtoarray[$i]->auMenu = $dto->auMenu;
					$dtoarray[$i]->auAuth = $dto->auAuth;  
                                $i++;
                            }

                            if($i==0) parent::setResult(0);
                        }
                    }catch(PDOException $e){ parent::setResult(ResError::dbPrepare);}
                   
                }else{
                    parent::setResult(ResError::dbConn);
                }
                $dto = null;
                return $dtoarray;
            }

            public function setInsert($dto){
                parent::setResult(-1);
                if(!$this->db) $this->db=parent::getDatabase(); if($this->db){
                    try{
                        $sql = "INSERT INTO web_admin_auth(
				mb_id,
				au_menu,
				au_auth)
                                VALUES(?,?,?)";

                        if($this->db) $stmt = $this->db->prepare($sql);
                        if($stmt){
                            $j=1;
                            
			$stmt->bindValue( $j++, $dto->mbId, PDO::PARAM_STR);
			$stmt->bindValue( $j++, $dto->auMenu, PDO::PARAM_STR);
			$stmt->bindValue( $j++, $dto->auAuth, PDO::PARAM_STR);
                            $stmt->execute();
                            if($stmt->rowCount()==1) parent::setResult($this->db->lastInsertId());
                            else parent::setResult(0);
                        }
                    
                    }catch(PDOException $e){ parent::setResult(ResError::dbPrepare);}
                }else{
                    parent::setResult(ResError::dbConn);
                }
                return parent::getResult();
            }

            public function setUpdate($dto){
                parent::setResult(-1);
                if(!$this->db) $this->db=parent::getDatabase(); if($this->db){

                    $sql = "UPDATE web_admin_auth SET 
				mb_id=?,
				au_menu=?,
				au_auth=? WHERE au_no=?";

                    if($this->db) $stmt = $this->db->prepare($sql);
                    if($stmt){
                        $j=1;
                        
			$stmt->bindValue( $j++, $dto->mbId, PDO::PARAM_STR);
			$stmt->bindValue( $j++, $dto->auMenu, PDO::PARAM_STR);
			$stmt->bindValue( $j++, $dto->auAuth, PDO::PARAM_STR);
			
			$stmt->bindValue( $j++, (int)$dto->auNo, PDO::PARAM_INT);
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

            function deleteFromPri($pri){
                parent::setResult(-1);
                if(!$this->db) $this->db=parent::getDatabase(); if($this->db){

                    $sql = "DELETE FROM web_admin_auth WHERE au_no=?";

                    if($this->db) $stmt = $this->db->prepare($sql);
                    if($stmt){
                        $stmt->bindValue( 1, $pri, PDO::PARAM_STR);
                        $stmt->execute();
                        if($stmt->rowCount()>0) parent::setResult($stmt->rowCount());
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