<?php      
/**
* Description of WebConfigTradeFeeByVolumeDAO
* @description FunHanSoft Co.,Ltd  PHP auto templet
* @date 2017-08-08
* @copyright (c)funhansoft.com
* @license 해당 사이트 이외의 사용은 엄격히 금지되어 있습니다.
* @version 0.3.0
*/
        class WebConfigTradeFeeByVolumeDAO extends BaseDAO{

            private $db;
            private $dbSlave;

            function __construct() {
                parent::__construct();
            }

            public function getViewById($cf_no){
                $dto = new WebConfigTradeFeeByVolumeDTO();
                if(!$this->dbSlave) $this->dbSlave=parent::getDatabaseSlave();
                if($this->dbSlave){
                    try{
                        $sql = "SELECT 	cf_no,
				cf_market_type,
				cf_max_volume,
				cf_tr_tracker_fee,
				cf_tr_marketmaker_fee,
				cf_max_day,
				cf_reg_dt 
                                FROM web_config_trade_fee_by_volume WHERE cf_no=?  limit 1";

                        if($this->dbSlave) $stmt = $this->dbSlave->prepare($sql);
                        if($stmt){
                            $stmt->bindValue( 1, $cf_no, PDO::PARAM_STR);
                            $stmt->execute();
                            list(
				$dto->cfNo,
				$dto->cfMarketType,
				$dto->cfMaxVolume,
				$dto->cfTrTrackerFee,
				$dto->cfTrMarketmakerFee,
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
                        $singleFields = array('cf_no','cf_max_volume','cf_max_day');
                        $sql_add = $this->getListSearchSQL($singleFields,$field,$value);
                        
                        $sql = "SELECT count(*)  FROM web_config_trade_fee_by_volume{$sql_add}";

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
                $dto = new WebConfigTradeFeeByVolumeDTO();
                $dtoarray = array();
                if(!$this->dbSlave) $this->dbSlave=parent::getDatabaseSlave();
                if($this->dbSlave){
                    try{
                        $singleFields = array('cf_no','cf_max_volume','cf_max_day');
                        $sql_add = $this->getListSearchSQL($singleFields,$field,$value);
                        $sql_add .= $this->getListOrderSQL($singleFields,'cf_no');
                        
                        $sql = "SELECT 	cf_no,
				cf_market_type,
				cf_max_volume,
				cf_tr_tracker_fee,
				cf_tr_marketmaker_fee,
				cf_max_day,
				cf_reg_dt 
                                FROM web_config_trade_fee_by_volume{$sql_add} LIMIT ?,?";

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
				$dto->cfNo,
				$dto->cfMarketType,
				$dto->cfMaxVolume,
				$dto->cfTrTrackerFee,
				$dto->cfTrMarketmakerFee,
				$dto->cfMaxDay,
				$dto->cfRegDt) = $stmt->fetch()) {
                                    $dtoarray[$i] = new WebConfigTradeFeeByVolumeDTO();
                                    parent::setResult($i+1);
                                    $dtoarray[$i]->cfNo = $dto->cfNo;
					$dtoarray[$i]->cfMarketType = $dto->cfMarketType;
					$dtoarray[$i]->cfMaxVolume = $dto->cfMaxVolume;
					$dtoarray[$i]->cfTrTrackerFee = $dto->cfTrTrackerFee;
					$dtoarray[$i]->cfTrMarketmakerFee = $dto->cfTrMarketmakerFee;
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
                        $sql = "INSERT INTO web_config_trade_fee_by_volume(
				cf_market_type,
				cf_max_volume,
				cf_tr_tracker_fee,
				cf_tr_marketmaker_fee,
				cf_max_day)
                                VALUES(?,?,?,?,?)";

                        if($this->db) $stmt = $this->db->prepare($sql);
                        if($stmt){
                            $j=1;
                            
		$stmt->bindValue( $j++, $dto->cfMarketType, ($dto->cfMarketType)?PDO::PARAM_STR:PDO::PARAM_NULL);
		$stmt->bindValue( $j++, $dto->cfMaxVolume, ($dto->cfMaxVolume)?PDO::PARAM_INT:PDO::PARAM_NULL);
		$stmt->bindValue( $j++, $dto->cfTrTrackerFee, ($dto->cfTrTrackerFee)?PDO::PARAM_STR:PDO::PARAM_NULL);
		$stmt->bindValue( $j++, $dto->cfTrMarketmakerFee, ($dto->cfTrMarketmakerFee)?PDO::PARAM_STR:PDO::PARAM_NULL);
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

                    $sql = "UPDATE web_config_trade_fee_by_volume SET 
				cf_market_type=?,
				cf_max_volume=?,
				cf_tr_tracker_fee=?,
				cf_tr_marketmaker_fee=?,
				cf_max_day=? WHERE cf_no=?";

                    if($this->db) $stmt = $this->db->prepare($sql);
                    if($stmt){
                        $j=1;
                        
		$stmt->bindValue( $j++, $dto->cfMarketType, ($dto->cfMarketType)?PDO::PARAM_STR:PDO::PARAM_NULL);
		$stmt->bindValue( $j++, $dto->cfMaxVolume, ($dto->cfMaxVolume)?PDO::PARAM_INT:PDO::PARAM_NULL);
		$stmt->bindValue( $j++, $dto->cfTrTrackerFee, ($dto->cfTrTrackerFee)?PDO::PARAM_STR:PDO::PARAM_NULL);
		$stmt->bindValue( $j++, $dto->cfTrMarketmakerFee, ($dto->cfTrMarketmakerFee)?PDO::PARAM_STR:PDO::PARAM_NULL);
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

                    $sql = "DELETE FROM web_config_trade_fee_by_volume WHERE cf_no=?";

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