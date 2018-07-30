<?php      
/**
* Description of WebChartDataMinuteBtcEtcDAO
* @description FunHanSoft Co.,Ltd  PHP auto templet
* @date 2017-07-12
* @copyright (c)funhansoft.com
* @license 해당 사이트 이외의 사용은 엄격히 금지되어 있습니다.
* @version 4.0.0
*/
        class WebChartDataMinuteBtcEtcDAO extends BaseDAO{

            private $db;
            private $dbSlave;

            function __construct() {
                parent::__construct();
            }

            public function getViewById($ch_date){
                $dto = new WebChartDataMinuteBtcEtcDTO();
                if(!$this->dbSlave) $this->dbSlave=parent::getDatabaseTradeSlave();
                if($this->dbSlave){
                    try{
                        $sql = "SELECT 	ch_date,
				ch_trade_count,
				ch_total_amount,
				ch_begin_cost,
				ch_max_cost,
				ch_min_cost,
				ch_close_cost,
				ch_reg_dt 
                                FROM web_chart_data_minute_btc_etc WHERE ch_date=?  limit 1";

                        if($this->dbSlave) $stmt = $this->dbSlave->prepare($sql);
                        if($stmt){
                            $stmt->bindValue( 1, $ch_date, PDO::PARAM_STR);
                            $stmt->execute();
                            list(
				$dto->chDate,
				$dto->chTradeCount,
				$dto->chTotalAmount,
				$dto->chBeginCost,
				$dto->chMaxCost,
				$dto->chMinCost,
				$dto->chCloseCost,
				$dto->chRegDt) = $stmt->fetch();
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
                if(!$this->dbSlave) $this->dbSlave=parent::getDatabaseTradeSlave();
                if($this->dbSlave){
                    try{
                        $singleFields = array('ch_date','ch_trade_count');
                        $sql_add = $this->getListSearchSQL($singleFields,$field,$value);
                        
                        $sql = "SELECT count(*)  FROM web_chart_data_minute_btc_etc{$sql_add}";

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
                $dto = new WebChartDataMinuteBtcEtcDTO();
                $dtoarray = array();
                if(!$this->dbSlave) $this->dbSlave=parent::getDatabaseTradeSlave();
                if($this->dbSlave){
                    try{
                        $singleFields = array('ch_date','ch_trade_count');
                        $sql_add = $this->getListSearchSQL($singleFields,$field,$value);
                        $sql_add .= $this->getListOrderSQL($singleFields,'ch_date');
                        
                        $sql = "SELECT 	ch_date,
				ch_trade_count,
				ch_total_amount,
				ch_begin_cost,
				ch_max_cost,
				ch_min_cost,
				ch_close_cost,
				ch_reg_dt 
                                FROM web_chart_data_minute_btc_etc{$sql_add} LIMIT ?,?";

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
				$dto->chDate,
				$dto->chTradeCount,
				$dto->chTotalAmount,
				$dto->chBeginCost,
				$dto->chMaxCost,
				$dto->chMinCost,
				$dto->chCloseCost,
				$dto->chRegDt) = $stmt->fetch()) {
                                    $dtoarray[$i] = new WebChartDataMinuteBtcEtcDTO();
                                    parent::setResult($i+1);
                                    $dtoarray[$i]->chDate = $dto->chDate;
					$dtoarray[$i]->chTradeCount = $dto->chTradeCount;
					$dtoarray[$i]->chTotalAmount = $dto->chTotalAmount;
					$dtoarray[$i]->chBeginCost = $dto->chBeginCost;
					$dtoarray[$i]->chMaxCost = $dto->chMaxCost;
					$dtoarray[$i]->chMinCost = $dto->chMinCost;
					$dtoarray[$i]->chCloseCost = $dto->chCloseCost;
					$dtoarray[$i]->chRegDt = $dto->chRegDt;  
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
                        $sql = "INSERT INTO web_chart_data_minute_btc_etc(
				ch_trade_count,
				ch_total_amount,
				ch_begin_cost,
				ch_max_cost,
				ch_min_cost,
				ch_close_cost)
                                VALUES(?,?,?,?,?,?)";

                        if($this->db) $stmt = $this->db->prepare($sql);
                        if($stmt){
                            $j=1;
                            
		$stmt->bindValue( $j++, $dto->chTradeCount, PDO::PARAM_INT);
		$stmt->bindValue( $j++, $dto->chTotalAmount, PDO::PARAM_STR);
		$stmt->bindValue( $j++, $dto->chBeginCost, PDO::PARAM_STR);
		$stmt->bindValue( $j++, $dto->chMaxCost, PDO::PARAM_STR);
		$stmt->bindValue( $j++, $dto->chMinCost, PDO::PARAM_STR);
		$stmt->bindValue( $j++, $dto->chCloseCost, PDO::PARAM_STR);
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

                    $sql = "UPDATE web_chart_data_minute_btc_etc SET 
				ch_trade_count=?,
				ch_total_amount=?,
				ch_begin_cost=?,
				ch_max_cost=?,
				ch_min_cost=?,
				ch_close_cost=? WHERE ch_date=?";

                    if($this->db) $stmt = $this->db->prepare($sql);
                    if($stmt){
                        $j=1;
                        
		$stmt->bindValue( $j++, $dto->chTradeCount, PDO::PARAM_INT);
		$stmt->bindValue( $j++, $dto->chTotalAmount, PDO::PARAM_STR);
		$stmt->bindValue( $j++, $dto->chBeginCost, PDO::PARAM_STR);
		$stmt->bindValue( $j++, $dto->chMaxCost, PDO::PARAM_STR);
		$stmt->bindValue( $j++, $dto->chMinCost, PDO::PARAM_STR);
		$stmt->bindValue( $j++, $dto->chCloseCost, PDO::PARAM_STR);
			
		$stmt->bindValue( $j++, $dto->chDate, ($dto->chDate)?PDO::PARAM_STR:PDO::PARAM_NULL);
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

                    $sql = "DELETE FROM web_chart_data_minute_btc_etc WHERE ch_date=?";

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