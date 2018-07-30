<?php
/**
* Description of WebMemberDAO
* @description Funhansoft PHP auto templet
* @date 2013-09-06
* @copyright (c)funhansoft.com
* @license http://funhansoft.com/license
* @version 0.2.1
*/
        class WebMemberApiKeyDAO extends BaseDAO{

            private $db;
            private $dbSlave;

            function __construct() {
                parent::__construct();

            }

            public function getViewById($mb_no){
                $dto = new WebMemberApiKeyDTO();
                if(!$this->dbSlave) $this->dbSlave=parent::getDatabaseSlave();
                if($this->dbSlave){
                    try{
                        $sql = "SELECT 	
                                mk_no,
                                mb_no,
                                mb_id,
                                mk_api_key,
                                mk_wallet_yn,
                                mk_trade_yn,
                                mk_etc,
                                mk_access_ips,
                                mk_expire_dt,
                                mk_reg_ip,
                                mk_up_ip,
                                mk_reg_dt
                                FROM web_member_api_key WHERE mb_no=?  limit 1";

                        if($this->dbSlave) $stmt = $this->dbSlave->prepare($sql);
                        if($stmt){
                            $stmt->bindValue( 1, $mb_no, PDO::PARAM_STR);
                            $stmt->execute();
                            list(
                                $dto->mkNo,
                                $dto->mbNo,
                                $dto->mbId,
                                $dto->mkApiKey,
                                $dto->mkWalletYn,
                                $dto->mkTradeYn,
                                $dto->mkEtc,
                                $dto->mkAccessIps,
                                $dto->mkExpireDt,
                                $dto->mkRegIp,
                                $dto->mkUpIp,
                                $dto->mkRegDt) = $stmt->fetch();
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

            public function getViewByMbId($mb_id){
                $dto = new WebMemberApiKeyDTO();
                if(!$this->dbSlave) $this->dbSlave=parent::getDatabaseSlave(); if($this->dbSlave){
                    try{
                        $sql = "SELECT 	
                                mk_no,
                                mb_no,
                                mb_id,
                                mk_api_key,
                                mk_wallet_yn,
                                mk_trade_yn,
                                mk_etc,
                                mk_access_ips,
                                mk_expire_dt,
                                mk_reg_ip,
                                mk_up_ip,
                                mk_reg_dt
                                FROM web_member_api_key WHERE mb_no=?  limit 1";

                        if($this->dbSlave) $stmt = $this->dbSlave->prepare($sql);
                        if($stmt){
                            $stmt->bindValue( 1, $mb_id, PDO::PARAM_STR);
                            $stmt->execute();
                            list(
                                $dto->mkNo,
                                $dto->mbNo,
                                $dto->mbId,
                                $dto->mkApiKey,
                                $dto->mkWalletYn,
                                $dto->mkTradeYn,
                                $dto->mkEtc,
                                $dto->mkAccessIps,
                                $dto->mkExpireDt,
                                $dto->mkRegIp,
                                $dto->mkUpIp,
                                $dto->mkRegDt) = $stmt->fetch();
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
                if(!$this->dbSlave) $this->dbSlave=parent::getDatabaseSlave(); if($this->dbSlave){
                    try{
                        $sql_add = '';
                        if($value){
                            $sql_add = " WHERE {$field} LIKE CONCAT('%',?,'%')";
                            if($field=='mb_no'){ $sql_add = " WHERE {$field}=?"; }
                        }
                        if($svdf && $svdt){
                            if($sql_add){
                                $sql_add .= " AND ";
                            }else{
                                $sql_add .= " WHERE ";
                            }
                            $sql_add .= "(mb_reg_dt BETWEEN ? AND DATE_ADD(?,INTERVAL 1 DAY) ) ";
                        }
                        $sql = "SELECT count(*)  FROM web_member_api_key{$sql_add}";

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
                $dto = new WebMemberApiKeyDTO();
                $dtoarray = array();
                if(!$this->dbSlave) $this->dbSlave=parent::getDatabaseSlave(); 
                if($this->dbSlave){
                    try{
                        $sql_add = '';
                        if($value){
                            $sql_add = " WHERE {$field} LIKE CONCAT('%',?,'%')";
                            if($field=='mb_no'){ $sql_add = " WHERE {$field}=?"; }
                        }
                        if($svdf && $svdt){
                            if($sql_add){
                                $sql_add .= " AND ";
                            }else{
                                $sql_add .= " WHERE ";
                            }
                            $sql_add .= "(mk_reg_dt BETWEEN ? AND DATE_ADD(?,INTERVAL 1 DAY) ) ";
                        }
                        $sql = "SELECT 
                                mk_no,
                                mb_no,
                                mb_id,
                                mk_api_key,
                                mk_wallet_yn,
                                mk_trade_yn,
                                mk_etc,
                                mk_access_ips,
                                mk_expire_dt,
                                mk_reg_ip,
                                mk_up_ip,
                                mk_reg_dt
                                FROM web_member_api_key{$sql_add} ORDER BY mb_no DESC LIMIT ?,?";

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
                                $dto->mkNo,
                                $dto->mbNo,
                                $dto->mbId,
                                $dto->mkApiKey,
                                $dto->mkWalletYn,
                                $dto->mkTradeYn,
                                $dto->mkEtc,
                                $dto->mkAccessIps,
                                $dto->mkExpireDt,
                                $dto->mkRegIp,
                                $dto->mkUpIp,
                                $dto->mkRegDt
                                ) = $stmt->fetch()) {
                                    $dtoarray[$i] = new WebMemberApiKeyDTO();
                                    parent::setResult($i+1);
                                    $dtoarray[$i]->mkNo = $dto->mkNo;
                                    $dtoarray[$i]->mbNo = $dto->mbNo;
                                    $dtoarray[$i]->mbId = $dto->mbId;
                                    $dtoarray[$i]->mkApiKey = $dto->mkApiKey;
                                    $dtoarray[$i]->mkWalletYn = $dto->mkWalletYn;
                                    $dtoarray[$i]->mkTradeYn = $dto->mkTradeYn;
                                    $dtoarray[$i]->mkEtc = $dto->mkEtc;
                                    $dtoarray[$i]->mkAccessIps = $dto->mkAccessIps;
                                    $dtoarray[$i]->mkExpireDt = $dto->mkExpireDt;
                                    $dtoarray[$i]->mkRegIp = $dto->mkRegIp;
                                    $dtoarray[$i]->mkUpIp = $dto->mkUpIp;
                                    $dtoarray[$i]->mkRegDt = $dto->mkRegDt;
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
            
            //  입출금 금액 추가
            public function getListExtend($field='',$value=''){
                $balanceDAO = new TradeMemberPointBalanceDAO();
                $volumeDAO = new WebMemberTotalVolumeDAO();
                
                $dto = new WebMemberApiKeyDTO();
                $dtoarray = array();
                if(!$this->dbSlave) $this->dbSlave=parent::getDatabaseSlave(); if($this->dbSlave){
                    try{
                        $sql_add = '';
                        if($value){
                            $sql_add = " WHERE {$field} LIKE CONCAT('%',?,'%')";
                            if($field=='mb_no'){ $sql_add = " WHERE {$field}=?"; }
                        }
                        $sql = "SELECT
                                    mb_no,
                                    mb_id,
                                    mb_name,
                                    mb_last_name,
                                    mb_first_name,
                                    mb_nick,
                                    mb_level,
                                    mb_gender,
                                    mb_birth,
                                    mb_email,
                                    mb_country_dial_code,
                                    mb_hp,
                                    mb_certificate,
                                    contry_code,
                                    mb_reg_ip,
                                    mb_reg_dt,
                                    mb_del_yn
                                FROM web_member wm {$sql_add} ORDER BY mb_no DESC LIMIT ?,?";

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
                                $dto->mbNo,
                                $dto->mbId,
                                $dto->mbName,
                                $dto->mbLastName,
                                $dto->mbFirstName,
                                $dto->mbNick,
                                $dto->mbLevel,
                                $dto->mbGender,
                                $dto->mbBirth,
                                $dto->mbEmail,
                                $dto->mbCountryDialCode,
                                $dto->mbHp,
                                $dto->mbCertificate,
                                $dto->contryCode,
                                $dto->mbRegIp,
                                $dto->mbRegDt,
                                $dto->mbDelYn
                                ) = $stmt->fetch()) {
                                    $dtoarray[$i] = new WebMemberApiKeyDTO();
                                    parent::setResult($i+1);
                                    $dtoarray[$i]->mbNo = $dto->mbNo;
                                    $dtoarray[$i]->mbId = $dto->mbId;
                                    $dtoarray[$i]->mbName = $dto->mbName;
                                    $dtoarray[$i]->mbFirstName = $dto->mbFirstName;
                                    $dtoarray[$i]->mbLastName = $dto->mbLastName;
                                    $dtoarray[$i]->mbNick = $dto->mbNick;
                                    $dtoarray[$i]->mbLevel = $dto->mbLevel;
                                    $dtoarray[$i]->mbGender = $dto->mbGender;
                                    $dtoarray[$i]->mbBirth = $dto->mbBirth;
                                    $dtoarray[$i]->mbEmail = $dto->mbEmail;
                                    $dtoarray[$i]->mbCountryDialCode = $dto->mbCountryDialCode;
                                    $dtoarray[$i]->mbHp = $dto->mbHp;
                                    $dtoarray[$i]->mbCertificate = $dto->mbCertificate;
                                    $dtoarray[$i]->contryCode = $dto->contryCode;
                                    $dtoarray[$i]->mbRegIp = $dto->mbRegIp;
                                    $dtoarray[$i]->mbRegDt = $dto->mbRegDt;
                                    $dtoarray[$i]->mbDelYn = $dto->mbDelYn;
                                    
                                    $balance = $balanceDAO->getMemberBalance($dto->mbNo);
                                    $dtoarray[$i]->balance = $balance;
                                    $volume = $volumeDAO->getViewById($dto->mbNo);
                                    $dtoarray[$i]->volume = $volume;
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

            public function getListByKey($field='',$value=''){
                $dto = new WebMemberApiKeyDTO();
                $dtoarray = array();
                if(!$this->dbSlave) $this->dbSlave=parent::getDatabaseSlave(); if($this->dbSlave){
                    try{
                        $sql_add = '';
                        if($value){
                            $sql_add = " WHERE {$field}=?";
                        }
                        $sql = "SELECT
                                    mb_no,
                                    mb_id,
                                    mb_name,
                                    mb_last_name,
                                    mb_first_name,
                                    mb_nick,
                                    mb_key,
                                    mb_level,
                                    mb_gender,
                                    mb_birth,
                                    mb_email,
                                    mb_country_dial_code,
                                    mb_hp,
                                    mb_password_q,
                                    mb_password_a,
                                    mb_certificate,
                                    mb_agent,
                                    contry_code,
                                    mb_reg_ip,
                                    mb_reg_dt,
                                    mb_up_dt,
                                    mb_del_yn,
                                    mb_logind_alert,
                                    mb_admin_memo
                                FROM web_member{$sql_add} ORDER BY mb_no DESC LIMIT ?,?";

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
                                $dto->mbNo,
                                $dto->mbId,
                                $dto->mbName,
                                $dto->mbLastName,
                                $dto->mbFristName,
                                $dto->mbNick,
                                $dto->mbKey,
                                $dto->mbLevel,
                                $dto->mbGender,
                                $dto->mbBirth,
                                $dto->mbEmail,
                                $dto->mbCountryDialCode,
                                $dto->mbHp,
                                $dto->mbPasswordQ,
                                $dto->mbPasswordA,
                                $dto->mbCertificate,
                                $dto->mbAgent,
                                $dto->contryCode,
                                $dto->mbRegIp,
                                $dto->mbRegDt,
                                $dto->mbUpDt,
                                $dto->mbDelYn,
                                $dto->mbLogindAlert,
                                $dto->mbAdminMemo) = $stmt->fetch()) {
                                    $dtoarray[$i] = new WebMemberApiKeyDTO();
                                    parent::setResult($i+1);
                                    $dtoarray[$i]->mbNo = $dto->mbNo;
                                    $dtoarray[$i]->mbId = $dto->mbId;
                                    $dtoarray[$i]->mbName = $dto->mbName;
                                    $dtoarray[$i]->mbLastName = $dto->mbLastName;
                                    $dtoarray[$i]->mbFirstName = $dto->mbFirstName;
                                    $dtoarray[$i]->mbNick = $dto->mbNick;
                                    $dtoarray[$i]->mbKey = $dto->mbKey;
                                    $dtoarray[$i]->mbLevel = $dto->mbLevel;
                                    $dtoarray[$i]->mbGender = $dto->mbGender;
                                    $dtoarray[$i]->mbBirth = $dto->mbBirth;
                                    $dtoarray[$i]->mbEmail = $dto->mbEmail;
                                    $dtoarray[$i]->mbCountryDialCode = $dto->mbCountryDialCode;
                                    $dtoarray[$i]->mbHp = $dto->mbHp;
                                    $dtoarray[$i]->mbPasswordQ = $dto->mbPasswordQ;
                                    $dtoarray[$i]->mbPasswordA = $dto->mbPasswordA;
                                    $dtoarray[$i]->mbCertificate = $dto->mbCertificate;
                                    $dtoarray[$i]->mbAgent = $dto->mbAgent;
                                    $dtoarray[$i]->contryCode = $dto->contryCode;
                                    $dtoarray[$i]->mbRegIp = $dto->mbRegIp;
                                    $dtoarray[$i]->mbRegDt = $dto->mbRegDt;
                                    $dtoarray[$i]->mbUpDt = $dto->mbUpDt;
                                    $dtoarray[$i]->mbDelYn = $dto->mbDelYn;
                                    $dtoarray[$i]->mbLogindAlert = $dto->mbLogindAlert;
                                    $dtoarray[$i]->mbAdminMemo = $dto->mbAdminMemo;
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
                        $sql = "INSERT INTO web_member_api_key
                                (
                                    mk_no,
                                    mb_no,
                                    mb_id,
                                    mk_api_key,
                                    mk_wallet_yn,
                                    mk_trade_yn,
                                    mk_etc,
                                    mk_access_ips,
                                    mk_expire_dt,
                                    mk_reg_ip,
                                    mk_up_ip,
                                    mk_reg_dt
                                )
                                VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
                        


                        if($this->db) $stmt = $this->db->prepare($sql);
                        if($stmt){
                            $j=1;
                            $stmt->bindValue( $j++, $dto->mkNo, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, $dto->mbNo, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, $dto->mbId, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, $dto->mkApiKey, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, $dto->mkWalletYn, PDO::PARAM_STR);
//                            $stmt->bindValue( $j++, (int)$dto->mbLevel, PDO::PARAM_INT);
                            $stmt->bindValue( $j++, $dto->mkTradeYn, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, $dto->mkEtc, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, $dto->mkAccessIps, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, $dto->mkExpireDt, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, $dto->mkRegIp, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, $dto->mkUpIp, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, $dto->mkRegDt, PDO::PARAM_STR);
//                            $stmt->bindValue( $j++, $dto->mk_reg_dt, PDO::PARAM_STR);
//                            $stmt->bindValue( $j++, $dto->contryCode, PDO::PARAM_STR);
//                            $stmt->bindValue( $j++, $dto->mbRegIp, PDO::PARAM_STR);
//                            $stmt->bindValue( $j++, $dto->mbDelYn, PDO::PARAM_STR);
//                            $stmt->bindValue( $j++, $dto->mbLogindAlert, PDO::PARAM_STR);
//                            $stmt->bindValue( $j++, $dto->mbAdminMemo, PDO::PARAM_STR);
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

                    $sql = "UPDATE web_member SET
                                mb_id=?,
                                mb_name=?,
                                mb_last_name=?,
                                mb_first_name=?,
                                mb_nick=?,
                                mb_nick_dt=?,
                                mb_key=?,
                                mb_level=?,
                                mb_gender=?,
                                mb_birth=?,
                                mb_email=?,
                                mb_country_dial_code=?,
                                mb_hp=?,
                                mb_password_q=?,
                                mb_password_a=?,
                                mb_certificate=?,
                                mb_agent=?,
                                contry_code=?,
                                mb_reg_ip=?,
                                mb_up_dt=?,
                                mb_del_yn=?,
                                mb_logind_alert=?,
                                mb_admin_memo=? WHERE mb_no=?";

                    if($this->db) $stmt = $this->db->prepare($sql);
                    if($stmt){
                        $j=1;
                        $name = '';
                        if($dto->contryCode=='KR' || $dto->contryCode=='KP'){
                            $name = $dto->mbLastName.$dto->mbFirstName;
                        }else if($dto->contryCode=='JP' || $dto->contryCode=='CN'){
                            $name = $dto->mbLastName.' '.$dto->mbFirstName;
                        }else{
                            $name = $dto->mbFirstName.' '.$dto->mbLastName;
                        }
                        $stmt->bindValue( $j++, $dto->mbId, PDO::PARAM_STR);
                        $stmt->bindValue( $j++, $name, PDO::PARAM_STR);
                        $stmt->bindValue( $j++, $dto->mbLastName, PDO::PARAM_STR);
                        $stmt->bindValue( $j++, $dto->mbFirstName, PDO::PARAM_STR);
                        $stmt->bindValue( $j++, $dto->mbNick, PDO::PARAM_STR);
                        $stmt->bindValue( $j++, $dto->mbNickDt, PDO::PARAM_STR);
                        $stmt->bindValue( $j++, $dto->mbKey, PDO::PARAM_STR);
                        $stmt->bindValue( $j++, (int)$dto->mbLevel, PDO::PARAM_INT);
                        $stmt->bindValue( $j++, $dto->mbGender, PDO::PARAM_STR);
                        $stmt->bindValue( $j++, $dto->mbBirth, PDO::PARAM_STR);
                        $stmt->bindValue( $j++, $dto->mbEmail, PDO::PARAM_STR);
                        $stmt->bindValue( $j++, $dto->mbCountryDialCode, PDO::PARAM_STR);
                        $stmt->bindValue( $j++, $dto->mbHp, PDO::PARAM_STR);
                        $stmt->bindValue( $j++, $dto->mbPasswordQ, PDO::PARAM_STR);
                        $stmt->bindValue( $j++, $dto->mbPasswordA, PDO::PARAM_STR);
                        $stmt->bindValue( $j++, $dto->mbCertificate, PDO::PARAM_STR);
                        $stmt->bindValue( $j++, $dto->mbAgent, PDO::PARAM_STR);
                        $stmt->bindValue( $j++, $dto->contryCode, PDO::PARAM_STR);
                        $stmt->bindValue( $j++, $dto->mbRegIp, PDO::PARAM_STR);
                        $stmt->bindValue( $j++, $dto->mbUpDt, PDO::PARAM_STR);
                        $stmt->bindValue( $j++, $dto->mbDelYn, PDO::PARAM_STR);
                        $stmt->bindValue( $j++, $dto->mbLogindAlert, PDO::PARAM_STR);
                        $stmt->bindValue( $j++, $dto->mbAdminMemo, PDO::PARAM_STR);

                        $stmt->bindValue( $j++, (int)$dto->mbNo, PDO::PARAM_INT);
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

                    $sql = "DELETE FROM web_member_api_key WHERE mb_no=?";

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