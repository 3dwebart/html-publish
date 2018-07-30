<?php
/**
* Description of WebAdminMemberDAO
* @description Funhansoft PHP auto templet
* @date 2013-09-04
* @copyright (c)funhansoft.com
* @license http://funhansoft.com/license
* @version 0.2.1
*/
        class WebAdminMemberDAO extends BaseDAO{

            private $db;
            private $dbSlave;

            function __construct() {
                parent::__construct();
            }

            public function getViewById($mb_no){
                $dto = new WebAdminMemberDTO();
                if(!$this->dbSlave) $this->dbSlave=parent::getDatabaseSlave(); if($this->dbSlave){
                    try{
                        $sql = "SELECT 	mb_no,
                                    mb_id,
                                    mb_password,
                                    mb_name,
                                    mb_auth,
                                    mb_access_ip,
                                    mb_today_login,
                                    mb_login_ip,
                                    mb_datetime
                                FROM web_admin_member WHERE mb_no=?  limit 1";

                        if($this->dbSlave) $stmt = $this->dbSlave->prepare($sql);
                        if($stmt){
                            $stmt->bindValue( 1, $mb_no, PDO::PARAM_STR);
                            $stmt->execute();
                            list(
                                $dto->mbNo,
                                $dto->mbId,
                                $dto->mbPassword,
                                $dto->mbName,
                                $dto->mbAuth,
                                $dto->mbAccessIp,
                                $dto->mbTodayLogin,
                                $dto->mbLoginIp,
                                $dto->mbDatetime) = $stmt->fetch();
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

            public function getViewByMbId($mb_id){
                $dto = new WebAdminMemberDTO();
                if(!$this->dbSlave) $this->dbSlave=parent::getDatabaseSlave(); if($this->dbSlave){
                    try{
                        $sql = "SELECT 	mb_no,
                                    mb_id,
                                    mb_password,
                                    mb_name,
                                    mb_auth,
                                    mb_access_ip,
                                    mb_today_login,
                                    mb_login_ip,
                                    mb_datetime
                                FROM web_admin_member WHERE mb_id=?  limit 1";

                        if($this->dbSlave) $stmt = $this->dbSlave->prepare($sql);
                        if($stmt){
                            $stmt->bindValue( 1, $mb_id, PDO::PARAM_STR);
                            $stmt->execute();
                            list(
                                $dto->mbNo,
                                $dto->mbId,
                                $dto->mbPassword,
                                $dto->mbName,
                                $dto->mbAuth,
                                $dto->mbAccessIp,
                                $dto->mbTodayLogin,
                                $dto->mbLoginIp,
                                $dto->mbDatetime) = $stmt->fetch();
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
                        $sql = "SELECT count(*)  FROM web_admin_member{$sql_add}";

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

            public function getList($field='',$value='',$svdf='',$svdt=''){
                $dto = new WebAdminMemberDTO();
                $dtoarray = array();
                if(!$this->dbSlave) $this->dbSlave=parent::getDatabaseSlave(); if($this->dbSlave){
                    try{
                        $sql_add = '';
                        if($value){
                            $sql_add = " WHERE {$field} LIKE CONCAT('%',?,'%')";
                            if($field=='mb_no'){ $sql_add = " WHERE {$field}=?"; }
                        }
                        $sql = "SELECT 	mb_no,
                                    mb_id,
                                    mb_password,
                                    mb_name,
                                    mb_auth,
                                    mb_access_ip,
                                    mb_today_login,
                                    mb_login_ip,
                                    mb_datetime
                                FROM web_admin_member{$sql_add} ORDER BY mb_no DESC LIMIT ?,?";

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
                                $dto->mbPassword,
                                $dto->mbName,
                                $dto->mbAuth,
                                $dto->mbAccessIp,
                                $dto->mbTodayLogin,
                                $dto->mbLoginIp,
                                $dto->mbDatetime) = $stmt->fetch()) {
                                    if($dto->mbAuth=='super'){
                                        $dto->mbAuth = '최고관리자권한';
                                    }else{
                                        $dto->mbAuth = '기본권한';
                                    }

                                    if($dto->mbAccessIp=='0.0.0.0'){
                                        $dto->mbAccessIp = '0.0.0.0(모든IP)';
                                    }
                                    $dtoarray[$i] = new WebAdminMemberDTO();
                                    parent::setResult($i+1);
                                    $dtoarray[$i]->mbNo = $dto->mbNo;
                                    $dtoarray[$i]->mbId = $dto->mbId;
                                    $dtoarray[$i]->mbPassword = $dto->mbPassword;
                                    $dtoarray[$i]->mbName = $dto->mbName;
                                    $dtoarray[$i]->mbAuth = $dto->mbAuth;
                                    $dtoarray[$i]->mbAccessIp = $dto->mbAccessIp;
                                    $dtoarray[$i]->mbTodayLogin = $dto->mbTodayLogin;
                                    $dtoarray[$i]->mbLoginIp = $dto->mbLoginIp;
                                    $dtoarray[$i]->mbDatetime = $dto->mbDatetime;
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

            public function getListByPri($pri){
                $dto = new WebAdminMemberDTO();
                $dtoarray = array();
                if(!$this->dbSlave) $this->dbSlave=parent::getDatabaseSlave(); if($this->dbSlave){
                    try{
                         $sql = "SELECT 	mb_no,
                                mb_id,
                                mb_password,
                                mb_name,
                                mb_auth,
                                mb_access_ip,
                                mb_today_login,
                                mb_login_ip,
                                mb_datetime
                            FROM web_admin_member WHERE mb_no LIKE CONCAT('%',?,'%') ORDER BY mb_no DESC LIMIT ?,?";

                        if($this->dbSlave) $stmt = $this->dbSlave->prepare($sql);
                        if($stmt){
                            $j=1;
                            $stmt->bindValue( $j++, $pri, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, parent::getListLimitStart(), PDO::PARAM_INT);
                            $stmt->bindValue( $j++, parent::getListLimitRow(), PDO::PARAM_INT);
                            $stmt->execute();

                            $i = 0;
                            while(list(
                                $dto->mbNo,
                                $dto->mbId,
                                $dto->mbPassword,
                                $dto->mbName,
                                $dto->mbAuth,
                                $dto->mbAccessIp,
                                $dto->mbTodayLogin,
                                $dto->mbLoginIp,
                                $dto->mbDatetime) = $stmt->fetch()) {
                                $dtoarray[$i] = new WebAdminMemberDTO();
                                parent::setResult($i+1);
                                $dtoarray[$i]->mbNo = $dto->mbNo;
                                $dtoarray[$i]->mbId = $dto->mbId;
                                $dtoarray[$i]->mbPassword = $dto->mbPassword;
                                $dtoarray[$i]->mbName = $dto->mbName;
                                $dtoarray[$i]->mbAuth = $dto->mbAuth;
                                $dtoarray[$i]->mbAccessIp = $dto->mbAccessIp;
                                $dtoarray[$i]->mbTodayLogin = $dto->mbTodayLogin;
                                $dtoarray[$i]->mbLoginIp = $dto->mbLoginIp;
                                $dtoarray[$i]->mbDatetime = $dto->mbDatetime;
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
                        $sql = "INSERT INTO web_admin_member(
                                mb_id,
                                mb_password,
                                mb_name,
                                mb_auth,
                                mb_access_ip,
                                mb_today_login,
                                mb_login_ip)
                                VALUES(?,?,?,?,?,?,?)";

                        if($this->db) $stmt = $this->db->prepare($sql);
                        if($stmt){
                            $j=1;

                            $stmt->bindValue( $j++, $dto->mbId, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, $dto->mbPassword, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, $dto->mbName, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, $dto->mbAuth, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, $dto->mbAccessIp, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, $dto->mbTodayLogin, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, $dto->mbLoginIp, PDO::PARAM_STR);
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

                    $sql = "UPDATE web_admin_member SET
                                mb_id=?,
                                mb_password=?,
                                mb_name=?,
                                mb_auth=?,
                                mb_access_ip=?,
                                mb_today_login=?,
                                mb_login_ip=? WHERE mb_no=?";

                    if($this->db) $stmt = $this->db->prepare($sql);
                    if($stmt){
                        $j=1;
                        $stmt->bindValue( $j++, $dto->mbId, PDO::PARAM_STR);
                        $stmt->bindValue( $j++, $dto->mbPassword, PDO::PARAM_STR);
                        $stmt->bindValue( $j++, $dto->mbName, PDO::PARAM_STR);
                        $stmt->bindValue( $j++, $dto->mbAuth, PDO::PARAM_STR);
                        $stmt->bindValue( $j++, $dto->mbAccessIp, PDO::PARAM_STR);
                        $stmt->bindValue( $j++, $dto->mbTodayLogin, PDO::PARAM_STR);
                        $stmt->bindValue( $j++, $dto->mbLoginIp, PDO::PARAM_STR);

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

                    $sql = "DELETE FROM web_admin_member WHERE mb_no=?";

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