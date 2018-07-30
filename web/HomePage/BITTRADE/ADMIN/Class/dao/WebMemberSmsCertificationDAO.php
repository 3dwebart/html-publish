<?php
/**
* Description of WebMemberSmsCertificationDAO
* @description FunHanSoft Co.,Ltd  PHP auto templet
* @date 2016-08-24
* @copyright (c)funhansoft.com
* @license 해당 사이트 이외의 사용은 엄격히 금지되어 있습니다.
* @version 4.0.0
*/
        class WebMemberSmsCertificationDAO extends BaseDAO{

            private $db;
            private $dbSlave;

            function __construct() {
                parent::__construct();
            }

            public function getViewById($sc_no){
                $dto = new WebMemberSmsCertificationDTO();
                if(!$this->dbSlave) $this->dbSlave=parent::getDatabaseSlave();
                if($this->dbSlave){
                    try{
                        $sql = "SELECT 	sc_no,
                                    mb_no,
                                    mb_id,
                                    mb_name,
                                    mb_hp,
                                    contry_code,
                                    mb_country_dial_code,
                                    sc_reg_ip,
                                    sc_reg_dt
                                FROM web_member_sms_certification WHERE sc_no=?  limit 1";

                        if($this->dbSlave) $stmt = $this->dbSlave->prepare($sql);
                        if($stmt){
                            $stmt->bindValue( 1, $sc_no, PDO::PARAM_STR);
                            $stmt->execute();
                            list(
                                $dto->scNo,
                                $dto->mbNo,
                                $dto->mbId,
                                $dto->mbName,
                                $dto->mbHp,
                                $dto->contryCode,
                                $dto->mbCountryDialCode,
                                $dto->scRegIp,
                                $dto->scRegDt) = $stmt->fetch();
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
                        $singleFields = array('sc_no','mb_no');
                        $sql_add = $this->getListSearchSQL($singleFields,$field,$value);
                        if($svdf && $svdt){
                            if($sql_add){
                                $sql_add .= " AND ";
                            }else{
                                $sql_add .= " WHERE ";
                            }
                            $sql_add .= "(sc_reg_dt BETWEEN ? AND DATE_ADD(?,INTERVAL 1 DAY) ) ";
                        }
                        $sql = "SELECT count(*)  FROM web_member_sms_certification{$sql_add}";

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
                $dto = new WebMemberSmsCertificationDTO();
                $dtoarray = array();
                if(!$this->dbSlave) $this->dbSlave=parent::getDatabaseSlave();
                if($this->dbSlave){
                    try{
                        $singleFields = array('sc_no','mb_no');
                        $sql_add = $this->getListSearchSQL($singleFields,$field,$value);
                        if($svdf && $svdt){
                            if($sql_add){
                                $sql_add .= " AND ";
                            }else{
                                $sql_add .= " WHERE ";
                            }
                            $sql_add .= "(sc_reg_dt BETWEEN ? AND DATE_ADD(?,INTERVAL 1 DAY) ) ";
                        }
                        $sql_add .= $this->getListOrderSQL($singleFields,'sc_no');

                        $sql = "SELECT 	sc_no,
                                    mb_no,
                                    mb_id,
                                    mb_name,
                                    mb_hp,
                                    contry_code,
                                    mb_country_dial_code,
                                    sc_reg_ip,
                                    sc_reg_dt
                                FROM web_member_sms_certification{$sql_add} LIMIT ?,?";

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
                                $dto->scNo,
                                $dto->mbNo,
                                $dto->mbId,
                                $dto->mbName,
                                $dto->mbHp,
                                $dto->contryCode,
                                $dto->mbCountryDialCode,
                                $dto->scRegIp,
                                $dto->scRegDt) = $stmt->fetch()) {
                                    $dtoarray[$i] = new WebMemberSmsCertificationDTO();
                                    parent::setResult($i+1);
                                    $dtoarray[$i]->scNo = $dto->scNo;
                                    $dtoarray[$i]->mbNo = $dto->mbNo;
                                    $dtoarray[$i]->mbId = $dto->mbId;
                                    $dtoarray[$i]->mbName = $dto->mbName;
                                    $dtoarray[$i]->mbHp = $dto->mbHp;
                                    $dtoarray[$i]->contryCode = $dto->contryCode;
                                    $dtoarray[$i]->mbCountryDialCode = $dto->mbCountryDialCode;
                                    $dtoarray[$i]->scRegIp = $dto->scRegIp;
                                    $dtoarray[$i]->scRegDt = $dto->scRegDt;
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
                        $sql = "INSERT INTO web_member_sms_certification(
                                    mb_no,
                                    mb_id,
                                    mb_name,
                                    mb_hp,
                                    contry_code,
                                    mb_country_dial_code,
                                    sc_reg_ip)
                                VALUES(?,?,?,?,?,?,?)";

                        if($this->db) $stmt = $this->db->prepare($sql);
                        if($stmt){
                            $j=1;

                            $stmt->bindValue( $j++, $dto->mbNo, PDO::PARAM_INT);
                            $stmt->bindValue( $j++, $dto->mbId, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, $dto->mbName, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, $dto->mbHp, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, $dto->contryCode, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, $dto->mbCountryDialCode, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, $dto->scRegIp, PDO::PARAM_STR);
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

                    $sql = "UPDATE web_member_sms_certification SET
                            mb_no=?,
                            mb_id=?,
                            mb_name=?,
                            mb_hp=?,
                            contry_code=?,
                            mb_country_dial_code=?,
                            sc_reg_ip=? WHERE sc_no=?";

                    if($this->db) $stmt = $this->db->prepare($sql);
                    if($stmt){
                        $j=1;

                        $stmt->bindValue( $j++, $dto->mbNo, PDO::PARAM_INT);
                        $stmt->bindValue( $j++, $dto->mbId, PDO::PARAM_STR);
                        $stmt->bindValue( $j++, $dto->mbName, PDO::PARAM_STR);
                        $stmt->bindValue( $j++, $dto->mbHp, PDO::PARAM_STR);
                        $stmt->bindValue( $j++, $dto->contryCode, PDO::PARAM_STR);
                        $stmt->bindValue( $j++, $dto->mbCountryDialCode, PDO::PARAM_STR);
                        $stmt->bindValue( $j++, $dto->scRegIp, PDO::PARAM_STR);

                        $stmt->bindValue( $j++, $dto->scNo, PDO::PARAM_INT);
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

                    $sql = "DELETE FROM web_member_sms_certification WHERE sc_no=?";

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