<?php      
/**
* Description of CronScheduleDAO
* @description Funhansoft PHP auto templet
* @date 2013-12-15
* @copyright (c)funhansoft.com
* @license http://funhansoft.com/license
* @version 0.3.0
*/
        class CronScheduleDAO extends BaseDAO{

            private $db;
            private $dbSlave;

            function __construct() {
                parent::__construct();
                $this->db = parent::getDatabase();
		$this->dbSlave = parent::getDatabaseSlave();
            }

            public function getViewById($cs_no){
                $dto = new CronScheduleDTO();
                if($this->dbSlave){
                    try{
                        $sql = "SELECT 	cs_no,
				cs_subject,
				cs_content,
				cs_proc_yn,
				cs_proc_time,
				cs_proc_query,
				cs_reg_dt 
                                FROM cron_schedule WHERE cs_no=?  limit 1";

                        if($this->dbSlave) $stmt = $this->dbSlave->prepare($sql);
                        if($stmt){
                            $stmt->bindValue( 1, $cs_no, PDO::PARAM_STR);
                            $stmt->execute();
                            list(
				$dto->csNo,
				$dto->csSubject,
				$dto->csContent,
				$dto->csProcYn,
				$dto->csProcTime,
				$dto->csProcQuery,
				$dto->csRegDt) = $stmt->fetch();
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
                if($this->dbSlave){
                    try{
                        $singleFields = array('cs_no','cs_proc_yn');
                        $sql_add = $this->getListSearchSQL($singleFields,$field,$value);
                        if($svdf && $svdt){
                            if($sql_add){
                                $sql_add .= " AND ";
                            }else{
                                $sql_add .= " WHERE ";
                            }
                            $sql_add .= "(cs_reg_dt BETWEEN ? AND DATE_ADD(?,INTERVAL 1 DAY) ) ";
                        }
                        $sql = "SELECT count(*)  FROM cron_schedule{$sql_add}";

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
                $dto = new CronScheduleDTO();
                $dtoarray = array();          
                if($this->dbSlave){
                    try{
                        $singleFields = array('cs_no','cs_proc_yn');
                        $sql_add = $this->getListSearchSQL($singleFields,$field,$value);
                        if($svdf && $svdt){
                            if($sql_add){
                                $sql_add .= " AND ";
                            }else{
                                $sql_add .= " WHERE ";
                            }
                            $sql_add .= "(cs_reg_dt BETWEEN ? AND DATE_ADD(?,INTERVAL 1 DAY) ) ";
                        }
                        $sql_add .= $this->getListOrderSQL($singleFields,'cs_no');
                        
                        $sql = "SELECT 	cs_no,
				cs_subject,
				LEFT(cs_content,256),
				cs_proc_yn,
				cs_proc_time,
				LEFT(cs_proc_query,256),
				cs_reg_dt 
                                FROM cron_schedule{$sql_add} LIMIT ?,?";

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
				$dto->csNo,
				$dto->csSubject,
				$dto->csContent,
				$dto->csProcYn,
				$dto->csProcTime,
				$dto->csProcQuery,
				$dto->csRegDt) = $stmt->fetch()) {
                                    $dtoarray[$i] = new CronScheduleDTO();
                                    parent::setResult($i+1);
                                    $dtoarray[$i]->csNo = $dto->csNo;
					$dtoarray[$i]->csSubject = $dto->csSubject;
					$dtoarray[$i]->csContent = $dto->csContent;
					$dtoarray[$i]->csProcYn = $dto->csProcYn;
					$dtoarray[$i]->csProcTime = $dto->csProcTime;
					$dtoarray[$i]->csProcQuery = $dto->csProcQuery;
					$dtoarray[$i]->csRegDt = $dto->csRegDt;  
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
                if($this->db){
                    try{
                        $sql = "INSERT INTO cron_schedule(
				cs_subject,
				cs_content,
				cs_proc_yn,
				cs_proc_time,
				cs_proc_query)
                                VALUES(?,?,?,?,?)";

                        if($this->db) $stmt = $this->db->prepare($sql);
                        if($stmt){
                            $j=1;
                            
		$stmt->bindValue( $j++, $dto->csSubject, PDO::PARAM_STR);
		$stmt->bindValue( $j++, $dto->csContent, PDO::PARAM_STR);
		$stmt->bindValue( $j++, $dto->csProcYn, PDO::PARAM_STR);
		$stmt->bindValue( $j++, $dto->csProcTime, PDO::PARAM_STR);
		$stmt->bindValue( $j++, $dto->csProcQuery, PDO::PARAM_STR);
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
                if($this->db){

                    $sql = "UPDATE cron_schedule SET 
				cs_subject=?,
				cs_content=?,
				cs_proc_yn=?,
				cs_proc_time=?,
				cs_proc_query=? WHERE cs_no=?";

                    if($this->db) $stmt = $this->db->prepare($sql);
                    if($stmt){
                        $j=1;
                        
		$stmt->bindValue( $j++, $dto->csSubject, PDO::PARAM_STR);
		$stmt->bindValue( $j++, $dto->csContent, PDO::PARAM_STR);
		$stmt->bindValue( $j++, $dto->csProcYn, PDO::PARAM_STR);
		$stmt->bindValue( $j++, $dto->csProcTime, PDO::PARAM_STR);
		$stmt->bindValue( $j++, $dto->csProcQuery, PDO::PARAM_STR);
			
		$stmt->bindValue( $j++, $dto->csNo, PDO::PARAM_INT);
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
                if($this->db){

                    $sql = "DELETE FROM cron_schedule WHERE cs_no=?";

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