<?php
/**
* Description of WebMemberInfoHisDAO
* @description FunHanSoft Co.,Ltd  PHP auto templet
* @date 2016-08-24
* @copyright (c)funhansoft.com
* @license 해당 사이트 이외의 사용은 엄격히 금지되어 있습니다.
* @version 4.0.0
*/
        class WebMemberInfoHisDAO extends BaseDAO{

            private $db;
            private $dbSlave;

            function __construct() {
                parent::__construct();
            }

            public function getViewById($ih_no){
                $dto = new WebMemberInfoHisDTO();
                if(!$this->dbSlave) $this->dbSlave=parent::getDatabaseSlave();
                if($this->dbSlave){
                    try{
                        $sql = "SELECT 	ih_no,
                                    mb_no,
                                    orgin_mb_name,
                                    orgin_mb_hp,
                                    orgin_mb_bit_wallet,
                                    mb_name,
                                    mb_hp,
                                    mb_bit_wallet,
                                    ih_reg_dt
                                FROM web_member_info_his WHERE ih_no=?  limit 1";

                        if($this->dbSlave) $stmt = $this->dbSlave->prepare($sql);
                        if($stmt){
                            $stmt->bindValue( 1, $ih_no, PDO::PARAM_STR);
                            $stmt->execute();
                            list(
                                $dto->ihNo,
                                $dto->mbNo,
                                $dto->orginMbName,
                                $dto->orginMbHp,
                                $dto->orginMbBitWallet,
                                $dto->mbName,
                                $dto->mbHp,
                                $dto->mbBitWallet,
                                $dto->ihRegDt) = $stmt->fetch();
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
                        $singleFields = array('ih_no','mb_no');
                        $sql_add = $this->getListSearchSQL($singleFields,$field,$value);
                        if($svdf && $svdt){
                            if($sql_add){
                                $sql_add .= " AND ";
                            }else{
                                $sql_add .= " WHERE ";
                            }
                            $sql_add .= "(ih_reg_dt BETWEEN ? AND DATE_ADD(?,INTERVAL 1 DAY) ) ";
                        }
                        $sql = "SELECT count(*)  FROM web_member_info_his{$sql_add}";

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
                $dto = new WebMemberInfoHisDTO();
                $dtoarray = array();
                if(!$this->dbSlave) $this->dbSlave=parent::getDatabaseSlave();
                if($this->dbSlave){
                    try{
                        $singleFields = array('ih_no','mb_no');
                        $sql_add = $this->getListSearchSQL($singleFields,$field,$value);
                        if($svdf && $svdt){
                            if($sql_add){
                                $sql_add .= " AND ";
                            }else{
                                $sql_add .= " WHERE ";
                            }
                            $sql_add .= "(ih_reg_dt BETWEEN ? AND DATE_ADD(?,INTERVAL 1 DAY) ) ";
                        }
                        $sql_add .= $this->getListOrderSQL($singleFields,'ih_no');

                        $sql = "SELECT 	ih_no,
                                    mb_no,
                                    orgin_mb_name,
                                    orgin_mb_hp,
                                    orgin_mb_pwd,
                                    orgin_api_use,
                                    orgin_otp_use,
                                    mb_name,
                                    mb_hp,
                                    mb_pwd,
                                    ih_reg_dt
                                FROM web_member_info_his{$sql_add} LIMIT ?,?";

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
                                $dto->ihNo,
                                $dto->mbNo,
                                $dto->orginMbName,
                                $dto->orginMbHp,
                                $dto->orginMbPwd,
                                $dto->orginApiUse,
                                $dto->originOtpUse,
                                $dto->mbName,
                                $dto->mbHp,
                                $dto->mbPwd,
                                $dto->ihRegDt) = $stmt->fetch()) {
                                    $dtoarray[$i] = new WebMemberInfoHisDTO();
                                    parent::setResult($i+1);
                                    $dtoarray[$i]->ihNo = $dto->ihNo;
                                    $dtoarray[$i]->mbNo = $dto->mbNo;
                                    $dtoarray[$i]->orginMbName = $dto->orginMbName;
                                    $dtoarray[$i]->orginMbHp = $dto->orginMbHp;
                                    $dtoarray[$i]->orginMbPwd = $dto->orginMbPwd;
                                    $dtoarray[$i]->orginApiUse = $dto->orginApiUse;
                                    $dtoarray[$i]->originOtpUse = $dto->originOtpUse;
                                    $dtoarray[$i]->mbName = $dto->mbName;
                                    $dtoarray[$i]->mbHp = $dto->mbHp;
                                    $dtoarray[$i]->mbPwd = $dto->mbPwd;
                                    $dtoarray[$i]->ihRegDt = $dto->ihRegDt;
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
                        $sql = "INSERT INTO web_member_info_his(
                                    mb_no,
                                    orgin_mb_name,
                                    orgin_mb_hp,
                                    orgin_mb_pwd,
                                    orgin_api_use,
                                    orgin_otp_use
                                    mb_name,
                                    mb_hp,
                                    mb_pwd,
                                    ih_reg_dt)
                                VALUES(?,?,?,?,?,?,?)";

                        if($this->db) $stmt = $this->db->prepare($sql);
                        if($stmt){
                            $j=1;

                            $stmt->bindValue( $j++, $dto->mbNo, PDO::PARAM_INT);
                            $stmt->bindValue( $j++, $dto->orginMbName, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, $dto->orginMbHp, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, $dto->orginMbPwd, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, $dto->orginApiUse, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, $dto->originOtpUse, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, $dto->mbName, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, $dto->mbHp, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, $dto->mbPwd, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, $dto->ihRegDt, PDO::PARAM_STR);
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

                    $sql = "UPDATE web_member_info_his SET
                                mb_no=?,
                                orgin_mb_name=?,
                                orgin_mb_hp=?,
                                orgin_mb_pwd=?,
                                orgin_api_use=?,
                                orgin_otp_use=?,
                                mb_name=?,
                                mb_hp=?,
                                mb_pwd=?,
                                ih_reg_dt=? WHERE ih_no=?";

                    if($this->db) $stmt = $this->db->prepare($sql);
                    if($stmt){
                        $j=1;

                        $stmt->bindValue( $j++, $dto->mbNo, PDO::PARAM_INT);
                        $stmt->bindValue( $j++, $dto->orginMbName, PDO::PARAM_STR);
                        $stmt->bindValue( $j++, $dto->orginMbHp, PDO::PARAM_STR);
                        $stmt->bindValue( $j++, $dto->orginMbPwd, PDO::PARAM_STR);
                        $stmt->bindValue( $j++, $dto->orginApiUse, PDO::PARAM_STR);
                        $stmt->bindValue( $j++, $dto->originOtpUse, PDO::PARAM_STR);
                        $stmt->bindValue( $j++, $dto->mbName, PDO::PARAM_STR);
                        $stmt->bindValue( $j++, $dto->mbHp, PDO::PARAM_STR);
                        $stmt->bindValue( $j++, $dto->mbPwd, PDO::PARAM_STR);
                        $stmt->bindValue( $j++, $dto->ihRegDt, PDO::PARAM_STR);
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

                    $sql = "DELETE FROM web_member_info_his WHERE ih_no=?";

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