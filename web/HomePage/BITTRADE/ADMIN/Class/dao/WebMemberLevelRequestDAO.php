<?php      
/**
* Description of WebMemberLevelRequestDAO
* @description FunHanSoft Co.,Ltd  PHP auto templet
* @date 2016-07-15
* @copyright (c)funhansoft.com
* @license 해당 사이트 이외의 사용은 엄격히 금지되어 있습니다.
* @version 4.0.0
*/
        class WebMemberLevelRequestDAO extends BaseDAO{

            private $db;
            private $dbSlave;

            function __construct() {
                parent::__construct();
                $this->cfg = Config::getConfig();
            }

            public function getViewById($req_no){
                $dto = new WebMemberLevelRequestDTO();
                if(!$this->dbSlave) $this->dbSlave=parent::getDatabaseSlave();
                if($this->dbSlave){
                    try{
                        $sql = "SELECT 	req_no,
                                mb_no,
                                mb_id,
                                mb_cur_level,
                                mb_req_level,
                                mb_prove_method,
                                mb_passport_id,
                                mb_birth,
                                mb_zipcode,
                                mb_addr1,
                                mb_addr2,
                                mb_city,
                                mb_state,
                                mb_tel,
                                mb_dial_code,
                                mb_phone,
                                mb_phone_auth,
                                mb_prove_file1,
                                mb_prove_file2,
                                mb_prove_file3,
                                mb_prove_file4,
                                req_dt,
                                req_ip,
                                admin_confirm,
                                admin_confirm_dt,
                                admin_memo 
                                FROM web_member_level_request WHERE req_no=?  limit 1";

                        if($this->dbSlave) $stmt = $this->dbSlave->prepare($sql);
                        if($stmt){
                            $stmt->bindValue( 1, $req_no, PDO::PARAM_STR);
                            $stmt->execute();
                            list(
				$dto->reqNo,
				$dto->mbNo,
				$dto->mbId,
				$dto->mbCurLevel,
				$dto->mbReqLevel,
				$dto->mbProveMethod,
				$dto->mbPassportId,
				$dto->mbBirth,
				$dto->mbZipcode,
				$dto->mbAddr1,
				$dto->mbAddr2,
				$dto->mbCity,
				$dto->mbState,
				$dto->mbTel,
				$dto->mbDialCode,
				$dto->mbPhone,
				$dto->mbPhoneAuth,
				$dto->mbProveFile1,
				$dto->mbProveFile2,
				$dto->mbProveFile3,
				$dto->mbProveFile4,
				$dto->reqDt,
				$dto->reqIp,
				$dto->adminConfirm,
				$dto->adminConfirmDt,
				$dto->adminMemo) = $stmt->fetch();
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

            public function getListCount($field='',$value='',$svdf='',$svdt=''){
                $dto = new CommonListDTO();
                if(!$this->dbSlave) $this->dbSlave=parent::getDatabaseSlave();
                if($this->dbSlave){
                    try{
                        $singleFields = array('req_no','mb_no','mb_cur_level','mb_req_level','mb_prove_method','admin_confirm');
                        $sql_add = $this->getListSearchSQL($singleFields,$field,$value);
                        if($svdf && $svdt){
                            if($sql_add){
                                $sql_add .= " AND ";
                            }else{
                                $sql_add .= " WHERE ";
                            }
                            $sql_add .= "(req_dt BETWEEN ? AND DATE_ADD(?,INTERVAL 1 DAY) ) ";
                        }
                        $sql = "SELECT count(*)  FROM web_member_level_request{$sql_add}";

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
                $dto = new WebMemberLevelRequestDTO();
                $dtoarray = array();
                if(!$this->dbSlave) $this->dbSlave=parent::getDatabaseSlave();
                if($this->dbSlave){
                    try{
                        $singleFields = array('req_no','mb_no','mb_cur_level','mb_req_level','mb_prove_method','admin_confirm');
                        $sql_add = $this->getListSearchSQL($singleFields,$field,$value);
                        if($svdf && $svdt){
                            if($sql_add){
                                $sql_add .= " AND ";
                            }else{
                                $sql_add .= " WHERE ";
                            }
                            $sql_add .= "(req_dt BETWEEN ? AND DATE_ADD(?,INTERVAL 1 DAY) ) ";
                        }
                        $sql_add .= $this->getListOrderSQL($singleFields,'req_no');

                        $sql = "SELECT 	req_no,
                                    mb_no,
                                    mb_id,
                                    mb_cur_level,
                                    mb_req_level,
                                    mb_prove_method,
                                    mb_prove_file1,
                                    mb_prove_file2,
                                    mb_prove_file3,
                                    req_dt,
                                    req_ip,
                                    admin_confirm,
                                    admin_confirm_dt,
                                    LEFT(admin_memo,256)
                                FROM web_member_level_request{$sql_add} LIMIT ?,?";

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
                                $dto->reqNo,
                                $dto->mbNo,
                                $dto->mbId,
                                $dto->mbCurLevel,
                                $dto->mbReqLevel,
                                $dto->mbProveMethod,
                                $dto->mbProveFile1,
                                $dto->mbProveFile2,
                                $dto->mbProveFile3,
                                $dto->reqDt,
                                $dto->reqIp,
                                $dto->adminConfirm,
                                $dto->adminConfirmDt,
                                $dto->adminMemo) = $stmt->fetch()) {
                                    $dtoarray[$i] = new WebMemberLevelRequestDTO();
                                    parent::setResult($i+1);
                                    $dtoarray[$i]->reqNo = $dto->reqNo;
                                    $dtoarray[$i]->mbNo = $dto->mbNo;
                                    $dtoarray[$i]->mbId = $dto->mbId;
                                    $dtoarray[$i]->mbCurLevel = $dto->mbCurLevel;
                                    $dtoarray[$i]->mbReqLevel = $dto->mbReqLevel;
                                    $dtoarray[$i]->mbProveMethod = $dto->mbProveMethod;
                                    $dtoarray[$i]->mbProveFile1 = $dto->mbProveFile1;
                                    $dtoarray[$i]->mbProveFile2 = $dto->mbProveFile2;
                                    $dtoarray[$i]->mbProveFile3 = $dto->mbProveFile3;
                                    $dtoarray[$i]->reqDt = $dto->reqDt;
                                    $dtoarray[$i]->reqIp = $dto->reqIp;
                                    $dtoarray[$i]->adminConfirm = $dto->adminConfirm;
                                    $dtoarray[$i]->adminConfirmDt = $dto->adminConfirmDt;
                                    $dtoarray[$i]->adminMemo = $dto->adminMemo;
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
                        $sql = "INSERT INTO web_member_level_request(
                                    mb_no,
                                    mb_id,
                                    mb_cur_level,
                                    mb_req_level,
                                    mb_prove_method,
                                    mb_prove_file1,
                                    mb_prove_file2,
                                    mb_prove_file3,
                                    req_ip,
                                    admin_confirm,
                                    admin_confirm_dt,
                                    admin_memo)
                                VALUES(?,?,?,?,?,?,?,?,?,?,?,?)";

                        if($this->db) $stmt = $this->db->prepare($sql);
                        if($stmt){
                            $j=1;

                            $stmt->bindValue( $j++, $dto->mbNo, PDO::PARAM_INT);
                            $stmt->bindValue( $j++, $dto->mbId, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, $dto->mbCurLevel, PDO::PARAM_INT);
                            $stmt->bindValue( $j++, $dto->mbReqLevel, PDO::PARAM_INT);
                            $stmt->bindValue( $j++, $dto->mbProveMethod, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, $dto->mbProveFile1, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, $dto->mbProveFile2, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, $dto->mbProveFile3, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, $dto->reqIp, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, $dto->adminConfirm, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, $dto->adminConfirmDt, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, $dto->adminMemo, PDO::PARAM_STR);
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

                    $sql = "UPDATE web_member_level_request SET
                            mb_no=?,
                            mb_id=?,
                            mb_cur_level=?,
                            mb_req_level=?,
                            mb_prove_method=?,
                            mb_prove_file1=?,
                            mb_prove_file2=?,
                            mb_prove_file3=?,
                            req_ip=?,
                            admin_confirm=?,
                            admin_confirm_dt=?,
                            admin_memo=? WHERE req_no=?";

                    if($this->db) $stmt = $this->db->prepare($sql);
                    if($stmt){
                        $j=1;
                        $stmt->bindValue( $j++, $dto->mbNo, PDO::PARAM_INT);
                        $stmt->bindValue( $j++, $dto->mbId, PDO::PARAM_STR);
                        $stmt->bindValue( $j++, $dto->mbCurLevel, PDO::PARAM_INT);
                        $stmt->bindValue( $j++, $dto->mbReqLevel, PDO::PARAM_INT);
                        $stmt->bindValue( $j++, $dto->mbProveMethod, PDO::PARAM_STR);
                        $stmt->bindValue( $j++, $dto->mbProveFile1, PDO::PARAM_STR);
                        $stmt->bindValue( $j++, $dto->mbProveFile2, PDO::PARAM_STR);
                        $stmt->bindValue( $j++, $dto->mbProveFile3, PDO::PARAM_STR);
                        $stmt->bindValue( $j++, $dto->reqIp, PDO::PARAM_STR);
                        $stmt->bindValue( $j++, $dto->adminConfirm, PDO::PARAM_STR);
                        $stmt->bindValue( $j++, $dto->adminConfirmDt, PDO::PARAM_STR);
                        $stmt->bindValue( $j++, $dto->adminMemo, PDO::PARAM_STR);
                        $stmt->bindValue( $j++, $dto->reqNo, PDO::PARAM_INT);
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

                    $sql = "DELETE FROM web_member_level_request WHERE req_no=?";

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