<?php      
/**
* Description of WebMemberDespositWalletDAO
* @description FunHanSoft Co.,Ltd  PHP auto templet
* @date 2017-08-19
* @copyright (c)funhansoft.com
* @license 해당 사이트 이외의 사용은 엄격히 금지되어 있습니다.
* @version 4.0.0
*/
        class WebMemberDespositWalletDAO extends BaseDAO{

            private $db;
            private $dbSlave;

            function __construct() {
                parent::__construct();
            }

            public function getViewById($mw_no){
                $dto = new WebMemberDespositWalletDTO();
                if(!$this->dbSlave) $this->dbSlave=parent::getDatabaseSlave();
                if($this->dbSlave){
                    try{
                        $sql = "SELECT 	mw_no,
                            wa_no,
                            po_type,
                            mb_no,
                            mw_address,
                            mw_reg_dt,
                            mb_reg_ip,
                            mw_server_host,
                            mw_etc1,
                            mw_etc2,
                            mw_etc3,
                            mw_etc4 
                                FROM web_member_desposit_wallet WHERE mw_no=?  limit 1";

                        if($this->dbSlave) $stmt = $this->dbSlave->prepare($sql);
                        if($stmt){
                            $stmt->bindValue( 1, $mw_no, PDO::PARAM_STR);
                            $stmt->execute();
                            list(
				$dto->mwNo,
				$dto->waNo,
				$dto->poType,
				$dto->mbNo,
				$dto->mwAddress,
				$dto->mwRegDt,
				$dto->mbRegIp,
				$dto->mwServerHost,
				$dto->mwEtc1,
				$dto->mwEtc2,
				$dto->mwEtc3,
				$dto->mwEtc4) = $stmt->fetch();
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
            
            public function getViewByMbNo($mb_no){
                $dto = new WebMemberDespositWalletDTO();
                if(!$this->dbSlave) $this->dbSlave=parent::getDatabaseSlave();
                if($this->dbSlave){
                    try{
                        $sql = "SELECT 	mw_no,
                            wa_no,
                            po_type,
                            mb_no,
                            mw_address,
                            mw_reg_dt,
                            mb_reg_ip,
                            mw_server_host,
                            mw_etc1,
                            mw_etc2,
                            mw_etc3,
                            mw_etc4 
                                FROM web_member_desposit_wallet WHERE mb_no=?  limit 1";

                        if($this->dbSlave) $stmt = $this->dbSlave->prepare($sql);
                        if($stmt){
                            $stmt->bindValue( 1, $mb_no, PDO::PARAM_STR);
                            $stmt->execute();
                            list(
				$dto->mwNo,
				$dto->waNo,
				$dto->poType,
				$dto->mbNo,
				$dto->mwAddress,
				$dto->mwRegDt,
				$dto->mbRegIp,
				$dto->mwServerHost,
				$dto->mwEtc1,
				$dto->mwEtc2,
				$dto->mwEtc3,
				$dto->mwEtc4) = $stmt->fetch();
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
                        $singleFields = array('mw_no','wa_no','mb_no');
                        $sql_add = $this->getListSearchSQL($singleFields,$field,$value);
                        
                        $sql = "SELECT count(*)  FROM web_member_desposit_wallet{$sql_add}";

                        if($this->dbSlave) $stmt = $this->dbSlave->prepare($sql);
                        if($stmt){
                            if($value)
                                $stmt->bindValue( 1, $value, PDO::PARAM_STR);
                            
                            $stmt->execute();
                            list($dto->totalCount) =  $stmt->fetch();
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
                $dto->totalPage = ceil((int)$dto->totalCount / parent::getListLimitRow() );
                $dto->limitRow =  (int)parent::getListLimitRow();
                
                return $dto;
            }

            public function getList($field='',$value='',$svdf='',$svdt=''){
                $dto = new WebMemberDespositWalletDTO();
                $dtoarray = array();
                if(!$this->dbSlave) $this->dbSlave=parent::getDatabaseSlave();
                if($this->dbSlave){
                    try{
                        $singleFields = array('mw_no','wa_no','mb_no');
                        $sql_add = $this->getListSearchSQL($singleFields,$field,$value);
                        $sql_add .= $this->getListOrderSQL($singleFields,'mw_no');
                        
                        $sql = "SELECT 	mw_no,
				wa_no,
				po_type,
				mb_no,
				mw_address,
				mw_reg_dt,
				mb_reg_ip,
				mw_server_host,
				mw_etc1,
				mw_etc2,
				mw_etc3,
				mw_etc4 
                                FROM web_member_desposit_wallet{$sql_add} LIMIT ?,?";

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
				$dto->mwNo,
				$dto->waNo,
				$dto->poType,
				$dto->mbNo,
				$dto->mwAddress,
				$dto->mwRegDt,
				$dto->mbRegIp,
				$dto->mwServerHost,
				$dto->mwEtc1,
				$dto->mwEtc2,
				$dto->mwEtc3,
				$dto->mwEtc4) = $stmt->fetch()) {
                                    $dtoarray[$i] = new WebMemberDespositWalletDTO();
                                    parent::setResult($i+1);
                                    $dtoarray[$i]->mwNo = $dto->mwNo;
					$dtoarray[$i]->waNo = $dto->waNo;
					$dtoarray[$i]->poType = $dto->poType;
					$dtoarray[$i]->mbNo = $dto->mbNo;
					$dtoarray[$i]->mwAddress = $dto->mwAddress;
					$dtoarray[$i]->mwRegDt = $dto->mwRegDt;
					$dtoarray[$i]->mbRegIp = $dto->mbRegIp;
					$dtoarray[$i]->mwServerHost = $dto->mwServerHost;
					$dtoarray[$i]->mwEtc1 = $dto->mwEtc1;
					$dtoarray[$i]->mwEtc2 = $dto->mwEtc2;
					$dtoarray[$i]->mwEtc3 = $dto->mwEtc3;
					$dtoarray[$i]->mwEtc4 = $dto->mwEtc4;  
                                    $i++;
                            }

                            if($i==0) parent::setResult(0);
                        }
                    }catch(DBException $e){ parent::setResult(Error::dbPrepare);}
                }else{
                    parent::setResult(Error::dbConn);
                }
                $dto = null;
                return $dtoarray;
            }

            public function setInsert($dto){
                parent::setResult(-1);
                if(!$this->db) $this->db=parent::getDatabase();
                if($this->db){
                    try{
                        $sql = "INSERT INTO web_member_desposit_wallet(
				wa_no,
				po_type,
				mb_no,
				mw_address,
				mb_reg_ip,
				mw_server_host,
				mw_etc1,
				mw_etc2,
				mw_etc3,
				mw_etc4)
                                VALUES(?,?,?,?,?,?,?,?,?,?)";

                        if($this->db) $stmt = $this->db->prepare($sql);
                        if($stmt){
                            $j=1;
                            
		$stmt->bindValue( $j++, $dto->waNo, ($dto->waNo)?PDO::PARAM_INT:PDO::PARAM_NULL);
		$stmt->bindValue( $j++, $dto->poType, ($dto->poType)?PDO::PARAM_STR:PDO::PARAM_NULL);
		$stmt->bindValue( $j++, $dto->mbNo, ($dto->mbNo)?PDO::PARAM_INT:PDO::PARAM_NULL);
		$stmt->bindValue( $j++, $dto->mwAddress, ($dto->mwAddress)?PDO::PARAM_STR:PDO::PARAM_NULL);
		$stmt->bindValue( $j++, $dto->mbRegIp, PDO::PARAM_STR);
		$stmt->bindValue( $j++, $dto->mwServerHost, ($dto->mwServerHost)?PDO::PARAM_STR:PDO::PARAM_NULL);
		$stmt->bindValue( $j++, $dto->mwEtc1, ($dto->mwEtc1)?PDO::PARAM_STR:PDO::PARAM_NULL);
		$stmt->bindValue( $j++, $dto->mwEtc2, ($dto->mwEtc2)?PDO::PARAM_STR:PDO::PARAM_NULL);
		$stmt->bindValue( $j++, $dto->mwEtc3, ($dto->mwEtc3)?PDO::PARAM_STR:PDO::PARAM_NULL);
		$stmt->bindValue( $j++, $dto->mwEtc4, ($dto->mwEtc4)?PDO::PARAM_STR:PDO::PARAM_NULL);
                            $stmt->execute();
                            if($stmt->rowCount()==1) parent::setResult($this->db->lastInsertId());
                            else parent::setResult(0);
                        }
                    
                    }catch(DBException $e){ parent::setResult(Error::dbPrepare);}
                }else{
                    parent::setResult(Error::dbConn);
                }
                return parent::getResult();
            }

            public function setUpdate($dto){
                parent::setResult(-1);
                if(!$this->db) $this->db=parent::getDatabase();
                if($this->db){

                    $sql = "UPDATE web_member_desposit_wallet SET 
				wa_no=?,
				po_type=?,
				mb_no=?,
				mw_address=?,
				mb_reg_ip=?,
				mw_server_host=?,
				mw_etc1=?,
				mw_etc2=?,
				mw_etc3=?,
				mw_etc4=? WHERE mw_no=?";

                    if($this->db) $stmt = $this->db->prepare($sql);
                    if($stmt){
                        $j=1;
                        
		$stmt->bindValue( $j++, $dto->waNo, ($dto->waNo)?PDO::PARAM_INT:PDO::PARAM_NULL);
		$stmt->bindValue( $j++, $dto->poType, ($dto->poType)?PDO::PARAM_STR:PDO::PARAM_NULL);
		$stmt->bindValue( $j++, $dto->mbNo, ($dto->mbNo)?PDO::PARAM_INT:PDO::PARAM_NULL);
		$stmt->bindValue( $j++, $dto->mwAddress, ($dto->mwAddress)?PDO::PARAM_STR:PDO::PARAM_NULL);
		$stmt->bindValue( $j++, $dto->mbRegIp, PDO::PARAM_STR);
		$stmt->bindValue( $j++, $dto->mwServerHost, ($dto->mwServerHost)?PDO::PARAM_STR:PDO::PARAM_NULL);
		$stmt->bindValue( $j++, $dto->mwEtc1, ($dto->mwEtc1)?PDO::PARAM_STR:PDO::PARAM_NULL);
		$stmt->bindValue( $j++, $dto->mwEtc2, ($dto->mwEtc2)?PDO::PARAM_STR:PDO::PARAM_NULL);
		$stmt->bindValue( $j++, $dto->mwEtc3, ($dto->mwEtc3)?PDO::PARAM_STR:PDO::PARAM_NULL);
		$stmt->bindValue( $j++, $dto->mwEtc4, ($dto->mwEtc4)?PDO::PARAM_STR:PDO::PARAM_NULL);
			
		$stmt->bindValue( $j++, $dto->mwNo, ($dto->mwNo)?PDO::PARAM_INT:PDO::PARAM_NULL);
                        $stmt->execute();
                        if($stmt->rowCount()==1) parent::setResult(1);
                        else parent::setResult(0);
                    }else{
                        parent::setResult(Error::dbPrepare);
                    }
                }else{
                    parent::setResult(Error::dbConn);
                }
                return parent::getResult();
            }

            function deleteFromPri($pri){
                parent::setResult(-1);
                if(!$this->db) $this->db=parent::getDatabase();
                if($this->db){

                    $sql = "DELETE FROM web_member_desposit_wallet WHERE mw_no=?";

                    if($this->db) $stmt = $this->db->prepare($sql);
                    if($stmt){
                        $stmt->bindValue( 1, $pri, PDO::PARAM_STR);
                        $stmt->execute();
                        if($stmt->rowCount()>0) parent::setResult($stmt->rowCount());
                        else parent::setResult(0);
                    }else{
                        parent::setResult(Error::dbPrepare);
                    }
                }else{
                    parent::setResult(Error::dbConn);
                }
                return parent::getResult();
            }

            function __destruct(){
                $this->db = NULL;
                $this->dto = NULL;
            }
        }