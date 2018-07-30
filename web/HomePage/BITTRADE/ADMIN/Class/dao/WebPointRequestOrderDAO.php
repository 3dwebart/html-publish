<?php      
/**
* Description of WebPointRequestOrderDAO
* @description Funhansoft PHP auto templet
* @date 2015-07-02
* @copyright (c)funhansoft.com
* @license http://funhansoft.com/license
* @version 4.0.0
*/
        class WebPointRequestOrderDAO extends BaseDAO{

            private $db;
            private $dbSlave;

            function __construct() {
                parent::__construct();
            }

            public function getViewById($od_id){
                $dto = new WebPointRequestOrderDTO();
                if(!$this->dbSlave) $this->dbSlave=parent::getDatabaseSlave();
                if($this->dbSlave){
                    try{
                        $sql = "SELECT
                                    od_id,
                                    od_name,
                                    od_action,
                                    od_pay_status,
                                    od_pay_status_msg,
                                    mb_no,
                                    mb_id,
                                    is_user_confirm_yn,
                                    is_user_confirm_dt,
                                    is_user_confirm_ip,
                                    is_admin_confirm_yn,
                                    is_admin_confirm_dt,
                                    od_request_coin,
                                    od_request_krw,
                                    od_receipt_coin,
                                    od_receipt_krw,
                                    od_total_fee,
                                    od_bank_account,
                                    od_bank_name,
                                    od_bank_holder,
                                    od_btc_account,
                                    od_btc_comment,
                                    od_btc_sendto,
                                    od_del_yn,
                                    od_reg_dt,
                                    od_reg_ip,
                                    od_cnt,
                                    partner
                                FROM web_point_request_order WHERE od_id=?  limit 1";

                        if($this->dbSlave) $stmt = $this->dbSlave->prepare($sql);
                        if($stmt){
                            $stmt->bindValue( 1, $od_id, PDO::PARAM_STR);
                            $stmt->execute();
                            list(
                                $dto->odId,
                                $dto->odName,
                                $dto->odAction,
                                $dto->odPayStatus,
                                $dto->odPayStatusMsg,
                                $dto->mbNo,
                                $dto->mbId,
                                $dto->isUserConfirmYn,
                                $dto->isUserConfirmDt,
                                $dto->isUserConfirmIp,
                                $dto->isAdminConfirmYn,
                                $dto->isAdminConfirmDt,
                                $dto->odRequestCoin,
                                $dto->odRequestKrw,
                                $dto->odReceiptCoin,
                                $dto->odReceiptKrw,
                                $dto->odTotalFee,
                                $dto->odBankAccount,
                                $dto->odBankName,
                                $dto->odBankHolder,
                                $dto->odBtcAccount,
                                $dto->odBtcComment,
                                $dto->odBtcSendto,
                                $dto->odDelYn,
                                $dto->odRegDt,
                                $dto->odRegIp,
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

            public function getListCount($field='',$value='',$svdf='',$svdt=''){
                $dto = new CommonListDTO();
                if(!$this->dbSlave) $this->dbSlave=parent::getDatabaseSlave();
                if($this->dbSlave){
                    try{
                        $singleFields = array('od_id','od_action','od_pay_status','mb_no','is_user_confirm_yn','is_admin_confirm_yn','od_request_krw','od_receipt_krw','od_del_yn');
                        $sql_add = $this->getListSearchSQL($singleFields,$field,$value);
                        if($svdf && $svdt){
                            if($sql_add){
                                $sql_add .= " AND ";
                            }else{
                                $sql_add .= " WHERE ";
                            }
                            $sql_add .= "(od_reg_dt BETWEEN ? AND DATE_ADD(?,INTERVAL 1 DAY) ) ";
                        }
                        $sql = "SELECT COUNT(*)  FROM web_point_request_order{$sql_add}";

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
                $dto = new WebPointRequestOrderDTO();
                $dtoarray = array();
                if(!$this->dbSlave) $this->dbSlave=parent::getDatabaseSlave();
                if($this->dbSlave){
                    try{
                        $singleFields = array('od_id','od_action','od_pay_status','mb_no','is_user_confirm_yn','is_admin_confirm_yn','od_request_krw','od_receipt_krw','od_del_yn');
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
                                    od_name,
                                    od_action,
                                    od_pay_status,
                                    od_pay_status_msg,
                                    mb_no,
                                    mb_id,
                                    is_user_confirm_yn,
                                    is_user_confirm_dt,
                                    is_user_confirm_ip,
                                    is_admin_confirm_yn,
                                    is_admin_confirm_dt,
                                    od_request_coin,
                                    od_request_krw,
                                    od_receipt_coin,
                                    od_receipt_krw,
                                    od_total_fee,
                                    od_bank_account,
                                    od_bank_name,
                                    od_bank_holder,
                                    od_btc_account,
                                    od_btc_comment,
                                    od_btc_sendto,
                                    od_del_yn,
                                    od_reg_dt,
                                    od_reg_ip,
                                    od_cnt,
                                    partner
                                FROM web_point_request_order{$sql_add} LIMIT ?,?";

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
                                $dto->odName,
                                $dto->odAction,
                                $dto->odPayStatus,
                                $dto->odPayStatusMsg,
                                $dto->mbNo,
                                $dto->mbId,
                                $dto->isUserConfirmYn,
                                $dto->isUserConfirmDt,
                                $dto->isUserConfirmIp,
                                $dto->isAdminConfirmYn,
                                $dto->isAdminConfirmDt,
                                $dto->odRequestCoin,
                                $dto->odRequestKrw,
                                $dto->odReceiptCoin,
                                $dto->odReceiptKrw,
                                $dto->odTotalFee,
                                $dto->odBankAccount,
                                $dto->odBankName,
                                $dto->odBankHolder,
                                $dto->odBtcAccount,
                                $dto->odBtcComment,
                                $dto->odBtcSendto,
                                $dto->odDelYn,
                                $dto->odRegDt,
                                $dto->odRegIp,
                                $dto->odCnt,
                                $dto->partner) = $stmt->fetch()) {
                                    $dtoarray[$i] = new WebPointRequestOrderDTO();
                                    parent::setResult($i+1);
                                    $dtoarray[$i]->odId = $dto->odId;
                                    $dtoarray[$i]->odName = $dto->odName;
                                    $dtoarray[$i]->odAction = $dto->odAction;
                                    $dtoarray[$i]->odPayStatus = $dto->odPayStatus;
                                    $dtoarray[$i]->odPayStatusMsg = $dto->odPayStatusMsg;
                                    $dtoarray[$i]->mbNo = $dto->mbNo;
                                    $dtoarray[$i]->mbId = $dto->mbId;
                                    $dtoarray[$i]->isUserConfirmYn = $dto->isUserConfirmYn;
                                    $dtoarray[$i]->isUserConfirmDt = $dto->isUserConfirmDt;
                                    $dtoarray[$i]->isUserConfirmIp = $dto->isUserConfirmIp;
                                    $dtoarray[$i]->isAdminConfirmYn = $dto->isAdminConfirmYn;
                                    $dtoarray[$i]->isAdminConfirmDt = $dto->isAdminConfirmDt;
                                    $dtoarray[$i]->odRequestCoin = $dto->odRequestCoin;
                                    $dtoarray[$i]->odRequestKrw = $dto->odRequestKrw;
                                    $dtoarray[$i]->odReceiptCoin = $dto->odReceiptCoin;
                                    $dtoarray[$i]->odReceiptKrw = $dto->odReceiptKrw;
                                    $dtoarray[$i]->odTotalFee = $dto->odTotalFee;
                                    $dtoarray[$i]->odBankAccount = $dto->odBankAccount;
                                    $dtoarray[$i]->odBankName = $dto->odBankName;
                                    $dtoarray[$i]->odBankHolder = $dto->odBankHolder;
                                    $dtoarray[$i]->odBtcAccount = $dto->odBtcAccount;
                                    $dtoarray[$i]->odBtcComment = $dto->odBtcComment;
                                    $dtoarray[$i]->odBtcSendto = $dto->odBtcSendto;
                                    $dtoarray[$i]->odDelYn = $dto->odDelYn;
                                    $dtoarray[$i]->odRegDt = $dto->odRegDt;
                                    $dtoarray[$i]->odRegIp = $dto->odRegIp;
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
                if(!$this->db) $this->db=parent::getDatabase();
                if($this->db){
                    try{
                        $sql = "INSERT INTO web_point_request_order
                                (
                                    od_name,
                                    od_action,
                                    od_pay_status,
                                    od_pay_status_msg,
                                    mb_no,
                                    mb_id,
                                    is_user_confirm_yn,
                                    is_user_confirm_dt,
                                    is_user_confirm_ip,
                                    is_admin_confirm_yn,
                                    is_admin_confirm_dt,
                                    od_request_coin,
                                    od_request_krw,
                                    od_receipt_coin,
                                    od_receipt_krw,
                                    od_total_fee,
                                    od_bank_account,
                                    od_bank_name,
                                    od_bank_holder,
                                    od_btc_account,
                                    od_btc_comment,
                                    od_btc_sendto,
                                    od_del_yn,
                                    od_reg_ip,
                                    od_cnt,
                                    partner
                                )
                                VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
                        if($this->db) $stmt = $this->db->prepare($sql);
                        if($stmt){
                            $j=1;
                            $stmt->bindValue( $j++, $dto->odName, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, $dto->odAction, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, $dto->odPayStatus, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, $dto->odPayStatusMsg, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, $dto->mbNo, PDO::PARAM_INT);
                            $stmt->bindValue( $j++, $dto->mbId, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, $dto->isUserConfirmYn, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, $dto->isUserConfirmDt, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, $dto->isUserConfirmIp, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, $dto->isAdminConfirmYn, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, $dto->isAdminConfirmDt, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, $dto->odRequestCoin, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, $dto->odRequestKrw, PDO::PARAM_INT);
                            $stmt->bindValue( $j++, $dto->odReceiptCoin, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, $dto->odReceiptKrw, PDO::PARAM_INT);
                            $stmt->bindValue( $j++, $dto->odTotalFee, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, $dto->odBankAccount, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, $dto->odBankName, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, $dto->odBankHolder, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, $dto->odBtcAccount, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, $dto->odBtcComment, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, $dto->odBtcSendto, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, $dto->odDelYn, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, $dto->odRegIp, PDO::PARAM_STR);
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
                if(!$this->db) $this->db=parent::getDatabase();
                if($this->db){

                    $sql = "UPDATE web_point_request_order SET
                                od_name=?,
                                od_action=?,
                                od_pay_status=?,
                                od_pay_status_msg=?,
                                mb_no=?,
                                mb_id=?,
                                is_user_confirm_yn=?,
                                is_user_confirm_dt=?,
                                is_user_confirm_ip=?,
                                is_admin_confirm_yn=?,
                                is_admin_confirm_dt=?,
                                od_request_coin=?,
                                od_request_krw=?,
                                od_receipt_coin=?,
                                od_receipt_krw=?,
                                od_total_fee=?,
                                od_bank_account=?,
                                od_bank_name=?,
                                od_bank_holder=?,
                                od_btc_account=?,
                                od_btc_comment=?,
                                od_btc_sendto=?,
                                od_del_yn=?,
                                od_reg_ip=?,
                                od_cnt=?,
                                partner=?
                            WHERE od_id=?";

                    if($this->db) $stmt = $this->db->prepare($sql);
                    if($stmt){
                        $j=1;

                        $stmt->bindValue( $j++, $dto->odName, PDO::PARAM_STR);
                        $stmt->bindValue( $j++, $dto->odAction, PDO::PARAM_STR);
                        $stmt->bindValue( $j++, $dto->odPayStatus, PDO::PARAM_STR);
                        $stmt->bindValue( $j++, $dto->odPayStatusMsg, PDO::PARAM_STR);
                        $stmt->bindValue( $j++, $dto->mbNo, PDO::PARAM_INT);
                        $stmt->bindValue( $j++, $dto->mbId, PDO::PARAM_STR);
                        $stmt->bindValue( $j++, $dto->isUserConfirmYn, PDO::PARAM_STR);
                        $stmt->bindValue( $j++, $dto->isUserConfirmDt, PDO::PARAM_STR);
                        $stmt->bindValue( $j++, $dto->isUserConfirmIp, PDO::PARAM_STR);
                        $stmt->bindValue( $j++, $dto->isAdminConfirmYn, PDO::PARAM_STR);
                        $stmt->bindValue( $j++, $dto->isAdminConfirmDt, PDO::PARAM_STR);
                        $stmt->bindValue( $j++, $dto->odRequestCoin, PDO::PARAM_STR);
                        $stmt->bindValue( $j++, $dto->odRequestKrw, PDO::PARAM_INT);
                        $stmt->bindValue( $j++, $dto->odReceiptCoin, PDO::PARAM_STR);
                        $stmt->bindValue( $j++, $dto->odReceiptKrw, PDO::PARAM_INT);
                        $stmt->bindValue( $j++, $dto->odTotalFee, PDO::PARAM_STR);
                        $stmt->bindValue( $j++, $dto->odBankAccount, PDO::PARAM_STR);
                        $stmt->bindValue( $j++, $dto->odBankName, PDO::PARAM_STR);
                        $stmt->bindValue( $j++, $dto->odBankHolder, PDO::PARAM_STR);
                        $stmt->bindValue( $j++, $dto->odBtcAccount, PDO::PARAM_STR);
                        $stmt->bindValue( $j++, $dto->odBtcComment, PDO::PARAM_STR);
                        $stmt->bindValue( $j++, $dto->odBtcSendto, PDO::PARAM_STR);
                        $stmt->bindValue( $j++, $dto->odDelYn, PDO::PARAM_STR);
                        $stmt->bindValue( $j++, $dto->odRegIp, PDO::PARAM_STR);
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
                if(!$this->db) $this->db=parent::getDatabase();
                if($this->db){

                    $sql = "DELETE FROM web_point_request_order WHERE od_id=?";

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