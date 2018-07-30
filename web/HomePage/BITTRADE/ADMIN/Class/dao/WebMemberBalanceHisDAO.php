<?php      
/**
* Description of WebMemberBalanceHisDAO
* @description FunHanSoft Co.,Ltd  PHP auto templet
* @date 2017-02-24
* @copyright (c)funhansoft.com
* @license 해당 사이트 이외의 사용은 엄격히 금지되어 있습니다.
* @version 4.0.0
*/
        class WebMemberBalanceHisDAO extends BaseDAO{

            private $db;
            private $dbSlave;

            function __construct() {
                parent::__construct();
            }

            public function getViewById($mh_no){
                $dto = new WebMemberBalanceHisDTO();
                if(!$this->dbSlave) $this->dbSlave=parent::getDatabaseSlave();
                if($this->dbSlave){
                    try{
                        $sql = "SELECT 	mh_no,
				mb_no,
				mb_point,
				mb_btc,
				mb_krw,
				mb_usd,
				mb_cny,
				mb_used_btc,
				mb_used_krw,
				mb_used_usd,
				mb_used_cny,
				mb_reg_dt 
                                FROM web_member_balance_his WHERE mh_no=?  limit 1";

                        if($this->dbSlave) $stmt = $this->dbSlave->prepare($sql);
                        if($stmt){
                            $stmt->bindValue( 1, $mh_no, PDO::PARAM_STR);
                            $stmt->execute();
                            list(
				$dto->mhNo,
				$dto->mbNo,
				$dto->mbPoint,
				$dto->mbBtc,
				$dto->mbKrw,
				$dto->mbUsd,
				$dto->mbCny,
				$dto->mbUsedBtc,
				$dto->mbUsedKrw,
				$dto->mbUsedUsd,
				$dto->mbUsedCny,
				$dto->mbRegDt) = $stmt->fetch();
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
                        $singleFields = array('mh_no','mb_no','mb_point');
                        $sql_add = $this->getListSearchSQL($singleFields,$field,$value);
                        if($svdf && $svdt){
                            if($sql_add){
                                $sql_add .= " AND ";
                            }else{
                                $sql_add .= " WHERE ";
                            }
                            $sql_add .= "(mb_reg_dt BETWEEN ? AND DATE_ADD(?,INTERVAL 1 DAY) ) ";
                        }
                        $sql = "SELECT count(*)  FROM web_member_balance_his{$sql_add}";

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
                $dto = new WebMemberBalanceHisDTO();
                $dtoarray = array();
                if(!$this->dbSlave) $this->dbSlave=parent::getDatabaseSlave();
                if($this->dbSlave){
                    try{
                        $singleFields = array('mh_no','mb_no','mb_point');
                        $sql_add = $this->getListSearchSQL($singleFields,$field,$value);
                        if($svdf && $svdt){
                            if($sql_add){
                                $sql_add .= " AND ";
                            }else{
                                $sql_add .= " WHERE ";
                            }
                            $sql_add .= "(mb_reg_dt BETWEEN ? AND DATE_ADD(?,INTERVAL 1 DAY) ) ";
                        }
                        $sql_add .= $this->getListOrderSQL($singleFields,'mh_no');
                        
                        $sql = "SELECT 	mh_no,
				mb_no,
				mb_point,
				mb_btc,
				mb_krw,
				mb_usd,
				mb_cny,
				mb_used_btc,
				mb_used_krw,
				mb_used_usd,
				mb_used_cny,
				mb_reg_dt 
                                FROM web_member_balance_his{$sql_add} LIMIT ?,?";

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
				$dto->mhNo,
				$dto->mbNo,
				$dto->mbPoint,
				$dto->mbBtc,
				$dto->mbKrw,
				$dto->mbUsd,
				$dto->mbCny,
				$dto->mbUsedBtc,
				$dto->mbUsedKrw,
				$dto->mbUsedUsd,
				$dto->mbUsedCny,
				$dto->mbRegDt) = $stmt->fetch()) {
                                    $dtoarray[$i] = new WebMemberBalanceHisDTO();
                                    parent::setResult($i+1);
                                    $dtoarray[$i]->mhNo = $dto->mhNo;
					$dtoarray[$i]->mbNo = $dto->mbNo;
					$dtoarray[$i]->mbPoint = $dto->mbPoint;
					$dtoarray[$i]->mbBtc = $dto->mbBtc;
					$dtoarray[$i]->mbKrw = $dto->mbKrw;
					$dtoarray[$i]->mbUsd = $dto->mbUsd;
					$dtoarray[$i]->mbCny = $dto->mbCny;
					$dtoarray[$i]->mbUsedBtc = $dto->mbUsedBtc;
					$dtoarray[$i]->mbUsedKrw = $dto->mbUsedKrw;
					$dtoarray[$i]->mbUsedUsd = $dto->mbUsedUsd;
					$dtoarray[$i]->mbUsedCny = $dto->mbUsedCny;
					$dtoarray[$i]->mbRegDt = $dto->mbRegDt;  
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
                        $sql = "INSERT INTO web_member_balance_his(
				mb_no,
				mb_point,
				mb_btc,
				mb_krw,
				mb_usd,
				mb_cny,
				mb_used_btc,
				mb_used_krw,
				mb_used_usd,
				mb_used_cny)
                                VALUES(?,?,?,?,?,?,?,?,?,?)";

                        if($this->db) $stmt = $this->db->prepare($sql);
                        if($stmt){
                            $j=1;
                            
		$stmt->bindValue( $j++, $dto->mbNo, ($dto->mbNo)?PDO::PARAM_INT:PDO::PARAM_NULL);
		$stmt->bindValue( $j++, $dto->mbPoint, PDO::PARAM_INT);
		$stmt->bindValue( $j++, $dto->mbBtc, PDO::PARAM_STR);
		$stmt->bindValue( $j++, $dto->mbKrw, PDO::PARAM_STR);
		$stmt->bindValue( $j++, $dto->mbUsd, PDO::PARAM_STR);
		$stmt->bindValue( $j++, $dto->mbCny, PDO::PARAM_STR);
		$stmt->bindValue( $j++, $dto->mbUsedBtc, PDO::PARAM_STR);
		$stmt->bindValue( $j++, $dto->mbUsedKrw, PDO::PARAM_STR);
		$stmt->bindValue( $j++, $dto->mbUsedUsd, PDO::PARAM_STR);
		$stmt->bindValue( $j++, $dto->mbUsedCny, PDO::PARAM_STR);
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

                    $sql = "UPDATE web_member_balance_his SET 
				mb_no=?,
				mb_point=?,
				mb_btc=?,
				mb_krw=?,
				mb_usd=?,
				mb_cny=?,
				mb_used_btc=?,
				mb_used_krw=?,
				mb_used_usd=?,
				mb_used_cny=? WHERE mh_no=?";

                    if($this->db) $stmt = $this->db->prepare($sql);
                    if($stmt){
                        $j=1;
                        
		$stmt->bindValue( $j++, $dto->mbNo, ($dto->mbNo)?PDO::PARAM_INT:PDO::PARAM_NULL);
		$stmt->bindValue( $j++, $dto->mbPoint, PDO::PARAM_INT);
		$stmt->bindValue( $j++, $dto->mbBtc, PDO::PARAM_STR);
		$stmt->bindValue( $j++, $dto->mbKrw, PDO::PARAM_STR);
		$stmt->bindValue( $j++, $dto->mbUsd, PDO::PARAM_STR);
		$stmt->bindValue( $j++, $dto->mbCny, PDO::PARAM_STR);
		$stmt->bindValue( $j++, $dto->mbUsedBtc, PDO::PARAM_STR);
		$stmt->bindValue( $j++, $dto->mbUsedKrw, PDO::PARAM_STR);
		$stmt->bindValue( $j++, $dto->mbUsedUsd, PDO::PARAM_STR);
		$stmt->bindValue( $j++, $dto->mbUsedCny, PDO::PARAM_STR);
			
		$stmt->bindValue( $j++, $dto->mhNo, ($dto->mhNo)?PDO::PARAM_INT:PDO::PARAM_NULL);
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

                    $sql = "DELETE FROM web_member_balance_his WHERE mh_no=?";

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