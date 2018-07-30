<?php      
/**
* Description of WebMemberDelDAO
* @description Funhansoft PHP auto templet
* @date 2013-09-03
* @copyright (c)funhansoft.com
* @license http://funhansoft.com/license
* @version 0.2.1
*/
        class WebMemberDelDAO extends BaseDAO{

            private $db;
            private $dbSlave;

            function __construct() {
                parent::__construct();
                
            }

            public function getViewById($mb_no){
                $dto = new WebMemberDelDTO();
                if(!$this->dbSlave) $this->dbSlave=parent::getDatabaseSlave(); if($this->dbSlave){
                    try{
                        $sql = "SELECT 	mb_no,
				mb_id,
				mb_nick,
				mb_pwd,
				mb_key,
				mb_level,
				mb_point,
				mb_gender,
				mb_agent,
				mb_reg_ip,
				mb_reg_dt,
				mb_up_dt,
				mb_del_yn 
                                FROM web_member_del WHERE mb_no=?  limit 1";

                        if($this->dbSlave) $stmt = $this->dbSlave->prepare($sql);
                        if($stmt){
                            $stmt->bindValue( 1, $mb_no, PDO::PARAM_STR);
                            $stmt->execute();
                            list(
				$dto->mbNo,
				$dto->mbId,
				$dto->mbNick,
				$dto->mbPwd,
				$dto->mbKey,
				$dto->mbLevel,
				$dto->mbPoint,
				$dto->mbGender,
				$dto->mbAgent,
				$dto->mbRegIp,
				$dto->mbRegDt,
				$dto->mbUpDt,
				$dto->mbDelYn) = $stmt->fetch();
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
                            if($field=='mb_no'){ $sql_add = " WHERE {$field}=?"; }
                        }
                        $sql = "SELECT count(*)  FROM web_member_del{$sql_add}";

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
                $dto = new WebMemberDelDTO();
                $dtoarray = array();          
                if(!$this->dbSlave) $this->dbSlave=parent::getDatabaseSlave(); if($this->dbSlave){
                    try{
                        $sql_add = '';
                        if($value){
                            $sql_add = " WHERE {$field} LIKE CONCAT('%',?,'%')";
                            if($field=='mb_no'){ $sql_add = " WHERE {$field}=?"; }
                        }
                        $sql = "SELECT 	mb_no,
				mb_id,
				mb_nick,
				mb_pwd,
				mb_key,
				mb_level,
				mb_point,
				mb_gender,
				mb_agent,
				mb_reg_ip,
				mb_reg_dt,
				mb_up_dt,
				mb_del_yn 
                                FROM web_member_del{$sql_add} ORDER BY mb_no DESC LIMIT ?,?";

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
				$dto->mbNo,
				$dto->mbId,
				$dto->mbNick,
				$dto->mbPwd,
				$dto->mbKey,
				$dto->mbLevel,
				$dto->mbPoint,
				$dto->mbGender,
				$dto->mbAgent,
				$dto->mbRegIp,
				$dto->mbRegDt,
				$dto->mbUpDt,
				$dto->mbDelYn) = $stmt->fetch()) {
                                    $dtoarray[$i] = new WebMemberDelDTO();
                                    parent::setResult($i+1);
                                    $dtoarray[$i]->mbNo = $dto->mbNo;
					$dtoarray[$i]->mbId = $dto->mbId;
					$dtoarray[$i]->mbNick = $dto->mbNick;
					$dtoarray[$i]->mbPwd = $dto->mbPwd;
					$dtoarray[$i]->mbKey = $dto->mbKey;
					$dtoarray[$i]->mbLevel = $dto->mbLevel;
					$dtoarray[$i]->mbPoint = $dto->mbPoint;
					$dtoarray[$i]->mbGender = $dto->mbGender;
					$dtoarray[$i]->mbAgent = $dto->mbAgent;
					$dtoarray[$i]->mbRegIp = $dto->mbRegIp;
					$dtoarray[$i]->mbRegDt = $dto->mbRegDt;
					$dtoarray[$i]->mbUpDt = $dto->mbUpDt;
					$dtoarray[$i]->mbDelYn = $dto->mbDelYn;  
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
                $dto = new WebMemberDelDTO();
                $dtoarray = array();
                if(!$this->dbSlave) $this->dbSlave=parent::getDatabaseSlave(); if($this->dbSlave){
                    try{
                         $sql = "SELECT 	mb_no,
				mb_id,
				mb_nick,
				mb_pwd,
				mb_key,
				mb_level,
				mb_point,
				mb_gender,
				mb_agent,
				mb_reg_ip,
				mb_reg_dt,
				mb_up_dt,
				mb_del_yn 
                            FROM web_member_del WHERE mb_no LIKE CONCAT('%',?,'%') ORDER BY mb_no DESC LIMIT ?,?";

                        if($this->dbSlave) $stmt = $this->dbSlave->prepare($sql);
                        if($stmt){
                            $j=1;
                            $stmt->bindValue( $j++, $pri, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, parent::getListLimitStart(), PDO::PARAM_INT);
                            $stmt->bindValue( $j++, parent::getListLimitRow(), PDO::PARAM_INT);
                            $stmt->execute();

                            $i = 0;
                            while(list(
				$dto->mbNo,
				$dto->mbId,
				$dto->mbNick,
				$dto->mbPwd,
				$dto->mbKey,
				$dto->mbLevel,
				$dto->mbPoint,
				$dto->mbGender,
				$dto->mbAgent,
				$dto->mbRegIp,
				$dto->mbRegDt,
				$dto->mbUpDt,
				$dto->mbDelYn) = $stmt->fetch()) {
                                $dtoarray[$i] = new WebMemberDelDTO();
                                parent::setResult($i+1);
                                $dtoarray[$i]->mbNo = $dto->mbNo;
					$dtoarray[$i]->mbId = $dto->mbId;
					$dtoarray[$i]->mbNick = $dto->mbNick;
					$dtoarray[$i]->mbPwd = $dto->mbPwd;
					$dtoarray[$i]->mbKey = $dto->mbKey;
					$dtoarray[$i]->mbLevel = $dto->mbLevel;
					$dtoarray[$i]->mbPoint = $dto->mbPoint;
					$dtoarray[$i]->mbGender = $dto->mbGender;
					$dtoarray[$i]->mbAgent = $dto->mbAgent;
					$dtoarray[$i]->mbRegIp = $dto->mbRegIp;
					$dtoarray[$i]->mbRegDt = $dto->mbRegDt;
					$dtoarray[$i]->mbUpDt = $dto->mbUpDt;
					$dtoarray[$i]->mbDelYn = $dto->mbDelYn;  
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
                        $sql = "INSERT INTO web_member_del(
				mb_id,
				mb_nick,
				mb_pwd,
				mb_key,
				mb_level,
				mb_point,
				mb_gender,
				mb_agent,
				mb_reg_ip,
				mb_up_dt,
				mb_del_yn)
                                VALUES(?,?,?,?,?,?,?,?,?,?,?)";

                        if($this->db) $stmt = $this->db->prepare($sql);
                        if($stmt){
                            $j=1;
                            
			$stmt->bindValue( $j++, $dto->mbId, PDO::PARAM_STR);
			$stmt->bindValue( $j++, $dto->mbNick, PDO::PARAM_STR);
			$stmt->bindValue( $j++, $dto->mbPwd, PDO::PARAM_STR);
			$stmt->bindValue( $j++, $dto->mbKey, PDO::PARAM_STR);
			$stmt->bindValue( $j++, (int)$dto->mbLevel, PDO::PARAM_INT);
			$stmt->bindValue( $j++, (int)$dto->mbPoint, PDO::PARAM_INT);
			$stmt->bindValue( $j++, $dto->mbGender, PDO::PARAM_STR);
			$stmt->bindValue( $j++, $dto->mbAgent, PDO::PARAM_STR);
			$stmt->bindValue( $j++, $dto->mbRegIp, PDO::PARAM_STR);
			$stmt->bindValue( $j++, $dto->mbUpDt, PDO::PARAM_STR);
			$stmt->bindValue( $j++, $dto->mbDelYn, PDO::PARAM_STR);
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

                    $sql = "UPDATE web_member_del SET 
				mb_id=?,
				mb_nick=?,
				mb_pwd=?,
				mb_key=?,
				mb_level=?,
				mb_point=?,
				mb_gender=?,
				mb_agent=?,
				mb_reg_ip=?,
				mb_up_dt=?,
				mb_del_yn=? WHERE mb_no=?";

                    if($this->db) $stmt = $this->db->prepare($sql);
                    if($stmt){
                        $j=1;
                        
			$stmt->bindValue( $j++, $dto->mbId, PDO::PARAM_STR);
			$stmt->bindValue( $j++, $dto->mbNick, PDO::PARAM_STR);
			$stmt->bindValue( $j++, $dto->mbPwd, PDO::PARAM_STR);
			$stmt->bindValue( $j++, $dto->mbKey, PDO::PARAM_STR);
			$stmt->bindValue( $j++, (int)$dto->mbLevel, PDO::PARAM_INT);
			$stmt->bindValue( $j++, (int)$dto->mbPoint, PDO::PARAM_INT);
			$stmt->bindValue( $j++, $dto->mbGender, PDO::PARAM_STR);
			$stmt->bindValue( $j++, $dto->mbAgent, PDO::PARAM_STR);
			$stmt->bindValue( $j++, $dto->mbRegIp, PDO::PARAM_STR);
			$stmt->bindValue( $j++, $dto->mbUpDt, PDO::PARAM_STR);
			$stmt->bindValue( $j++, $dto->mbDelYn, PDO::PARAM_STR);
			
			$stmt->bindValue( $j++, (int)$dto->mbNo, PDO::PARAM_INT);
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

                    $sql = "DELETE FROM web_member_del WHERE mb_no=?";

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