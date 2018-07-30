<?php      
/**
* Description of TradeMemberPointBalanceDAO
* @description FunHanSoft Co.,Ltd  PHP auto templet
* @date 2017-08-29
* @copyright (c)funhansoft.com
* @license 해당 사이트 이외의 사용은 엄격히 금지되어 있습니다.
* @version 4.0.0
*/
        class TradeMemberPointBalanceDAO extends BaseDAO{

            private $db;
            private $dbSlave;
            private $dbName = 'fns_trade_point';


            function __construct() {
                parent::__construct();
            }
            
            public function getMemberBalance($mb_no){
                $dto = new TradeMemberPointBalanceDTO();
                $dtoarray = array();
                if(!$this->dbSlave) $this->dbSlave=parent::getDatabaseTrade($this->dbName);
                if($this->dbSlave){
                    try{
                        
                        $sql = "call proc_get_all_balance(?)";

                        if($this->dbSlave) $stmt = $this->dbSlave->prepare($sql);
                        if($stmt){
                            $j=1;
                            $stmt->bindValue( $j++, $mb_no, PDO::PARAM_STR);
                            $stmt->execute();

                            $i = 0;
                            while(list(
                                    $dto->poType,
                                    $dto->poTotal,
                                    $dto->poOnTrade,
                                    $dto->poOnEtc,
                                    $dto->poOnLend,
                                    $dto->poPoss ) = $stmt->fetch()) {
                                        $dtoarray[$i] = new TradeMemberPointBalanceDTO();
                                        parent::setResult($i+1);
                                        $dtoarray[$i]->poType = $dto->poType;
                                        $dtoarray[$i]->poTotal = $dto->poTotal;
                                        $dtoarray[$i]->poOnTrade = $dto->poOnTrade;
                                        $dtoarray[$i]->poOnEtc = $dto->poOnEtc;
                                        $dtoarray[$i]->poOnLend = $dto->poOnLend;
                                        $dtoarray[$i]->poPoss = $dto->poPoss;
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

            public function getViewById($po_no){
                $dto = new TradeMemberPointBalanceDTO();
                if(!$this->dbSlave) $this->dbSlave=parent::getDatabaseTradeSlave($this->dbName);
                if($this->dbSlave){
                    try{
                        $sql = "SELECT 	po_no,
				po_type,
				po_market_type,
				mb_no,
				po_total,
				po_on_trade,
				po_on_etc,
				po_on_lend,
				po_action,
				po_reg_dt 
                                FROM trade_member_point_balance WHERE po_no=?  limit 1";

                        if($this->dbSlave) $stmt = $this->dbSlave->prepare($sql);
                        if($stmt){
                            $stmt->bindValue( 1, $po_no, PDO::PARAM_STR);
                            $stmt->execute();
                            list(
				$dto->poNo,
				$dto->poType,
				$dto->poMarketType,
				$dto->mbNo,
				$dto->poTotal,
				$dto->poOnTrade,
				$dto->poOnEtc,
				$dto->poOnLend,
				$dto->poAction,
				$dto->poRegDt) = $stmt->fetch();
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
                        $singleFields = array('po_no');
                        $sql_add = $this->getListSearchSQL($singleFields,$field,$value);
                        if($svdf && $svdt){
                            if($sql_add){
                                $sql_add .= " AND ";
                            }else{
                                $sql_add .= " WHERE ";
                            }
                            $sql_add .= "(po_reg_dt BETWEEN ? AND DATE_ADD(?,INTERVAL 1 DAY) ) ";
                        }
                        
                        $sql = "SELECT count(*)  FROM trade_member_point_balance{$sql_add}";

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
                $dto = new TradeMemberPointBalanceDTO();
                $dtoarray = array();
                if(!$this->dbSlave) $this->dbSlave=parent::getDatabaseTradeSlave($this->dbName);
                if($this->dbSlave){
                    try{
                        $singleFields = array('po_no');
                        $sql_add = $this->getListSearchSQL($singleFields,$field,$value);
                        if($svdf && $svdt){
                            if($sql_add){
                                $sql_add .= " AND ";
                            }else{
                                $sql_add .= " WHERE ";
                            }
                            $sql_add .= "(po_reg_dt BETWEEN ? AND DATE_ADD(?,INTERVAL 1 DAY) ) ";
                        }
                        $sql_add .= $this->getListOrderSQL($singleFields,'po_no');
                        
                        $sql = "SELECT 	po_no,
				po_type,
				po_market_type,
				mb_no,
				po_total,
				po_on_trade,
				po_on_etc,
				po_on_lend,
				po_action,
				po_reg_dt 
                                FROM trade_member_point_balance{$sql_add} LIMIT ?,?";

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
				$dto->poNo,
				$dto->poType,
				$dto->poMarketType,
				$dto->mbNo,
				$dto->poTotal,
				$dto->poOnTrade,
				$dto->poOnEtc,
				$dto->poOnLend,
				$dto->poAction,
				$dto->poRegDt) = $stmt->fetch()) {
                                    $dtoarray[$i] = new TradeMemberPointBalanceDTO();
                                    parent::setResult($i+1);
                                    $dtoarray[$i]->poNo = $dto->poNo;
					$dtoarray[$i]->poType = $dto->poType;
					$dtoarray[$i]->poMarketType = $dto->poMarketType;
					$dtoarray[$i]->mbNo = $dto->mbNo;
					$dtoarray[$i]->poTotal = $dto->poTotal;
					$dtoarray[$i]->poOnTrade = $dto->poOnTrade;
					$dtoarray[$i]->poOnEtc = $dto->poOnEtc;
					$dtoarray[$i]->poOnLend = $dto->poOnLend;
					$dtoarray[$i]->poAction = $dto->poAction;
					$dtoarray[$i]->poRegDt = $dto->poRegDt;  
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
                        $sql = "INSERT INTO trade_member_point_balance(
				po_type,
				po_market_type,
				mb_no,
				po_total,
				po_on_trade,
				po_on_etc,
				po_on_lend,
				po_action)
                                VALUES(?,?,?,?,?,?,?,?)";

                        if($this->db) $stmt = $this->db->prepare($sql);
                        if($stmt){
                            $j=1;
                            
		$stmt->bindValue( $j++, $dto->poType, ($dto->poType)?PDO::PARAM_STR:PDO::PARAM_NULL);
		$stmt->bindValue( $j++, $dto->poMarketType, ($dto->poMarketType)?PDO::PARAM_STR:PDO::PARAM_NULL);
		$stmt->bindValue( $j++, $dto->mbNo, ($dto->mbNo)?PDO::PARAM_STR:PDO::PARAM_NULL);
		$stmt->bindValue( $j++, $dto->poTotal, PDO::PARAM_STR);
		$stmt->bindValue( $j++, $dto->poOnTrade, PDO::PARAM_STR);
		$stmt->bindValue( $j++, $dto->poOnEtc, PDO::PARAM_STR);
		$stmt->bindValue( $j++, $dto->poOnLend, PDO::PARAM_STR);
		$stmt->bindValue( $j++, $dto->poAction, ($dto->poAction)?PDO::PARAM_STR:PDO::PARAM_NULL);
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

                    $sql = "UPDATE trade_member_point_balance SET 
				po_type=?,
				po_market_type=?,
				mb_no=?,
				po_total=?,
				po_on_trade=?,
				po_on_etc=?,
				po_on_lend=?,
				po_action=? WHERE po_no=?";

                    if($this->db) $stmt = $this->db->prepare($sql);
                    if($stmt){
                        $j=1;
                        
		$stmt->bindValue( $j++, $dto->poType, ($dto->poType)?PDO::PARAM_STR:PDO::PARAM_NULL);
		$stmt->bindValue( $j++, $dto->poMarketType, ($dto->poMarketType)?PDO::PARAM_STR:PDO::PARAM_NULL);
		$stmt->bindValue( $j++, $dto->mbNo, ($dto->mbNo)?PDO::PARAM_STR:PDO::PARAM_NULL);
		$stmt->bindValue( $j++, $dto->poTotal, PDO::PARAM_STR);
		$stmt->bindValue( $j++, $dto->poOnTrade, PDO::PARAM_STR);
		$stmt->bindValue( $j++, $dto->poOnEtc, PDO::PARAM_STR);
		$stmt->bindValue( $j++, $dto->poOnLend, PDO::PARAM_STR);
		$stmt->bindValue( $j++, $dto->poAction, ($dto->poAction)?PDO::PARAM_STR:PDO::PARAM_NULL);
			
		$stmt->bindValue( $j++, $dto->poNo, ($dto->poNo)?PDO::PARAM_INT:PDO::PARAM_NULL);
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

                    $sql = "DELETE FROM trade_member_point_balance WHERE po_no=?";

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