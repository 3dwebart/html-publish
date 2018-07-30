<?php      
/**
* Description of WebTradeCompleteDAO
* @description FunHanSoft Co.,Ltd  PHP auto templet
* @date 2017-07-12
* @copyright (c)funhansoft.com
* @license 해당 사이트 이외의 사용은 엄격히 금지되어 있습니다.
* @version 4.0.0
*/
        class WebTradeCompleteDAO extends BaseDAO{

            private $db;
            private $dbSlave;
            private $dbName = 'fns_trade_order_etc';
            private $dbTableName = 'web_trade_complete_krw_etc';

            function __construct() {
                parent::__construct();
            }
            
            public function setDbName($nm){
                $this->dbName = $nm;
            }
            
            public function setTableName($nm){
                $this->dbTableName = $nm;
            }

            public function getViewById($tr_no){
                $dto = new WebTradeCompleteDTO();
                if(!$this->dbSlave) $this->dbSlave=parent::getDatabaseTradeSlave($this->dbName);
                if($this->dbSlave){
                    try{
                        $sql = "SELECT 	tr_no,
				od_action,
				tr_market_cost,
				tr_total_coin,
				tr_reg_dt,
				od_id,
				from_mb_no,
				tr_sync_dt 
                                FROM ".$this->dbTableName." WHERE tr_no=?  limit 1";

                        if($this->dbSlave) $stmt = $this->dbSlave->prepare($sql);
                        if($stmt){
                            $stmt->bindValue( 1, $tr_no, PDO::PARAM_STR);
                            $stmt->execute();
                            list(
				$dto->trNo,
				$dto->odAction,
				$dto->trMarketCost,
				$dto->trTotalCoin,
				$dto->trRegDt,
				$dto->odId,
				$dto->fromMbNo,
				$dto->trSyncDt) = $stmt->fetch();
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
                if(!$this->dbSlave) $this->dbSlave=parent::getDatabaseTradeSlave($this->dbName);
                if($this->dbSlave){
                    try{
                        $singleFields = array('tr_no','od_action','od_id','from_mb_no');
                        $sql_add = $this->getListSearchSQL($singleFields,$field,$value);
                        
                        $sql = "SELECT count(*)  FROM ".$this->dbTableName."{$sql_add}";

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
                $dto = new WebTradeCompleteDTO();
                $dtoarray = array();
                if(!$this->dbSlave) $this->dbSlave=parent::getDatabaseTradeSlave($this->dbName);
                if($this->dbSlave){
                    try{
                        $singleFields = array('tr_no','od_action','od_id','from_mb_no');
                        $sql_add = $this->getListSearchSQL($singleFields,$field,$value);
                        $sql_add .= $this->getListOrderSQL($singleFields,'tr_no');
                        
                        $sql = "SELECT 	tr_no,
				od_action,
				tr_market_cost,
				tr_total_coin,
				tr_reg_dt,
				od_id,
				from_mb_no,
				tr_sync_dt 
                                FROM ".$this->dbTableName."{$sql_add} LIMIT ?,?";

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
				$dto->trNo,
				$dto->odAction,
				$dto->trMarketCost,
				$dto->trTotalCoin,
				$dto->trRegDt,
				$dto->odId,
				$dto->fromMbNo,
				$dto->trSyncDt) = $stmt->fetch()) {
                                    $dtoarray[$i] = new WebTradeCompleteDTO();
                                    parent::setResult($i+1);
                                    $dtoarray[$i]->trNo = $dto->trNo;
					$dtoarray[$i]->odAction = $dto->odAction;
					$dtoarray[$i]->trMarketCost = $dto->trMarketCost;
					$dtoarray[$i]->trTotalCoin = $dto->trTotalCoin;
					$dtoarray[$i]->trRegDt = $dto->trRegDt;
					$dtoarray[$i]->odId = $dto->odId;
					$dtoarray[$i]->fromMbNo = $dto->fromMbNo;
					$dtoarray[$i]->trSyncDt = $dto->trSyncDt;  
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
                if(!$this->db) $this->db=parent::getDatabaseTrade($this->dbName);
                if($this->db){
                    try{
                        $sql = "INSERT INTO ".$this->dbTableName."(
				od_action,
				tr_market_cost,
				tr_total_coin,
				tr_reg_dt,
				od_id,
				from_mb_no)
                                VALUES(?,?,?,?,?,?)";

                        if($this->db) $stmt = $this->db->prepare($sql);
                        if($stmt){
                            $j=1;
                            
		$stmt->bindValue( $j++, $dto->odAction, ($dto->odAction)?PDO::PARAM_STR:PDO::PARAM_NULL);
		$stmt->bindValue( $j++, $dto->trMarketCost, PDO::PARAM_STR);
		$stmt->bindValue( $j++, $dto->trTotalCoin, PDO::PARAM_STR);
		$stmt->bindValue( $j++, $dto->trRegDt, ($dto->trRegDt)?PDO::PARAM_STR:PDO::PARAM_NULL);
		$stmt->bindValue( $j++, $dto->odId, ($dto->odId)?PDO::PARAM_INT:PDO::PARAM_NULL);
		$stmt->bindValue( $j++, $dto->fromMbNo, ($dto->fromMbNo)?PDO::PARAM_INT:PDO::PARAM_NULL);
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
                if(!$this->db) $this->db=parent::getDatabaseTrade($this->dbName);
                if($this->db){

                    $sql = "UPDATE ".$this->dbTableName." SET 
				od_action=?,
				tr_market_cost=?,
				tr_total_coin=?,
				tr_reg_dt=?,
				od_id=?,
				from_mb_no=? WHERE tr_no=?";

                    if($this->db) $stmt = $this->db->prepare($sql);
                    if($stmt){
                        $j=1;
                        
		$stmt->bindValue( $j++, $dto->odAction, ($dto->odAction)?PDO::PARAM_STR:PDO::PARAM_NULL);
		$stmt->bindValue( $j++, $dto->trMarketCost, PDO::PARAM_STR);
		$stmt->bindValue( $j++, $dto->trTotalCoin, PDO::PARAM_STR);
		$stmt->bindValue( $j++, $dto->trRegDt, ($dto->trRegDt)?PDO::PARAM_STR:PDO::PARAM_NULL);
		$stmt->bindValue( $j++, $dto->odId, ($dto->odId)?PDO::PARAM_INT:PDO::PARAM_NULL);
		$stmt->bindValue( $j++, $dto->fromMbNo, ($dto->fromMbNo)?PDO::PARAM_INT:PDO::PARAM_NULL);
			
		$stmt->bindValue( $j++, $dto->trNo, ($dto->trNo)?PDO::PARAM_INT:PDO::PARAM_NULL);
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
                if(!$this->db) $this->db=parent::getDatabaseTrade($this->dbName);
                if($this->db){

                    $sql = "DELETE FROM ".$this->dbTableName." WHERE tr_no=?";

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