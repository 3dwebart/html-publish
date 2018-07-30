<?php      
/**
* Description of WebWalletWithdrawalsDAO
* @description FunHanSoft Co.,Ltd  PHP auto templet
* @date 2017-08-19
* @copyright (c)funhansoft.com
* @license 해당 사이트 이외의 사용은 엄격히 금지되어 있습니다.
* @version 4.0.0
*/
        class WebWalletWithdrawalsDAO extends BaseDAO{

            private $db;
            private $dbSlave;
            private $dbName = 'fns_trade_point';
            private $is_admin_confirm = false;
            private $po_type = '';

            function __construct() {
                parent::__construct();
            }
            
            public function setIsAdminConfirm($boolean){
                $this->is_admin_confirm = $boolean;
            }
            
            public function setPoType($str){
                $this->po_type = $str;
            }

            public function getViewById($od_id){
                $dto = new WebWalletWithdrawalsDTO();
                if(!$this->dbSlave) $this->dbSlave=parent::getDatabaseSlave($this->dbName);
                if($this->dbSlave){
                    try{
                        $sql = "SELECT 	od_id,
                                od_status,
                                od_status_msg,
                                mb_no,
                                is_user_confirm_yn,
                                is_user_confirm_dt,
                                is_user_confirm_ip,
                                is_admin_confirm_yn,
                                is_admin_confirm_dt,
                                od_temp_amount,
                                od_temp_currency_total,
                                od_receipt_amount,
                                od_addr,
                                od_addr_msg,
                                od_sendto,
                                od_fee,
                                od_txid,
                                po_type,
                                po_amount,
                                po_pay_yn,
                                po_pay_dt,
                                od_reg_dt,
                                od_etc1,
                                od_etc2,
                                od_etc3 
                                FROM web_wallet_withdrawals WHERE od_id=?  limit 1";

                        if($this->dbSlave) $stmt = $this->dbSlave->prepare($sql);
                        if($stmt){
                            $stmt->bindValue( 1, $od_id, PDO::PARAM_STR);
                            $stmt->execute();
                            list(
                                $dto->odId,
                                $dto->odStatus,
                                $dto->odStatusMsg,
                                $dto->mbNo,
                                $dto->isUserConfirmYn,
                                $dto->isUserConfirmDt,
                                $dto->isUserConfirmIp,
                                $dto->isAdminConfirmYn,
                                $dto->isAdminConfirmDt,
                                $dto->odTempAmount,
                                $dto->odTempCurrencyTotal,
                                $dto->odReceiptAmount,
                                $dto->odAddr,
                                $dto->odAddrMsg,
                                $dto->odSendto,
                                $dto->odFee,
                                $dto->odTxid,
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
                if(!$this->dbSlave) $this->dbSlave=parent::getDatabaseSlave($this->dbName);
                if($this->dbSlave){
                    try{
                        $singleFields = array('mb_no');
                        $sql_add = $this->getListSearchSQL($singleFields,$field,$value);
                        
                        if($this->is_admin_confirm){
                            if($sql_add){
                                $sql_add .= " AND is_admin_confirm_yn='Y' ";
                            }else{
                                $sql_add .= " WHERE is_admin_confirm_yn='Y' ";
                            }
                        }
                        
                        
                        if($this->po_type){
                            if($sql_add){
                                $sql_add .= " AND po_type='".$this->po_type."' ";
                            }else{
                                $sql_add .= " WHERE po_type='".$this->po_type."' ";
                            }
                        }
                        
                        if($svdf && $svdt){
                            if($sql_add){
                                $sql_add .= " AND ";
                            }else{
                                $sql_add .= " WHERE ";
                            }
                            $sql_add .= "(od_reg_dt BETWEEN ? AND DATE_ADD(?,INTERVAL 1 DAY) ) ";
                        }
                        
                        $sql = "SELECT count(*)  FROM web_wallet_withdrawals{$sql_add}";

                        if($this->dbSlave) $stmt = $this->dbSlave->prepare($sql);
                        if($stmt){
                            
                            $j = 1;
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
                $dto = new WebWalletWithdrawalsDTO();
                $dtoarray = array();
                if(!$this->dbSlave) $this->dbSlave=parent::getDatabaseSlave($this->dbName);
                if($this->dbSlave){
                    try{
                        $singleFields = array('mb_no');
                        $sql_add = $this->getListSearchSQL($singleFields,$field,$value);
                        
                        if($this->is_admin_confirm){
                            if($sql_add){
                                $sql_add .= " AND is_admin_confirm_yn='Y' ";
                            }else{
                                $sql_add .= " WHERE is_admin_confirm_yn='Y' ";
                            }
                        }
                        
                        if($this->po_type){
                            if($sql_add){
                                $sql_add .= " AND po_type='".$this->po_type."' ";
                            }else{
                                $sql_add .= " WHERE po_type='".$this->po_type."' ";
                            }
                        }
                        
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
                                od_status,
                                od_status_msg,
                                mb_no,
                                is_user_confirm_yn,
                                is_user_confirm_dt,
                                is_user_confirm_ip,
                                is_admin_confirm_yn,
                                is_admin_confirm_dt,
                                od_temp_amount,
                                od_temp_currency_total,
                                od_receipt_amount,
                                od_addr,
                                od_addr_msg,
                                od_sendto,
                                od_fee,
                                od_txid,
                                po_type,
                                po_amount,
                                po_pay_yn,
                                po_pay_dt,
                                od_reg_dt,
                                od_reg_cnt,
                                od_etc1,
                                od_etc2,
                                od_etc3 
                                FROM web_wallet_withdrawals{$sql_add} LIMIT ?,?";

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
				$dto->odStatus,
				$dto->odStatusMsg,
				$dto->mbNo,
				$dto->isUserConfirmYn,
				$dto->isUserConfirmDt,
				$dto->isUserConfirmIp,
				$dto->isAdminConfirmYn,
				$dto->isAdminConfirmDt,
				$dto->odTempAmount,
				$dto->odTempCurrencyTotal,
				$dto->odReceiptAmount,
				$dto->odAddr,
				$dto->odAddrMsg,
				$dto->odSendto,
				$dto->odFee,
				$dto->odTxid,
				$dto->poType,
				$dto->poAmount,
				$dto->poPayYn,
				$dto->poPayDt,
				$dto->odRegDt,
				$dto->odRegCnt,
				$dto->odEtc1,
				$dto->odEtc2,
				$dto->odEtc3) = $stmt->fetch()) {
                                    $dtoarray[$i] = new WebWalletWithdrawalsDTO();
                                    parent::setResult($i+1);
                                    $dtoarray[$i]->odId = $dto->odId;
					$dtoarray[$i]->odStatus = $dto->odStatus;
					$dtoarray[$i]->odStatusMsg = $dto->odStatusMsg;
					$dtoarray[$i]->mbNo = $dto->mbNo;
					$dtoarray[$i]->isUserConfirmYn = $dto->isUserConfirmYn;
					$dtoarray[$i]->isUserConfirmDt = $dto->isUserConfirmDt;
					$dtoarray[$i]->isUserConfirmIp = $dto->isUserConfirmIp;
					$dtoarray[$i]->isAdminConfirmYn = $dto->isAdminConfirmYn;
					$dtoarray[$i]->isAdminConfirmDt = $dto->isAdminConfirmDt;
					$dtoarray[$i]->odTempAmount = $dto->odTempAmount;
					$dtoarray[$i]->odTempCurrencyTotal = $dto->odTempCurrencyTotal;
					$dtoarray[$i]->odReceiptAmount = $dto->odReceiptAmount;
					$dtoarray[$i]->odAddr = $dto->odAddr;
					$dtoarray[$i]->odAddrMsg = $dto->odAddrMsg;
					$dtoarray[$i]->odSendto = $dto->odSendto;
					$dtoarray[$i]->odFee = $dto->odFee;
					$dtoarray[$i]->odTxid = $dto->odTxid;
					$dtoarray[$i]->poType = $dto->poType;
					$dtoarray[$i]->poAmount = $dto->poAmount;
					$dtoarray[$i]->poPayYn = $dto->poPayYn;
					$dtoarray[$i]->poPayDt = $dto->poPayDt;
					$dtoarray[$i]->odRegDt = $dto->odRegDt;
					$dtoarray[$i]->odRegCnt = $dto->odRegCnt;
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
                if(!$this->db) $this->db=parent::getDatabase($this->dbName);
                if($this->db){
                    try{
                        $sql = "INSERT INTO web_wallet_withdrawals(
                                od_status,
                                od_status_msg,
                                mb_no,
                                is_user_confirm_yn,
                                is_user_confirm_dt,
                                is_user_confirm_ip,
                                is_admin_confirm_yn,
                                is_admin_confirm_dt,
                                od_temp_amount,
                                od_temp_currency_total,
                                od_receipt_amount,
                                od_addr,
                                od_addr_msg,
                                od_sendto,
                                od_fee,
                                od_txid,
                                po_type,
                                po_amount,
                                po_pay_yn,
                                po_pay_dt,
                                od_etc1,
                                od_etc2,
                                od_etc3)
                                VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";

                        if($this->db) $stmt = $this->db->prepare($sql);
                        if($stmt){
                            $j=1;
                            
                            $stmt->bindValue( $j++, $dto->odStatus, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, $dto->odStatusMsg, ($dto->odStatusMsg)?PDO::PARAM_STR:PDO::PARAM_NULL);
                            $stmt->bindValue( $j++, $dto->mbNo, PDO::PARAM_INT);
                            $stmt->bindValue( $j++, $dto->isUserConfirmYn, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, $dto->isUserConfirmDt, ($dto->isUserConfirmDt)?PDO::PARAM_STR:PDO::PARAM_NULL);
                            $stmt->bindValue( $j++, $dto->isUserConfirmIp, ($dto->isUserConfirmIp)?PDO::PARAM_STR:PDO::PARAM_NULL);
                            $stmt->bindValue( $j++, $dto->isAdminConfirmYn, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, $dto->isAdminConfirmDt, ($dto->isAdminConfirmDt)?PDO::PARAM_STR:PDO::PARAM_NULL);
                            $stmt->bindValue( $j++, $dto->odTempAmount, ($dto->odTempAmount)?PDO::PARAM_STR:PDO::PARAM_NULL);
                            $stmt->bindValue( $j++, $dto->odTempCurrencyTotal, ($dto->odTempCurrencyTotal)?PDO::PARAM_STR:PDO::PARAM_NULL);
                            $stmt->bindValue( $j++, $dto->odReceiptAmount, ($dto->odReceiptAmount)?PDO::PARAM_STR:PDO::PARAM_NULL);
                            $stmt->bindValue( $j++, $dto->odAddr, ($dto->odAddr)?PDO::PARAM_STR:PDO::PARAM_NULL);
                            $stmt->bindValue( $j++, $dto->odAddrMsg, ($dto->odAddrMsg)?PDO::PARAM_STR:PDO::PARAM_NULL);
                            $stmt->bindValue( $j++, $dto->odSendto, ($dto->odSendto)?PDO::PARAM_STR:PDO::PARAM_NULL);
                            $stmt->bindValue( $j++, $dto->odFee, ($dto->odFee)?PDO::PARAM_STR:PDO::PARAM_NULL);
                            $stmt->bindValue( $j++, $dto->odTxid, ($dto->odTxid)?PDO::PARAM_STR:PDO::PARAM_NULL);
                            $stmt->bindValue( $j++, $dto->poType, ($dto->poType)?PDO::PARAM_STR:PDO::PARAM_NULL);
                            $stmt->bindValue( $j++, $dto->poAmount, ($dto->poAmount)?PDO::PARAM_STR:PDO::PARAM_NULL);
                            $stmt->bindValue( $j++, $dto->poPayYn, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, $dto->poPayDt, ($dto->poPayDt)?PDO::PARAM_STR:PDO::PARAM_NULL);
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
            
            public function setUpdateAdminconfirm($dto){
                parent::setResult(-1);
                if(!$this->db) $this->db=parent::getDatabase($this->dbName);
                if($this->db){

                    $sql = "UPDATE web_wallet_withdrawals SET 
                            is_admin_confirm_yn='Y',
                            is_admin_confirm_dt=now()
                            WHERE od_id=?";

                    if($this->db) $stmt = $this->db->prepare($sql);
                    if($stmt){
                        $j=1;
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
            
            
            public function setUpdateStatus($dto){
                parent::setResult(-1);
                if(!$this->db) $this->db=parent::getDatabase($this->dbName);
                if($this->db){

                    $sql = "UPDATE web_wallet_withdrawals SET 
                            od_status=?,
                            od_status_msg=?
                            WHERE od_id=?";

                    if($this->db) $stmt = $this->db->prepare($sql);
                    if($stmt){
                        $j=1;
                        $stmt->bindValue( $j++, $dto->odStatus, ($dto->odId)?PDO::PARAM_INT:PDO::PARAM_NULL);
                        $stmt->bindValue( $j++, $dto->odStatusMsg, ($dto->odId)?PDO::PARAM_INT:PDO::PARAM_NULL);
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
            
            public function setUpdateSendOK($dto){
                parent::setResult(-1);
                if(!$this->db) $this->db=parent::getDatabase($this->dbName);
                if($this->db){

                    $sql = "UPDATE web_wallet_withdrawals SET 
                            od_status='OK',
                            po_pay_yn='Y',
                            po_pay_dt=now(),
                            od_txid=?,
                            od_receipt_amount=?,
                            po_amount=?
                            WHERE od_id=?";

                    if($this->db) $stmt = $this->db->prepare($sql);
                    if($stmt){
                        $j=1;
                        $stmt->bindValue( $j++, $dto->odTxid, ($dto->odTxid)?PDO::PARAM_STR:PDO::PARAM_NULL);
                        $stmt->bindValue( $j++, $dto->odReceiptAmount, ($dto->odReceiptAmount)?PDO::PARAM_STR:PDO::PARAM_NULL);
                        $stmt->bindValue( $j++, $dto->poAmount, ($dto->poAmount)?PDO::PARAM_STR:PDO::PARAM_NULL);
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

            public function setUpdate($dto){
//                parent::setResult(-1);
//                if(!$this->db) $this->db=parent::getDatabase($this->dbName);
//                if($this->db){
//
//                    $sql = "UPDATE web_wallet_withdrawals SET 
//				od_status=?,
//				od_status_msg=?,
//				mb_no=?,
//				is_user_confirm_yn=?,
//				is_user_confirm_dt=?,
//				is_user_confirm_ip=?,
//				is_admin_confirm_yn=?,
//				is_admin_confirm_dt=?,
//				od_temp_amount=?,
//				od_temp_currency_total=?,
//				od_receipt_amount=?,
//				od_addr=?,
//				od_addr_msg=?,
//				od_sendto=?,
//				od_fee=?,
//				od_txid=?,
//				po_type=?,
//				po_amount=?,
//				po_pay_yn=?,
//				po_pay_dt=?,
//				od_etc1=?,
//				od_etc2=?,
//				od_etc3=? WHERE od_id=?";
//
//                    if($this->db) $stmt = $this->db->prepare($sql);
//                    if($stmt){
//                        $j=1;
//                        
//		$stmt->bindValue( $j++, $dto->odStatus, PDO::PARAM_STR);
//		$stmt->bindValue( $j++, $dto->odStatusMsg, ($dto->odStatusMsg)?PDO::PARAM_STR:PDO::PARAM_NULL);
//		$stmt->bindValue( $j++, $dto->mbNo, PDO::PARAM_INT);
//		$stmt->bindValue( $j++, $dto->isUserConfirmYn, PDO::PARAM_STR);
//		$stmt->bindValue( $j++, $dto->isUserConfirmDt, ($dto->isUserConfirmDt)?PDO::PARAM_STR:PDO::PARAM_NULL);
//		$stmt->bindValue( $j++, $dto->isUserConfirmIp, ($dto->isUserConfirmIp)?PDO::PARAM_STR:PDO::PARAM_NULL);
//		$stmt->bindValue( $j++, $dto->isAdminConfirmYn, PDO::PARAM_STR);
//		$stmt->bindValue( $j++, $dto->isAdminConfirmDt, ($dto->isAdminConfirmDt)?PDO::PARAM_STR:PDO::PARAM_NULL);
//		$stmt->bindValue( $j++, $dto->odTempAmount, ($dto->odTempAmount)?PDO::PARAM_STR:PDO::PARAM_NULL);
//		$stmt->bindValue( $j++, $dto->odTempCurrencyTotal, ($dto->odTempCurrencyTotal)?PDO::PARAM_STR:PDO::PARAM_NULL);
//		$stmt->bindValue( $j++, $dto->odReceiptAmount, ($dto->odReceiptAmount)?PDO::PARAM_STR:PDO::PARAM_NULL);
//		$stmt->bindValue( $j++, $dto->odAddr, ($dto->odAddr)?PDO::PARAM_STR:PDO::PARAM_NULL);
//		$stmt->bindValue( $j++, $dto->odAddrMsg, ($dto->odAddrMsg)?PDO::PARAM_STR:PDO::PARAM_NULL);
//		$stmt->bindValue( $j++, $dto->odSendto, ($dto->odSendto)?PDO::PARAM_STR:PDO::PARAM_NULL);
//		$stmt->bindValue( $j++, $dto->odFee, ($dto->odFee)?PDO::PARAM_STR:PDO::PARAM_NULL);
//		$stmt->bindValue( $j++, $dto->odTxid, ($dto->odTxid)?PDO::PARAM_STR:PDO::PARAM_NULL);
//		$stmt->bindValue( $j++, $dto->poType, ($dto->poType)?PDO::PARAM_STR:PDO::PARAM_NULL);
//		$stmt->bindValue( $j++, $dto->poAmount, ($dto->poAmount)?PDO::PARAM_STR:PDO::PARAM_NULL);
//		$stmt->bindValue( $j++, $dto->poPayYn, PDO::PARAM_STR);
//		$stmt->bindValue( $j++, $dto->poPayDt, ($dto->poPayDt)?PDO::PARAM_STR:PDO::PARAM_NULL);
//		$stmt->bindValue( $j++, $dto->odEtc1, ($dto->odEtc1)?PDO::PARAM_STR:PDO::PARAM_NULL);
//		$stmt->bindValue( $j++, $dto->odEtc2, ($dto->odEtc2)?PDO::PARAM_STR:PDO::PARAM_NULL);
//		$stmt->bindValue( $j++, $dto->odEtc3, ($dto->odEtc3)?PDO::PARAM_STR:PDO::PARAM_NULL);
//			
//		$stmt->bindValue( $j++, $dto->odId, ($dto->odId)?PDO::PARAM_INT:PDO::PARAM_NULL);
//                        $stmt->execute();
//                        if($stmt->rowCount()==1) parent::setResult(1);
//                        else parent::setResult(0);
//                    }else{
//                        parent::setResult(Error::dbPrepare);
//                    }
//                }else{
//                    parent::setResult(Error::dbConn);
//                }
//                return parent::getResult();
            }

            function deleteFromPri($pri){
//                parent::setResult(-1);
//                if(!$this->db) $this->db=parent::getDatabase($this->dbName);
//                if($this->db){
//
//                    $sql = "DELETE FROM web_wallet_withdrawals WHERE od_id=?";
//
//                    if($this->db) $stmt = $this->db->prepare($sql);
//                    if($stmt){
//                        $stmt->bindValue( 1, $pri, PDO::PARAM_STR);
//                        $stmt->execute();
//                        if($stmt->rowCount()>0) parent::setResult($stmt->rowCount());
//                        else parent::setResult(0);
//                    }else{
//                        parent::setResult(Error::dbPrepare);
//                    }
//                }else{
//                    parent::setResult(Error::dbConn);
//                }
//                return parent::getResult();
            }

            function __destruct(){
                $this->db = NULL;
                $this->dto = NULL;
            }
        }