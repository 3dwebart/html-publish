<?php      
/**
* Description of WebWalletDepositsDAO
* @description FunHanSoft Co.,Ltd  PHP auto templet
* @date 2017-08-22
* @copyright (c)funhansoft.com
* @license 해당 사이트 이외의 사용은 엄격히 금지되어 있습니다.
* @version 0.3.0
*/
        class WebWalletDepositsDAO extends BaseDAO{

            private $db;
            private $dbSlave;
            private $dbName = 'fns_trade_point';
            private $poType = '';

            function __construct() {
                parent::__construct();
            }
            
            function setPoType($potype){
                $this->poType = $potype;
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
            
            public function getLastestBlockHeight($poType){
                $resultcnt = 0;
                if(!$this->dbSlave) $this->dbSlave=parent::getDatabaseTradeSlave($this->dbName);
                if($this->dbSlave){
                    try{
                        $sql = "SELECT block_height
                                FROM web_wallet_deposits WHERE po_type=? AND od_status='OK' ORDER BY od_id DESC limit 1";

                        if($this->dbSlave) $stmt = $this->dbSlave->prepare($sql);
                        if($stmt){
                            $stmt->bindValue( 1, $poType, PDO::PARAM_STR);
                            $stmt->execute();
                            list($height) = $stmt->fetch();
                            
                            if($stmt->rowCount()==1) $resultcnt = $height; 
                           
                        }else{
                            parent::setResult(Error::dbPrepare);
                        }
                    }catch(DBException $e){ parent::setResult(Error::dbPrepare);}
                }else{
                    parent::setResult(Error::dbConn);
                }
                return $resultcnt;
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
            
            public function getListUnConfirm($po_type){
                $dto = new WebWalletDepositsDTO();
                $dtoarray = array();
                if(!$this->dbSlave) $this->dbSlave=parent::getDatabaseTradeSlave($this->dbName);
                if($this->dbSlave){
                    try{
                        $sql_add = " WHERE po_type=? AND od_status='WAIT' AND po_pay_yn='N' AND od_category<>'send'";
                        
                        $sql = "SELECT od_txid
                                FROM web_wallet_deposits{$sql_add} ORDER BY od_id ASC LIMIT ?,?";

   
                        if($this->dbSlave) $stmt = $this->dbSlave->prepare($sql);
                        if($stmt){
                            $j=1;
                            $stmt->bindValue( $j++, $po_type, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, parent::getListLimitStart(), PDO::PARAM_INT);
                            $stmt->bindValue( $j++, parent::getListLimitRow(), PDO::PARAM_INT);
                            $stmt->execute();

                            $i = 0;
                            while(list(
                                    $dto->odTxid) = $stmt->fetch()) {
                                    $dtoarray[$i] = '';
                                    parent::setResult($i+1);
                                    $dtoarray[$i] = $dto->odTxid;
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

            
            public function getListByUnConfirm($po_type,$max_confirm=50){
                $dto = new WebWalletDepositsDTO();
                $dtoarray = array();
                if(!$this->dbSlave) $this->dbSlave=parent::getDatabaseTradeSlave($this->dbName);
                if($this->dbSlave){
                    try{
                        $sql_add = " WHERE po_type=? AND od_status='WAIT' AND od_category = 'receive' AND od_confirm < ". (int)$max_confirm . " AND po_pay_yn='N' ";
                        $sql_add .= " ORDER BY od_id ASC";
                        
                        $sql = "SELECT 	od_id,
                                od_status,
                                block_height,
                                od_category,
                                mb_no,
                                od_tmp_mb,
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
                            $stmt->bindValue( $j++, $po_type, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, parent::getListLimitStart(), PDO::PARAM_INT);
                            $stmt->bindValue( $j++, parent::getListLimitRow(), PDO::PARAM_INT);
                            $stmt->execute();

                            $i = 0;
                            while(list(
                                $dto->odId,
                                $dto->odStatus,
                                $dto->blockHeight,
                                $dto->odCategory,
                                $dto->mbNo,
                                $dto->odTmpMb,
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
                                    $dtoarray[$i]->blockHeight = $dto->blockHeight;
                                    $dtoarray[$i]->odCategory = $dto->odCategory;
                                    $dtoarray[$i]->mbNo = $dto->mbNo;
                                    $dtoarray[$i]->odTmpMb = $dto->odTmpMb;
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
            
            public function getListsByTxId($txid){
                $dto = new WebWalletDepositsDTO();
                $dtoarray = array();
                if(!$this->dbSlave) $this->dbSlave=parent::getDatabaseTradeSlave($this->dbName);
                if($this->dbSlave){
                    try{
                        $sql_add = " WHERE od_txid=?";
                        
                        $sql = "SELECT 	od_id,
                                od_status,
                                block_height,
                                od_category,
                                mb_no,
                                od_tmp_mb,
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
                            $stmt->bindValue( $j++, $txid, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, parent::getListLimitStart(), PDO::PARAM_INT);
                            $stmt->bindValue( $j++, parent::getListLimitRow(), PDO::PARAM_INT);
                            $stmt->execute();

                            $i = 0;
                            while(list(
                                $dto->odId,
                                $dto->odStatus,
                                $dto->blockHeight,
                                $dto->odCategory,
                                $dto->mbNo,
                                $dto->odTmpMb,
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
                                    $dtoarray[$i]->blockHeight = $dto->blockHeight;
                                    $dtoarray[$i]->odCategory = $dto->odCategory;
                                    $dtoarray[$i]->mbNo = $dto->mbNo;
                                    $dtoarray[$i]->odTmpMb = $dto->odTmpMb;
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
                if(!isset($dto->blockHeight)){
                    $dto->blockHeight = 0;
                }
                parent::setResult(-1);
                if(!$this->db) $this->db=parent::getDatabaseTrade($this->dbName);
                if($this->db){
                    try{
                        $sql = "INSERT INTO web_wallet_deposits(
                                block_height,
                                od_category,
                                mb_no,
                                od_tmp_mb,
                                od_amount,
                                od_txid,
                                od_fee,
                                od_from_addr,
                                od_to_addr,
                                od_confirm,
                                po_type,
                                od_reg_ip,
                                od_etc1,
                                od_etc2,
                                od_etc3)
                                VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";

                        if($this->db) $stmt = $this->db->prepare($sql);
                        if($stmt){
                            $j=1;
                            $stmt->bindValue( $j++, $dto->blockHeight, ($dto->blockHeight)?PDO::PARAM_STR:0);
                            $stmt->bindValue( $j++, $dto->odCategory, ($dto->odCategory)?PDO::PARAM_STR:PDO::PARAM_NULL);
                            $stmt->bindValue( $j++, $dto->mbNo, PDO::PARAM_INT);
                            $stmt->bindValue( $j++, $dto->odTmpMb, ($dto->odTmpMb)?PDO::PARAM_STR:PDO::PARAM_NULL);
                            $stmt->bindValue( $j++, $dto->odAmount, ($dto->odAmount)?PDO::PARAM_STR:PDO::PARAM_NULL);
                            $stmt->bindValue( $j++, $dto->odTxid, ($dto->odTxid)?PDO::PARAM_STR:PDO::PARAM_NULL);
                            $stmt->bindValue( $j++, $dto->odFee, ($dto->odFee)?PDO::PARAM_STR:PDO::PARAM_NULL);
                            $stmt->bindValue( $j++, $dto->odFromAddr, ($dto->odFromAddr)?PDO::PARAM_STR:PDO::PARAM_NULL);
                            $stmt->bindValue( $j++, $dto->odToAddr, ($dto->odToAddr)?PDO::PARAM_STR:PDO::PARAM_NULL);
                            $stmt->bindValue( $j++, $dto->odConfirm, ($dto->odConfirm)?PDO::PARAM_INT:PDO::PARAM_NULL);
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

            
            public function setUpdateConfirm($txid,$confirm,$status='',$etc3=''){
                parent::setResult(-1);
                if(!$this->db) $this->db=parent::getDatabaseTrade($this->dbName);
                if($this->db){
                    $sql_add = '';
                    if($status){
                        $sql_add .= ',od_status=?';
                        if($status=='OK'){
                            $sql_add .= ",po_pay_yn='Y',po_pay_dt=now()";
                        }
                        
                        if($etc3){
                            $sql_add .= ",etc3='".$etc3."'";
                        }
                        
                    }
                    $sql = "UPDATE web_wallet_deposits SET od_confirm=? {$sql_add} WHERE od_txid=?";

                    if($this->db) $stmt = $this->db->prepare($sql);
                    if($stmt){
                        $j=1;

                        $stmt->bindValue( $j++, $confirm, ($confirm)?PDO::PARAM_INT:PDO::PARAM_NULL);
                        if($status){
                            $stmt->bindValue( $j++, $status, ($txid)?PDO::PARAM_STR:PDO::PARAM_NULL);
                        }
                        $stmt->bindValue( $j++, $txid, ($txid)?PDO::PARAM_STR:PDO::PARAM_NULL);

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

            function __destruct(){
                $this->db = NULL;
                $this->dto = NULL;
            }
        }