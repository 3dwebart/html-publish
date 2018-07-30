<?php      
/**
* Description of WebMemberLoginHisDAO
* @description Funhansoft PHP auto templet
* @date 2013-09-25
* @copyright (c)funhansoft.com
* @license http://funhansoft.com/license
* @version 0.2.3
*/
        class WebMemberLoginHisDAO extends WebMemberLoginHisDAOExt{

            function __construct() {
                parent::__construct();
            }

            public function getViewById($lh_no){
                $dto = new WebMemberLoginHisDTO();
                if(!$this->dbSlave) $this->dbSlave=parent::getDatabaseSlave(); if($this->dbSlave){
                    try{
                        $sql = "SELECT 	lh_no,
                                    lh_type,
                                    mb_no,
                                    mb_id,
                                    mb_key,
                                    lh_result_code,
                                    lh_result_msg,
                                    lh_agent,
                                    lh_block_yn,
                                    lh_block_force_yn,
                                    lh_reg_dt,
                                    lh_reg_ip
                                FROM web_member_login_his WHERE lh_no=?  limit 1";

                        if($this->dbSlave) $stmt = $this->dbSlave->prepare($sql);
                        if($stmt){
                            $stmt->bindValue( 1, $lh_no, PDO::PARAM_STR);
                            $stmt->execute();
                            list(
                                $dto->lhNo,
                                $dto->lhType,
                                $dto->mbNo,
                                $dto->mbId,
                                $dto->mbKey,
                                $dto->lhResultCode,
                                $dto->lhResultMsg,
                                $dto->lhAgent,
                                $dto->lhBlockYn,
                                $dto->lhBlockForceYn,
                                $dto->lhRegDt,
                                $dto->lhRegIp) = $stmt->fetch();
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
                        $singleFields = array('lh_no','lh_type','mb_no','lh_result_code','lh_block_yn','lh_block_force_yn');
                        $sql_add = $this->getListSearchSQL($singleFields,$field,$value);
                        if($svdf && $svdt){
                            if($sql_add){
                                $sql_add .= " AND ";
                            }else{
                                $sql_add .= " WHERE ";
                            }
                            $sql_add .= "(lh_reg_dt BETWEEN ? AND DATE_ADD(?,INTERVAL 1 DAY) ) ";
                        }
                        $sql = "SELECT count(*)  FROM web_member_login_his{$sql_add}";

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



            public function getBlockList($ips){
                $dto = new WebMemberLoginHisDTO();
                $dtoarray = array();
                if(!$this->dbSlave) $this->dbSlave=parent::getDatabaseSlave(); if($this->dbSlave){
                    try{
                        $sql_add = " WHERE lh_block_yn='Y' and lh_reg_ip=?";
                        $sql_add .= " and lh_reg_dt>DATE('".date("Y-m-d",strtotime("-1 day"))."')";
                        $sql_add .= " ORDER BY lh_no DESC";
                        $sql = "SELECT 	lh_no,
                                    lh_type,
                                    mb_no,
                                    mb_id,
                                    lh_reg_dt,
                                    lh_reg_ip
                                FROM web_member_login_his{$sql_add} LIMIT ?,?";

                        if($this->dbSlave) $stmt = $this->dbSlave->prepare($sql);
                        if($stmt){
                            $j=1;
                            $stmt->bindValue( $j++, $ips, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, parent::getListLimitStart(), PDO::PARAM_INT);
                            $stmt->bindValue( $j++, parent::getListLimitRow(), PDO::PARAM_INT);
                            $stmt->execute();

                            $i = 0;
                            while(list(
                                $dto->lhNo,
                                $dto->lhType,
                                $dto->mbNo,
                                $dto->mbId,
                                $dto->lhRegDt,
                                $dto->lhRegIp) = $stmt->fetch()) {
                                    $dtoarray[$i] = new WebMemberLoginHisDTO();
                                    parent::setResult($i+1);
                                    $dtoarray[$i]->lhNo = $dto->lhNo;
                                    $dtoarray[$i]->lhType = $dto->lhType;
                                    $dtoarray[$i]->mbNo = $dto->mbNo;
                                    $dtoarray[$i]->mbId = $dto->mbId;
                                    $dtoarray[$i]->lhRegDt = $dto->lhRegDt;
                                    $dtoarray[$i]->lhRegIp = $dto->lhRegIp;
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


            public function getList($field='',$value='',$svdf='',$svdt=''){
                $dto = new WebMemberLoginHisDTO();
                $dtoarray = array();
                if(!$this->dbSlave) $this->dbSlave=parent::getDatabaseSlave(); if($this->dbSlave){
                    try{
                        $singleFields = array('lh_no','lh_type','mb_no','lh_result_code','lh_block_yn','lh_block_force_yn');
                        $sql_add = $this->getListSearchSQL($singleFields,$field,$value);
                        if($svdf && $svdt){
                            if($sql_add){
                                $sql_add .= " AND ";
                            }else{
                                $sql_add .= " WHERE ";
                            }
                            $sql_add .= "(lh_reg_dt BETWEEN ? AND DATE_ADD(?,INTERVAL 1 DAY) ) ";
                        }
                        $sql_add .= $this->getListOrderSQL($singleFields,'lh_no');

                        $sql = "SELECT 	lh_no,
                                    lh_type,
                                    mb_no,
                                    mb_id,
                                    mb_key,
                                    lh_result_code,
                                    lh_result_msg,
                                    lh_agent,
                                    lh_block_yn,
                                    lh_block_force_yn,
                                    lh_reg_dt,
                                    lh_reg_ip
                                FROM web_member_login_his{$sql_add} LIMIT ?,?";

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
                                $dto->lhNo,
                                $dto->lhType,
                                $dto->mbNo,
                                $dto->mbId,
                                $dto->mbKey,
                                $dto->lhResultCode,
                                $dto->lhResultMsg,
                                $dto->lhAgent,
                                $dto->lhBlockYn,
                                $dto->lhBlockForceYn,
                                $dto->lhRegDt,
                                $dto->lhRegIp) = $stmt->fetch()) {
                                    $dtoarray[$i] = new WebMemberLoginHisDTO();
                                    parent::setResult($i+1);
                                    $dtoarray[$i]->lhNo = $dto->lhNo;
                                    $dtoarray[$i]->lhType = $dto->lhType;
                                    $dtoarray[$i]->mbNo = $dto->mbNo;
                                    $dtoarray[$i]->mbId = $dto->mbId;
                                    $dtoarray[$i]->mbKey = $dto->mbKey;
                                    $dtoarray[$i]->lhResultCode = $dto->lhResultCode;
                                    $dtoarray[$i]->lhResultMsg = $dto->lhResultMsg;
                                    $dtoarray[$i]->lhAgent = $dto->lhAgent;
                                    $dtoarray[$i]->lhBlockYn = $dto->lhBlockYn;
                                    $dtoarray[$i]->lhBlockForceYn = $dto->lhBlockForceYn;
                                    $dtoarray[$i]->lhRegDt = $dto->lhRegDt;
                                    $dtoarray[$i]->lhRegIp = $dto->lhRegIp;
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
                        $sql = "INSERT INTO web_member_login_his(
                                lh_type,
                                mb_no,
                                mb_id,
                                mb_key,
                                lh_result_code,
                                lh_result_msg,
                                lh_agent,
                                lh_block_yn,
                                lh_block_force_yn,
                                lh_reg_ip)
                                VALUES(?,?,?,?,?,?,?,?,?,?)";

                        if($this->db) $stmt = $this->db->prepare($sql);
                        if($stmt){
                            $j=1;
                            $stmt->bindValue( $j++, $dto->lhType, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, $dto->mbNo, PDO::PARAM_INT);
                            $stmt->bindValue( $j++, $dto->mbId, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, $dto->mbKey, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, $dto->lhResultCode, PDO::PARAM_INT);
                            $stmt->bindValue( $j++, $dto->lhResultMsg, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, $dto->lhAgent, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, $dto->lhBlockYn, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, $dto->lhBlockForceYn, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, $dto->lhRegIp, PDO::PARAM_STR);
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

                    $sql = "UPDATE web_member_login_his SET
                                lh_type=?,
                                mb_no=?,
                                mb_id=?,
                                mb_key=?,
                                lh_result_code=?,
                                lh_result_msg=?,
                                lh_agent=?,
                                lh_block_yn=?,
                                lh_block_force_yn=?,
                                lh_reg_ip=? WHERE lh_no=?";

                    if($this->db) $stmt = $this->db->prepare($sql);
                    if($stmt){
                        $j=1;
                        $stmt->bindValue( $j++, $dto->lhType, PDO::PARAM_STR);
                        $stmt->bindValue( $j++, $dto->mbNo, PDO::PARAM_INT);
                        $stmt->bindValue( $j++, $dto->mbId, PDO::PARAM_STR);
                        $stmt->bindValue( $j++, $dto->mbKey, PDO::PARAM_STR);
                        $stmt->bindValue( $j++, $dto->lhResultCode, PDO::PARAM_INT);
                        $stmt->bindValue( $j++, $dto->lhResultMsg, PDO::PARAM_STR);
                        $stmt->bindValue( $j++, $dto->lhAgent, PDO::PARAM_STR);
                        $stmt->bindValue( $j++, $dto->lhBlockYn, PDO::PARAM_STR);
                        $stmt->bindValue( $j++, $dto->lhBlockForceYn, PDO::PARAM_STR);
                        $stmt->bindValue( $j++, $dto->lhRegIp, PDO::PARAM_STR);
                        $stmt->bindValue( $j++, $dto->lhNo, PDO::PARAM_INT);
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

                    $sql = "DELETE FROM web_member_login_his WHERE lh_no=?";

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