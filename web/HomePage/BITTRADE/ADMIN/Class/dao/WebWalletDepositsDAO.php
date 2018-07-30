<?php      
/**
* Description of WebWalletDepositsDAO
* @description FunHanSoft Co.,Ltd  PHP auto templet
* @date 2017-08-22
* @copyright (c)funhansoft.com
* @license 해당 사이트 이외의 사용은 엄격히 금지되어 있습니다.
* @version 4.0.0
*/
        class WebWalletDepositsDAO extends BaseDAO{

            private $db;
            private $dbSlave;
            private $dbName = 'fns_trade_point';

            function __construct() {
                parent::__construct();
            }

            public function getViewById($od_id){
                $dto = new WebWalletDepositsDTO();
                if(!$this->dbSlave) $this->dbSlave=parent::getDatabaseTradeSlave($this->dbName);
                if($this->dbSlave){
                    try{
                        $sql = "SELECT 	od_id,
				od_status,
				od_category,
				mb_no,
				od_amount,
				od_txid,
				od_fee,
				od_from_addr,
				od_to_addr,
				od_confirm,
				po_type,
				po_amount,
				po_pay_yn,
				po_pay_dt,
				od_reg_dt,
				od_etc1,
				od_etc2,
				od_etc3 
                                FROM web_wallet_deposits WHERE od_id=?  limit 1";

                        if($this->dbSlave) $stmt = $this->dbSlave->prepare($sql);
                        if($stmt){
                            $stmt->bindValue( 1, $od_id, PDO::PARAM_STR);
                            $stmt->execute();
                            list(
				$dto->odId,
				$dto->odStatus,
				$dto->odCategory,
				$dto->mbNo,
				$dto->odAmount,
				$dto->odTxid,
				$dto->odFee,
				$dto->odFromAddr,
				$dto->odToAddr,
				$dto->odConfirm,
				$dto->poType,
				$dto->poAmount,
				$dto->poPayYn,
				$dto->poPayDt,
				$dto->odRegDt,
				$dto->odEtc1,
				$dto->odEtc2,
				$dto->odEtc3) = $stmt->fetch();
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
                        $singleFields = array('od_id','od_status','mb_no','od_confirm','po_pay_yn');
                        $sql_add = $this->getListSearchSQL($singleFields,$field,$value);
                        
                        $sql = "SELECT count(*)  FROM web_wallet_deposits{$sql_add}";

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

            public function getList($field='',$value='',$svdf='',$svdt=''){
                $dto = new WebWalletDepositsDTO();
                $dtoarray = array();
                if(!$this->dbSlave) $this->dbSlave=parent::getDatabaseTradeSlave($this->dbName);
                if($this->dbSlave){
                    try{
                        $singleFields = array('od_id','od_status','mb_no','od_confirm','po_pay_yn');
                        $sql_add = $this->getListSearchSQL($singleFields,$field,$value);
                        $sql_add .= $this->getListOrderSQL($singleFields,'od_id');
                        
                        $sql = "SELECT 	od_id,
				od_status,
				od_category,
				mb_no,
				od_amount,
				od_txid,
				od_fee,
				od_from_addr,
				od_to_addr,
				od_confirm,
				po_type,
				po_amount,
				po_pay_yn,
				po_pay_dt,
				od_reg_dt,
				od_etc1,
				od_etc2,
				od_etc3 
                                FROM web_wallet_deposits{$sql_add} LIMIT ?,?";

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
				$dto->odStatus,
				$dto->odCategory,
				$dto->mbNo,
				$dto->odAmount,
				$dto->odTxid,
				$dto->odFee,
				$dto->odFromAddr,
				$dto->odToAddr,
				$dto->odConfirm,
				$dto->poType,
				$dto->poAmount,
				$dto->poPayYn,
				$dto->poPayDt,
				$dto->odRegDt,
				$dto->odEtc1,
				$dto->odEtc2,
				$dto->odEtc3) = $stmt->fetch()) {
                                    $dtoarray[$i] = new WebWalletDepositsDTO();
                                    parent::setResult($i+1);
                                    $dtoarray[$i]->odId = $dto->odId;
					$dtoarray[$i]->odStatus = $dto->odStatus;
					$dtoarray[$i]->odCategory = $dto->odCategory;
					$dtoarray[$i]->mbNo = $dto->mbNo;
					$dtoarray[$i]->odAmount = $dto->odAmount;
					$dtoarray[$i]->odTxid = $dto->odTxid;
					$dtoarray[$i]->odFee = $dto->odFee;
					$dtoarray[$i]->odFromAddr = $dto->odFromAddr;
					$dtoarray[$i]->odToAddr = $dto->odToAddr;
					$dtoarray[$i]->odConfirm = $dto->odConfirm;
					$dtoarray[$i]->poType = $dto->poType;
					$dtoarray[$i]->poAmount = $dto->poAmount;
					$dtoarray[$i]->poPayYn = $dto->poPayYn;
					$dtoarray[$i]->poPayDt = $dto->poPayDt;
					$dtoarray[$i]->odRegDt = $dto->odRegDt;
					$dtoarray[$i]->odEtc1 = $dto->odEtc1;
					$dtoarray[$i]->odEtc2 = $dto->odEtc2;
					$dtoarray[$i]->odEtc3 = $dto->odEtc3;  
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
                        $sql = "INSERT INTO web_wallet_deposits(
                                od_status,
                                od_category,
                                mb_no,
                                od_tmp_mb,
                                od_amount,
                                od_txid,
                                od_fee,
                                od_from_addr,
                                od_to_addr,
                                od_confirm,
                                po_pay_yn,
                                po_pay_dt,
                                po_type,
                                od_reg_ip,
                                od_etc1,
                                od_etc2,
                                od_etc3)
                                VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";

                        if($this->db) $stmt = $this->db->prepare($sql);
                        if($stmt){
                            $j=1;
                            $stmt->bindValue( $j++, $dto->odStatus, ($dto->odStatus)?PDO::PARAM_STR:'WAIT');
                            $stmt->bindValue( $j++, $dto->odCategory, ($dto->odCategory)?PDO::PARAM_STR:PDO::PARAM_NULL);
                            $stmt->bindValue( $j++, $dto->mbNo, PDO::PARAM_INT);
                            $stmt->bindValue( $j++, $dto->odTmpMb, ($dto->odTmpMb)?PDO::PARAM_STR:PDO::PARAM_NULL);
                            $stmt->bindValue( $j++, $dto->odAmount, ($dto->odAmount)?PDO::PARAM_STR:PDO::PARAM_NULL);
                            $stmt->bindValue( $j++, $dto->odTxid, ($dto->odTxid)?PDO::PARAM_STR:PDO::PARAM_NULL);
                            $stmt->bindValue( $j++, $dto->odFee, ($dto->odFee)?PDO::PARAM_STR:PDO::PARAM_NULL);
                            $stmt->bindValue( $j++, $dto->odFromAddr, ($dto->odFromAddr)?PDO::PARAM_STR:PDO::PARAM_NULL);
                            $stmt->bindValue( $j++, $dto->odToAddr, ($dto->odToAddr)?PDO::PARAM_STR:PDO::PARAM_NULL);
                            $stmt->bindValue( $j++, $dto->odConfirm, ($dto->odConfirm)?PDO::PARAM_INT:PDO::PARAM_NULL);
                            $stmt->bindValue( $j++, $dto->poPayYn, ($dto->poPayYn)?PDO::PARAM_STR:PDO::PARAM_NULL);
                            $stmt->bindValue( $j++, $dto->poPayDt, ($dto->poPayDt)?PDO::PARAM_STR:PDO::PARAM_NULL);
                            $stmt->bindValue( $j++, $dto->poType, ($dto->poType)?PDO::PARAM_STR:PDO::PARAM_NULL);
                            $stmt->bindValue( $j++, $dto->odRegIp, ($dto->odRegIp)?PDO::PARAM_STR:PDO::PARAM_NULL);
                            $stmt->bindValue( $j++, $dto->odEtc1, ($dto->odEtc1)?PDO::PARAM_STR:PDO::PARAM_NULL);
                            $stmt->bindValue( $j++, $dto->odEtc2, ($dto->odEtc2)?PDO::PARAM_STR:PDO::PARAM_NULL);
                            $stmt->bindValue( $j++, $dto->odEtc3, ($dto->odEtc3)?PDO::PARAM_STR:PDO::PARAM_NULL);
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

                    $sql = "UPDATE web_wallet_deposits SET 
				od_status=?,
				od_category=?,
				mb_no=?,
				od_amount=?,
				od_txid=?,
				od_fee=?,
				od_from_addr=?,
				od_to_addr=?,
				od_confirm=?,
				po_type=?,
				po_amount=?,
				po_pay_yn=?,
				po_pay_dt=?,
				od_etc1=?,
				od_etc2=?,
				od_etc3=? WHERE od_id=?";

                    if($this->db) $stmt = $this->db->prepare($sql);
                    if($stmt){
                        $j=1;
                        
		$stmt->bindValue( $j++, $dto->odStatus, ($dto->odStatus)?PDO::PARAM_STR:PDO::PARAM_NULL);
		$stmt->bindValue( $j++, $dto->odCategory, ($dto->odCategory)?PDO::PARAM_STR:PDO::PARAM_NULL);
		$stmt->bindValue( $j++, $dto->mbNo, PDO::PARAM_INT);
		$stmt->bindValue( $j++, $dto->odAmount, ($dto->odAmount)?PDO::PARAM_STR:PDO::PARAM_NULL);
		$stmt->bindValue( $j++, $dto->odTxid, ($dto->odTxid)?PDO::PARAM_STR:PDO::PARAM_NULL);
		$stmt->bindValue( $j++, $dto->odFee, ($dto->odFee)?PDO::PARAM_STR:PDO::PARAM_NULL);
		$stmt->bindValue( $j++, $dto->odFromAddr, ($dto->odFromAddr)?PDO::PARAM_STR:PDO::PARAM_NULL);
		$stmt->bindValue( $j++, $dto->odToAddr, ($dto->odToAddr)?PDO::PARAM_STR:PDO::PARAM_NULL);
		$stmt->bindValue( $j++, $dto->odConfirm, ($dto->odConfirm)?PDO::PARAM_INT:PDO::PARAM_NULL);
		$stmt->bindValue( $j++, $dto->poType, ($dto->poType)?PDO::PARAM_STR:PDO::PARAM_NULL);
		$stmt->bindValue( $j++, $dto->poAmount, ($dto->poAmount)?PDO::PARAM_STR:PDO::PARAM_NULL);
		$stmt->bindValue( $j++, $dto->poPayYn, PDO::PARAM_STR);
		$stmt->bindValue( $j++, $dto->poPayDt, ($dto->poPayDt)?PDO::PARAM_STR:PDO::PARAM_NULL);
		$stmt->bindValue( $j++, $dto->odEtc1, ($dto->odEtc1)?PDO::PARAM_STR:PDO::PARAM_NULL);
		$stmt->bindValue( $j++, $dto->odEtc2, ($dto->odEtc2)?PDO::PARAM_STR:PDO::PARAM_NULL);
		$stmt->bindValue( $j++, $dto->odEtc3, ($dto->odEtc3)?PDO::PARAM_STR:PDO::PARAM_NULL);
			
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

            function deleteFromPri($pri){
                parent::setResult(-1);
                if(!$this->db) $this->db=parent::getDatabaseTrade($this->dbName);
                if($this->db){

                    $sql = "DELETE FROM web_wallet_deposits WHERE od_id=?";

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