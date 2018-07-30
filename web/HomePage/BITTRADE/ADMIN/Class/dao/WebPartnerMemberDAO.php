<?php      
/**
* Description of WebPartnerMemberDAO
* @description Funhansoft PHP auto templet
* @date 2015-04-25
* @copyright (c)funhansoft.com
* @license http://funhansoft.com/license
* @version 4.0.0
*/
        class WebPartnerMemberDAO extends BaseDAO{

            private $db;
            private $dbSlave;

            function __construct() {
                parent::__construct();
            }

            public function getViewById($pm_no){
                $dto = new WebPartnerMemberDTO();
                if(!$this->dbSlave) $this->dbSlave=parent::getDatabaseSlave();
                if($this->dbSlave){
                    try{
                        $sql = "SELECT 	pm_no,
				pm_id,
				pm_code,
				pm_password,
				pm_name,
				pm_auth,
				pm_access_ip,
				pm_today_login,
				pm_login_ip,
				pm_datetime 
                                FROM web_partner_member WHERE pm_no=?  limit 1";

                        if($this->dbSlave) $stmt = $this->dbSlave->prepare($sql);
                        if($stmt){
                            $stmt->bindValue( 1, $pm_no, PDO::PARAM_STR);
                            $stmt->execute();
                            list(
				$dto->pmNo,
				$dto->pmId,
				$dto->pmCode,
				$dto->pmPassword,
				$dto->pmName,
				$dto->pmAuth,
				$dto->pmAccessIp,
				$dto->pmTodayLogin,
				$dto->pmLoginIp,
				$dto->pmDatetime) = $stmt->fetch();
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
                $dto = new WebPartnerMemberDTO();
                if(!$this->dbSlave) $this->dbSlave=parent::getDatabaseSlave();
                if($this->dbSlave){
                    try{
                        $sql = "SELECT 	pm_no,
				pm_id,
				pm_code,
				pm_password,
				pm_name,
				pm_auth,
				pm_access_ip,
				pm_today_login,
				pm_login_ip,
				pm_datetime 
                                FROM web_partner_member WHERE pm_id=?  limit 1";

                        if($this->dbSlave) $stmt = $this->dbSlave->prepare($sql);
                        if($stmt){
                            $stmt->bindValue( 1, $pm_no, PDO::PARAM_STR);
                            $stmt->execute();
                            list(
				$dto->pmNo,
				$dto->pmId,
				$dto->pmCode,
				$dto->pmPassword,
				$dto->pmName,
				$dto->pmAuth,
				$dto->pmAccessIp,
				$dto->pmTodayLogin,
				$dto->pmLoginIp,
				$dto->pmDatetime) = $stmt->fetch();
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
                        $singleFields = array('pm_no','pm_auth');
                        $sql_add = $this->getListSearchSQL($singleFields,$field,$value);
                        
                        $sql = "SELECT count(*)  FROM web_partner_member{$sql_add}";

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
                $dto = new WebPartnerMemberDTO();
                $dtoarray = array();
                if(!$this->dbSlave) $this->dbSlave=parent::getDatabaseSlave();
                if($this->dbSlave){
                    try{
                        $singleFields = array('pm_no','pm_auth');
                        $sql_add = $this->getListSearchSQL($singleFields,$field,$value);
                        $sql_add .= $this->getListOrderSQL($singleFields,'pm_no');
                        
                        $sql = "SELECT 	pm_no,
				pm_id,
				pm_code,
				pm_password,
				pm_name,
				pm_auth,
				pm_access_ip,
				pm_today_login,
				pm_login_ip,
				pm_datetime 
                                FROM web_partner_member{$sql_add} LIMIT ?,?";

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
				$dto->pmNo,
				$dto->pmId,
				$dto->pmCode,
				$dto->pmPassword,
				$dto->pmName,
				$dto->pmAuth,
				$dto->pmAccessIp,
				$dto->pmTodayLogin,
				$dto->pmLoginIp,
				$dto->pmDatetime) = $stmt->fetch()) {
                                    $dtoarray[$i] = new WebPartnerMemberDTO();
                                    parent::setResult($i+1);
                                    $dtoarray[$i]->pmNo = $dto->pmNo;
					$dtoarray[$i]->pmId = $dto->pmId;
					$dtoarray[$i]->pmCode = $dto->pmCode;
					$dtoarray[$i]->pmPassword = $dto->pmPassword;
					$dtoarray[$i]->pmName = $dto->pmName;
					$dtoarray[$i]->pmAuth = $dto->pmAuth;
					$dtoarray[$i]->pmAccessIp = $dto->pmAccessIp;
					$dtoarray[$i]->pmTodayLogin = $dto->pmTodayLogin;
					$dtoarray[$i]->pmLoginIp = $dto->pmLoginIp;
					$dtoarray[$i]->pmDatetime = $dto->pmDatetime;  
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
                        $sql = "INSERT INTO web_partner_member(
				pm_id,
				pm_code,
				pm_password,
				pm_name,
				pm_auth,
				pm_access_ip,
				pm_today_login,
				pm_login_ip)
                                VALUES(?,?,?,?,?,?,?,?)";

                        if($this->db) $stmt = $this->db->prepare($sql);
                        if($stmt){
                            $j=1;
                            
		$stmt->bindValue( $j++, $dto->pmId, PDO::PARAM_STR);
		$stmt->bindValue( $j++, $dto->pmCode, PDO::PARAM_STR);
		$stmt->bindValue( $j++, $dto->pmPassword, PDO::PARAM_STR);
		$stmt->bindValue( $j++, $dto->pmName, PDO::PARAM_STR);
		$stmt->bindValue( $j++, $dto->pmAuth, PDO::PARAM_STR);
		$stmt->bindValue( $j++, $dto->pmAccessIp, PDO::PARAM_STR);
		$stmt->bindValue( $j++, $dto->pmTodayLogin, PDO::PARAM_STR);
		$stmt->bindValue( $j++, $dto->pmLoginIp, PDO::PARAM_STR);
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

                    $sql = "UPDATE web_partner_member SET 
				pm_id=?,
				pm_code=?,
				pm_password=?,
				pm_name=?,
				pm_auth=?,
				pm_access_ip=?,
				pm_today_login=?,
				pm_login_ip=? WHERE pm_no=?";

                    if($this->db) $stmt = $this->db->prepare($sql);
                    if($stmt){
                        $j=1;
                        
		$stmt->bindValue( $j++, $dto->pmId, PDO::PARAM_STR);
		$stmt->bindValue( $j++, $dto->pmCode, PDO::PARAM_STR);
		$stmt->bindValue( $j++, $dto->pmPassword, PDO::PARAM_STR);
		$stmt->bindValue( $j++, $dto->pmName, PDO::PARAM_STR);
		$stmt->bindValue( $j++, $dto->pmAuth, PDO::PARAM_STR);
		$stmt->bindValue( $j++, $dto->pmAccessIp, PDO::PARAM_STR);
		$stmt->bindValue( $j++, $dto->pmTodayLogin, PDO::PARAM_STR);
		$stmt->bindValue( $j++, $dto->pmLoginIp, PDO::PARAM_STR);
			
		$stmt->bindValue( $j++, $dto->pmNo, PDO::PARAM_INT);
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

                    $sql = "DELETE FROM web_partner_member WHERE pm_no=?";

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