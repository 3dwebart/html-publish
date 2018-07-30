<?php      
/**
* Description of WebAdminLoginHisDAO
* @description Funhansoft PHP auto templet
* @date 2013-09-03
* @copyright (c)funhansoft.com
* @license http://funhansoft.com/license
* @version 0.2.1
*/
        class WebAdminLoginHisDAO extends WebAdminLoginHisDAOExt{

            function __construct() {
                parent::__construct();
            }

            public function getViewById($lh_no){
                $dto = new WebAdminLoginHisDTO();
                if(!$this->dbSlave) $this->dbSlave=parent::getDatabaseSlave(); if($this->dbSlave){
                    try{
                        $sql = "SELECT 	lh_no,
				lh_type,
				mb_no,
				mb_id,
				mb_key,
				lh_result_code,
				lh_result_msg,
				lh_reg_dt,
				lh_reg_ip,
				lh_ip_block 
                                FROM web_admin_login_his WHERE lh_no=?  limit 1";

                        if($this->dbSlave) $stmt = $this->dbSlave->prepare($sql);
                        if($stmt){
                            $stmt->bindValue( 1, $lh_no, PDO::PARAM_STR);
                            $stmt->execute();
                            list(
				$dto->lhNo,
				$dto->lhType,
				$dto->mbNo,
				$dto->mbId,
				$dto->mbKey,
				$dto->lhResultCode,
				$dto->lhResultMsg,
				$dto->lhRegDt,
				$dto->lhRegIp,
				$dto->lhIpBlock) = $stmt->fetch();
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
                            if($field=='lh_no'){ $sql_add = " WHERE {$field}=?"; }
                        }
                        if($svdf && $svdt){
                            if($sql_add){
                                $sql_add .= " AND ";
                            }else{
                                $sql_add .= " WHERE ";
                            }
                            $sql_add .= "(lh_reg_dt BETWEEN ? AND DATE_ADD(?,INTERVAL 1 DAY) ) ";
                        }
                        $sql = "SELECT count(*)  FROM web_admin_login_his{$sql_add}";

                        if($this->dbSlave) $stmt = $this->dbSlave->prepare($sql);
                        if($stmt){
                            $j=1;
                            if($value)
                                $stmt->bindValue( $j++, $value, PDO::PARAM_STR);
                            if($svdf && $svdt){
                                $stmt->bindValue( $j++, $svdf, PDO::PARAM_STR);
                                $stmt->bindValue( $j++, $svdt, PDO::PARAM_STR);
                            }
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
                $dto = new WebAdminLoginHisDTO();
                $dtoarray = array();          
                if(!$this->dbSlave) $this->dbSlave=parent::getDatabaseSlave(); if($this->dbSlave){
                    try{
                        $sql_add = '';
                        if($value){
                            $sql_add = " WHERE {$field} LIKE CONCAT('%',?,'%')";
                            if($field=='lh_no'){ $sql_add = " WHERE {$field}=?"; }
                        }
                        if($svdf && $svdt){
                            if($sql_add){
                                $sql_add .= " AND ";
                            }else{
                                $sql_add .= " WHERE ";
                            }
                            $sql_add .= "(lh_reg_dt BETWEEN ? AND DATE_ADD(?,INTERVAL 1 DAY) ) ";
                        }
                        $sql = "SELECT 	lh_no,
				lh_type,
				mb_no,
				mb_id,
				substr(mb_key,1,60),
				lh_result_code,
				lh_result_msg,
				lh_reg_dt,
				lh_reg_ip,
				lh_ip_block 
                                FROM web_admin_login_his{$sql_add} ORDER BY lh_no DESC LIMIT ?,?";

                        if($this->dbSlave) $stmt = $this->dbSlave->prepare($sql);
                        if($stmt){
                            $j=1;
                            if($value)
                                $stmt->bindValue( $j++, $value, PDO::PARAM_STR);
                            if($svdf && $svdt){
                                $stmt->bindValue( $j++, $svdf, PDO::PARAM_STR);
                                $stmt->bindValue( $j++, $svdt, PDO::PARAM_STR);
                            }
                            $stmt->bindValue( $j++, parent::getListLimitStart(), PDO::PARAM_INT);
                            $stmt->bindValue( $j++, parent::getListLimitRow(), PDO::PARAM_INT);
                            $stmt->execute();

                            $i = 0;
                            while(list(
				$dto->lhNo,
				$dto->lhType,
				$dto->mbNo,
				$dto->mbId,
				$dto->mbKey,
				$dto->lhResultCode,
				$dto->lhResultMsg,
				$dto->lhRegDt,
				$dto->lhRegIp,
				$dto->lhIpBlock) = $stmt->fetch()) {
                                    $dtoarray[$i] = new WebAdminLoginHisDTO();
                                    parent::setResult($i+1);
                                    $dtoarray[$i]->lhNo = $dto->lhNo;
					$dtoarray[$i]->lhType = $dto->lhType;
					$dtoarray[$i]->mbNo = $dto->mbNo;
					$dtoarray[$i]->mbId = $dto->mbId;
					$dtoarray[$i]->mbKey = $dto->mbKey;
					$dtoarray[$i]->lhResultCode = $dto->lhResultCode;
					$dtoarray[$i]->lhResultMsg = $dto->lhResultMsg;
					$dtoarray[$i]->lhRegDt = $dto->lhRegDt;
					$dtoarray[$i]->lhRegIp = $dto->lhRegIp;
					$dtoarray[$i]->lhIpBlock = $dto->lhIpBlock;  
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
                $dto = new WebAdminLoginHisDTO();
                $dtoarray = array();
                if(!$this->dbSlave) $this->dbSlave=parent::getDatabaseSlave(); if($this->dbSlave){
                    try{
                         $sql = "SELECT 	lh_no,
				lh_type,
				mb_no,
				mb_id,
				mb_key,
				lh_result_code,
				lh_result_msg,
				lh_reg_dt,
				lh_reg_ip,
				lh_ip_block 
                            FROM web_admin_login_his WHERE lh_no LIKE CONCAT('%',?,'%') ORDER BY lh_no DESC LIMIT ?,?";

                        if($this->dbSlave) $stmt = $this->dbSlave->prepare($sql);
                        if($stmt){
                            $j=1;
                            $stmt->bindValue( $j++, $pri, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, parent::getListLimitStart(), PDO::PARAM_INT);
                            $stmt->bindValue( $j++, parent::getListLimitRow(), PDO::PARAM_INT);
                            $stmt->execute();

                            $i = 0;
                            while(list(
				$dto->lhNo,
				$dto->lhType,
				$dto->mbNo,
				$dto->mbId,
				$dto->mbKey,
				$dto->lhResultCode,
				$dto->lhResultMsg,
				$dto->lhRegDt,
				$dto->lhRegIp,
				$dto->lhIpBlock) = $stmt->fetch()) {
                                $dtoarray[$i] = new WebAdminLoginHisDTO();
                                parent::setResult($i+1);
                                $dtoarray[$i]->lhNo = $dto->lhNo;
					$dtoarray[$i]->lhType = $dto->lhType;
					$dtoarray[$i]->mbNo = $dto->mbNo;
					$dtoarray[$i]->mbId = $dto->mbId;
					$dtoarray[$i]->mbKey = $dto->mbKey;
					$dtoarray[$i]->lhResultCode = $dto->lhResultCode;
					$dtoarray[$i]->lhResultMsg = $dto->lhResultMsg;
					$dtoarray[$i]->lhRegDt = $dto->lhRegDt;
					$dtoarray[$i]->lhRegIp = $dto->lhRegIp;
					$dtoarray[$i]->lhIpBlock = $dto->lhIpBlock;  
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
                        $sql = "INSERT INTO web_admin_login_his(
				lh_type,
				mb_no,
				mb_id,
				mb_key,
				lh_result_code,
				lh_result_msg,
				lh_reg_ip,
				lh_ip_block)
                                VALUES(?,?,?,?,?,?,?,?)";

                        if($this->db) $stmt = $this->db->prepare($sql);
                        if($stmt){
                            $j=1;
                            
			$stmt->bindValue( $j++, $dto->lhType, PDO::PARAM_STR);
			$stmt->bindValue( $j++, (int)$dto->mbNo, PDO::PARAM_INT);
			$stmt->bindValue( $j++, $dto->mbId, PDO::PARAM_STR);
			$stmt->bindValue( $j++, $dto->mbKey, PDO::PARAM_STR);
			$stmt->bindValue( $j++, (int)$dto->lhResultCode, PDO::PARAM_INT);
			$stmt->bindValue( $j++, $dto->lhResultMsg, PDO::PARAM_STR);
			$stmt->bindValue( $j++, $dto->lhRegIp, PDO::PARAM_STR);
			$stmt->bindValue( $j++, $dto->lhIpBlock, PDO::PARAM_STR);
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

                    $sql = "UPDATE web_admin_login_his SET 
				lh_type=?,
				mb_no=?,
				mb_id=?,
				mb_key=?,
				lh_result_code=?,
				lh_result_msg=?,
				lh_reg_ip=?,
				lh_ip_block=? WHERE lh_no=?";

                    if($this->db) $stmt = $this->db->prepare($sql);
                    if($stmt){
                        $j=1;
                        
			$stmt->bindValue( $j++, $dto->lhType, PDO::PARAM_STR);
			$stmt->bindValue( $j++, (int)$dto->mbNo, PDO::PARAM_INT);
			$stmt->bindValue( $j++, $dto->mbId, PDO::PARAM_STR);
			$stmt->bindValue( $j++, $dto->mbKey, PDO::PARAM_STR);
			$stmt->bindValue( $j++, (int)$dto->lhResultCode, PDO::PARAM_INT);
			$stmt->bindValue( $j++, $dto->lhResultMsg, PDO::PARAM_STR);
			$stmt->bindValue( $j++, $dto->lhRegIp, PDO::PARAM_STR);
			$stmt->bindValue( $j++, $dto->lhIpBlock, PDO::PARAM_STR);
			
			$stmt->bindValue( $j++, (int)$dto->lhNo, PDO::PARAM_INT);
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

                    $sql = "DELETE FROM web_admin_login_his WHERE lh_no=?";

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