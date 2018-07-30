<?php      
/**
* Description of WebTradeBitcoinCompleteDAO
* @description Funhansoft PHP auto templet
* @date 2015-08-21
* @copyright (c)funhansoft.com
* @license http://funhansoft.com/license
* @version 4.0.0
*/
        class WebTradeBitcoinCompleteDAO extends BaseDAO{

            private $db;
            private $dbSlave;

            function __construct() {
                parent::__construct();
            }

            public function getViewById($tr_no){
                $dto = new WebTradeBitcoinCompleteDTO();
                if(!$this->dbSlave) $this->dbSlave=parent::getDatabaseTradeSlave();
                if($this->dbSlave){
                    try{
                        $sql = "SELECT 	tr_no,
				tr_market_cost,
				tr_total_coin,
				tr_reg_dt,
				od_id,
				from_mb_no,
				from_mb_id 
                                FROM web_trade_bitcoin_complete WHERE tr_no=?  limit 1";

                        if($this->dbSlave) $stmt = $this->dbSlave->prepare($sql);
                        if($stmt){
                            $stmt->bindValue( 1, $tr_no, PDO::PARAM_STR);
                            $stmt->execute();
                            list(
				$dto->trNo,
				$dto->trMarketCost,
				$dto->trTotalCoin,
				$dto->trRegDt,
				$dto->odId,
				$dto->fromMbNo,
				$dto->fromMbId) = $stmt->fetch();
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
                if(!$this->dbSlave) $this->dbSlave=parent::getDatabaseTradeSlave();
                if($this->dbSlave){
                    try{
                        $singleFields = array('tr_no','od_id','from_mb_no');
                        $sql_add = $this->getListSearchSQL($singleFields,$field,$value);
                         if($svdf && $svdt){
                            if($sql_add){
                                $sql_add .= " AND ";
                            }else{
                                $sql_add .= " WHERE ";
                            }
                            $sql_add .= "(tr_reg_dt BETWEEN ? AND DATE_ADD(?,INTERVAL 1 DAY) ) ";
                        }
                        $sql = "SELECT count(*)  FROM web_trade_bitcoin_complete{$sql_add}";

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
                $dto = new WebTradeBitcoinCompleteDTO();
                $dtoarray = array();
                if(!$this->dbSlave) $this->dbSlave=parent::getDatabaseTradeSlave();
                if($this->dbSlave){
                    try{
                        $singleFields = array('tr_no','od_id','from_mb_no');
                        $sql_add = $this->getListSearchSQL($singleFields,$field,$value);
                        if($svdf && $svdt){
                            if($sql_add){
                                $sql_add .= " AND ";
                            }else{
                                $sql_add .= " WHERE ";
                            }
                            $sql_add .= "(tr_reg_dt BETWEEN ? AND DATE_ADD(?,INTERVAL 1 DAY) ) ";
                        }
                        $sql_add .= $this->getListOrderSQL($singleFields,'tr_no');
                        
                        $sql = "SELECT 	tr_no,
				tr_market_cost,
				tr_total_coin,
				tr_reg_dt,
				od_id,
				from_mb_no,
				from_mb_id 
                                FROM web_trade_bitcoin_complete{$sql_add} LIMIT ?,?";

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
				$dto->trNo,
				$dto->trMarketCost,
				$dto->trTotalCoin,
				$dto->trRegDt,
				$dto->odId,
				$dto->fromMbNo,
				$dto->fromMbId) = $stmt->fetch()) {
                                    $dtoarray[$i] = new WebTradeBitcoinCompleteDTO();
                                    parent::setResult($i+1);
                                    $dtoarray[$i]->trNo = $dto->trNo;
					$dtoarray[$i]->trMarketCost = $dto->trMarketCost;
					$dtoarray[$i]->trTotalCoin = $dto->trTotalCoin;
					$dtoarray[$i]->trRegDt = $dto->trRegDt;
					$dtoarray[$i]->odId = $dto->odId;
					$dtoarray[$i]->fromMbNo = $dto->fromMbNo;
					$dtoarray[$i]->fromMbId = $dto->fromMbId;  
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
                if(!$this->db) $this->db=parent::getDatabaseTrade();
                if($this->db){
                    try{
                        $sql = "INSERT INTO web_trade_bitcoin_complete(
				tr_market_cost,
				tr_total_coin,
				od_id,
				from_mb_no,
				from_mb_id)
                                VALUES(?,?,?,?,?)";

                        if($this->db) $stmt = $this->db->prepare($sql);
                        if($stmt){
                            $j=1;
                            
		$stmt->bindValue( $j++, $dto->trMarketCost, PDO::PARAM_STR);
		$stmt->bindValue( $j++, $dto->trTotalCoin, PDO::PARAM_STR);
		$stmt->bindValue( $j++, $dto->odId, PDO::PARAM_INT);
		$stmt->bindValue( $j++, $dto->fromMbNo, PDO::PARAM_INT);
		$stmt->bindValue( $j++, $dto->fromMbId, PDO::PARAM_STR);
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
                if(!$this->db) $this->db=parent::getDatabaseTrade();
                if($this->db){

                    $sql = "UPDATE web_trade_bitcoin_complete SET 
				tr_market_cost=?,
				tr_total_coin=?,
				od_id=?,
				from_mb_no=?,
				from_mb_id=? WHERE tr_no=?";

                    if($this->db) $stmt = $this->db->prepare($sql);
                    if($stmt){
                        $j=1;
                        
		$stmt->bindValue( $j++, $dto->trMarketCost, PDO::PARAM_STR);
		$stmt->bindValue( $j++, $dto->trTotalCoin, PDO::PARAM_STR);
		$stmt->bindValue( $j++, $dto->odId, PDO::PARAM_INT);
		$stmt->bindValue( $j++, $dto->fromMbNo, PDO::PARAM_INT);
		$stmt->bindValue( $j++, $dto->fromMbId, PDO::PARAM_STR);
			
		$stmt->bindValue( $j++, $dto->trNo, PDO::PARAM_INT);
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
                if(!$this->db) $this->db=parent::getDatabaseTrade();
                if($this->db){

                    $sql = "DELETE FROM web_trade_bitcoin_complete WHERE tr_no=?";

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