<?php      
/**
* Description of WebSmsSenderDAO
* @description FunHanSoft Co.,Ltd  PHP auto templet
* @date 2017-02-24
* @copyright (c)funhansoft.com
* @license 해당 사이트 이외의 사용은 엄격히 금지되어 있습니다.
* @version 4.0.0
*/
        class WebSmsSenderDAO extends BaseDAO{

            private $db;
            private $dbSlave;

            function __construct() {
                parent::__construct();
            }

            public function getViewById($ss_no){
                $dto = new WebSmsSenderDTO();
                if(!$this->dbSlave) $this->dbSlave=parent::getDatabaseSlave();
                if($this->dbSlave){
                    try{
                        $sql = "SELECT 	ss_no,
				mb_id,
				ss_is_mms,
				ss_is_inter,
				ss_tel_code,
				ss_tel_num,
				ss_content,
				ss_page_type,
				ss_provider,
				reg_dt 
                                FROM web_sms_sender WHERE ss_no=?  limit 1";

                        if($this->dbSlave) $stmt = $this->dbSlave->prepare($sql);
                        if($stmt){
                            $stmt->bindValue( 1, $ss_no, PDO::PARAM_STR);
                            $stmt->execute();
                            list(
				$dto->ssNo,
				$dto->mbId,
				$dto->ssIsMms,
				$dto->ssIsInter,
				$dto->ssTelCode,
				$dto->ssTelNum,
				$dto->ssContent,
				$dto->ssPageType,
				$dto->ssProvider,
				$dto->regDt) = $stmt->fetch();
                            if($stmt->rowCount()==1) parent::setResult(1);
                            else parent::setResult(0);
                        }else{
                            parent::setResult(ResError::dbPrepare);
                        }
                    }catch(DBException $e){ parent::setResult(ResError::dbPrepare);}
                }else{
                    parent::setResult(ResError::dbConn);
                }
                $dto->result = parent::getResult();
                return $dto;
            }

            public function getListCount($field='',$value='',$svdf='',$svdt=''){
                $dto = new CommonListDTO();
                if(!$this->dbSlave) $this->dbSlave=parent::getDatabaseSlave();
                if($this->dbSlave){
                    try{
                        $singleFields = array('ss_no','ss_is_mms','ss_is_inter');
                        $sql_add = $this->getListSearchSQL($singleFields,$field,$value);
                        
                        $sql = "SELECT count(*)  FROM web_sms_sender{$sql_add}";

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
                    }catch(DBException $e){ parent::setResult(ResError::dbPrepare);}
                }else{
                    parent::setResult(ResError::dbConn);
                }
                $dto->result = parent::getResult();
                $dto->totalPage = ceil((int)$dto->totalCount / parent::getListLimitRow() );
                $dto->limitRow =  (int)parent::getListLimitRow();
                
                return $dto;
            }

            public function getList($field='',$value='',$svdf='',$svdt=''){
                $dto = new WebSmsSenderDTO();
                $dtoarray = array();
                if(!$this->dbSlave) $this->dbSlave=parent::getDatabaseSlave();
                if($this->dbSlave){
                    try{
                        $singleFields = array('ss_no','ss_is_mms','ss_is_inter');
                        $sql_add = $this->getListSearchSQL($singleFields,$field,$value);
                        $sql_add .= $this->getListOrderSQL($singleFields,'ss_no');
                        
                        $sql = "SELECT 	ss_no,
				mb_id,
				ss_is_mms,
				ss_is_inter,
				ss_tel_code,
				ss_tel_num,
				ss_content,
				ss_page_type,
				ss_provider,
				reg_dt 
                                FROM web_sms_sender{$sql_add} LIMIT ?,?";

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
				$dto->ssNo,
				$dto->mbId,
				$dto->ssIsMms,
				$dto->ssIsInter,
				$dto->ssTelCode,
				$dto->ssTelNum,
				$dto->ssContent,
				$dto->ssPageType,
				$dto->ssProvider,
				$dto->regDt) = $stmt->fetch()) {
                                    $dtoarray[$i] = new WebSmsSenderDTO();
                                    parent::setResult($i+1);
                                    $dtoarray[$i]->ssNo = $dto->ssNo;
					$dtoarray[$i]->mbId = $dto->mbId;
					$dtoarray[$i]->ssIsMms = $dto->ssIsMms;
					$dtoarray[$i]->ssIsInter = $dto->ssIsInter;
					$dtoarray[$i]->ssTelCode = $dto->ssTelCode;
					$dtoarray[$i]->ssTelNum = $dto->ssTelNum;
					$dtoarray[$i]->ssContent = $dto->ssContent;
					$dtoarray[$i]->ssPageType = $dto->ssPageType;
					$dtoarray[$i]->ssProvider = $dto->ssProvider;
					$dtoarray[$i]->regDt = $dto->regDt;  
                                    $i++;
                            }

                            if($i==0) parent::setResult(0);
                        }
                    }catch(DBException $e){ parent::setResult(ResError::dbPrepare);}
                }else{
                    parent::setResult(ResError::dbConn);
                }
                $dto = null;
                return $dtoarray;
            }

            public function setInsert($dto){
                parent::setResult(-1);
                if(!$this->db) $this->db=parent::getDatabase();
                if($this->db){
                    try{
                        $sql = "INSERT INTO web_sms_sender(
				mb_id,
				ss_is_mms,
				ss_is_inter,
				ss_tel_code,
				ss_tel_num,
				ss_content,
				ss_page_type,
				ss_provider)
                                VALUES(?,?,?,?,?,?,?,?)";

                        if($this->db) $stmt = $this->db->prepare($sql);
                        if($stmt){
                            $j=1;
                            
		$stmt->bindValue( $j++, $dto->mbId, ($dto->mbId)?PDO::PARAM_STR:PDO::PARAM_NULL);
		$stmt->bindValue( $j++, $dto->ssIsMms, PDO::PARAM_STR);
		$stmt->bindValue( $j++, $dto->ssIsInter, ($dto->ssIsInter)?PDO::PARAM_STR:PDO::PARAM_NULL);
		$stmt->bindValue( $j++, $dto->ssTelCode, PDO::PARAM_STR);
		$stmt->bindValue( $j++, $dto->ssTelNum, ($dto->ssTelNum)?PDO::PARAM_STR:PDO::PARAM_NULL);
		$stmt->bindValue( $j++, $dto->ssContent, ($dto->ssContent)?PDO::PARAM_STR:PDO::PARAM_NULL);
		$stmt->bindValue( $j++, $dto->ssPageType, ($dto->ssPageType)?PDO::PARAM_STR:PDO::PARAM_NULL);
		$stmt->bindValue( $j++, $dto->ssProvider, PDO::PARAM_STR);
                            $stmt->execute();
                            if($stmt->rowCount()==1) parent::setResult($this->db->lastInsertId());
                            else parent::setResult(0);
                        }
                    
                    }catch(DBException $e){ parent::setResult(ResError::dbPrepare);}
                }else{
                    parent::setResult(ResError::dbConn);
                }
                return parent::getResult();
            }

            public function setUpdate($dto){
                parent::setResult(-1);
                if(!$this->db) $this->db=parent::getDatabase();
                if($this->db){

                    $sql = "UPDATE web_sms_sender SET 
				mb_id=?,
				ss_is_mms=?,
				ss_is_inter=?,
				ss_tel_code=?,
				ss_tel_num=?,
				ss_content=?,
				ss_page_type=?,
				ss_provider=? WHERE ss_no=?";

                    if($this->db) $stmt = $this->db->prepare($sql);
                    if($stmt){
                        $j=1;
                        
		$stmt->bindValue( $j++, $dto->mbId, ($dto->mbId)?PDO::PARAM_STR:PDO::PARAM_NULL);
		$stmt->bindValue( $j++, $dto->ssIsMms, PDO::PARAM_STR);
		$stmt->bindValue( $j++, $dto->ssIsInter, ($dto->ssIsInter)?PDO::PARAM_STR:PDO::PARAM_NULL);
		$stmt->bindValue( $j++, $dto->ssTelCode, PDO::PARAM_STR);
		$stmt->bindValue( $j++, $dto->ssTelNum, ($dto->ssTelNum)?PDO::PARAM_STR:PDO::PARAM_NULL);
		$stmt->bindValue( $j++, $dto->ssContent, ($dto->ssContent)?PDO::PARAM_STR:PDO::PARAM_NULL);
		$stmt->bindValue( $j++, $dto->ssPageType, ($dto->ssPageType)?PDO::PARAM_STR:PDO::PARAM_NULL);
		$stmt->bindValue( $j++, $dto->ssProvider, PDO::PARAM_STR);
			
		$stmt->bindValue( $j++, $dto->ssNo, ($dto->ssNo)?PDO::PARAM_INT:PDO::PARAM_NULL);
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
                if(!$this->db) $this->db=parent::getDatabase();
                if($this->db){

                    $sql = "DELETE FROM web_sms_sender WHERE ss_no=?";

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