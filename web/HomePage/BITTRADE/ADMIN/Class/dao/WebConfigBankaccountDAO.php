<?php
/**
* Description of WebConfigBankaccountDAO
* @description FunHanSoft Co.,Ltd  PHP auto templet
* @date 2016-07-15
* @copyright (c)funhansoft.com
* @license 해당 사이트 이외의 사용은 엄격히 금지되어 있습니다.
* @version 4.0.0
*/
        class WebConfigBankaccountDAO extends BaseDAO{

            private $db;
            private $dbSlave;

            function __construct() {
                parent::__construct();
            }

            public function getViewById($cf_no){
                $dto = new WebConfigBankaccountDTO();
                if(!$this->dbSlave) $this->dbSlave=parent::getDatabaseSlave();
                if($this->dbSlave){
                    try{
                        $sql = "SELECT 	cf_no,
                                    cf_country_code,
                                    cf_bank_name,
                                    cf_bank_account,
                                    cf_bank_owner,
                                    cf_use_yn,
                                    cf_reg_dt
                                FROM web_config_bankaccount WHERE cf_no=?  limit 1";

                        if($this->dbSlave) $stmt = $this->dbSlave->prepare($sql);
                        if($stmt){
                            $stmt->bindValue( 1, $cf_no, PDO::PARAM_STR);
                            $stmt->execute();
                            list(
                                $dto->cfNo,
                                $dto->cfCountryCode,
                                $dto->cfBankName,
                                $dto->cfBankAccount,
                                $dto->cfBankOwner,
                                $dto->cfUseYn,
                                $dto->cfRegDt) = $stmt->fetch();
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

            public function getListCount($field='',$value=''){
                $dto = new CommonListDTO();
                if(!$this->dbSlave) $this->dbSlave=parent::getDatabaseSlave();
                if($this->dbSlave){
                    try{
                        $singleFields = array('cf_no','cf_use_yn');
                        $sql_add = $this->getListSearchSQL($singleFields,$field,$value);

                        $sql = "SELECT count(*)  FROM web_config_bankaccount{$sql_add}";

                        if($this->dbSlave) $stmt = $this->dbSlave->prepare($sql);
                        if($stmt){
                            if($value)
                                $stmt->bindValue( 1, $value, PDO::PARAM_STR);

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

            public function getList($field='',$value=''){
                $dto = new WebConfigBankaccountDTO();
                $dtoarray = array();
                if(!$this->dbSlave) $this->dbSlave=parent::getDatabaseSlave();
                if($this->dbSlave){
                    try{
                        $singleFields = array('cf_no','cf_use_yn');
                        $sql_add = $this->getListSearchSQL($singleFields,$field,$value);
                        $sql_add .= $this->getListOrderSQL($singleFields,'cf_no');

                        $sql = "SELECT 	cf_no,
                                    cf_country_code,
                                    cf_bank_name,
                                    cf_bank_account,
                                    cf_bank_owner,
                                    cf_use_yn,
                                    cf_reg_dt
                                FROM web_config_bankaccount{$sql_add} LIMIT ?,?";

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
                                $dto->cfNo,
                                $dto->cfCountryCode,
                                $dto->cfBankName,
                                $dto->cfBankAccount,
                                $dto->cfBankOwner,
                                $dto->cfUseYn,
                                $dto->cfRegDt) = $stmt->fetch()) {
                                    $dtoarray[$i] = new WebConfigBankaccountDTO();
                                    parent::setResult($i+1);
                                    $dtoarray[$i]->cfNo = $dto->cfNo;
                                    $dtoarray[$i]->cfCountryCode = $dto->cfCountryCode;
                                    $dtoarray[$i]->cfBankName = $dto->cfBankName;
                                    $dtoarray[$i]->cfBankAccount = $dto->cfBankAccount;
                                    $dtoarray[$i]->cfBankOwner = $dto->cfBankOwner;
                                    $dtoarray[$i]->cfUseYn = $dto->cfUseYn;
                                    $dtoarray[$i]->cfRegDt = $dto->cfRegDt;
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
                        $sql = "INSERT INTO web_config_bankaccount(
                                    cf_country_code,
                                    cf_bank_name,
                                    cf_bank_account,
                                    cf_bank_owner,
                                    cf_use_yn)
                                VALUES(?,?,?,?,?)";

                        if($this->db) $stmt = $this->db->prepare($sql);
                        if($stmt){
                            $j=1;
                            $stmt->bindValue( $j++, $dto->cfCountryCode, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, $dto->cfBankName, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, $dto->cfBankAccount, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, $dto->cfBankOwner, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, $dto->cfUseYn, PDO::PARAM_STR);
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

                    $sql = "UPDATE web_config_bankaccount SET
                                cf_country_code=?,
                                cf_bank_name=?,
                                cf_bank_account=?,
                                cf_bank_owner=?,
                                cf_use_yn=? WHERE cf_no=?";

                    if($this->db) $stmt = $this->db->prepare($sql);
                    if($stmt){
                        $j=1;
                        $stmt->bindValue( $j++, $dto->cfCountryCode, PDO::PARAM_STR);
                        $stmt->bindValue( $j++, $dto->cfBankName, PDO::PARAM_STR);
                        $stmt->bindValue( $j++, $dto->cfBankAccount, PDO::PARAM_STR);
                        $stmt->bindValue( $j++, $dto->cfBankOwner, PDO::PARAM_STR);
                        $stmt->bindValue( $j++, $dto->cfUseYn, PDO::PARAM_STR);
                        $stmt->bindValue( $j++, $dto->cfNo, PDO::PARAM_INT);
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

                    $sql = "DELETE FROM web_config_bankaccount WHERE cf_no=?";

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