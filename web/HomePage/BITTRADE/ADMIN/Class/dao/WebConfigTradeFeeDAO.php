<?php      
/**
* Description of WebConfigTradeFeeDAO
* @description FunHanSoft Co.,Ltd  PHP auto templet
* @date 2016-11-02
* @copyright (c)funhansoft.com
* @license 해당 사이트 이외의 사용은 엄격히 금지되어 있습니다.
* @version 4.0.0
*/
        class WebConfigTradeFeeDAO extends BaseDAO{

            private $db;
            private $dbSlave;

            function __construct() {
                parent::__construct();
            }

            public function getViewById($cf_no){
                $dto = new WebConfigTradeFeeDTO();
                if(!$this->dbSlave) $this->dbSlave=parent::getDatabaseSlave();
                if($this->dbSlave){
                    try{
                        $sql = "SELECT 	cf_no,
                                    cf_market_type,
                                    cf_tr_tracker_fee,
                                    cf_tr_marketmaker_fee,
                                    cf_order_min_krw,
                                    cf_order_min_coin,
                                    cf_call_unit_krw,
                                    cf_call_unit_coin,
                                    cf_reg_dt
                                FROM web_config_trade_fee WHERE cf_no=?  limit 1";

                        if($this->dbSlave) $stmt = $this->dbSlave->prepare($sql);
                        if($stmt){
                            $stmt->bindValue( 1, $cf_no, PDO::PARAM_STR);
                            $stmt->execute();
                            list(
                                $dto->cfNo,
                                $dto->cfMarketType,
                                $dto->cfTrTrackerFee,
                                $dto->cfTrMarketmakerFee,
                                $dto->cfOrderMinKrw,
                                $dto->cfOrderMinCoin,
                                $dto->cfCallUnitKrw,
                                $dto->cfCallUnitCoin,
                                $dto->cfRegDt) = $stmt->fetch();
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
                if(!$this->dbSlave) $this->dbSlave=parent::getDatabaseSlave();
                if($this->dbSlave){
                    try{
                        $singleFields = array('cf_no','cf_market_type');
                        $sql_add = $this->getListSearchSQL($singleFields,$field,$value);
                        if($svdf && $svdt){
                            if($sql_add){
                                $sql_add .= " AND ";
                            }else{
                                $sql_add .= " WHERE ";
                            }
                            $sql_add .= "(cf_reg_dt BETWEEN ? AND DATE_ADD(?,INTERVAL 1 DAY) ) ";
                        }
                        $sql = "SELECT count(*)  FROM web_config_trade_fee{$sql_add}";

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
                $dto = new WebConfigTradeFeeDTO();
                $dtoarray = array();
                if(!$this->dbSlave) $this->dbSlave=parent::getDatabaseSlave();
                if($this->dbSlave){
                    try{
                        $singleFields = array('cf_no','cf_market_type');
                        $sql_add = $this->getListSearchSQL($singleFields,$field,$value);
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
                                    cf_market_type,
                                    cf_tr_tracker_fee,
                                    cf_tr_marketmaker_fee,
                                    cf_order_min_krw,
                                    cf_order_min_coin,
                                    cf_call_unit_krw,
                                    cf_call_unit_coin,
                                    cf_reg_dt
                                FROM web_config_trade_fee{$sql_add} LIMIT ?,?";

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
                                $dto->cfMarketType,
                                $dto->cfTrTrackerFee,
                                $dto->cfTrMarketmakerFee,
                                $dto->cfOrderMinKrw,
                                $dto->cfOrderMinCoin,
                                $dto->cfCallUnitKrw,
                                $dto->cfCallUnitCoin,
                                $dto->cfRegDt) = $stmt->fetch()) {
                                    $dtoarray[$i] = new WebConfigTradeFeeDTO();
                                    parent::setResult($i+1);
                                    $dtoarray[$i]->cfNo = $dto->cfNo;
                                    $dtoarray[$i]->cfMarketType = $dto->cfMarketType;
                                    $dtoarray[$i]->cfTrTrackerFee = $dto->cfTrTrackerFee;
                                    $dtoarray[$i]->cfTrMarketmakerFee = $dto->cfTrMarketmakerFee;
                                    $dtoarray[$i]->cfOrderMinKrw = $dto->cfOrderMinKrw;
                                    $dtoarray[$i]->cfOrderMinCoin = $dto->cfOrderMinCoin;
                                    $dtoarray[$i]->cfCallUnitKrw = $dto->cfCallUnitKrw;
                                    $dtoarray[$i]->cfCallUnitCoin = $dto->cfCallUnitCoin;
                                    $dtoarray[$i]->cfRegDt = $dto->cfRegDt;
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
                if(!$this->db) $this->db=parent::getDatabase();
                if($this->db){
                    try{
                        $sql = "INSERT INTO web_config_trade_fee(
                                    cf_market_type,
                                    cf_tr_tracker_fee,
                                    cf_tr_marketmaker_fee,
                                    cf_order_min_krw,
                                    cf_order_min_coin,
                                    cf_call_unit_krw,
                                    cf_call_unit_coin)
                                VALUES(?,?,?,?,?,?,?)";

                        if($this->db) $stmt = $this->db->prepare($sql);
                        if($stmt){
                            $j=1;

                            $stmt->bindValue( $j++, $dto->cfMarketType, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, $dto->cfTrTrackerFee, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, $dto->cfTrMarketmakerFee, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, $dto->cfOrderMinKrw, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, $dto->cfOrderMinCoin, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, $dto->cfCallUnitKrw, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, $dto->cfCallUnitCoin, PDO::PARAM_STR);
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
                if(!$this->db) $this->db=parent::getDatabase();
                if($this->db){

                    $sql = "UPDATE web_config_trade_fee SET
                            cf_market_type=?,
                            cf_tr_tracker_fee=?,
                            cf_tr_marketmaker_fee=?,
                            cf_order_min_krw=?,
                            cf_order_min_coin=?,
                            cf_call_unit_krw=?,
                            cf_call_unit_coin=? WHERE cf_no=?";

                    if($this->db) $stmt = $this->db->prepare($sql);
                    if($stmt){
                        $j=1;

                        $stmt->bindValue( $j++, $dto->cfMarketType, PDO::PARAM_STR);
                        $stmt->bindValue( $j++, $dto->cfTrTrackerFee, PDO::PARAM_STR);
                        $stmt->bindValue( $j++, $dto->cfTrMarketmakerFee, PDO::PARAM_STR);
                        $stmt->bindValue( $j++, $dto->cfOrderMinKrw, PDO::PARAM_STR);
                        $stmt->bindValue( $j++, $dto->cfOrderMinCoin, PDO::PARAM_STR);
                        $stmt->bindValue( $j++, $dto->cfCallUnitKrw, PDO::PARAM_STR);
                        $stmt->bindValue( $j++, $dto->cfCallUnitCoin, PDO::PARAM_STR);

                        $stmt->bindValue( $j++, $dto->cfNo, PDO::PARAM_INT);
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
                if(!$this->db) $this->db=parent::getDatabase();
                if($this->db){

                    $sql = "DELETE FROM web_config_trade_fee WHERE cf_no=?";

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