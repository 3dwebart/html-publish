<?php      
/**
* Description of ViewTradeBitcoinCompleteDAO
* @description Funhansoft PHP auto templet
* @date 2015-08-21
* @copyright (c)funhansoft.com
* @license http://funhansoft.com/license
* @version 4.0.0
*/
        class ViewTradeBitcoinCompleteDAO extends BaseDAO{

            private $db;
            private $dbSlave;

            function __construct() {
                parent::__construct();
            }

            public function getList($field='',$value='',$svdf='',$svdt=''){
                $dto = new ViewTradeBitcoinCompleteDTO();
                $dtoarray = array();
                if(!$this->dbSlave) $this->dbSlave=parent::getDatabaseTradeSlave();
                if($this->dbSlave){
                    try{
                        $singleFields = array('tr_no','od_id');
                        $sql_add = $this->getListSearchSQL($singleFields,$field,$value);
                        $sql_add .= $this->getListOrderSQL($singleFields,'');

                        $sql = "SELECT
                                    tr_no,
                                    tr_market_cost,
                                    tr_total_coin,
                                    tr_reg_dt,
                                    od_id,
                                    tr_total_cost
                                FROM view_trade_bitcoin_complete{$sql_add} LIMIT ?,?";

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
                                $dto->trMarketCost,
                                $dto->trTotalCoin,
                                $dto->trRegDt,
                                $dto->odId,
                                $dto->trTotalCost) = $stmt->fetch()) {
                                $dtoarray[$i] = new ViewTradeBitcoinCompleteDTO();
                                parent::setResult($i+1);
                                $dtoarray[$i]->trNo = $dto->trNo;
                                $dtoarray[$i]->trMarketCost = $dto->trMarketCost;
                                $dtoarray[$i]->trTotalCoin = $dto->trTotalCoin;
                                $dtoarray[$i]->trRegDt = $dto->trRegDt;
                                $dtoarray[$i]->odId = $dto->odId;
                                $dtoarray[$i]->trTotalCost = $dto->trTotalCost;
                                $i++;
                                echo $dtoarray[$i];
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