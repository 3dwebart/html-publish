<?php      
/**
* Description of MemOpenOrderDAO
* @description FunHanSoft Co.,Ltd  PHP auto templet
* @date 2017-07-12
* @copyright (c)funhansoft.com
* @license 해당 사이트 이외의 사용은 엄격히 금지되어 있습니다.
* @version 4.0.0
*/
        class MemOpenOrderDAO extends BaseDAO{

            private $db;
            private $dbSlave;
            private $dbName = 'fns_trade_order_etc';
            private $dbTableName = 'mem_open_order_krw_etc';
            private $poType = 'etc';

            function __construct() {
                parent::__construct();
            }
            
    
            public function setDbName($nm){
                $this->dbName = $nm;
            }
            
            public function setTableName($nm){
                $this->dbTableName = $nm;
            }
            public function setPoType($nm){
                $this->poType = $nm;
            }

            public function getViewById($od_id){
                $dto = new MemOpenOrderDTO();
                if(!$this->dbSlave) $this->dbSlave=parent::getDatabaseTradeSlave($this->dbName);
                if($this->dbSlave){
                    try{
                        $sql = "SELECT 	od_id,
				od_action,
				od_pay_status,
				od_market_price,
				od_total_cost,
				mb_no,
				mb_id,
				od_fee_rate,
				od_temp_coin,
				od_receipt_coin,
				od_receipt_fee,
				od_receipt_avg,
				od_receipt_dt,
				od_reg_dt,
				tmp_triger_ac 
                                FROM ".$this->dbTableName." WHERE od_id=?  limit 1";

                        if($this->dbSlave) $stmt = $this->dbSlave->prepare($sql);
                        if($stmt){
                            $stmt->bindValue( 1, $od_id, PDO::PARAM_STR);
                            $stmt->execute();
                            list(
				$dto->odId,
				$dto->odAction,
				$dto->odPayStatus,
				$dto->odMarketPrice,
				$dto->odTotalCost,
				$dto->mbNo,
				$dto->mbId,
				$dto->odFeeRate,
				$dto->odTempCoin,
				$dto->odReceiptCoin,
				$dto->odReceiptFee,
				$dto->odReceiptAvg,
				$dto->odReceiptDt,
				$dto->odRegDt,
				$dto->tmpTrigerAc) = $stmt->fetch();
                            if($stmt->rowCount()==1) parent::setResult(1);
                            else parent::setResult(0);
                        }else{
                            parent::setResult(Error::dbPrepare);
                        }
                    }catch(DBException $e){ parent::setResult(Error::dbPrepare);}
                }else{
                    parent::setResult(Error::dbConn);
                }
                $dto->result = parent::getResult();
                return $dto;
            }

            public function getListCount($field='',$value='',$svdf='',$svdt=''){
                $dto = new CommonListDTO();
                if(!$this->dbSlave) $this->dbSlave=parent::getDatabaseTradeSlave($this->dbName);
                if($this->dbSlave){
                    try{
                        $singleFields = array('od_id','mb_no','mb_id','od_market_price');
                        $sql_add = $this->getListSearchSQL($singleFields,$field,$value);
                        if($svdf && $svdt){
                            if($sql_add){
                                $sql_add .= " AND ";
                            }else{
                                $sql_add .= " WHERE ";
                            }
                            $sql_add .= "(od_reg_dt BETWEEN ? AND DATE_ADD(?,INTERVAL 1 DAY) ) ";
                        }
                        
                        $sql = "SELECT count(*) FROM ".$this->dbTableName."{$sql_add}";

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
                            parent::setResult(Error::dbPrepare);
                        }
                    }catch(DBException $e){ parent::setResult(Error::dbPrepare);}
                }else{
                    parent::setResult(Error::dbConn);
                }
                $dto->result = parent::getResult();
                $dto->totalPage = ceil((int)$dto->totalCount / parent::getListLimitRow() );
                $dto->limitRow =  (int)parent::getListLimitRow();
                
                return $dto;
            }

            public function getList($field='',$value='',$svdf='',$svdt=''){
                $dto = new MemOpenOrderDTO();
                $dtoarray = array();
                if(!$this->dbSlave) $this->dbSlave=parent::getDatabaseTradeSlave($this->dbName);
                if($this->dbSlave){
                    try{
                        $singleFields = array('od_id','mb_no','mb_id','od_market_price');
                        $sql_add = $this->getListSearchSQL($singleFields,$field,$value);
                        if($svdf && $svdt){
                            if($sql_add){
                                $sql_add .= " AND ";
                            }else{
                                $sql_add .= " WHERE ";
                            }
                            $sql_add .= "(od_reg_dt BETWEEN ? AND DATE_ADD(?,INTERVAL 1 DAY) ) ";
                        }
                        $sql_add .= $this->getListOrderSQL($singleFields,'od_id');
                        
                        $sql = "SELECT 	od_id,
				od_action,
				od_pay_status,
				od_market_price,
				od_total_cost,
				mb_no,
				mb_id,
				od_fee_rate,
				od_temp_coin,
				od_receipt_coin,
				od_receipt_fee,
				od_receipt_avg,
				od_receipt_dt,
				od_reg_dt,
				tmp_triger_ac 
                                FROM ".$this->dbTableName."{$sql_add} LIMIT ?,?";

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
				$dto->odId,
				$dto->odAction,
				$dto->odPayStatus,
				$dto->odMarketPrice,
				$dto->odTotalCost,
				$dto->mbNo,
				$dto->mbId,
				$dto->odFeeRate,
				$dto->odTempCoin,
				$dto->odReceiptCoin,
				$dto->odReceiptFee,
				$dto->odReceiptAvg,
				$dto->odReceiptDt,
				$dto->odRegDt,
				$dto->tmpTrigerAc) = $stmt->fetch()) {
                                    $dtoarray[$i] = new MemOpenOrderDTO();
                                    parent::setResult($i+1);
                                    $dtoarray[$i]->odId = $dto->odId;
					$dtoarray[$i]->odAction = $dto->odAction;
					$dtoarray[$i]->odPayStatus = $dto->odPayStatus;
					$dtoarray[$i]->odMarketPrice = $dto->odMarketPrice;
					$dtoarray[$i]->odTotalCost = $dto->odTotalCost;
					$dtoarray[$i]->mbNo = $dto->mbNo;
					$dtoarray[$i]->mbId = $dto->mbId;
					$dtoarray[$i]->odFeeRate = $dto->odFeeRate;
					$dtoarray[$i]->odTempCoin = $dto->odTempCoin;
					$dtoarray[$i]->odReceiptCoin = $dto->odReceiptCoin;
					$dtoarray[$i]->odReceiptFee = $dto->odReceiptFee;
					$dtoarray[$i]->odReceiptAvg = $dto->odReceiptAvg;
					$dtoarray[$i]->odReceiptDt = $dto->odReceiptDt;
					$dtoarray[$i]->odRegDt = $dto->odRegDt;
					$dtoarray[$i]->tmpTrigerAc = $dto->tmpTrigerAc;  
                                    $i++;
                            }

                            if($i==0) parent::setResult(0);
                        }
                    }catch(DBException $e){ parent::setResult(Error::dbPrepare);}
                }else{
                    parent::setResult(Error::dbConn);
                }
                $dto = null;
                return $dtoarray;
            }

            public function setInsert($dto){
                parent::setResult(-1);
                if(!$this->db) $this->db=parent::getDatabaseTrade($this->dbName);
                if($this->db){
                    try{
                        $sql = "INSERT INTO ".$this->dbTableName."(
				od_action,
				od_pay_status,
				od_market_price,
				od_total_cost,
				mb_no,
				mb_id,
				od_fee_rate,
				od_temp_coin,
				od_receipt_coin,
				od_receipt_fee,
				od_receipt_avg,
				od_receipt_dt,
				tmp_triger_ac)
                                VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?)";

                        if($this->db) $stmt = $this->db->prepare($sql);
                        if($stmt){
                            $j=1;
                            
		$stmt->bindValue( $j++, $dto->odAction, ($dto->odAction)?PDO::PARAM_STR:PDO::PARAM_NULL);
		$stmt->bindValue( $j++, $dto->odPayStatus, ($dto->odPayStatus)?PDO::PARAM_STR:PDO::PARAM_NULL);
		$stmt->bindValue( $j++, $dto->odMarketPrice, PDO::PARAM_STR);
		$stmt->bindValue( $j++, $dto->odTotalCost, PDO::PARAM_STR);
		$stmt->bindValue( $j++, $dto->mbNo, ($dto->mbNo)?PDO::PARAM_INT:PDO::PARAM_NULL);
		$stmt->bindValue( $j++, $dto->mbId, ($dto->mbId)?PDO::PARAM_STR:PDO::PARAM_NULL);
		$stmt->bindValue( $j++, $dto->odFeeRate, PDO::PARAM_STR);
		$stmt->bindValue( $j++, $dto->odTempCoin, PDO::PARAM_STR);
		$stmt->bindValue( $j++, $dto->odReceiptCoin, PDO::PARAM_STR);
		$stmt->bindValue( $j++, $dto->odReceiptFee, PDO::PARAM_STR);
		$stmt->bindValue( $j++, $dto->odReceiptAvg, PDO::PARAM_STR);
		$stmt->bindValue( $j++, $dto->odReceiptDt, ($dto->odReceiptDt)?PDO::PARAM_STR:PDO::PARAM_NULL);
		$stmt->bindValue( $j++, $dto->tmpTrigerAc, ($dto->tmpTrigerAc)?PDO::PARAM_STR:PDO::PARAM_NULL);
                            $stmt->execute();
                            if($stmt->rowCount()==1) parent::setResult($this->db->lastInsertId());
                            else parent::setResult(0);
                        }
                    
                    }catch(DBException $e){ parent::setResult(Error::dbPrepare);}
                }else{
                    parent::setResult(Error::dbConn);
                }
                return parent::getResult();
            }

            public function setUpdate($dto){
                parent::setResult(-1);
                if(!$this->db) $this->db=parent::getDatabaseTrade($this->dbName);
                if($this->db){

                    $sql = "UPDATE ".$this->dbTableName." SET 
				od_action=?,
				od_pay_status=?,
				od_market_price=?,
				od_total_cost=?,
				mb_no=?,
				mb_id=?,
				od_fee_rate=?,
				od_temp_coin=?,
				od_receipt_coin=?,
				od_receipt_fee=?,
				od_receipt_avg=?,
				od_receipt_dt=?,
				tmp_triger_ac=? WHERE od_id=?";

                    if($this->db) $stmt = $this->db->prepare($sql);
                    if($stmt){
                        $j=1;
                        
		$stmt->bindValue( $j++, $dto->odAction, ($dto->odAction)?PDO::PARAM_STR:PDO::PARAM_NULL);
		$stmt->bindValue( $j++, $dto->odPayStatus, ($dto->odPayStatus)?PDO::PARAM_STR:PDO::PARAM_NULL);
		$stmt->bindValue( $j++, $dto->odMarketPrice, PDO::PARAM_STR);
		$stmt->bindValue( $j++, $dto->odTotalCost, PDO::PARAM_STR);
		$stmt->bindValue( $j++, $dto->mbNo, ($dto->mbNo)?PDO::PARAM_INT:PDO::PARAM_NULL);
		$stmt->bindValue( $j++, $dto->mbId, ($dto->mbId)?PDO::PARAM_STR:PDO::PARAM_NULL);
		$stmt->bindValue( $j++, $dto->odFeeRate, PDO::PARAM_STR);
		$stmt->bindValue( $j++, $dto->odTempCoin, PDO::PARAM_STR);
		$stmt->bindValue( $j++, $dto->odReceiptCoin, PDO::PARAM_STR);
		$stmt->bindValue( $j++, $dto->odReceiptFee, PDO::PARAM_STR);
		$stmt->bindValue( $j++, $dto->odReceiptAvg, PDO::PARAM_STR);
		$stmt->bindValue( $j++, $dto->odReceiptDt, ($dto->odReceiptDt)?PDO::PARAM_STR:PDO::PARAM_NULL);
		$stmt->bindValue( $j++, $dto->tmpTrigerAc, ($dto->tmpTrigerAc)?PDO::PARAM_STR:PDO::PARAM_NULL);
			
		$stmt->bindValue( $j++, $dto->odId, ($dto->odId)?PDO::PARAM_INT:PDO::PARAM_NULL);
                        $stmt->execute();
                        if($stmt->rowCount()==1) parent::setResult(1);
                        else parent::setResult(0);
                    }else{
                        parent::setResult(Error::dbPrepare);
                    }
                }else{
                    parent::setResult(Error::dbConn);
                }
                return parent::getResult();
            }

            function deleteCompleteData(){
                parent::setResult(-1);
                if(!$this->db) $this->db=parent::getDatabaseTrade($this->dbName);
                if($this->db){

                    $sql = "DELETE FROM ".$this->dbTableName." WHERE od_pay_status='OK' LIMIT 500 ";

                    if($this->db) $stmt = $this->db->prepare($sql);
                    if($stmt){
                        $stmt->execute();
                        if($stmt->rowCount()>0) parent::setResult($stmt->rowCount());
                        else parent::setResult(0);
                    }else{
                        parent::setResult(Error::dbPrepare);
                    }
                }else{
                    parent::setResult(Error::dbConn);
                }
                return parent::getResult();
                
            }
            
            function deleteFromPri($pri){
                parent::setResult(-1);
                if(!$this->db) $this->db=parent::getDatabaseTrade($this->dbName);
                if($this->db){

                    $sql = "DELETE FROM ".$this->dbTableName." WHERE od_id=?";

                    if($this->db) $stmt = $this->db->prepare($sql);
                    if($stmt){
                        $stmt->bindValue( 1, $pri, PDO::PARAM_STR);
                        $stmt->execute();
                        if($stmt->rowCount()>0) parent::setResult($stmt->rowCount());
                        else parent::setResult(0);
                    }else{
                        parent::setResult(Error::dbPrepare);
                    }
                }else{
                    parent::setResult(Error::dbConn);
                }
                return parent::getResult();
            }

            function __destruct(){
                $this->db = NULL;
                $this->dto = NULL;
            }
        }