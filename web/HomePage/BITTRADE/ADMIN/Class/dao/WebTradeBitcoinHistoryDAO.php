<?php      
/**
* Description of WebTradeBitcoinHistoryDAO
* @description FunHanSoft Co.,Ltd  PHP auto templet
* @date 2017-02-24
* @copyright (c)funhansoft.com
* @license 해당 사이트 이외의 사용은 엄격히 금지되어 있습니다.
* @version 4.0.0
*/
        class WebTradeBitcoinHistoryDAO extends BaseDAO{

            private $db;
            private $dbSlave;

            function __construct() {
                parent::__construct();
            }

            public function getViewById($hs_no){
                $dto = new WebTradeBitcoinHistoryDTO();
                if(!$this->dbSlave) $this->dbSlave=parent::getDatabaseTradeSlave();
                if($this->dbSlave){
                    try{
                        $sql = "SELECT 	hs_no,
				od_id,
				od_action,
				od_pay_status,
				od_pay_po_ids,
				od_market_price,
				od_total_cost,
				mb_no,
				mb_id,
				od_temp_coin,
				od_temp_fee,
				od_receipt_coin,
				od_receipt_fee,
				od_receipt_avg,
				od_del_yn,
				od_reg_dt,
				od_receipt_dt,
				od_cancel_dt,
				od_reg_ip,
				tmp_triger_ac,
				partner 
                                FROM web_trade_bitcoin_history WHERE hs_no=?  limit 1";

                        if($this->dbSlave) $stmt = $this->dbSlave->prepare($sql);
                        if($stmt){
                            $stmt->bindValue( 1, $hs_no, PDO::PARAM_STR);
                            $stmt->execute();
                            list(
				$dto->hsNo,
				$dto->odId,
				$dto->odAction,
				$dto->odPayStatus,
				$dto->odPayPoIds,
				$dto->odMarketPrice,
				$dto->odTotalCost,
				$dto->mbNo,
				$dto->mbId,
				$dto->odTempCoin,
				$dto->odTempFee,
				$dto->odReceiptCoin,
				$dto->odReceiptFee,
				$dto->odReceiptAvg,
				$dto->odDelYn,
				$dto->odRegDt,
				$dto->odReceiptDt,
				$dto->odCancelDt,
				$dto->odRegIp,
				$dto->tmpTrigerAc,
				$dto->partner) = $stmt->fetch();
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
                if(!$this->dbSlave) $this->dbSlave=parent::getDatabaseTradeSlave();
                if($this->dbSlave){
                    try{
                        $singleFields = array('hs_no','od_id','od_action','od_pay_status','mb_no','od_del_yn');
                        $sql_add = $this->getListSearchSQL($singleFields,$field,$value);
                         if($svdf && $svdt){
                            if($sql_add){
                                $sql_add .= " AND ";
                            }else{
                                $sql_add .= " WHERE ";
                            }
                            $sql_add .= "(od_receipt_dt BETWEEN ? AND DATE_ADD(?,INTERVAL 1 DAY) ) ";
                        } 
                        $sql = "SELECT count(*)  FROM web_trade_bitcoin_history{$sql_add}";

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
                $dto = new WebTradeBitcoinHistoryDTO();
                $dtoarray = array();
                if(!$this->dbSlave) $this->dbSlave=parent::getDatabaseTradeSlave();
                if($this->dbSlave){
                    try{
                        $singleFields = array('hs_no','od_id','od_action','od_pay_status','mb_no','od_del_yn');
                        $sql_add = $this->getListSearchSQL($singleFields,$field,$value);
                        if($svdf && $svdt){
                            if($sql_add){
                                $sql_add .= " AND ";
                            }else{
                                $sql_add .= " WHERE ";
                            }
                            $sql_add .= "(od_receipt_dt BETWEEN ? AND DATE_ADD(?,INTERVAL 1 DAY) ) ";
                        }
                        $sql_add .= $this->getListOrderSQL($singleFields,'hs_no');
                        
                        $sql = "SELECT 	hs_no,
				od_id,
				od_action,
				od_pay_status,
				LEFT(od_pay_po_ids,256),
				od_market_price,
				od_total_cost,
				mb_no,
				mb_id,
				od_temp_coin,
				od_temp_fee,
				od_receipt_coin,
				od_receipt_fee,
				od_receipt_avg,
				od_del_yn,
				od_reg_dt,
				od_receipt_dt,
				od_cancel_dt,
				od_reg_ip,
				tmp_triger_ac,
				partner 
                                FROM web_trade_bitcoin_history{$sql_add} LIMIT ?,?";

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
				$dto->hsNo,
				$dto->odId,
				$dto->odAction,
				$dto->odPayStatus,
				$dto->odPayPoIds,
				$dto->odMarketPrice,
				$dto->odTotalCost,
				$dto->mbNo,
				$dto->mbId,
				$dto->odTempCoin,
				$dto->odTempFee,
				$dto->odReceiptCoin,
				$dto->odReceiptFee,
				$dto->odReceiptAvg,
				$dto->odDelYn,
				$dto->odRegDt,
				$dto->odReceiptDt,
				$dto->odCancelDt,
				$dto->odRegIp,
				$dto->tmpTrigerAc, $dto->partner) = $stmt->fetch()) {
                                    $dtoarray[$i] = new WebTradeBitcoinHistoryDTO();
                                    parent::setResult($i+1);
                                    $dtoarray[$i]->hsNo = $dto->hsNo;
					$dtoarray[$i]->odId = $dto->odId;
					$dtoarray[$i]->odAction = $dto->odAction;
					$dtoarray[$i]->odPayStatus = $dto->odPayStatus;
					$dtoarray[$i]->odPayPoIds = $dto->odPayPoIds;
					$dtoarray[$i]->odMarketPrice = $dto->odMarketPrice;
					$dtoarray[$i]->odTotalCost = $dto->odTotalCost;
					$dtoarray[$i]->mbNo = $dto->mbNo;
					$dtoarray[$i]->mbId = $dto->mbId;
					$dtoarray[$i]->odTempCoin = $dto->odTempCoin;
					$dtoarray[$i]->odTempFee = $dto->odTempFee;
					$dtoarray[$i]->odReceiptCoin = $dto->odReceiptCoin;
					$dtoarray[$i]->odReceiptFee = $dto->odReceiptFee;
					$dtoarray[$i]->odReceiptAvg = $dto->odReceiptAvg;
					$dtoarray[$i]->odDelYn = $dto->odDelYn;
					$dtoarray[$i]->odRegDt = $dto->odRegDt;
					$dtoarray[$i]->odReceiptDt = $dto->odReceiptDt;
					$dtoarray[$i]->odCancelDt = $dto->odCancelDt;
					$dtoarray[$i]->odRegIp = $dto->odRegIp;
					$dtoarray[$i]->tmpTrigerAc = $dto->tmpTrigerAc;
					$dtoarray[$i]->partner = $dto->partner;  
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
                if(!$this->db) $this->db=parent::getDatabaseTrade();
                if($this->db){
                    try{
                        $sql = "INSERT INTO web_trade_bitcoin_history(
				od_id,
				od_action,
				od_pay_status,
				od_pay_po_ids,
				od_market_price,
				od_total_cost,
				mb_no,
				mb_id,
				od_temp_coin,
				od_temp_fee,
				od_receipt_coin,
				od_receipt_fee,
				od_receipt_avg,
				od_del_yn,
				od_receipt_dt,
				od_cancel_dt,
				od_reg_ip,
				tmp_triger_ac,
				partner)
                                VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";

                        if($this->db) $stmt = $this->db->prepare($sql);
                        if($stmt){
                            $j=1;
                            
		$stmt->bindValue( $j++, $dto->odId, ($dto->odId)?PDO::PARAM_INT:PDO::PARAM_NULL);
		$stmt->bindValue( $j++, $dto->odAction, ($dto->odAction)?PDO::PARAM_STR:PDO::PARAM_NULL);
		$stmt->bindValue( $j++, $dto->odPayStatus, ($dto->odPayStatus)?PDO::PARAM_STR:PDO::PARAM_NULL);
		$stmt->bindValue( $j++, $dto->odPayPoIds, ($dto->odPayPoIds)?PDO::PARAM_STR:PDO::PARAM_NULL);
		$stmt->bindValue( $j++, $dto->odMarketPrice, PDO::PARAM_STR);
		$stmt->bindValue( $j++, $dto->odTotalCost, PDO::PARAM_STR);
		$stmt->bindValue( $j++, $dto->mbNo, ($dto->mbNo)?PDO::PARAM_INT:PDO::PARAM_NULL);
		$stmt->bindValue( $j++, $dto->mbId, ($dto->mbId)?PDO::PARAM_STR:PDO::PARAM_NULL);
		$stmt->bindValue( $j++, $dto->odTempCoin, PDO::PARAM_STR);
		$stmt->bindValue( $j++, $dto->odTempFee, PDO::PARAM_STR);
		$stmt->bindValue( $j++, $dto->odReceiptCoin, PDO::PARAM_STR);
		$stmt->bindValue( $j++, $dto->odReceiptFee, PDO::PARAM_STR);
		$stmt->bindValue( $j++, $dto->odReceiptAvg, PDO::PARAM_STR);
		$stmt->bindValue( $j++, $dto->odDelYn, PDO::PARAM_STR);
		$stmt->bindValue( $j++, $dto->odReceiptDt, ($dto->odReceiptDt)?PDO::PARAM_STR:PDO::PARAM_NULL);
		$stmt->bindValue( $j++, $dto->odCancelDt, ($dto->odCancelDt)?PDO::PARAM_STR:PDO::PARAM_NULL);
		$stmt->bindValue( $j++, $dto->odRegIp, ($dto->odRegIp)?PDO::PARAM_STR:PDO::PARAM_NULL);
		$stmt->bindValue( $j++, $dto->tmpTrigerAc, ($dto->tmpTrigerAc)?PDO::PARAM_STR:PDO::PARAM_NULL);
		$stmt->bindValue( $j++, $dto->partner, ($dto->partner)?PDO::PARAM_STR:PDO::PARAM_NULL);
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
                if(!$this->db) $this->db=parent::getDatabaseTrade();
                if($this->db){

                    $sql = "UPDATE web_trade_bitcoin_history SET 
				od_id=?,
				od_action=?,
				od_pay_status=?,
				od_pay_po_ids=?,
				od_market_price=?,
				od_total_cost=?,
				mb_no=?,
				mb_id=?,
				od_temp_coin=?,
				od_temp_fee=?,
				od_receipt_coin=?,
				od_receipt_fee=?,
				od_receipt_avg=?,
				od_del_yn=?,
				od_receipt_dt=?,
				od_cancel_dt=?,
				od_reg_ip=?,
				tmp_triger_ac=?,
				partner=? WHERE hs_no=?";

                    if($this->db) $stmt = $this->db->prepare($sql);
                    if($stmt){
                        $j=1;
                        
		$stmt->bindValue( $j++, $dto->odId, ($dto->odId)?PDO::PARAM_INT:PDO::PARAM_NULL);
		$stmt->bindValue( $j++, $dto->odAction, ($dto->odAction)?PDO::PARAM_STR:PDO::PARAM_NULL);
		$stmt->bindValue( $j++, $dto->odPayStatus, ($dto->odPayStatus)?PDO::PARAM_STR:PDO::PARAM_NULL);
		$stmt->bindValue( $j++, $dto->odPayPoIds, ($dto->odPayPoIds)?PDO::PARAM_STR:PDO::PARAM_NULL);
		$stmt->bindValue( $j++, $dto->odMarketPrice, PDO::PARAM_STR);
		$stmt->bindValue( $j++, $dto->odTotalCost, PDO::PARAM_STR);
		$stmt->bindValue( $j++, $dto->mbNo, ($dto->mbNo)?PDO::PARAM_INT:PDO::PARAM_NULL);
		$stmt->bindValue( $j++, $dto->mbId, ($dto->mbId)?PDO::PARAM_STR:PDO::PARAM_NULL);
		$stmt->bindValue( $j++, $dto->odTempCoin, PDO::PARAM_STR);
		$stmt->bindValue( $j++, $dto->odTempFee, PDO::PARAM_STR);
		$stmt->bindValue( $j++, $dto->odReceiptCoin, PDO::PARAM_STR);
		$stmt->bindValue( $j++, $dto->odReceiptFee, PDO::PARAM_STR);
		$stmt->bindValue( $j++, $dto->odReceiptAvg, PDO::PARAM_STR);
		$stmt->bindValue( $j++, $dto->odDelYn, PDO::PARAM_STR);
		$stmt->bindValue( $j++, $dto->odReceiptDt, ($dto->odReceiptDt)?PDO::PARAM_STR:PDO::PARAM_NULL);
		$stmt->bindValue( $j++, $dto->odCancelDt, ($dto->odCancelDt)?PDO::PARAM_STR:PDO::PARAM_NULL);
		$stmt->bindValue( $j++, $dto->odRegIp, ($dto->odRegIp)?PDO::PARAM_STR:PDO::PARAM_NULL);
		$stmt->bindValue( $j++, $dto->tmpTrigerAc, ($dto->tmpTrigerAc)?PDO::PARAM_STR:PDO::PARAM_NULL);
		$stmt->bindValue( $j++, $dto->partner, ($dto->partner)?PDO::PARAM_STR:PDO::PARAM_NULL);
			
		$stmt->bindValue( $j++, $dto->hsNo, ($dto->hsNo)?PDO::PARAM_INT:PDO::PARAM_NULL);
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
                if(!$this->db) $this->db=parent::getDatabaseTrade();
                if($this->db){

                    $sql = "DELETE FROM web_trade_bitcoin_history WHERE hs_no=?";

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