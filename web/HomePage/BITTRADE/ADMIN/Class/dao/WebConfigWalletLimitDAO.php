<?php      
/**
* Description of WebConfigWalletLimitDAO
* @description FunHanSoft Co.,Ltd  PHP auto templet
* @date 2017-09-11
* @copyright (c)funhansoft.com
* @license 해당 사이트 이외의 사용은 엄격히 금지되어 있습니다.
* @version 4.0.0
*/
        class WebConfigWalletLimitDAO extends BaseDAO{

            private $db;
            private $dbSlave;

            function __construct() {
                parent::__construct();
            }

            public function getViewById($cf_no){
                $dto = new WebConfigWalletLimitDTO();
                if(!$this->dbSlave) $this->dbSlave=parent::getDatabaseSlave();
                if($this->dbSlave){
                    try{
                        $sql = "SELECT 	cf_no,
				cf_country_code,
				cf_wallet_type,
				cf_mb_level,
				cf_max_deposit,
				cf_max_withdraw,
				cf_min_withdraw,
				cf_max_day,
				cf_reg_dt 
                                FROM web_config_wallet_limit WHERE cf_no=?  limit 1";

                        if($this->dbSlave) $stmt = $this->dbSlave->prepare($sql);
                        if($stmt){
                            $stmt->bindValue( 1, $cf_no, PDO::PARAM_STR);
                            $stmt->execute();
                            list(
				$dto->cfNo,
				$dto->cfCountryCode,
				$dto->cfWalletType,
				$dto->cfMbLevel,
				$dto->cfMaxDeposit,
				$dto->cfMaxWithdraw,
				$dto->cfMinWithdraw,
				$dto->cfMaxDay,
				$dto->cfRegDt) = $stmt->fetch();
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
                        $singleFields = array('cf_no','cf_mb_level','cf_max_deposit','cf_max_withdraw','cf_max_day');
                        $sql_add = $this->getListSearchSQL($singleFields,$field,$value);
                        if($field == 'cf_mb_level' && $value == 0){
                            $sql_add = 'WHERE cf_mb_level=?';
                        }
                        if($svdf && $svdt){
                            if($sql_add){
                                $sql_add .= " AND ";
                            }else{
                                $sql_add .= " WHERE ";
                            }
                            $sql_add .= "(cf_reg_dt BETWEEN ? AND DATE_ADD(?,INTERVAL 1 DAY) ) ";
                        }
                        
                        $sql = "SELECT count(*)  FROM web_config_wallet_limit{$sql_add}";

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
                $dto = new WebConfigWalletLimitDTO();
                $dtoarray = array();
                if(!$this->dbSlave) $this->dbSlave=parent::getDatabaseSlave();
                if($this->dbSlave){
                    try{
                        $singleFields = array('cf_no','cf_mb_level','cf_max_deposit','cf_max_withdraw','cf_max_day');
                        $sql_add = $this->getListSearchSQL($singleFields,$field,$value);
                        if($field == 'cf_mb_level' && $value == 0){
                            $sql_add = 'WHERE cf_mb_level=?';
                        }
                        if($svdf && $svdt){
                            if($sql_add){
                                $sql_add .= " AND ";
                            }else{
                                $sql_add .= " WHERE ";
                            }
                            $sql_add .= "(cf_reg_dt BETWEEN ? AND DATE_ADD(?,INTERVAL 1 DAY) ) ";
                        }
                        $sql_add .= $this->getListOrderSQL($singleFields,'cf_no');
                        
                        $sql = "SELECT 	cf_no,
				cf_country_code,
				cf_wallet_type,
				cf_mb_level,
				cf_max_deposit,
				cf_max_withdraw,
				cf_min_withdraw,
				cf_max_day,
				cf_reg_dt 
                                FROM web_config_wallet_limit{$sql_add} LIMIT ?,?";

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
				$dto->cfNo,
				$dto->cfCountryCode,
				$dto->cfWalletType,
				$dto->cfMbLevel,
				$dto->cfMaxDeposit,
				$dto->cfMaxWithdraw,
				$dto->cfMinWithdraw,
				$dto->cfMaxDay,
				$dto->cfRegDt) = $stmt->fetch()) {
                                    $dtoarray[$i] = new WebConfigWalletLimitDTO();
                                    parent::setResult($i+1);
                                    $dtoarray[$i]->cfNo = $dto->cfNo;
					$dtoarray[$i]->cfCountryCode = $dto->cfCountryCode;
					$dtoarray[$i]->cfWalletType = $dto->cfWalletType;
					$dtoarray[$i]->cfMbLevel = $dto->cfMbLevel;
					$dtoarray[$i]->cfMaxDeposit = $dto->cfMaxDeposit;
					$dtoarray[$i]->cfMaxWithdraw = $dto->cfMaxWithdraw;
					$dtoarray[$i]->cfMinWithdraw = $dto->cfMinWithdraw;
					$dtoarray[$i]->cfMaxDay = $dto->cfMaxDay;
					$dtoarray[$i]->cfRegDt = $dto->cfRegDt;  
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
                        $sql = "INSERT INTO web_config_wallet_limit(
				cf_country_code,
				cf_wallet_type,
				cf_mb_level,
				cf_max_deposit,
				cf_max_withdraw,
				cf_min_withdraw,
				cf_max_day)
                                VALUES(?,?,?,?,?,?,?)";

                        if($this->db) $stmt = $this->db->prepare($sql);
                        if($stmt){
                            $j=1;
                            
		$stmt->bindValue( $j++, $dto->cfCountryCode, ($dto->cfCountryCode)?PDO::PARAM_STR:PDO::PARAM_NULL);
		$stmt->bindValue( $j++, $dto->cfWalletType, ($dto->cfWalletType)?PDO::PARAM_STR:PDO::PARAM_NULL);
		$stmt->bindValue( $j++, $dto->cfMbLevel, ($dto->cfMbLevel)?PDO::PARAM_INT:PDO::PARAM_NULL);
		$stmt->bindValue( $j++, $dto->cfMaxDeposit, ($dto->cfMaxDeposit)?PDO::PARAM_INT:PDO::PARAM_NULL);
		$stmt->bindValue( $j++, $dto->cfMaxWithdraw, ($dto->cfMaxWithdraw)?PDO::PARAM_INT:PDO::PARAM_NULL);
		$stmt->bindValue( $j++, $dto->cfMinWithdraw, PDO::PARAM_STR);
		$stmt->bindValue( $j++, $dto->cfMaxDay, PDO::PARAM_INT);
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

                    $sql = "UPDATE web_config_wallet_limit SET 
				cf_country_code=?,
				cf_wallet_type=?,
				cf_mb_level=?,
				cf_max_deposit=?,
				cf_max_withdraw=?,
				cf_min_withdraw=?,
				cf_max_day=? WHERE cf_no=?";

                    if($this->db) $stmt = $this->db->prepare($sql);
                    if($stmt){
                        $j=1;
                        
		$stmt->bindValue( $j++, $dto->cfCountryCode, ($dto->cfCountryCode)?PDO::PARAM_STR:PDO::PARAM_NULL);
		$stmt->bindValue( $j++, $dto->cfWalletType, ($dto->cfWalletType)?PDO::PARAM_STR:PDO::PARAM_NULL);
		$stmt->bindValue( $j++, $dto->cfMbLevel, ($dto->cfMbLevel)?PDO::PARAM_INT:PDO::PARAM_NULL);
		$stmt->bindValue( $j++, $dto->cfMaxDeposit, ($dto->cfMaxDeposit)?PDO::PARAM_INT:PDO::PARAM_NULL);
		$stmt->bindValue( $j++, $dto->cfMaxWithdraw, ($dto->cfMaxWithdraw)?PDO::PARAM_INT:PDO::PARAM_NULL);
		$stmt->bindValue( $j++, $dto->cfMinWithdraw, PDO::PARAM_STR);
		$stmt->bindValue( $j++, $dto->cfMaxDay, PDO::PARAM_INT);
			
		$stmt->bindValue( $j++, $dto->cfNo, ($dto->cfNo)?PDO::PARAM_INT:PDO::PARAM_NULL);
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

                    $sql = "DELETE FROM web_config_wallet_limit WHERE cf_no=?";

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