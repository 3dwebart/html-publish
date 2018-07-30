<?php
/**
* Description of ViewShopOrderDAO
* @description Funhansoft PHP auto templet
* @date 2014-01-10
* @copyright (c)funhansoft.com
* @license http://funhansoft.com/license
* @version 4.0.0
*/
        class ViewShopOrderDAO extends BaseDAO{

            private $db;
            private $dbSlave;

            function __construct() {
                parent::__construct();
            }

            public function getViewById($od_id){
                $dto = new ViewShopOrderDTO();
                if(!$this->dbSlave) $this->dbSlave=parent::getDatabaseSlave();
                if($this->dbSlave){
                    try{
                        $sql = "SELECT 	od_id,
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
				partner,
				od_temp_total,
				od_receipt_total
                                FROM view_shop_order WHERE od_id=?  limit 1";

                        if($this->dbSlave) $stmt = $this->dbSlave->prepare($sql);
                        if($stmt){
                            $stmt->bindValue( 1, $od_id, PDO::PARAM_STR);
                            $stmt->execute();
                            list(
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
				$dto->partner,
				$dto->odTempTotal,
				$dto->odReceiptTotal) = $stmt->fetch();
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
                if(!$this->dbSlave) $this->dbSlave=parent::getDatabaseSlave();
                if($this->dbSlave){
                    try{
                        $singleFields = array('od_id','it_id','it_id_pay','mb_no','od_temp_bank','od_temp_card','od_temp_mobile','od_temp_ars','od_temp_phonebill','od_receipt_bank','od_receipt_card','od_receipt_mobile','od_receipt_ars','od_receipt_phonebill','od_cancel_card','od_dc_amount','od_refund_amount','od_settle_case','od_del_yn','od_proc_db','od_temp_total','od_receipt_total');
                        $sql_add = $this->getListSearchSQL($singleFields,$field,$value);
                        if($svdf && $svdt){
                            if($sql_add){
                                $sql_add .= " AND ";
                            }else{
                                $sql_add .= " WHERE ";
                            }
                            $sql_add .= "(od_datetime BETWEEN ? AND DATE_ADD(?,INTERVAL 1 DAY) ) ";
                        }

                        if($sql_add) $sql_add.=" and od_temp_total=od_receipt_total ";
                        else  $sql_add.=" WHERE od_temp_total=od_receipt_total ";

                        $sql = "SELECT count(*)  FROM view_shop_order{$sql_add}";
                    
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
                $dto = new ViewShopOrderDTO();
                $dtoarray = array();
                if(!$this->dbSlave) $this->dbSlave=parent::getDatabaseSlave();
                if($this->dbSlave){
                    try{
                        $singleFields = array('od_id','it_id','it_id_pay','mb_no','od_temp_bank','od_temp_card','od_temp_mobile','od_temp_ars','od_temp_phonebill','od_receipt_bank','od_receipt_card','od_receipt_mobile','od_receipt_ars','od_receipt_phonebill','od_cancel_card','od_dc_amount','od_refund_amount','od_settle_case','od_del_yn','od_proc_db','od_temp_total','od_receipt_total');
                        $sql_add = $this->getListSearchSQL($singleFields,$field,$value);
                        if($svdf && $svdt){
                            if($sql_add){
                                $sql_add .= " AND ";
                            }else{
                                $sql_add .= " WHERE ";
                            }
                            $sql_add .= "(od_datetime BETWEEN ? AND DATE_ADD(?,INTERVAL 1 DAY) ) ";
                        }
                        if($sql_add) $sql_add.=" and od_temp_total=od_receipt_total ";
                        else  $sql_add.=" WHERE od_temp_total=od_receipt_total ";

                        $sql_add .= $this->getListOrderSQL($singleFields,'od_id');

                        $sql = "SELECT 	od_id,
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
                                    partner,
                                    od_temp_total,
                                    od_receipt_total
                                FROM view_shop_order{$sql_add} LIMIT ?,?";

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
                                $dto->partner,
                                $dto->odTempTotal,
                                $dto->odReceiptTotal) = $stmt->fetch()) {
                                    $dtoarray[$i] = new ViewShopOrderDTO();
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
                                    $dtoarray[$i]->odReceiptBank = $dto->odReceiptBank;
                                    $dtoarray[$i]->odReceiptCard = $dto->odReceiptCard;
                                    $dtoarray[$i]->odReceiptMobile = $dto->odReceiptMobile;
                                    $dtoarray[$i]->odReceiptArs = $dto->odReceiptArs;
                                    $dtoarray[$i]->odReceiptPhonebill = $dto->odReceiptPhonebill;
                                    $dtoarray[$i]->odBankTime = $dto->odBankTime;
                                    $dtoarray[$i]->odCardTime = $dto->odCardTime;
                                    $dtoarray[$i]->odPayTime = $dto->odPayTime;
                                    $dtoarray[$i]->odCancelCard = (int)$dto->odCancelCard;
                                    $dtoarray[$i]->odDcAmount = (int)$dto->odDcAmount;
                                    $dtoarray[$i]->odRefundAmount = (int)$dto->odRefundAmount;
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
                                    $dtoarray[$i]->odTempTotal = $dto->odTempTotal;
                                    $dtoarray[$i]->odReceiptTotal = $dto->odReceiptTotal;
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

            function __destruct(){
                $this->db = NULL;
                $this->dto = NULL;
            }
        }