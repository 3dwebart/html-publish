<?php      
/**
* Description of WebMemoDAO
* @description Funhansoft PHP auto templet
* @date 2013-09-03
* @copyright (c)funhansoft.com
* @license http://funhansoft.com/license
* @version 0.2.1
*/
        class WebMemoDAO extends BaseDAO{

            private $db;
            private $dbSlave;

            function __construct() {
                parent::__construct();
                
            }

            public function getViewById($me_id){
                $dto = new WebMemoDTO();
                if(!$this->dbSlave) $this->dbSlave=parent::getDatabaseSlave(); if($this->dbSlave){
                    try{
                        $sql = "SELECT 	me_id,
				me_recv_mb_id,
				me_send_mb_id,
				me_send_datetime,
				me_read_datetime,
				me_memo 
                                FROM web_memo WHERE me_id=?  limit 1";

                        if($this->dbSlave) $stmt = $this->dbSlave->prepare($sql);
                        if($stmt){
                            $stmt->bindValue( 1, $me_id, PDO::PARAM_STR);
                            $stmt->execute();
                            list(
				$dto->meId,
				$dto->meRecvMbId,
				$dto->meSendMbId,
				$dto->meSendDatetime,
				$dto->meReadDatetime,
				$dto->meMemo) = $stmt->fetch();
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
                            if($field=='me_id'){ $sql_add = " WHERE {$field}=?"; }
                        }
                        $sql = "SELECT count(*)  FROM web_memo{$sql_add}";

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
                $dto = new WebMemoDTO();
                $dtoarray = array();          
                if(!$this->dbSlave) $this->dbSlave=parent::getDatabaseSlave(); if($this->dbSlave){
                    try{
                        $sql_add = '';
                        if($value){
                            $sql_add = " WHERE {$field} LIKE CONCAT('%',?,'%')";
                            if($field=='me_id'){ $sql_add = " WHERE {$field}=?"; }
                        }
                        $sql = "SELECT 	me_id,
				me_recv_mb_id,
				me_send_mb_id,
				me_send_datetime,
				me_read_datetime,
				LEFT(me_memo,100)
                                FROM web_memo{$sql_add} ORDER BY me_id DESC LIMIT ?,?";

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
				$dto->meId,
				$dto->meRecvMbId,
				$dto->meSendMbId,
				$dto->meSendDatetime,
				$dto->meReadDatetime,
				$dto->meMemo) = $stmt->fetch()) {
                                    $dtoarray[$i] = new WebMemoDTO();
                                    parent::setResult($i+1);
                                    $dtoarray[$i]->meId = $dto->meId;
					$dtoarray[$i]->meRecvMbId = $dto->meRecvMbId;
					$dtoarray[$i]->meSendMbId = $dto->meSendMbId;
					$dtoarray[$i]->meSendDatetime = $dto->meSendDatetime;
					$dtoarray[$i]->meReadDatetime = $dto->meReadDatetime;
					$dtoarray[$i]->meMemo = strip_tags($dto->meMemo);  
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
                $dto = new WebMemoDTO();
                $dtoarray = array();
                if(!$this->dbSlave) $this->dbSlave=parent::getDatabaseSlave(); if($this->dbSlave){
                    try{
                         $sql = "SELECT 	me_id,
				me_recv_mb_id,
				me_send_mb_id,
				me_send_datetime,
				me_read_datetime,
				me_memo 
                            FROM web_memo WHERE me_id LIKE CONCAT('%',?,'%') ORDER BY me_id DESC LIMIT ?,?";

                        if($this->dbSlave) $stmt = $this->dbSlave->prepare($sql);
                        if($stmt){
                            $j=1;
                            $stmt->bindValue( $j++, $pri, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, parent::getListLimitStart(), PDO::PARAM_INT);
                            $stmt->bindValue( $j++, parent::getListLimitRow(), PDO::PARAM_INT);
                            $stmt->execute();

                            $i = 0;
                            while(list(
				$dto->meId,
				$dto->meRecvMbId,
				$dto->meSendMbId,
				$dto->meSendDatetime,
				$dto->meReadDatetime,
				$dto->meMemo) = $stmt->fetch()) {
                                $dtoarray[$i] = new WebMemoDTO();
                                parent::setResult($i+1);
                                $dtoarray[$i]->meId = $dto->meId;
					$dtoarray[$i]->meRecvMbId = $dto->meRecvMbId;
					$dtoarray[$i]->meSendMbId = $dto->meSendMbId;
					$dtoarray[$i]->meSendDatetime = $dto->meSendDatetime;
					$dtoarray[$i]->meReadDatetime = $dto->meReadDatetime;
					$dtoarray[$i]->meMemo = $dto->meMemo;  
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
                        $sql = "INSERT INTO web_memo(
				me_recv_mb_id,
				me_send_mb_id,
				me_memo)
                                VALUES(?,?,?)";

                        if($this->db) $stmt = $this->db->prepare($sql);
                        if($stmt){
                            $j=1;
                            
			$stmt->bindValue( $j++, $dto->meRecvMbId, PDO::PARAM_STR);
			$stmt->bindValue( $j++, $dto->meSendMbId, PDO::PARAM_STR);
			$stmt->bindValue( $j++, $dto->meMemo, PDO::PARAM_STR);
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

                    $sql = "UPDATE web_memo SET 
				me_recv_mb_id=?,
				me_send_mb_id=?,
				me_read_datetime=?,
				me_memo=? WHERE me_id=?";

                    if($this->db) $stmt = $this->db->prepare($sql);
                    if($stmt){
                        $j=1;
                        
			$stmt->bindValue( $j++, $dto->meRecvMbId, PDO::PARAM_STR);
			$stmt->bindValue( $j++, $dto->meSendMbId, PDO::PARAM_STR);
			$stmt->bindValue( $j++, $dto->meReadDatetime, PDO::PARAM_STR);
			$stmt->bindValue( $j++, $dto->meMemo, PDO::PARAM_STR);
			
			$stmt->bindValue( $j++, (int)$dto->meId, PDO::PARAM_INT);
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

                    $sql = "DELETE FROM web_memo WHERE me_id=?";

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