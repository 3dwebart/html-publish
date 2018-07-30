<?php      
/**
* Description of WebCashWithdrawalsDAO
* @description Funhansoft PHP auto templet
* @date 2015-09-20
* @copyright (c)funhansoft.com
* @license http://funhansoft.com/license
* @version 0.2.2
*/
        class WebCashWithdrawalsDAO extends BaseDAO{

            private $db;
            private $dbSlave;
            private $dbName = 'fns_trade_point';

            function __construct() {
                parent::__construct();
                
            }

            public function getViewById($od_id){
                $dto = new WebCashWithdrawalsDTO();
                if(!$this->db) $this->db=parent::getDatabaseTrade($this->dbName); if($this->db){
                    try{
                        $sql = "SELECT
                                    od_id,
                                    od_status,
                                    od_status_msg,
                                    mb_no,
                                    mk_no,
                                    mb_id,
                                    od_name,
                                    is_user_confirm_yn,
                                    is_user_confirm_dt,
                                    is_user_confirm_ip,
                                    is_admin_confirm_yn,
                                    is_admin_confirm_dt,
                                    od_temp_amount,
                                    od_receipt_amount,
                                    od_fee,
                                    od_bank_account,
                                    od_bank_name,
                                    od_bank_holder,
                                    po_pay_yn,
                                    po_pay_dt,
                                    od_reg_dt,
                                    od_reg_cnt,
                                    od_reg_ip,
                                    od_etc1,
                                    od_etc2,
                                    od_etc3
                                FROM web_cash_withdraw WHERE od_id=?  limit 1";

                        if($this->db) $stmt = $this->db->prepare($sql);
                        if($stmt){
                            $stmt->bindValue( 1, $od_id, PDO::PARAM_STR);
                            $stmt->execute();
                            list(
                                $dto->odId,
                                $dto->odStatus,
                                $dto->odStatusMsg,
                                $dto->mbNo,
                                $dto->mkNo,
                                $dto->mbId,
                                $dto->odName,
                                $dto->isUserConfirmYn,
                                $dto->isUserConfirmDt,
                                $dto->isUserConfirmIp,
                                $dto->isAdminConfirmYn,
                                $dto->isAdminConfirmDt,
                                $dto->odTempAmount,
                                $dto->odReceiptAmount,
                                $dto->odFee,
                                $dto->odBankAccount,
                                $dto->odBankName,
                                $dto->odBankHolder,
                                $dto->poPayYn,
                                $dto->poPayDt,
                                $dto->odRegDt,
                                $dto->odRegCnt,
                                $dto->odRegIp,
                                $dto->odEtc1,
                                $dto->odEtc2,
                                $dto->odEtc3) = $stmt->fetch();
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
                if(!$this->db) $this->db=parent::getDatabaseTrade($this->dbName); if($this->db){
                    try{
                        $sql_add = '';
                        if($value){
                            $sql_add = " WHERE {$field} LIKE CONCAT('%',?,'%')";
                            if($field=='od_id'||$field=='mb_no'||$field=='mb_id'||$field=='od_name'||$field=='od_bank_account'||$field=='od_bank_name'||$field=='od_bank_holder'){ $sql_add = " WHERE {$field}=?"; }
                        }
                        if($svdf && $svdt){
                            if($sql_add){
                                $sql_add .= " AND ";
                            }else{
                                $sql_add .= " WHERE ";
                            }
                            $sql_add .= "(od_reg_dt BETWEEN ? AND DATE_ADD(?,INTERVAL 1 DAY) ) ";
                        }
                        $sql = "SELECT COUNT(*)  FROM web_cash_withdraw{$sql_add}";

                        if($this->db) $stmt = $this->db->prepare($sql);
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
                $dto = new WebCashWithdrawalsDTO();
                $dtoarray = array();          
                if(!$this->db) $this->db=parent::getDatabaseTrade($this->dbName); if($this->db){
                    try{
                        $sql_add = '';
                        if($value){
                            $sql_add = " WHERE {$field} LIKE CONCAT('%',?,'%')";
                            if($field=='od_id'||$field=='mb_no'||$field=='mb_id'||$field=='od_name'||$field=='od_bank_account'||$field=='od_bank_name'||$field=='od_bank_holder'){ $sql_add = " WHERE {$field}=?"; }
                        }
                        if($svdf && $svdt){
                            if($sql_add){
                                $sql_add .= " AND ";
                            }else{
                                $sql_add .= " WHERE ";
                            }
                            $sql_add .= "(od_reg_dt BETWEEN ? AND DATE_ADD(?,INTERVAL 1 DAY) ) ";
                        }
                        $sql = "SELECT
                                    od_id,
                                    od_status,
                                    od_status_msg,
                                    mb_no,
                                    mk_no,
                                    mb_id,
                                    od_name,
                                    is_user_confirm_yn,
                                    is_user_confirm_dt,
                                    is_user_confirm_ip,
                                    is_admin_confirm_yn,
                                    is_admin_confirm_dt,
                                    od_temp_amount,
                                    od_receipt_amount,
                                    od_fee,
                                    od_bank_account,
                                    od_bank_name,
                                    od_bank_holder,
                                    po_pay_yn,
                                    po_pay_dt,
                                    od_reg_dt,
                                    od_reg_cnt,
                                    od_reg_ip,
                                    od_etc1,
                                    od_etc2,
                                    od_etc3
                                FROM web_cash_withdraw{$sql_add} ORDER BY od_id DESC LIMIT ?,?";

                        if($this->db) $stmt = $this->db->prepare($sql);
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
                                $dto->mkNo,
                                $dto->mbId,
                                $dto->odName,
                                $dto->isUserConfirmYn,
                                $dto->isUserConfirmDt,
                                $dto->isUserConfirmIp,
                                $dto->isAdminConfirmYn,
                                $dto->isAdminConfirmDt,
                                $dto->odTempAmount,
                                $dto->odReceiptAmount,
                                $dto->odFee,
                                $dto->odBankAccount,
                                $dto->odBankName,
                                $dto->odBankHolder,
                                $dto->poPayYn,
                                $dto->poPayDt,
                                $dto->odRegDt,
                                $dto->odRegCnt,
                                $dto->odRegIp,
                                $dto->odEtc1,
                                $dto->odEtc2,
                                $dto->odEtc3) = $stmt->fetch()) {
                                    $dtoarray[$i] = new WebCashWithdrawalsDTO();
                                    parent::setResult($i+1);
                                    $dtoarray[$i]->odId = $dto->odId;
                                    $dtoarray[$i]->odStatus = $dto->odStatus;
                                    $dtoarray[$i]->odStatusMsg = $dto->odStatusMsg;
                                    $dtoarray[$i]->odName = $dto->odName;
                                    $dtoarray[$i]->mbNo = $dto->mbNo;
                                    $dtoarray[$i]->mkNo = $dto->mkNo;
                                    $dtoarray[$i]->mbId = $dto->mbId;
                                    $dtoarray[$i]->odName = $dto->odName;
                                    $dtoarray[$i]->isUserConfirmYn = $dto->isUserConfirmYn;
                                    $dtoarray[$i]->isUserConfirmDt = $dto->isUserConfirmDt;
                                    $dtoarray[$i]->isUserConfirmIp = $dto->isUserConfirmIp;
                                    $dtoarray[$i]->isAdminConfirmYn = $dto->isAdminConfirmYn;
                                    $dtoarray[$i]->isAdminConfirmDt = $dto->isAdminConfirmDt;
                                    $dtoarray[$i]->odTempAmount = $dto->odTempAmount;
                                    $dtoarray[$i]->odReceiptAmount = $dto->odReceiptAmount;
                                    $dtoarray[$i]->odFee = $dto->odFee;
                                    $dtoarray[$i]->odBankAccount = $dto->odBankAccount;
                                    $dtoarray[$i]->odBankName = $dto->odBankName;
                                    $dtoarray[$i]->odBankHolder = $dto->odBankHolder;
                                    $dtoarray[$i]->poPayYn = $dto->poPayYn;
                                    $dtoarray[$i]->poPayDt = $dto->poPayDt;
                                    $dtoarray[$i]->odRegDt = $dto->odRegDt;
                                    $dtoarray[$i]->odRegCnt = $dto->odRegCnt;
                                    $dtoarray[$i]->odRegIp = $dto->odRegIp;
                                    $dtoarray[$i]->odEtc1 = $dto->odEtc1;
                                    $dtoarray[$i]->odEtc2 = $dto->odEtc2;
                                    $dtoarray[$i]->odEtc3 = $dto->odEtc3;
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
                        $sql = "INSERT INTO web_cash_withdraw
                                (
                                    od_status,
                                    od_status_msg,
                                    mb_no,
                                    mk_no,
                                    mb_id,
                                    od_name,
                                    is_user_confirm_yn,
                                    is_user_confirm_dt,
                                    is_user_confirm_ip,
                                    is_admin_confirm_yn,
                                    is_admin_confirm_dt,
                                    od_temp_amount,
                                    od_receipt_amount,
                                    od_fee,
                                    od_bank_account,
                                    od_bank_name,
                                    od_bank_holder,
                                    po_pay_yn,
                                    po_pay_dt,
                                    od_reg_dt,
                                    od_reg_cnt,
                                    od_reg_ip,
                                    od_etc1,
                                    od_etc2,
                                    od_etc3
                                )
                                VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";

                        if($this->db) $stmt = $this->db->prepare($sql);
                        if($stmt){
                            $j=1;
                            $stmt->bindValue( $j++, $dto->odStatus, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, $dto->odStatusMsg, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, $dto->mbNo, PDO::PARAM_INT);
                            $stmt->bindValue( $j++, $dto->mkNo, PDO::PARAM_INT);
                            $stmt->bindValue( $j++, $dto->mbId, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, $dto->odName, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, $dto->isUserConfirmYn, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, $dto->isUserConfirmDt, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, $dto->isUserConfirmIp, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, $dto->isAdminConfirmYn, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, $dto->isAdminConfirmDt, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, $dto->odTempAmount, PDO::PARAM_INT);
                            $stmt->bindValue( $j++, $dto->odReceiptAmount, PDO::PARAM_INT);
                            $stmt->bindValue( $j++, $dto->odFee, PDO::PARAM_INT);
                            $stmt->bindValue( $j++, $dto->odBankAccount, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, $dto->odBankName, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, $dto->odBankHolder, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, $dto->poPayYn, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, $dto->poPayDt, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, $dto->odRegDt, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, $dto->odRegCnt, PDO::PARAM_INT);
                            $stmt->bindValue( $j++, $dto->odRegIp, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, $dto->odEtc1, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, $dto->odEtc2, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, $dto->odEtc3, PDO::PARAM_STR);
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
            
            public function setUpdateAdminconfirm($dto){
                parent::setResult(-1);
                if(!$this->db) $this->db=parent::getDatabase();
                if($this->db){

                    $sql = "UPDATE web_cash_withdraw SET 
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
                if(!$this->db) $this->db=parent::getDatabase();
                if($this->db){

                    $sql = "UPDATE web_cash_withdraw SET 
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

            public function setUpdate($dto){
//                parent::setResult(-1);
//                if(!$this->db) $this->db=parent::getDatabase(); if($this->db){
//
//                    $sql = "UPDATE web_cash_withdraw SET
//                                od_status=?,
//                                od_status_msg=?,
//                                mb_no=?,
//                                mk_no=?,
//                                mb_id=?,
//                                od_name=?,
//                                is_user_confirm_yn=?,
//                                is_user_confirm_dt=?,
//                                is_user_confirm_ip=?,
//                                is_admin_confirm_yn=?,
//                                is_admin_confirm_dt=?,
//                                od_temp_amount=?,
//                                od_receipt_amount=?,
//                                od_fee=?,
//                                od_bank_account=?,
//                                od_bank_name=?,
//                                od_bank_holder=?,
//                                po_pay_yn=?,
//                                po_pay_dt=?,
//                                od_etc1=?,
//                                od_etc2=?,
//                                od_etc3=?
//                            WHERE od_id=?";
//
//                    if($this->db) $stmt = $this->db->prepare($sql);
//                    if($stmt){
//                        $j=1;
//                        $stmt->bindValue( $j++, $dto->odStatus, PDO::PARAM_STR);
//                        $stmt->bindValue( $j++, $dto->odStatusMsg, PDO::PARAM_STR);
//                        $stmt->bindValue( $j++, $dto->mbNo, PDO::PARAM_INT);
//                        $stmt->bindValue( $j++, $dto->mkNo, PDO::PARAM_INT);
//                        $stmt->bindValue( $j++, $dto->mbId, PDO::PARAM_STR);
//                        $stmt->bindValue( $j++, $dto->odName, PDO::PARAM_STR);
//                        $stmt->bindValue( $j++, $dto->isUserConfirmYn, PDO::PARAM_STR);
//                        $stmt->bindValue( $j++, $dto->isUserConfirmDt, PDO::PARAM_STR);
//                        $stmt->bindValue( $j++, $dto->isUserConfirmIp, PDO::PARAM_STR);
//                        $stmt->bindValue( $j++, $dto->isAdminConfirmYn, PDO::PARAM_STR);
//                        $stmt->bindValue( $j++, $dto->isAdminConfirmDt, PDO::PARAM_STR);
//                        $stmt->bindValue( $j++, $dto->odTempAmount, PDO::PARAM_INT);
//                        $stmt->bindValue( $j++, $dto->odReceiptAmount, PDO::PARAM_INT);
//                        $stmt->bindValue( $j++, $dto->odFee, PDO::PARAM_INT);
//                        $stmt->bindValue( $j++, $dto->odBankAccount, PDO::PARAM_STR);
//                        $stmt->bindValue( $j++, $dto->odBankName, PDO::PARAM_STR);
//                        $stmt->bindValue( $j++, $dto->odBankHolder, PDO::PARAM_STR);
//                        $stmt->bindValue( $j++, $dto->poPayYn, PDO::PARAM_STR);
//                        $stmt->bindValue( $j++, $dto->poPayDt, PDO::PARAM_STR);
//                        $stmt->bindValue( $j++, $dto->odEtc1, PDO::PARAM_STR);
//                        $stmt->bindValue( $j++, $dto->odEtc2, PDO::PARAM_STR);
//                        $stmt->bindValue( $j++, $dto->odEtc3, PDO::PARAM_STR);
//                        $stmt->execute();
//                        if($stmt->rowCount()==1) parent::setResult(1);
//                        else parent::setResult(0);
//                    }else{
//                        parent::setResult(ResError::dbPrepare);
//                    }
//                }else{
//                    parent::setResult(ResError::dbConn);
//                }
//                return parent::getResult();
            }

            function deleteFromPri($pri){
//                parent::setResult(-1);
//                if(!$this->db) $this->db=parent::getDatabase(); if($this->db){
//
//                    $sql = "DELETE FROM web_cash_withdraw WHERE od_id=?";
//
//                    if($this->db) $stmt = $this->db->prepare($sql);
//                    if($stmt){
//                        $stmt->bindValue( 1, $pri, PDO::PARAM_STR);
//                        $stmt->execute();
//                        if($stmt->rowCount()>0) parent::setResult($stmt->rowCount());
//                        else parent::setResult(0);
//                    }else{
//                        parent::setResult(ResError::dbPrepare);
//                    }
//                }else{
//                    parent::setResult(ResError::dbConn);
//                }
//                return parent::getResult();
            }

            function __destruct(){
                $this->db = NULL;
                $this->dto = NULL;
            }
        }