<?php      
/**
* Description of WebCashDepositsDAO
* @description Funhansoft PHP auto templet
* @date 2015-09-20
* @copyright (c)funhansoft.com
* @license http://funhansoft.com/license
* @version 0.2.2
*/
        class WebCashDepositsDAO extends BaseDAO{

            private $db;
            private $dbSlave;
            private $dbName = 'fns_trade_point';

            function __construct() {
                parent::__construct();
                
            }

            public function getViewById($od_id){
                $dto = new WebCashDepositsDTO();
                if(!$this->db) $this->db=parent::getDatabaseTrade($this->dbName); if($this->db){
                    try{
                        $sql = "SELECT
                                    od_id,
                                    od_pg_id,
                                    it_id,
                                    it_id_pay,
                                    mb_no,
                                    mb_id,
                                    od_pwd,
                                    od_name,
                                    od_email,
                                    od_tel,
                                    od_hp,
                                    od_memo,
                                    od_temp_bank,
                                    od_temp_card,
                                    od_temp_mobile,
                                    od_temp_ars,
                                    od_temp_phonebill,
                                    od_receipt_bank,
                                    od_receipt_card,
                                    od_receipt_mobile,
                                    od_receipt_ars,
                                    od_receipt_phonebill,
                                    od_bank_time,
                                    od_card_time,
                                    od_pay_time,
                                    od_cancel_card,
                                    od_dc_amount,
                                    od_refund_amount,
                                    od_refund_dt,
                                    od_shop_memo,
                                    od_modify_history,
                                    od_deposit_name,
                                    od_bank_account,
                                    od_hope_date,
                                    od_datetime,
                                    od_ip,
                                    od_settle_case,
                                    od_del_yn,
                                    od_scno,
                                    od_etc,
                                    od_escrow1,
                                    od_escrow2,
                                    od_escrow3,
                                    od_proc_db,
                                    od_cnt,
                                    partner
                                FROM web_cash_deposits WHERE od_id=?  limit 1";

                        if($this->db) $stmt = $this->db->prepare($sql);
                        if($stmt){
                            $stmt->bindValue( 1, $od_id, PDO::PARAM_STR);
                            $stmt->execute();
                            list(
                                $dto->odId,
                                $dto->odPgId,
                                $dto->itId,
                                $dto->itIdPay,
                                $dto->mbNo,
                                $dto->mbId,
                                $dto->odPwd,
                                $dto->odName,
                                $dto->odEmail,
                                $dto->odTel,
                                $dto->odHp,
                                $dto->odMemo,
                                $dto->odTempBank,
                                $dto->odTempCard,
                                $dto->odTempMobile,
                                $dto->odTempArs,
                                $dto->odTempPhonebill,
                                $dto->odReceiptBank,
                                $dto->odReceiptCard,
                                $dto->odReceiptMobile,
                                $dto->odReceiptArs,
                                $dto->odReceiptPhonebill,
                                $dto->odBankTime,
                                $dto->odCardTime,
                                $dto->odPayTime,
                                $dto->odCancelCard,
                                $dto->odDcAmount,
                                $dto->odRefundAmount,
                                $dto->odRefundDt,
                                $dto->odShopMemo,
                                $dto->odModifyHistory,
                                $dto->odDepositName,
                                $dto->odBankAccount,
                                $dto->odHopeDate,
                                $dto->odDatetime,
                                $dto->odIp,
                                $dto->odSettleCase,
                                $dto->odDelYn,
                                $dto->odScno,
                                $dto->odEtc,
                                $dto->odEscrow1,
                                $dto->odEscrow2,
                                $dto->odEscrow3,
                                $dto->odProcDb,
                                $dto->odCnt,
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
            
            public function getBalance($mb_no){
            	$dto = new WebCashDepositsDTO();
            	if(!$this->db) $this->db=parent::getDatabaseTrade($this->dbName); if($this->db){
                    try{
                        $sql = "SELECT
                            SUM(od_temp_bank) AS od_temp_bank
                        FROM web_cash_deposits WHERE mb_no=? AND it_id_pay='Y'";

                        if($this->db) $stmt = $this->db->prepare($sql);
                        if($stmt){
                            $stmt->bindValue( 1, $mb_no, PDO::PARAM_INT);
                            $stmt->execute();
                            list(
                                            $dto->odTempBank

                            ) = $stmt->fetch();
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
                            if($field=='od_id'||$field=='it_id'||$field=='it_id_pay'||$field=='mb_no'||$field=='od_temp_bank'||$field=='od_temp_card'||$field=='od_temp_mobile'||$field=='od_temp_ars'||$field=='od_temp_phonebill'||$field=='od_receipt_bank'||$field=='od_receipt_card'||$field=='od_receipt_mobile'||$field=='od_receipt_ars'||$field=='od_receipt_phonebill'||$field=='od_cancel_card'||$field=='od_dc_amount'||$field=='od_refund_amount'||$field=='od_settle_case'||$field=='od_del_yn'||$field=='od_proc_db'){ $sql_add = " WHERE {$field}=?"; }
                        }
                        if($svdf && $svdt){
                            if($sql_add){
                                $sql_add .= " AND ";
                            }else{
                                $sql_add .= " WHERE ";
                            }
                            $sql_add .= "(od_datetime BETWEEN ? AND DATE_ADD(?,INTERVAL 1 DAY) ) ";
                        }
                        $sql = "SELECT COUNT(*)  FROM web_cash_deposits{$sql_add}";

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
                $dto = new WebCashDepositsDTO();
                $dtoarray = array();          
                if(!$this->db) $this->db=parent::getDatabaseTrade($this->dbName); if($this->db){
                    try{
                        $sql_add = '';
                        if($value){
                            $sql_add = " WHERE {$field} LIKE CONCAT('%',?,'%')";
                            if($field=='od_id'||$field=='it_id'||$field=='it_id_pay'||$field=='mb_no'||$field=='od_temp_bank'||$field=='od_temp_card'||$field=='od_temp_mobile'||$field=='od_temp_ars'||$field=='od_temp_phonebill'||$field=='od_receipt_bank'||$field=='od_receipt_card'||$field=='od_receipt_mobile'||$field=='od_receipt_ars'||$field=='od_receipt_phonebill'||$field=='od_cancel_card'||$field=='od_dc_amount'||$field=='od_refund_amount'||$field=='od_settle_case'||$field=='od_del_yn'||$field=='od_proc_db'){ $sql_add = " WHERE {$field}=?"; }
                        }
                        if($svdf && $svdt){
                            if($sql_add){
                                $sql_add .= " AND ";
                            }else{
                                $sql_add .= " WHERE ";
                            }
                            $sql_add .= "(od_datetime BETWEEN ? AND DATE_ADD(?,INTERVAL 1 DAY) ) ";
                        }
                        $sql = "SELECT
                                    od_id,
                                    od_pg_id,
                                    it_id,
                                    it_name,
                                    it_id_pay,
                                    mb_no,
                                    mb_id,
                                    od_pwd,
                                    od_name,
                                    od_email,
                                    od_tel,
                                    od_hp,
                                    LEFT(od_memo,256),
                                    od_temp_bank,
                                    od_temp_card,
                                    od_temp_mobile,
                                    od_temp_ars,
                                    od_temp_phonebill,
                                    od_receipt_bank,
                                    od_receipt_card,
                                    od_receipt_mobile,
                                    od_receipt_ars,
                                    od_receipt_phonebill,
                                    od_bank_time,
                                    od_card_time,
                                    od_pay_time,
                                    od_cancel_card,
                                    od_dc_amount,
                                    od_refund_amount,
                                    od_refund_dt,
                                    LEFT(od_shop_memo,256),
                                    LEFT(od_modify_history,256),
                                    od_deposit_name,
                                    od_bank_account,
                                    od_hope_date,
                                    od_datetime,
                                    od_ip,
                                    od_settle_case,
                                    od_del_yn,
                                    od_scno,
                                    od_etc,
                                    od_escrow1,
                                    od_escrow2,
                                    od_escrow3,
                                    od_proc_db,
                                    od_cnt,
                                    partner
                                FROM web_cash_deposits{$sql_add} ORDER BY od_id DESC LIMIT ?,?";

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
                                $dto->odPgId,
                                $dto->itId,
                                $dto->itName,
                                $dto->itIdPay,
                                $dto->mbNo,
                                $dto->mbId,
                                $dto->odPwd,
                                $dto->odName,
                                $dto->odEmail,
                                $dto->odTel,
                                $dto->odHp,
                                $dto->odMemo,
                                $dto->odTempBank,
                                $dto->odTempCard,
                                $dto->odTempMobile,
                                $dto->odTempArs,
                                $dto->odTempPhonebill,
                                $dto->odReceiptBank,
                                $dto->odReceiptCard,
                                $dto->odReceiptMobile,
                                $dto->odReceiptArs,
                                $dto->odReceiptPhonebill,
                                $dto->odBankTime,
                                $dto->odCardTime,
                                $dto->odPayTime,
                                $dto->odCancelCard,
                                $dto->odDcAmount,
                                $dto->odRefundAmount,
                                $dto->odRefundDt,
                                $dto->odShopMemo,
                                $dto->odModifyHistory,
                                $dto->odDepositName,
                                $dto->odBankAccount,
                                $dto->odHopeDate,
                                $dto->odDatetime,
                                $dto->odIp,
                                $dto->odSettleCase,
                                $dto->odDelYn,
                                $dto->odScno,
                                $dto->odEtc,
                                $dto->odEscrow1,
                                $dto->odEscrow2,
                                $dto->odEscrow3,
                                $dto->odProcDb,
                                $dto->odCnt,
                                $dto->partner) = $stmt->fetch()) {
                                    $dtoarray[$i] = new WebCashDepositsDTO();
                                    parent::setResult($i+1);
                                    $dtoarray[$i]->odId = $dto->odId;
                                    $dtoarray[$i]->odPgId = $dto->odPgId;
                                    $dtoarray[$i]->itId = $dto->itId;
                                    $dtoarray[$i]->itName = $dto->itName;
                                    $dtoarray[$i]->itIdPay = $dto->itIdPay;
                                    $dtoarray[$i]->mbNo = $dto->mbNo;
                                    $dtoarray[$i]->mbId = $dto->mbId;
                                    $dtoarray[$i]->odPwd = $dto->odPwd;
                                    $dtoarray[$i]->odName = $dto->odName;
                                    $dtoarray[$i]->odEmail = $dto->odEmail;
                                    $dtoarray[$i]->odTel = $dto->odTel;
                                    $dtoarray[$i]->odHp = $dto->odHp;
                                    $dtoarray[$i]->odMemo = $dto->odMemo;
                                    $dtoarray[$i]->odTempBank = $dto->odTempBank;
                                    $dtoarray[$i]->odTempCard = $dto->odTempCard;
                                    $dtoarray[$i]->odTempMobile = $dto->odTempMobile;
                                    $dtoarray[$i]->odTempArs = $dto->odTempArs;
                                    $dtoarray[$i]->odTempPhonebill = $dto->odTempPhonebill;
                                    $dtoarray[$i]->odTempTotal = $dto->odTempBank + $dto->odTempCard +
                                    $dto->odTempMobile + $dto->odTempArs + 
                                    $dto->odTempPhonebill;
                                    $dtoarray[$i]->odReceiptBank = $dto->odReceiptBank;
                                    $dtoarray[$i]->odReceiptCard = $dto->odReceiptCard;
                                    $dtoarray[$i]->odReceiptMobile = $dto->odReceiptMobile;
                                    $dtoarray[$i]->odReceiptArs = $dto->odReceiptArs;
                                    $dtoarray[$i]->odReceiptPhonebill = $dto->odReceiptPhonebill;
                                    $dtoarray[$i]->odReceiptTotal = $dto->odReceiptBank + $dto->odReceiptCard +
                                    $dto->odReceiptMobile + $dto->odReceiptArs + 
                                    $dto->odReceiptPhonebill;

                                    $dtoarray[$i]->odBankTime = $dto->odBankTime;
                                    $dtoarray[$i]->odCardTime = $dto->odCardTime;
                                    $dtoarray[$i]->odPayTime = $dto->odPayTime;
                                    $dtoarray[$i]->odCancelCard = $dto->odCancelCard;
                                    $dtoarray[$i]->odDcAmount = $dto->odDcAmount;
                                    $dtoarray[$i]->odRefundAmount = $dto->odRefundAmount;
                                    $dtoarray[$i]->odRefundDt = $dto->odRefundDt;
                                    $dtoarray[$i]->odShopMemo = $dto->odShopMemo;
                                    $dtoarray[$i]->odModifyHistory = $dto->odModifyHistory;
                                    $dtoarray[$i]->odDepositName = $dto->odDepositName;
                                    $dtoarray[$i]->odBankAccount = $dto->odBankAccount;
                                    $dtoarray[$i]->odHopeDate = $dto->odHopeDate;
                                    $dtoarray[$i]->odDatetime = $dto->odDatetime;
                                    $dtoarray[$i]->odIp = $dto->odIp;
                                    $dtoarray[$i]->odSettleCase = $dto->odSettleCase;
                                    $dtoarray[$i]->odDelYn = $dto->odDelYn;
                                    $dtoarray[$i]->odScno = $dto->odScno;
                                    $dtoarray[$i]->odEtc = $dto->odEtc;
                                    $dtoarray[$i]->odEscrow1 = $dto->odEscrow1;
                                    $dtoarray[$i]->odEscrow2 = $dto->odEscrow2;
                                    $dtoarray[$i]->odEscrow3 = $dto->odEscrow3;
                                    $dtoarray[$i]->odProcDb = $dto->odProcDb;
                                    $dtoarray[$i]->odCnt = $dto->odCnt;
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
                if(!$this->db) $this->db=parent::getDatabase(); if($this->db){
                    try{
                        $sql = "INSERT INTO web_cash_deposits
                                (
                                    od_pg_id,
                                    it_id,
                                    it_id_pay,
                                    mb_no,
                                    mb_id,
                                    od_pwd,
                                    od_name,
                                    od_email,
                                    od_tel,
                                    od_hp,
                                    od_memo,
                                    od_temp_bank,
                                    od_temp_card,
                                    od_temp_mobile,
                                    od_temp_ars,
                                    od_temp_phonebill,
                                    od_receipt_bank,
                                    od_receipt_card,
                                    od_receipt_mobile,
                                    od_receipt_ars,
                                    od_receipt_phonebill,
                                    od_bank_time,
                                    od_card_time,
                                    od_pay_time,
                                    od_cancel_card,
                                    od_dc_amount,
                                    od_refund_amount,
                                    od_refund_dt,
                                    od_shop_memo,
                                    od_modify_history,
                                    od_deposit_name,
                                    od_bank_account,
                                    od_hope_date,
                                    od_ip,
                                    od_settle_case,
                                    od_del_yn,
                                    od_scno,
                                    od_etc,
                                    od_escrow1,
                                    od_escrow2,
                                    od_escrow3,
                                    od_proc_db,
                                    od_cnt,
                                    partner
                                )
                                VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";

                        if($this->db) $stmt = $this->db->prepare($sql);
                        if($stmt){
                            $j=1;
                            $stmt->bindValue( $j++, $dto->odPgId, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, $dto->itId, PDO::PARAM_INT);
                            $stmt->bindValue( $j++, $dto->itIdPay, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, $dto->mbNo, PDO::PARAM_INT);
                            $stmt->bindValue( $j++, $dto->mbId, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, $dto->odPwd, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, $dto->odName, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, $dto->odEmail, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, $dto->odTel, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, $dto->odHp, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, $dto->odMemo, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, $dto->odTempBank, PDO::PARAM_INT);
                            $stmt->bindValue( $j++, $dto->odTempCard, PDO::PARAM_INT);
                            $stmt->bindValue( $j++, $dto->odTempMobile, PDO::PARAM_INT);
                            $stmt->bindValue( $j++, $dto->odTempArs, PDO::PARAM_INT);
                            $stmt->bindValue( $j++, $dto->odTempPhonebill, PDO::PARAM_INT);
                            $stmt->bindValue( $j++, $dto->odReceiptBank, PDO::PARAM_INT);
                            $stmt->bindValue( $j++, $dto->odReceiptCard, PDO::PARAM_INT);
                            $stmt->bindValue( $j++, $dto->odReceiptMobile, PDO::PARAM_INT);
                            $stmt->bindValue( $j++, $dto->odReceiptArs, PDO::PARAM_INT);
                            $stmt->bindValue( $j++, $dto->odReceiptPhonebill, PDO::PARAM_INT);
                            $stmt->bindValue( $j++, $dto->odBankTime, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, $dto->odCardTime, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, $dto->odPayTime, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, $dto->odCancelCard, PDO::PARAM_INT);
                            $stmt->bindValue( $j++, $dto->odDcAmount, PDO::PARAM_INT);
                            $stmt->bindValue( $j++, $dto->odRefundAmount, PDO::PARAM_INT);
                            $stmt->bindValue( $j++, $dto->odRefundDt, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, $dto->odShopMemo, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, $dto->odModifyHistory, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, $dto->odDepositName, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, $dto->odBankAccount, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, $dto->odHopeDate, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, $dto->odIp, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, $dto->odSettleCase, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, $dto->odDelYn, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, $dto->odScno, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, $dto->odEtc, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, $dto->odEscrow1, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, $dto->odEscrow2, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, $dto->odEscrow3, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, $dto->odProcDb, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, $dto->odCnt, PDO::PARAM_INT);
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
                if(!$this->db) $this->db=parent::getDatabase(); if($this->db){

                    $sql = "UPDATE web_cash_deposits SET
                                od_pg_id=?,
                                it_id=?,
                                it_id_pay=?,
                                mb_no=?,
                                mb_id=?,
                                od_pwd=?,
                                od_name=?,
                                od_email=?,
                                od_tel=?,
                                od_hp=?,
                                od_memo=?,
                                od_temp_bank=?,
                                od_temp_card=?,
                                od_temp_mobile=?,
                                od_temp_ars=?,
                                od_temp_phonebill=?,
                                od_receipt_bank=?,
                                od_receipt_card=?,
                                od_receipt_mobile=?,
                                od_receipt_ars=?,
                                od_receipt_phonebill=?,
                                od_bank_time=?,
                                od_card_time=?,
                                od_pay_time=?,
                                od_cancel_card=?,
                                od_dc_amount=?,
                                od_refund_amount=?,
                                od_refund_dt=?,
                                od_shop_memo=?,
                                od_modify_history=?,
                                od_deposit_name=?,
                                od_bank_account=?,
                                od_hope_date=?,
                                od_ip=?,
                                od_settle_case=?,
                                od_del_yn=?,
                                od_scno=?,
                                od_etc=?,
                                od_escrow1=?,
                                od_escrow2=?,
                                od_escrow3=?,
                                od_proc_db=?,
                                od_cnt=?,
                                partner=?
                            WHERE od_id=?";

                    if($this->db) $stmt = $this->db->prepare($sql);
                    if($stmt){
                        $j=1;
                        $stmt->bindValue( $j++, $dto->odPgId, PDO::PARAM_STR);
                        $stmt->bindValue( $j++, $dto->itId, PDO::PARAM_INT);
                        $stmt->bindValue( $j++, $dto->itIdPay, PDO::PARAM_STR);
                        $stmt->bindValue( $j++, $dto->mbNo, PDO::PARAM_INT);
                        $stmt->bindValue( $j++, $dto->mbId, PDO::PARAM_STR);
                        $stmt->bindValue( $j++, $dto->odPwd, PDO::PARAM_STR);
                        $stmt->bindValue( $j++, $dto->odName, PDO::PARAM_STR);
                        $stmt->bindValue( $j++, $dto->odEmail, PDO::PARAM_STR);
                        $stmt->bindValue( $j++, $dto->odTel, PDO::PARAM_STR);
                        $stmt->bindValue( $j++, $dto->odHp, PDO::PARAM_STR);
                        $stmt->bindValue( $j++, $dto->odMemo, PDO::PARAM_STR);
                        $stmt->bindValue( $j++, $dto->odTempBank, PDO::PARAM_INT);
                        $stmt->bindValue( $j++, $dto->odTempCard, PDO::PARAM_INT);
                        $stmt->bindValue( $j++, $dto->odTempMobile, PDO::PARAM_INT);
                        $stmt->bindValue( $j++, $dto->odTempArs, PDO::PARAM_INT);
                        $stmt->bindValue( $j++, $dto->odTempPhonebill, PDO::PARAM_INT);
                        $stmt->bindValue( $j++, $dto->odReceiptBank, PDO::PARAM_INT);
                        $stmt->bindValue( $j++, $dto->odReceiptCard, PDO::PARAM_INT);
                        $stmt->bindValue( $j++, $dto->odReceiptMobile, PDO::PARAM_INT);
                        $stmt->bindValue( $j++, $dto->odReceiptArs, PDO::PARAM_INT);
                        $stmt->bindValue( $j++, $dto->odReceiptPhonebill, PDO::PARAM_INT);
                        $stmt->bindValue( $j++, $dto->odBankTime, PDO::PARAM_STR);
                        $stmt->bindValue( $j++, $dto->odCardTime, PDO::PARAM_STR);
                        $stmt->bindValue( $j++, $dto->odPayTime, PDO::PARAM_STR);
                        $stmt->bindValue( $j++, $dto->odCancelCard, PDO::PARAM_INT);
                        $stmt->bindValue( $j++, $dto->odDcAmount, PDO::PARAM_INT);
                        $stmt->bindValue( $j++, $dto->odRefundAmount, PDO::PARAM_INT);
                        $stmt->bindValue( $j++, $dto->odRefundDt, PDO::PARAM_STR);
                        $stmt->bindValue( $j++, $dto->odShopMemo, PDO::PARAM_STR);
                        $stmt->bindValue( $j++, $dto->odModifyHistory, PDO::PARAM_STR);
                        $stmt->bindValue( $j++, $dto->odDepositName, PDO::PARAM_STR);
                        $stmt->bindValue( $j++, $dto->odBankAccount, PDO::PARAM_STR);
                        $stmt->bindValue( $j++, $dto->odHopeDate, PDO::PARAM_STR);
                        $stmt->bindValue( $j++, $dto->odIp, PDO::PARAM_STR);
                        $stmt->bindValue( $j++, $dto->odSettleCase, PDO::PARAM_STR);
                        $stmt->bindValue( $j++, $dto->odDelYn, PDO::PARAM_STR);
                        $stmt->bindValue( $j++, $dto->odScno, PDO::PARAM_STR);
                        $stmt->bindValue( $j++, $dto->odEtc, PDO::PARAM_STR);
                        $stmt->bindValue( $j++, $dto->odEscrow1, PDO::PARAM_STR);
                        $stmt->bindValue( $j++, $dto->odEscrow2, PDO::PARAM_STR);
                        $stmt->bindValue( $j++, $dto->odEscrow3, PDO::PARAM_STR);
                        $stmt->bindValue( $j++, $dto->odProcDb, PDO::PARAM_STR);
                        $stmt->bindValue( $j++, $dto->odCnt, PDO::PARAM_INT);
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
                if(!$this->db) $this->db=parent::getDatabase(); if($this->db){

                    $sql = "DELETE FROM web_cash_deposits WHERE od_id=?";

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