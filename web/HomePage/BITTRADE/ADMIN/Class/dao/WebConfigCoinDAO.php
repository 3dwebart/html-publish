<?php      
/**
* Description of WebConfigCoinDAO
* @description FunHanSoft Co.,Ltd  PHP auto templet
* @date 2017-07-12
* @copyright (c)funhansoft.com
* @license 해당 사이트 이외의 사용은 엄격히 금지되어 있습니다.
* @version 4.0.0
*/
        class WebConfigCoinDAO extends BaseDAO{

            private $db;
            private $dbSlave;

            function __construct() {
                parent::__construct();
            }

            public function getViewById($co_id){
                $dto = new WebConfigCoinDTO();
                if(!$this->dbSlave) $this->dbSlave=parent::getDatabaseSlave();
                if($this->dbSlave){
                    try{
                        $sql = "SELECT 	co_id,
				co_symbol,
				co_name,
				co_name_ko,
				co_name_zh,
				co_reg_dt 
                                FROM web_config_coin WHERE co_id=?  limit 1";

                        if($this->dbSlave) $stmt = $this->dbSlave->prepare($sql);
                        if($stmt){
                            $stmt->bindValue( 1, $co_id, PDO::PARAM_STR);
                            $stmt->execute();
                            list(
				$dto->coId,
				$dto->coSymbol,
				$dto->coName,
				$dto->coNameKo,
				$dto->coNameZh,
				$dto->coRegDt) = $stmt->fetch();
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
                        $singleFields = array('co_id');
                        $sql_add = $this->getListSearchSQL($singleFields,$field,$value);
                        
                        $sql = "SELECT count(*)  FROM web_config_coin{$sql_add}";

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
                $dto = new WebConfigCoinDTO();
                $dtoarray = array();
                if(!$this->dbSlave) $this->dbSlave=parent::getDatabaseSlave();
                if($this->dbSlave){
                    try{
                        $singleFields = array('co_id');
                        $sql_add = $this->getListSearchSQL($singleFields,$field,$value);
                        $sql_add .= $this->getListOrderSQL($singleFields,'co_id');
                        
                        $sql = "SELECT 	co_id,
				co_symbol,
				co_name,
				co_name_ko,
				co_name_zh,
				co_reg_dt 
                                FROM web_config_coin{$sql_add} LIMIT ?,?";

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
				$dto->coId,
				$dto->coSymbol,
				$dto->coName,
				$dto->coNameKo,
				$dto->coNameZh,
				$dto->coRegDt) = $stmt->fetch()) {
                                    $dtoarray[$i] = new WebConfigCoinDTO();
                                    parent::setResult($i+1);
                                    $dtoarray[$i]->coId = $dto->coId;
					$dtoarray[$i]->coSymbol = $dto->coSymbol;
					$dtoarray[$i]->coName = $dto->coName;
					$dtoarray[$i]->coNameKo = $dto->coNameKo;
					$dtoarray[$i]->coNameZh = $dto->coNameZh;
					$dtoarray[$i]->coRegDt = $dto->coRegDt;  
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
                        $sql = "INSERT INTO web_config_coin(
				co_symbol,
				co_name,
				co_name_ko,
				co_name_zh)
                                VALUES(?,?,?,?)";

                        if($this->db) $stmt = $this->db->prepare($sql);
                        if($stmt){
                            $j=1;
                            
		$stmt->bindValue( $j++, $dto->coSymbol, ($dto->coSymbol)?PDO::PARAM_STR:PDO::PARAM_NULL);
		$stmt->bindValue( $j++, $dto->coName, ($dto->coName)?PDO::PARAM_STR:PDO::PARAM_NULL);
		$stmt->bindValue( $j++, $dto->coNameKo, ($dto->coNameKo)?PDO::PARAM_STR:PDO::PARAM_NULL);
		$stmt->bindValue( $j++, $dto->coNameZh, ($dto->coNameZh)?PDO::PARAM_STR:PDO::PARAM_NULL);
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

                    $sql = "UPDATE web_config_coin SET 
				co_symbol=?,
				co_name=?,
				co_name_ko=?,
				co_name_zh=? WHERE co_id=?";

                    if($this->db) $stmt = $this->db->prepare($sql);
                    if($stmt){
                        $j=1;
                        
		$stmt->bindValue( $j++, $dto->coSymbol, ($dto->coSymbol)?PDO::PARAM_STR:PDO::PARAM_NULL);
		$stmt->bindValue( $j++, $dto->coName, ($dto->coName)?PDO::PARAM_STR:PDO::PARAM_NULL);
		$stmt->bindValue( $j++, $dto->coNameKo, ($dto->coNameKo)?PDO::PARAM_STR:PDO::PARAM_NULL);
		$stmt->bindValue( $j++, $dto->coNameZh, ($dto->coNameZh)?PDO::PARAM_STR:PDO::PARAM_NULL);
			
		$stmt->bindValue( $j++, $dto->coId, ($dto->coId)?PDO::PARAM_INT:PDO::PARAM_NULL);
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

                    $sql = "DELETE FROM web_config_coin WHERE co_id=?";

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