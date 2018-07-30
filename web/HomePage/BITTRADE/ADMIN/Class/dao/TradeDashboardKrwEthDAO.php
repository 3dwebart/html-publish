<?php      
/**
* Description of ViewTradeOrderbookCompleteDetailBtcEtcDAO
* @description FunHanSoft Co.,Ltd  PHP auto templet
* @date 2017-07-12
* @copyright (c)funhansoft.com
* @license 해당 사이트 이외의 사용은 엄격히 금지되어 있습니다.
* @version 4.0.0
*/
        class TradeDashboardKrwEthDAO extends BaseDAO{

            private $db;
            private $dbSlave;
            private $dbName;

            function __construct() {
                parent::__construct();
            }
            
            public function setDbName($nm){
                $this->dbName = $nm;
            }

            public function getListCount($field='',$value='',$svdf='',$svdt=''){
                $dto = new CommonListDTO();
                if(!$this->dbSlave) $this->dbSlave=parent::getDatabaseTradeSlave($this->dbName);
                if($this->dbSlave){
                    try{
                        $singleFields = array('tr_no','od_action','od_id');
                        $sql_add = $this->getListSearchSQL($singleFields,$field,$value);
                        
                        $sql = "SELECT count(*)  FROM mem_open_order_krw_eth{$sql_add}";

                        if($this->dbSlave) $stmt = $this->dbSlave->prepare($sql);
                        if($stmt){
                            if($value)
                                $stmt->bindValue( 1, $value, PDO::PARAM_STR);
                            
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

            public function getMarketList($field='',$value=''){
  
                $dto = new TradeDashboardKrwEthDTO();
                $dtoarray = array();
                if(!$this->dbSlave) $this->dbSlave=parent::getDatabaseTradeSlave($this->dbName);
                if($this->dbSlave){
                    try{
//                        $singleFields = array('od_id','od_action','mb_no');
//                        $sql_add = $this->getListSearchSQL($singleFields,$field,$value);
//                        $sql_add .= $this->getListOrderSQL($singleFields,'od_market_price');
                        
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
				od_cancel_dt,
                                od_reg_dt
                                FROM mem_open_order_krw_eth WHERE od_market_price=? ORDER BY od_market_price ASC";
                        
//                        echo $sql;
//                        exit;
                                
                                
                        if($this->dbSlave) $stmt = $this->dbSlave->prepare($sql);
                        if($stmt){
                            $j=1;
                            if($value)
                                $stmt->bindValue( $j++, $value, PDO::PARAM_STR);
                            //$stmt->bindValue( $j++, parent::getListLimitStart(), PDO::PARAM_INT);
                            //$stmt->bindValue( $j++, parent::getListLimitRow(), PDO::PARAM_INT);
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
				$dto->odCancelDt,
				$dto->odRegDt) = $stmt->fetch()) {
                                    $dtoarray[$i] = new TradeDashboardKrwEthDTO();
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
                                    $dtoarray[$i]->odCancelDt = $dto->odCancelDt;
                                    $dtoarray[$i]->odRegDt = $dto->odRegDt;
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

            public function getSellList($field='',$value='',$svdf='',$svdt=''){
  
                $dto = new TradeDashboardKrwEthDTO();
                $dtoarray = array();
                if(!$this->dbSlave) $this->dbSlave=parent::getDatabaseTradeSlave($this->dbName);
                if($this->dbSlave){
                    try{
//                        $singleFields = array('od_id','od_action','mb_no');
//                        $sql_add = $this->getListSearchSQL($singleFields,$field,$value);
//                        $sql_add .= $this->getListOrderSQL($singleFields,'od_market_price');
                        
                        $sql = "SELECT
                                od_id,
				od_action,
				od_pay_status,
				od_market_price,
				od_total_cost,
				mb_no,
				mb_id,
				od_fee_rate,
				sum(od_temp_coin) as od_temp_coin,
				sum(od_receipt_coin) as od_receipt_coin,
				od_receipt_fee,
				od_receipt_avg,
				od_receipt_dt,
				od_cancel_dt,
                                od_reg_dt
                                FROM mem_open_order_krw_eth WHERE od_action = 'sell' GROUP BY od_market_price ASC";
                                
//                                echo $sql;
//                                exit;
                                
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
				$dto->odCancelDt,
				$dto->odRegDt) = $stmt->fetch()) {
                                    $dtoarray[$i] = new TradeDashboardKrwEthDTO();
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
                                    $dtoarray[$i]->odCancelDt = $dto->odCancelDt;
                                    $dtoarray[$i]->odRegDt = $dto->odRegDt;
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
            
            public function getBuyList($field='',$value='',$svdf='',$svdt=''){
  
                $dto = new TradeDashboardKrwEthDTO();
                $dtoarray = array();
                if(!$this->dbSlave) $this->dbSlave=parent::getDatabaseTradeSlave($this->dbName);
                if($this->dbSlave){
                    try{
                        $singleFields = array('od_id','od_action','mb_no');
                        $sql_add = $this->getListSearchSQL($singleFields,$field,$value);
                        $sql_add .= $this->getListOrderSQL($singleFields,'od_market_price');
                        
                        $sql = "SELECT	
                                od_id,
				od_action,
				od_pay_status,
				od_market_price,
				od_total_cost,
				mb_no,
				mb_id,
				od_fee_rate,
				sum(od_temp_coin) as od_temp_coin,
				sum(od_receipt_coin) as od_receipt_coin,
				od_receipt_fee,
				od_receipt_avg,
				od_receipt_dt,
				od_cancel_dt,
                                od_reg_dt
                                FROM mem_open_order_krw_eth WHERE od_action = 'buy' GROUP BY od_market_price DESC";
                                
                                
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
				$dto->odCancelDt,
				$dto->odRegDt) = $stmt->fetch()) {
                                    $dtoarray[$i] = new TradeDashboardKrwEthDTO();
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
                                    $dtoarray[$i]->odCancelDt = $dto->odCancelDt;
                                    $dtoarray[$i]->odRegDt = $dto->odRegDt;
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
                if(!$this->db) $this->db=parent::getDatabaseTrade();
                if($this->db){
                    try{
                        $sql = "INSERT INTO mem_open_order_krw_eth(
                                od_id,
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
				od_cancel_dt,
				od_reg_dt
                                VALUES(?,?,?,?,?,?)";

                        if($this->db) $stmt = $this->db->prepare($sql);
                        if($stmt){
                            $j=1;
                            
                            $stmt->bindValue( $j++, $dto->odId, ($dto->odId)?PDO::PARAM_STR:PDO::PARAM_NULL);
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
                            $stmt->bindValue( $j++, $dto->odCancelDt, ($dto->odCancelDt)?PDO::PARAM_STR:PDO::PARAM_NULL);
                            $stmt->bindValue( $j++, $dto->odRegDt, PDO::PARAM_STR);
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
                if(!$this->db) $this->db=parent::getDatabaseTrade();
                if($this->db){

                    $sql = "UPDATE mem_open_order_krw_eth SET 
                                od_id=?,
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
				od_cancel_dt=?,
				od_reg_dt=? WHERE =?";

                    if($this->db) $stmt = $this->db->prepare($sql);
                    if($stmt){
                        $j=1;
                        
                        $stmt->bindValue( $j++, $dto->odId, ($dto->odId)?PDO::PARAM_STR:PDO::PARAM_NULL);
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
                        $stmt->bindValue( $j++, $dto->odCancelDt, ($dto->odCancelDt)?PDO::PARAM_STR:PDO::PARAM_NULL);
                        $stmt->bindValue( $j++, $dto->odRegDt, PDO::PARAM_STR);
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

            function deleteFromPri($pri){
                parent::setResult(-1);
                if(!$this->db) $this->db=parent::getDatabaseTrade();
                if($this->db){

                    $sql = "DELETE FROM mem_open_order_krw_eth WHERE =?";

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