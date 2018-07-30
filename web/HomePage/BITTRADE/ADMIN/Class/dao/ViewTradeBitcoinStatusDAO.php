<?php      
/**
* Description of ViewTradeBitcoinStatusDAO
* @description Funhansoft PHP auto templet
* @date 2015-08-21
* @copyright (c)funhansoft.com
* @license http://funhansoft.com/license
* @version 4.0.0
*/
        class ViewTradeBitcoinStatusDAO extends BaseDAO{

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
                        $singleFields = array('od_action');
                        $sql_add = $this->getListSearchSQL($singleFields,$field,$value);
                        
                        $sql = "SELECT count(*)  FROM view_trade_bitcoin_status{$sql_add}";

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

            public function getList(){
                $dto = new ViewTradeBitcoinStatusDTO();
                $dtoarray = array();
                if(!$this->dbSlave) $this->dbSlave=parent::getDatabaseTradeSlave();
                if($this->dbSlave){
                    try{

                        $sql = "(select CONCAT('sell'),od_market_price,od_temp_coin
                                from `view_trade_bitcoin_sell`
                                order by od_market_price desc
                                LIMIT ".parent::getListLimitRow()."
                                )
                                union all 

                                (select  CONCAT('buy'),od_market_price,od_temp_coin
                                from `view_trade_bitcoin_buy`
                                LIMIT ".parent::getListLimitRow()."
                                )";

                        if($this->dbSlave) $stmt = $this->dbSlave->prepare($sql);
                        if($stmt){
                            
                            $stmt->execute();

                            $i = 0;
                            while(list(
                                $dto->odAction,
                                $dto->odMarketPrice,
                                $dto->odTempCoin) = $stmt->fetch()) {
                                    $dtoarray[$i] = new ViewTradeBitcoinStatusDTO();
                                    parent::setResult($i+1);
                                    $dtoarray[$i]->odAction = $dto->odAction;
					$dtoarray[$i]->odMarketPrice = $dto->odMarketPrice;
					$dtoarray[$i]->odTempCoin = $dto->odTempCoin;  
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
            
            public function getUnTradeListGroup(){
                $dto = new ViewTradeBitcoinStatusDTO();
                $dtoarray = array();
                if(!$this->dbSlave) $this->dbSlave=parent::getDatabaseTradeSlave();
                if($this->dbSlave){
                    try{
                        $sql = "SELECT COUNT(od_market_price) AS groupcnt, od_market_price, od_temp_coin FROM view_trade_bitcoin_status GROUP BY od_market_price HAVING COUNT(*) > 1 order by groupcnt DESC";

                        if($this->dbSlave) $stmt = $this->dbSlave->prepare($sql);
                        if($stmt){
                            $stmt->execute();

                            $i = 0;
                            while(list(
                                $dto->groupcnt,
                                $dto->odMarketPrice,
                                $dto->odTempCoin) = $stmt->fetch()) {
                                    $dtoarray[$i] = new ViewTradeBitcoinStatusDTO();
                                    parent::setResult($i+1);
                                    $dtoarray[$i]->odAction = $dto->groupcnt;
                                    $dtoarray[$i]->odMarketPrice = $dto->odMarketPrice;
                                    $dtoarray[$i]->odTempCoin = $dto->odTempCoin;
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
            

            function __destruct(){
                $this->db = NULL;
                $this->dto = NULL;
            }
        }