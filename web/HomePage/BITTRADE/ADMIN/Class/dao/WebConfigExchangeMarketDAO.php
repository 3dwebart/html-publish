<?php      
/**
* Description of WebConfigExchangeMarketDAO
* @description FunHanSoft Co.,Ltd  PHP auto templet
* @date 2017-08-19
* @copyright (c)funhansoft.com
* @license 해당 사이트 이외의 사용은 엄격히 금지되어 있습니다.
* @version 4.0.0
*/
        class WebConfigExchangeMarketDAO extends BaseDAO{

            private $db;
            private $dbSlave;

            function __construct() {
                parent::__construct();
            }

            public function getViewById($it_no){
                $dto = new WebConfigExchangeMarketDTO();
                if(!$this->dbSlave) $this->dbSlave=parent::getDatabaseSlave();
                if($this->dbSlave){
                    try{
                        $sql = "SELECT 	it_no,
				it_market_id,
				it_name,
				it_explain,
				it_std_co_id,
				it_exc_co_id,
				it_sort,
				it_use,
				it_server_ip,
				it_server_port,
				it_server_sign_ip,
				it_server_sign_port,
				it_wallet_use,
				it_reg_dt 
                                FROM web_config_exchange_market WHERE it_no=?  limit 1";

                        if($this->dbSlave) $stmt = $this->dbSlave->prepare($sql);
                        if($stmt){
                            $stmt->bindValue( 1, $it_no, PDO::PARAM_STR);
                            $stmt->execute();
                            list(
				$dto->itNo,
				$dto->itMarketId,
				$dto->itName,
				$dto->itExplain,
				$dto->itStdCoId,
				$dto->itExcCoId,
				$dto->itSort,
				$dto->itUse,
				$dto->itServerIp,
				$dto->itServerPort,
				$dto->itServerSignIp,
				$dto->itServerSignPort,
				$dto->itWalletUse,
				$dto->itRegDt) = $stmt->fetch();
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
                        $singleFields = array('it_no','it_std_co_id','it_exc_co_id','it_sort','it_use','it_wallet_use');
                        $sql_add = $this->getListSearchSQL($singleFields,$field,$value);
                        
                        $sql = "SELECT count(*)  FROM web_config_exchange_market{$sql_add}";

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
                $dto = new WebConfigExchangeMarketDTO();
                $dtoarray = array();
                if(!$this->dbSlave) $this->dbSlave=parent::getDatabaseSlave();
                if($this->dbSlave){
                    try{
                        $singleFields = array('it_no','it_std_co_id','it_exc_co_id','it_sort','it_use','it_wallet_use');
                        $sql_add = $this->getListSearchSQL($singleFields,$field,$value);
                        $sql_add .= $this->getListOrderSQL($singleFields,'it_no');
                        
                        $sql = "SELECT 	it_no,
				it_market_id,
				it_name,
				it_explain,
				it_std_co_id,
				it_exc_co_id,
				it_sort,
				it_use,
				it_server_ip,
				it_server_port,
				it_server_sign_ip,
				it_server_sign_port,
				it_wallet_use,
				it_reg_dt 
                                FROM web_config_exchange_market{$sql_add} LIMIT ?,?";

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
				$dto->itNo,
				$dto->itMarketId,
				$dto->itName,
				$dto->itExplain,
				$dto->itStdCoId,
				$dto->itExcCoId,
				$dto->itSort,
				$dto->itUse,
				$dto->itServerIp,
				$dto->itServerPort,
				$dto->itServerSignIp,
				$dto->itServerSignPort,
				$dto->itWalletUse,
				$dto->itRegDt) = $stmt->fetch()) {
                                    $dtoarray[$i] = new WebConfigExchangeMarketDTO();
                                    parent::setResult($i+1);
                                    $dtoarray[$i]->itNo = $dto->itNo;
					$dtoarray[$i]->itMarketId = $dto->itMarketId;
					$dtoarray[$i]->itName = $dto->itName;
					$dtoarray[$i]->itExplain = $dto->itExplain;
					$dtoarray[$i]->itStdCoId = $dto->itStdCoId;
					$dtoarray[$i]->itExcCoId = $dto->itExcCoId;
					$dtoarray[$i]->itSort = $dto->itSort;
					$dtoarray[$i]->itUse = $dto->itUse;
					$dtoarray[$i]->itServerIp = $dto->itServerIp;
					$dtoarray[$i]->itServerPort = $dto->itServerPort;
					$dtoarray[$i]->itServerSignIp = $dto->itServerSignIp;
					$dtoarray[$i]->itServerSignPort = $dto->itServerSignPort;
					$dtoarray[$i]->itWalletUse = $dto->itWalletUse;
					$dtoarray[$i]->itRegDt = $dto->itRegDt;  
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
                        $sql = "INSERT INTO web_config_exchange_market(
				it_market_id,
				it_name,
				it_explain,
				it_std_co_id,
				it_exc_co_id,
				it_sort,
				it_use,
				it_server_ip,
				it_server_port,
				it_server_sign_ip,
				it_server_sign_port,
				it_wallet_use)
                                VALUES(?,?,?,?,?,?,?,?,?,?,?,?)";

                        if($this->db) $stmt = $this->db->prepare($sql);
                        if($stmt){
                            $j=1;
                            
		$stmt->bindValue( $j++, $dto->itMarketId, ($dto->itMarketId)?PDO::PARAM_STR:PDO::PARAM_NULL);
		$stmt->bindValue( $j++, $dto->itName, ($dto->itName)?PDO::PARAM_STR:PDO::PARAM_NULL);
		$stmt->bindValue( $j++, $dto->itExplain, ($dto->itExplain)?PDO::PARAM_STR:PDO::PARAM_NULL);
		$stmt->bindValue( $j++, $dto->itStdCoId, ($dto->itStdCoId)?PDO::PARAM_INT:PDO::PARAM_NULL);
		$stmt->bindValue( $j++, $dto->itExcCoId, ($dto->itExcCoId)?PDO::PARAM_INT:PDO::PARAM_NULL);
		$stmt->bindValue( $j++, $dto->itSort, PDO::PARAM_INT);
		$stmt->bindValue( $j++, $dto->itUse, ($dto->itUse)?PDO::PARAM_STR:PDO::PARAM_NULL);
		$stmt->bindValue( $j++, $dto->itServerIp, PDO::PARAM_STR);
		$stmt->bindValue( $j++, $dto->itServerPort, PDO::PARAM_STR);
		$stmt->bindValue( $j++, $dto->itServerSignIp, PDO::PARAM_STR);
		$stmt->bindValue( $j++, $dto->itServerSignPort, PDO::PARAM_STR);
		$stmt->bindValue( $j++, $dto->itWalletUse, PDO::PARAM_STR);
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

                    $sql = "UPDATE web_config_exchange_market SET 
				it_market_id=?,
				it_name=?,
				it_explain=?,
				it_std_co_id=?,
				it_exc_co_id=?,
				it_sort=?,
				it_use=?,
				it_server_ip=?,
				it_server_port=?,
				it_server_sign_ip=?,
				it_server_sign_port=?,
				it_wallet_use=? WHERE it_no=?";

                    if($this->db) $stmt = $this->db->prepare($sql);
                    if($stmt){
                        $j=1;
                        
		$stmt->bindValue( $j++, $dto->itMarketId, ($dto->itMarketId)?PDO::PARAM_STR:PDO::PARAM_NULL);
		$stmt->bindValue( $j++, $dto->itName, ($dto->itName)?PDO::PARAM_STR:PDO::PARAM_NULL);
		$stmt->bindValue( $j++, $dto->itExplain, ($dto->itExplain)?PDO::PARAM_STR:PDO::PARAM_NULL);
		$stmt->bindValue( $j++, $dto->itStdCoId, ($dto->itStdCoId)?PDO::PARAM_INT:PDO::PARAM_NULL);
		$stmt->bindValue( $j++, $dto->itExcCoId, ($dto->itExcCoId)?PDO::PARAM_INT:PDO::PARAM_NULL);
		$stmt->bindValue( $j++, $dto->itSort, PDO::PARAM_INT);
		$stmt->bindValue( $j++, $dto->itUse, ($dto->itUse)?PDO::PARAM_STR:PDO::PARAM_NULL);
		$stmt->bindValue( $j++, $dto->itServerIp, PDO::PARAM_STR);
		$stmt->bindValue( $j++, $dto->itServerPort, PDO::PARAM_STR);
		$stmt->bindValue( $j++, $dto->itServerSignIp, PDO::PARAM_STR);
		$stmt->bindValue( $j++, $dto->itServerSignPort, PDO::PARAM_STR);
		$stmt->bindValue( $j++, $dto->itWalletUse, PDO::PARAM_STR);
			
		$stmt->bindValue( $j++, $dto->itNo, ($dto->itNo)?PDO::PARAM_INT:PDO::PARAM_NULL);
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

                    $sql = "DELETE FROM web_config_exchange_market WHERE it_no=?";

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