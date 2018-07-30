<?php      
/**
* Description of WebTradeBitcoinDAO
* @description Funhansoft PHP auto templet
* @date 2015-08-21
* @copyright (c)funhansoft.com
* @license http://funhansoft.com/license
* @version 4.0.0
*/
        class WebTradeBitcoinDAO extends BaseDAO{

            private $db;
            private $dbSlave;



            function __construct() {
                parent::__construct();
            }



            public function getViewById($od_id){
                $dto = new WebTradeBitcoinDTO();
                if(!$this->dbSlave) $this->dbSlave=parent::getDatabaseTradeSlave();
                if($this->dbSlave){
                    try{
                        $sql = "SELECT
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
                                    od_reg_ip,
                                    partner
                                FROM web_trade_bitcoin WHERE od_id=?  limit 1";

                        if($this->dbSlave) $stmt = $this->dbSlave->prepare($sql);
                        if($stmt){
                            $stmt->bindValue( 1, $od_id, PDO::PARAM_STR);
                            $stmt->execute();
                            list(
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
                                $dto->odRegIp,
                                $dto->partner) = $stmt->fetch();
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
                if(!$this->dbSlave) $this->dbSlave=parent::getDatabaseTradeSlave();
                if($this->dbSlave){
                    try{
                        $singleFields = array('od_id','od_action','od_pay_status','mb_no','od_del_yn');
                        $sql_add = $this->getListSearchSQL($singleFields,$field,$value);
                         if($svdf && $svdt){
                            if($sql_add){
                                $sql_add .= " AND ";
                            }else{
                                $sql_add .= " WHERE ";
                            }
                            $sql_add .= "(od_reg_dt BETWEEN ? AND DATE_ADD(?,INTERVAL 1 DAY) ) ";
                        }

                        $sql = "SELECT count(*) FROM web_trade_bitcoin{$sql_add}";

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
                $dto = new WebTradeBitcoinDTO();
                $dtoarray = array();
                if(!$this->dbSlave) $this->dbSlave=parent::getDatabaseTradeSlave();
                if($this->dbSlave){
                    try{
                        $singleFields = array('od_id','od_action','od_pay_status','mb_no','od_del_yn');
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

                        $sql = "SELECT
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
                                    od_reg_ip,
                                    partner
                                FROM web_trade_bitcoin{$sql_add} LIMIT ?,?";
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
                                    $dto->odRegIp,
                                    $dto->partner) = $stmt->fetch()) {
                                    $dtoarray[$i] = new WebTradeBitcoinDTO();
                                    parent::setResult($i+1);
                                    $dtoarray[$i]->odId = $dto->odId;
                                    $dtoarray[$i]->odAction = $dto->odAction;
                                    $dtoarray[$i]->odPayStatus = $dto->odPayStatus;
                                    $dtoarray[$i]->odPayPoIds = $dto->odPayPoIds;
                                    $dtoarray[$i]->odMarketPrice = $dto->odMarketPrice;
                                    $dtoarray[$i]->odTotalCost = $dto->odTotalCost;
                                    $dtoarray[$i]->mbNo = $dto->mbNo;
                                    $dtoarray[$i]->mbId = $dto->mbId;
                                    $dtoarray[$i]->odTempCoin = $dto->odTempCoin;
                                    $dtoarray[$i]->odTempKrw = $dto->odTempKrw;
                                    $dtoarray[$i]->odTempFee = $dto->odTempFee;
                                    $dtoarray[$i]->odReceiptCoin = $dto->odReceiptCoin;
                                    $dtoarray[$i]->odReceiptKrw = $dto->odReceiptKrw;
                                    $dtoarray[$i]->odReceiptFee = $dto->odReceiptFee;
                                    $dtoarray[$i]->odReceiptAvg = $dto->odReceiptAvg;
                                    $dtoarray[$i]->odDelYn = $dto->odDelYn;
                                    $dtoarray[$i]->odRegDt = $dto->odRegDt;
                                    $dtoarray[$i]->odReceiptDt = $dto->odReceiptDt;
                                    $dtoarray[$i]->odRegIp = $dto->odRegIp;
                                    $dtoarray[$i]->partner = $dto->partner;
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


            
            public function getUnTradeList($field='',$value=''){
                $dto = new WebTradeBitcoinDTO();
                $dtoarray = array();
                if(!$this->dbSlave) $this->dbSlave=parent::getDatabaseTradeSlave();
                if($this->dbSlave){
                    try{
                        $singleFields = array('od_id','od_market_price','od_action','od_pay_status','mb_no','od_del_yn');
                        $sql_add = $this->getListSearchSQL($singleFields,$field,$value);
                        if($sql_add){
                            $sql_add .= " AND (od_pay_status='REQ' OR od_pay_status='PART')";
                        }else{
                            $sql_add .= " WHERE (od_pay_status='REQ' OR od_pay_status='PART')";
                        }

                        $sql_add .= $this->getListOrderSQL($singleFields,'od_id');

                        $sql = "SELECT
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
                                    od_reg_ip,
                                    partner
                                FROM web_trade_bitcoin{$sql_add} LIMIT ?,?";
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
                                $dto->odRegIp,
                                $dto->partner) = $stmt->fetch()) {
                                    $dtoarray[$i] = new WebTradeBitcoinDTO();
                                    parent::setResult($i+1);
                                    $dtoarray[$i]->odId = $dto->odId;
                                    $dtoarray[$i]->odAction = $dto->odAction;
                                    $dtoarray[$i]->odPayStatus = $dto->odPayStatus;
                                    $dtoarray[$i]->odPayPoIds = $dto->odPayPoIds;
                                    $dtoarray[$i]->odMarketPrice = $dto->odMarketPrice;
                                    $dtoarray[$i]->odTotalCost = $dto->odTotalCost;
                                    $dtoarray[$i]->mbNo = $dto->mbNo;
                                    $dtoarray[$i]->mbId = $dto->mbId;
                                    $dtoarray[$i]->odTempCoin = $dto->odTempCoin;
                                    $dtoarray[$i]->odTempKrw = $dto->odTempKrw;
                                    $dtoarray[$i]->odTempFee = $dto->odTempFee;
                                    $dtoarray[$i]->odReceiptCoin = $dto->odReceiptCoin;
                                    $dtoarray[$i]->odReceiptKrw = $dto->odReceiptKrw;
                                    $dtoarray[$i]->odReceiptFee = $dto->odReceiptFee;
                                    $dtoarray[$i]->odReceiptAvg = $dto->odReceiptAvg;
                                    $dtoarray[$i]->odDelYn = $dto->odDelYn;
                                    $dtoarray[$i]->odRegDt = $dto->odRegDt;
                                    $dtoarray[$i]->odReceiptDt = $dto->odReceiptDt;
                                    $dtoarray[$i]->odRegIp = $dto->odRegIp;
                                    $dtoarray[$i]->partner = $dto->partner;
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
                if(!$this->db) $this->db=parent::getDatabaseTrade();
                if($this->db){
                    try{
                        $sql = "INSERT INTO web_trade_bitcoin
                                (
                                    od_action,
                                    od_pay_status,
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
                                    od_reg_ip,
                                    partner)
                                VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";

                        if($this->db) $stmt = $this->db->prepare($sql);
                        if($stmt){
                            $j=1;

                            $stmt->bindValue( $j++, $dto->odAction, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, $dto->odPayStatus, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, $dto->odMarketPrice, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, $dto->odTotalCost, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, $dto->mbNo, PDO::PARAM_INT);
                            $stmt->bindValue( $j++, $dto->mbId, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, $dto->odTempCoin, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, $dto->odTempFee, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, $dto->odReceiptCoin, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, $dto->odReceiptFee, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, $dto->odReceiptAvg, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, $dto->odDelYn, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, $dto->odReceiptDt, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, $dto->odRegIp, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, $dto->partner, PDO::PARAM_STR);
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
                if(!$this->db) $this->db=parent::getDatabaseTrade();
                if($this->db){

                    $sql = "UPDATE web_trade_bitcoin SET
                                od_action=?,
                                od_pay_status=?,
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
                                od_reg_ip=?,
                                partner=? WHERE od_id=?";

                    if($this->db) $stmt = $this->db->prepare($sql);
                    if($stmt){
                        $j=1;
                        $stmt->bindValue( $j++, $dto->odAction, PDO::PARAM_STR);
                        $stmt->bindValue( $j++, $dto->odPayStatus, PDO::PARAM_STR);
                        $stmt->bindValue( $j++, $dto->odMarketPrice, PDO::PARAM_STR);
                        $stmt->bindValue( $j++, $dto->odTotalCost, PDO::PARAM_STR);
                        $stmt->bindValue( $j++, $dto->mbNo, PDO::PARAM_INT);
                        $stmt->bindValue( $j++, $dto->mbId, PDO::PARAM_STR);
                        $stmt->bindValue( $j++, $dto->odTempCoin, PDO::PARAM_STR);
                        $stmt->bindValue( $j++, $dto->odTempFee, PDO::PARAM_STR);
                        $stmt->bindValue( $j++, $dto->odReceiptCoin, PDO::PARAM_STR);
                        $stmt->bindValue( $j++, $dto->odReceiptFee, PDO::PARAM_STR);
                        $stmt->bindValue( $j++, $dto->odReceiptAvg, PDO::PARAM_STR);
                        $stmt->bindValue( $j++, $dto->odDelYn, PDO::PARAM_STR);
                        $stmt->bindValue( $j++, $dto->odReceiptDt, PDO::PARAM_STR);
                        $stmt->bindValue( $j++, $dto->odRegIp, PDO::PARAM_STR);
                        $stmt->bindValue( $j++, $dto->partner, PDO::PARAM_STR);
                        $stmt->bindValue( $j++, $dto->odId, PDO::PARAM_INT);
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

                    $sql = "DELETE FROM web_trade_bitcoin WHERE od_id=?";

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