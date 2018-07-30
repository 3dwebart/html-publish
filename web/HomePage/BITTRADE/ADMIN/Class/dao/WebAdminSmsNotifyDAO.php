<?php      
/**
* Description of WebAdminSmsNotifyDAO
* @description FunHanSoft Co.,Ltd  PHP auto templet
* @date 2016-08-20
* @copyright (c)funhansoft.com
* @license 해당 사이트 이외의 사용은 엄격히 금지되어 있습니다.
* @version 4.0.0
*/
        class WebAdminSmsNotifyDAO extends BaseDAO{

            private $db;
            private $dbSlave;

            function __construct() {
                parent::__construct();
            }

            public function getViewById($sn_no){
                $dto = new WebAdminSmsNotifyDTO();
                if(!$this->dbSlave) $this->dbSlave=parent::getDatabaseSlave();
                if($this->dbSlave){
                    try{
                        $sql = "SELECT
                                    sn_no,
                                    sn_name,
                                    sn_country_code,
                                    sn_country_dial_code,
                                    sn_hp,
                                    sn_n_deposit_yn,
                                    sn_n_withdrawals_yn,
                                    sn_reg_dt,
                                    sn_reg_ip,
                                    sn_up_dt
                                FROM web_admin_sms_notify WHERE sn_no=?  limit 1";

                        if($this->dbSlave) $stmt = $this->dbSlave->prepare($sql);
                        if($stmt){
                            $stmt->bindValue( 1, $sn_no, PDO::PARAM_STR);
                            $stmt->execute();
                            list(
                                $dto->snNo,
                                $dto->snName,
                                $dto->snCountryCode,
                                $dto->snCountryDialCode,
                                $dto->snHp,
                                $dto->snNDepositYn,
                                $dto->snNWithdrawalsYn,
                                $dto->snRegDt,
                                $dto->snRegIp,
                                $dto->snUpDt) = $stmt->fetch();
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
                        $singleFields = array('sn_no','sn_n_deposit_yn','sn_n_withdrawals_yn');
                        $sql_add = $this->getListSearchSQL($singleFields,$field,$value);
                        if($svdf && $svdt){
                            if($sql_add){
                                $sql_add .= " AND ";
                            }else{
                                $sql_add .= " WHERE ";
                            }
                            $sql_add .= "(sn_reg_dt BETWEEN ? AND DATE_ADD(?,INTERVAL 1 DAY) ) ";
                        }
                        $sql = "SELECT count(*)  FROM web_admin_sms_notify{$sql_add}";

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
                $dto = new WebAdminSmsNotifyDTO();
                $dtoarray = array();
                if(!$this->dbSlave) $this->dbSlave=parent::getDatabaseSlave();
                if($this->dbSlave){
                    try{
                        $singleFields = array('sn_no','sn_n_deposit_yn','sn_n_withdrawals_yn');
                        $sql_add = $this->getListSearchSQL($singleFields,$field,$value);
                        if($svdf && $svdt){
                            if($sql_add){
                                $sql_add .= " AND ";
                            }else{
                                $sql_add .= " WHERE ";
                            }
                            $sql_add .= "(sn_reg_dt BETWEEN ? AND DATE_ADD(?,INTERVAL 1 DAY) ) ";
                        }
                        $sql_add .= $this->getListOrderSQL($singleFields,'sn_no');

                        $sql = "SELECT
                                    sn_no,
                                    sn_name,
                                    sn_country_code,
                                    sn_country_dial_code,
                                    sn_hp,
                                    sn_n_deposit_yn,
                                    sn_n_withdrawals_yn,
                                    sn_reg_dt,
                                    sn_reg_ip,
                                    sn_up_dt
                                FROM web_admin_sms_notify{$sql_add} LIMIT ?,?";

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
                                $dto->snNo,
                                $dto->snName,
                                $dto->snCountryCode,
                                $dto->snCountryDialCode,
                                $dto->snHp,
                                $dto->snNDepositYn,
                                $dto->snNWithdrawalsYn,
                                $dto->snRegDt,
                                $dto->snRegIp,
                                $dto->snUpDt) = $stmt->fetch()) {
                                    $dtoarray[$i] = new WebAdminSmsNotifyDTO();
                                    parent::setResult($i+1);
                                    $dtoarray[$i]->snNo = $dto->snNo;
                                    $dtoarray[$i]->snName = $dto->snName;
                                    $dtoarray[$i]->snCountryCode = $dto->snCountryCode;
                                    $dtoarray[$i]->snCountryDialCode = $dto->snCountryDialCode;
                                    $dtoarray[$i]->snHp = $dto->snHp;
                                    $dtoarray[$i]->snNDepositYn = $dto->snNDepositYn;
                                    $dtoarray[$i]->snNWithdrawalsYn = $dto->snNWithdrawalsYn;
                                    $dtoarray[$i]->snRegDt = $dto->snRegDt;
                                    $dtoarray[$i]->snRegIp = $dto->snRegIp;
                                    $dtoarray[$i]->snUpDt = $dto->snUpDt;
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
                        $sql = "INSERT INTO web_admin_sms_notify(
                                sn_name,
                                sn_country_code,
                                sn_country_dial_code,
                                sn_hp,
                                sn_n_deposit_yn,
                                sn_n_withdrawals_yn,
                                sn_reg_ip,
                                sn_up_dt)
                                VALUES(?,?,?,?,?,?,?,?)";

                        if($this->db) $stmt = $this->db->prepare($sql);
                        if($stmt){
                            $j=1;

                            $stmt->bindValue( $j++, $dto->snName, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, $dto->snCountryCode, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, $dto->snCountryDialCode, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, $dto->snHp, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, $dto->snNDepositYn, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, $dto->snNWithdrawalsYn, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, $dto->snRegIp, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, $dto->snUpDt, PDO::PARAM_STR);
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

                    $sql = "UPDATE web_admin_sms_notify SET
                            sn_name=?,
                            sn_country_code=?,
                            sn_country_dial_code=?,
                            sn_hp=?,
                            sn_n_deposit_yn=?,
                            sn_n_withdrawals_yn=?,
                            sn_reg_ip=?,
                            sn_up_dt=? WHERE sn_no=?";

                    if($this->db) $stmt = $this->db->prepare($sql);
                    if($stmt){
                        $j=1;

                        $stmt->bindValue( $j++, $dto->snName, PDO::PARAM_STR);
                        $stmt->bindValue( $j++, $dto->snCountryCode, PDO::PARAM_STR);
                        $stmt->bindValue( $j++, $dto->snCountryDialCode, PDO::PARAM_STR);
                        $stmt->bindValue( $j++, $dto->snHp, PDO::PARAM_STR);
                        $stmt->bindValue( $j++, $dto->snNDepositYn, PDO::PARAM_STR);
                        $stmt->bindValue( $j++, $dto->snNWithdrawalsYn, PDO::PARAM_STR);
                        $stmt->bindValue( $j++, $dto->snRegIp, PDO::PARAM_STR);
                        $stmt->bindValue( $j++, $dto->snUpDt, PDO::PARAM_STR);
                        $stmt->bindValue( $j++, $dto->snNo, PDO::PARAM_INT);
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

                    $sql = "DELETE FROM web_admin_sms_notify WHERE sn_no=?";

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