<?php      
/**
* Description of WebMemberLoginKeyDAO
* @description Funhansoft PHP auto templet
* @date 2013-09-02
* @copyright (c)funhansoft.com
* @license http://funhansoft.com/license
* @version 0.2.1
*/
        class WebMemberLoginKeyDAO extends BaseDAO{

            private $db;
            private $dbSlave;

            function __construct() {
                parent::__construct();
                
            }

            public function getViewById($lk_no){
                $dto = new WebMemberLoginKeyDTO();
                if(!$this->dbSlave) $this->dbSlave=parent::getDatabaseSlave(); if($this->dbSlave){
                    try{
                        $sql = "SELECT 	lk_no,
				lk_type,
				mb_no,
				mb_id,
				mb_key,
				lk_result_code,
				lk_result_msg,
				lk_reg_ip 
                                FROM web_member_login_key WHERE lk_no=?  limit 1";

                        if($this->dbSlave) $stmt = $this->dbSlave->prepare($sql);
                        if($stmt){
                            $stmt->bindValue( 1, $lk_no, PDO::PARAM_STR);
                            $stmt->execute();
                            list(
				$dto->lkNo,
				$dto->lkType,
				$dto->mbNo,
				$dto->mbId,
				$dto->mbKey,
				$dto->lkResultCode,
				$dto->lkResultMsg,
				$dto->lkRegIp) = $stmt->fetch();
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
                            if($field=='lk_no'){ $sql_add = " WHERE {$field}=?"; }
                        }
                        $sql = "SELECT count(*)  FROM web_member_login_key{$sql_add}";

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
                $dto = new WebMemberLoginKeyDTO();
                $dtoarray = array();          
                if(!$this->dbSlave) $this->dbSlave=parent::getDatabaseSlave(); if($this->dbSlave){
                    try{
                        $sql_add = '';
                        if($value){
                            $sql_add = " WHERE {$field} LIKE CONCAT('%',?,'%')";
                            if($field=='lk_no'){ $sql_add = " WHERE {$field}=?"; }
                        }
                        $sql = "SELECT 	lk_no,
				lk_type,
				mb_no,
				mb_id,
				mb_key,
				lk_result_code,
				lk_result_msg,
				lk_reg_ip 
                                FROM web_member_login_key{$sql_add} ORDER BY lk_no DESC LIMIT ?,?";

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
				$dto->lkNo,
				$dto->lkType,
				$dto->mbNo,
				$dto->mbId,
				$dto->mbKey,
				$dto->lkResultCode,
				$dto->lkResultMsg,
				$dto->lkRegIp) = $stmt->fetch()) {
                                    $dtoarray[$i] = new WebMemberLoginKeyDTO();
                                    parent::setResult($i+1);
                                    $dtoarray[$i]->lkNo = $dto->lkNo;
					$dtoarray[$i]->lkType = $dto->lkType;
					$dtoarray[$i]->mbNo = $dto->mbNo;
					$dtoarray[$i]->mbId = $dto->mbId;
					$dtoarray[$i]->mbKey = $dto->mbKey;
					$dtoarray[$i]->lkResultCode = $dto->lkResultCode;
					$dtoarray[$i]->lkResultMsg = $dto->lkResultMsg;
					$dtoarray[$i]->lkRegIp = $dto->lkRegIp;  
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
                $dto = new WebMemberLoginKeyDTO();
                $dtoarray = array();
                if(!$this->dbSlave) $this->dbSlave=parent::getDatabaseSlave(); if($this->dbSlave){
                    try{
                         $sql = "SELECT 	lk_no,
				lk_type,
				mb_no,
				mb_id,
				mb_key,
				lk_result_code,
				lk_result_msg,
				lk_reg_ip 
                            FROM web_member_login_key WHERE lk_no LIKE CONCAT('%',?,'%') ORDER BY lk_no DESC LIMIT ?,?";

                        if($this->dbSlave) $stmt = $this->dbSlave->prepare($sql);
                        if($stmt){
                            $j=1;
                            $stmt->bindValue( $j++, $pri, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, parent::getListLimitStart(), PDO::PARAM_INT);
                            $stmt->bindValue( $j++, parent::getListLimitRow(), PDO::PARAM_INT);
                            $stmt->execute();

                            $i = 0;
                            while(list(
				$dto->lkNo,
				$dto->lkType,
				$dto->mbNo,
				$dto->mbId,
				$dto->mbKey,
				$dto->lkResultCode,
				$dto->lkResultMsg,
				$dto->lkRegIp) = $stmt->fetch()) {
                                $dtoarray[$i] = new WebMemberLoginKeyDTO();
                                parent::setResult($i+1);
                                $dtoarray[$i]->lkNo = $dto->lkNo;
					$dtoarray[$i]->lkType = $dto->lkType;
					$dtoarray[$i]->mbNo = $dto->mbNo;
					$dtoarray[$i]->mbId = $dto->mbId;
					$dtoarray[$i]->mbKey = $dto->mbKey;
					$dtoarray[$i]->lkResultCode = $dto->lkResultCode;
					$dtoarray[$i]->lkResultMsg = $dto->lkResultMsg;
					$dtoarray[$i]->lkRegIp = $dto->lkRegIp;  
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
                        $sql = "INSERT INTO web_member_login_key(
				lk_type,
				mb_no,
				mb_id,
				mb_key,
				lk_result_code,
				lk_result_msg,
				lk_reg_ip)
                                VALUES(?,?,?,?,?,?,?)";

                        if($this->db) $stmt = $this->db->prepare($sql);
                        if($stmt){
                            $j=1;
                            
			$stmt->bindValue( $j++, $dto->lkType, PDO::PARAM_STR);
			$stmt->bindValue( $j++, (int)$dto->mbNo, PDO::PARAM_INT);
			$stmt->bindValue( $j++, $dto->mbId, PDO::PARAM_STR);
			$stmt->bindValue( $j++, $dto->mbKey, PDO::PARAM_STR);
			$stmt->bindValue( $j++, (int)$dto->lkResultCode, PDO::PARAM_INT);
			$stmt->bindValue( $j++, $dto->lkResultMsg, PDO::PARAM_STR);
			$stmt->bindValue( $j++, $dto->lkRegIp, PDO::PARAM_STR);
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

                    $sql = "UPDATE web_member_login_key SET 
				lk_type=?,
				mb_no=?,
				mb_id=?,
				mb_key=?,
				lk_result_code=?,
				lk_result_msg=?,
				lk_reg_ip=? WHERE lk_no=?";

                    if($this->db) $stmt = $this->db->prepare($sql);
                    if($stmt){
                        $j=1;
                        
			$stmt->bindValue( $j++, $dto->lkType, PDO::PARAM_STR);
			$stmt->bindValue( $j++, (int)$dto->mbNo, PDO::PARAM_INT);
			$stmt->bindValue( $j++, $dto->mbId, PDO::PARAM_STR);
			$stmt->bindValue( $j++, $dto->mbKey, PDO::PARAM_STR);
			$stmt->bindValue( $j++, (int)$dto->lkResultCode, PDO::PARAM_INT);
			$stmt->bindValue( $j++, $dto->lkResultMsg, PDO::PARAM_STR);
			$stmt->bindValue( $j++, $dto->lkRegIp, PDO::PARAM_STR);
			
			$stmt->bindValue( $j++, (int)$dto->lkNo, PDO::PARAM_INT);
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

                    $sql = "DELETE FROM web_member_login_key WHERE lk_no=?";

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