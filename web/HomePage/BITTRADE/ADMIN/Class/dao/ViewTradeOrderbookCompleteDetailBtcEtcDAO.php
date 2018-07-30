<?php      
/**
* Description of ViewTradeOrderbookCompleteDetailBtcEtcDAO
* @description FunHanSoft Co.,Ltd  PHP auto templet
* @date 2017-07-12
* @copyright (c)funhansoft.com
* @license 해당 사이트 이외의 사용은 엄격히 금지되어 있습니다.
* @version 4.0.0
*/
        class ViewTradeOrderbookCompleteDetailBtcEtcDAO extends BaseDAO{

            private $db;
            private $dbSlave;

            function __construct() {
                parent::__construct();
            }

            

            public function getListCount($field='',$value='',$svdf='',$svdt=''){
                $dto = new CommonListDTO();
                if(!$this->dbSlave) $this->dbSlave=parent::getDatabaseTradeSlave();
                if($this->dbSlave){
                    try{
                        $singleFields = array('tr_no','od_action','od_id');
                        $sql_add = $this->getListSearchSQL($singleFields,$field,$value);
                        
                        $sql = "SELECT count(*)  FROM view_trade_orderbook_complete_detail_btc_etc{$sql_add}";

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
                $dto = new ViewTradeOrderbookCompleteDetailBtcEtcDTO();
                $dtoarray = array();
                if(!$this->dbSlave) $this->dbSlave=parent::getDatabaseTradeSlave();
                if($this->dbSlave){
                    try{
                        $singleFields = array('tr_no','od_action','od_id');
                        $sql_add = $this->getListSearchSQL($singleFields,$field,$value);
                        $sql_add .= $this->getListOrderSQL($singleFields,'');
                        
                        $sql = "SELECT 	tr_no,
				od_action,
				tr_market_cost,
				tr_total_coin,
				tr_reg_dt,
				od_id,
				tr_total_cost 
                                FROM view_trade_orderbook_complete_detail_btc_etc{$sql_add} LIMIT ?,?";

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
				$dto->trTotalCost) = $stmt->fetch()) {
                                    $dtoarray[$i] = new ViewTradeOrderbookCompleteDetailBtcEtcDTO();
                                    parent::setResult($i+1);
                                    $dtoarray[$i]->trNo = $dto->trNo;
					$dtoarray[$i]->odAction = $dto->odAction;
					$dtoarray[$i]->trMarketCost = $dto->trMarketCost;
					$dtoarray[$i]->trTotalCoin = $dto->trTotalCoin;
					$dtoarray[$i]->trRegDt = $dto->trRegDt;
					$dtoarray[$i]->odId = $dto->odId;
					$dtoarray[$i]->trTotalCost = $dto->trTotalCost;  
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
                if(!$this->db) $this->db=parent::getDatabaseTrade();
                if($this->db){
                    try{
                        $sql = "INSERT INTO view_trade_orderbook_complete_detail_btc_etc(
				od_action,
				tr_market_cost,
				tr_total_coin,
				tr_reg_dt,
				od_id,
				tr_total_cost)
                                VALUES(?,?,?,?,?,?)";

                        if($this->db) $stmt = $this->db->prepare($sql);
                        if($stmt){
                            $j=1;
                            
		$stmt->bindValue( $j++, $dto->odAction, ($dto->odAction)?PDO::PARAM_STR:PDO::PARAM_NULL);
		$stmt->bindValue( $j++, $dto->trMarketCost, PDO::PARAM_STR);
		$stmt->bindValue( $j++, $dto->trTotalCoin, PDO::PARAM_STR);
		$stmt->bindValue( $j++, $dto->trRegDt, ($dto->trRegDt)?PDO::PARAM_STR:PDO::PARAM_NULL);
		$stmt->bindValue( $j++, $dto->odId, ($dto->odId)?PDO::PARAM_INT:PDO::PARAM_NULL);
		$stmt->bindValue( $j++, $dto->trTotalCost, PDO::PARAM_STR);
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
                if(!$this->db) $this->db=parent::getDatabaseTrade();
                if($this->db){

                    $sql = "UPDATE view_trade_orderbook_complete_detail_btc_etc SET 
				od_action=?,
				tr_market_cost=?,
				tr_total_coin=?,
				tr_reg_dt=?,
				od_id=?,
				tr_total_cost=? WHERE =?";

                    if($this->db) $stmt = $this->db->prepare($sql);
                    if($stmt){
                        $j=1;
                        
		$stmt->bindValue( $j++, $dto->odAction, ($dto->odAction)?PDO::PARAM_STR:PDO::PARAM_NULL);
		$stmt->bindValue( $j++, $dto->trMarketCost, PDO::PARAM_STR);
		$stmt->bindValue( $j++, $dto->trTotalCoin, PDO::PARAM_STR);
		$stmt->bindValue( $j++, $dto->trRegDt, ($dto->trRegDt)?PDO::PARAM_STR:PDO::PARAM_NULL);
		$stmt->bindValue( $j++, $dto->odId, ($dto->odId)?PDO::PARAM_INT:PDO::PARAM_NULL);
		$stmt->bindValue( $j++, $dto->trTotalCost, PDO::PARAM_STR);
			
		$stmt->bindValue( $j++, $dto->trNo, PDO::PARAM_INT);
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
                if(!$this->db) $this->db=parent::getDatabaseTrade();
                if($this->db){

                    $sql = "DELETE FROM view_trade_orderbook_complete_detail_btc_etc WHERE =?";

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