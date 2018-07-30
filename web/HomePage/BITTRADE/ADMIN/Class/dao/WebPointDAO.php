<?php      
/**
* Description of WebPointDAO
* @description FunHanSoft Co.,Ltd  PHP auto templet
* @date 2017-07-12
* @copyright (c)funhansoft.com
* @license 해당 사이트 이외의 사용은 엄격히 금지되어 있습니다.
* @version 4.0.0
*/
        class WebPointDAO extends BaseDAO{

            private $db;
            private $dbSlave;
            private $dbName = 'fns_trade_point';
            private $dbTableName = 'web_point_etc';

            function __construct() {
                parent::__construct();
            }
            
            public function setDbName($nm){
                $this->dbName = $nm;
            }
            
            public function setTableName($nm){
                $this->dbTableName = $nm;
            }

            public function callBalanceSum($mb_no,$action,$markettype='ALL'){
                parent::setResult(-1);
                if(!$this->db) $this->db=parent::getDatabaseTrade($this->dbName);
                if($this->db){
                    try{
                        $sql = "call proc_set_member_point_balance_action(?,?,?);";
                        if($this->db) $stmt = $this->db->prepare($sql);
                        if($stmt){
                            $j=1;
                            $stmt->bindValue( $j++, $mb_no, PDO::PARAM_INT);
                            $stmt->bindValue( $j++, $markettype, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, $action, PDO::PARAM_STR);
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
            
            public function callBalanceSumRecovery($mb_no,$action,$markettype='ALL'){
                parent::setResult(-1);
                if(!$this->db) $this->db=parent::getDatabaseTrade($this->dbName);
                if($this->db){
                    try{
                        $sql = "call proc_set_member_point_balance_recovery(?,?,?);";
                        if($this->db) $stmt = $this->db->prepare($sql);
                        if($stmt){
                            $j=1;
                            $stmt->bindValue( $j++, $mb_no, PDO::PARAM_INT);
                            $stmt->bindValue( $j++, $markettype, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, $action, PDO::PARAM_STR);
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
            
            public function setRedisBalance($mb_no){
                $dto = new WebMemberRedisBalanceDTO();
                parent::setResult(-1);
                if(!$this->db) $this->db=parent::getDatabaseTrade($this->dbName);
                if($this->db){
                    try{
                        $sql = "SELECT po_type,
                                po_total as total,
                                po_on_trade as on_trade,
                                po_on_etc as on_etc,
                                po_on_lend as on_lend,
                                (po_total-po_on_trade-po_on_etc-po_on_lend ) as poss
                                FROM trade_member_point_balance WHERE mb_no=?";
                        if($this->db) $stmt = $this->db->prepare($sql);
                        if($stmt){
                            $j=1;
                            $stmt->bindValue( $j++, $mb_no, PDO::PARAM_INT);
                            $stmt->execute();

                            $i = 0;
                            while(list(
                                $dto->po_type,
                                $dto->total,
                                $dto->on_trade,
                                $dto->on_etc,
                                $dto->on_lend,
                                $dto->poss) = $stmt->fetch()) {
                                    $dtoarray[$i] = new WebMemberRedisBalanceDTO();
                                    parent::setResult($i+1);
                                    $dtoarray[$i]->po_type = $dto->po_type;
                                    $dtoarray[$i]->total = $dto->total;
                                    $dtoarray[$i]->on_trade = $dto->on_trade;
                                    $dtoarray[$i]->on_etc = $dto->on_etc;
                                    $dtoarray[$i]->on_lend = $dto->on_lend;
                                    $dtoarray[$i]->poss = $dto->poss;
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
                $dto = new WebPointDTO();
                if(!$this->dbSlave) $this->dbSlave=parent::getDatabaseTradeSlave($this->dbName);
                if($this->dbSlave){
                    try{
                        $sql = "SELECT 	po_no,
				mb_no,
				is_tracker,
				po_content,
				po_point,
				po_point_sum,
				od_total_cost,
				od_fee,
				po_rel_id,
				po_rel_action,
				po_trade_dt,
				po_reg_dt,
				po_reg_ip 
                                FROM ".$this->dbTableName." WHERE po_no=?  limit 1";

                        if($this->dbSlave) $stmt = $this->dbSlave->prepare($sql);
                        if($stmt){
                            $stmt->bindValue( 1, $po_no, PDO::PARAM_STR);
                            $stmt->execute();
                            list(
				$dto->poNo,
				$dto->mbNo,
				$dto->isTracker,
				$dto->poContent,
				$dto->poPoint,
				$dto->poPointSum,
				$dto->odTotalCost,
				$dto->odFee,
				$dto->poRelId,
				$dto->poRelAction,
				$dto->poTradeDt,
				$dto->poRegDt,
				$dto->poRegIp) = $stmt->fetch();
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
                        $singleFields = array('po_no','mb_no','is_tracker');
                        $sql_add = $this->getListSearchSQL($singleFields,$field,$value);
                        if($svdf && $svdt){
                            if($sql_add){
                                $sql_add .= " AND ";
                            }else{
                                $sql_add .= " WHERE ";
                            }
                            $sql_add .= "(po_reg_dt BETWEEN ? AND DATE_ADD(?,INTERVAL 1 DAY) ) ";
                        }
                        
                        $sql = "SELECT count(*) FROM ".$this->dbTableName."{$sql_add}";
                        
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
                $dto = new WebPointDTO();
                $dtoarray = array();
                if(!$this->dbSlave) $this->dbSlave=parent::getDatabaseTradeSlave($this->dbName);
                if($this->dbSlave){
                    try{
                        $singleFields = array('po_no','mb_no','is_tracker');
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
				mb_no,
				is_tracker,
				po_content,
				po_point,
				po_point_sum,
				od_total_cost,
				od_fee,
				po_rel_id,
				po_rel_action,
				po_trade_dt,
				po_reg_dt,
				po_reg_ip 
                                FROM ".$this->dbTableName."{$sql_add} LIMIT ?,?";

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
                                $dto->mbNo,
                                $dto->isTracker,
                                $dto->poContent,
                                $dto->poPoint,
                                $dto->poPointSum,
                                $dto->odTotalCost,
                                $dto->odFee,
                                $dto->poRelId,
                                $dto->poRelAction,
                                $dto->poTradeDt,
                                $dto->poRegDt,
                                $dto->poRegIp) = $stmt->fetch()) {
                                    $dtoarray[$i] = new WebPointDTO();
                                    parent::setResult($i+1);
                                    $dtoarray[$i]->poNo = $dto->poNo;
                                    $dtoarray[$i]->mbNo = $dto->mbNo;
                                    $dtoarray[$i]->isTracker = $dto->isTracker;
                                    $dtoarray[$i]->poContent = $dto->poContent;
                                    $dtoarray[$i]->poPoint = $dto->poPoint;
                                    $dtoarray[$i]->poPointSum = $dto->poPointSum;
                                    $dtoarray[$i]->odTotalCost = $dto->odTotalCost;
                                    $dtoarray[$i]->odFee = $dto->odFee;
                                    $dtoarray[$i]->poRelId = $dto->poRelId;
                                    $dtoarray[$i]->poRelAction = $dto->poRelAction;
                                    $dtoarray[$i]->poTradeDt = $dto->poTradeDt;
                                    $dtoarray[$i]->poRegDt = $dto->poRegDt;
                                    $dtoarray[$i]->poRegIp = $dto->poRegIp;  
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
            
            public function getViewByOrderId($mb_no,$order_id,$timest=null,$timedn=null,$pre_rel_action=null){
                $dto = new WebPointDTO();
                $dtoarray = array();
                if(!$this->dbSlave) $this->dbSlave=parent::getDatabaseTradeSlave($this->dbName);
                if($this->dbSlave){
                    try{
                        $sql_add = " WHERE mb_no=? ";
                        if($pre_rel_action) $sql_add .=  "AND po_rel_action like '".$pre_rel_action."%' ";
                        if($timest && $timedn) $sql_add .= " AND (po_reg_dt between '".$timest."' AND '".$timedn."') ";
						$sql_add .= " AND (";
                        $sql_add .= "od_id='".$order_id."' ";
						$sql_add .= ")";
                        
                        $sql = "SELECT 	po_no,
                                mb_no,
                                is_tracker,
                                po_content,
                                po_point,
                                po_point_sum,
                                od_total_cost,
                                od_fee,
                                po_rel_id,
                                po_rel_action,
                                po_trade_dt,
                                po_reg_dt,
                                po_reg_ip 
                                FROM ".$this->dbTableName."{$sql_add} LIMIT ?,?";

                        if($this->dbSlave) $stmt = $this->dbSlave->prepare($sql);
                        if($stmt){
                            $j=1;
                            $stmt->bindValue( $j++, $mb_no, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, parent::getListLimitStart(), PDO::PARAM_INT);
                            $stmt->bindValue( $j++, parent::getListLimitRow(), PDO::PARAM_INT);
                            $stmt->execute();

                            $i = 0;
                            while(list(
                                $dto->poNo,
                                $dto->mbNo,
                                $dto->isTracker,
                                $dto->poContent,
                                $dto->poPoint,
                                $dto->poPointSum,
                                $dto->odTotalCost,
                                $dto->odFee,
                                $dto->poRelId,
                                $dto->poRelAction,
                                $dto->poTradeDt,
                                $dto->poRegDt,
                                $dto->poRegIp) = $stmt->fetch()) {
                                    $dtoarray[$i] = new WebPointDTO();
                                    parent::setResult($i+1);
                                    $dtoarray[$i]->poNo = $dto->poNo;
                                    $dtoarray[$i]->mbNo = $dto->mbNo;
                                    $dtoarray[$i]->isTracker = $dto->isTracker;
                                    $dtoarray[$i]->poContent = $dto->poContent;
                                    $dtoarray[$i]->poPoint = $dto->poPoint;
                                    $dtoarray[$i]->poPointSum = $dto->poPointSum;
                                    $dtoarray[$i]->odTotalCost = $dto->odTotalCost;
                                    $dtoarray[$i]->odFee = $dto->odFee;
                                    $dtoarray[$i]->poRelId = $dto->poRelId;
                                    $dtoarray[$i]->poRelAction = $dto->poRelAction;
                                    $dtoarray[$i]->poTradeDt = $dto->poTradeDt;
                                    $dtoarray[$i]->poRegDt = $dto->poRegDt;
                                    $dtoarray[$i]->poRegIp = $dto->poRegIp;  
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
            
            public function getViewByRelId($mb_no,$rel_id,$timest=null,$timedn=null,$pre_rel_action=null){
                $dto = new WebPointDTO();
                $dtoarray = array();
                if(!$this->dbSlave) $this->dbSlave=parent::getDatabaseTradeSlave($this->dbName);
                if($this->dbSlave){
                    try{
                        $sql_add = " WHERE mb_no=? ";
                        if($pre_rel_action) $sql_add .=  " AND po_rel_action like '".$pre_rel_action."%' ";
                        if($timest && $timedn) $sql_add .= " AND (po_reg_dt between '".$timest."' AND '".$timedn."') ";
						$sql_add .= " AND (";
                        $sql_add .= "po_rel_id='".$rel_id."' ";
						$sql_add .= ")";
                        
                        $sql = "SELECT 	po_no,
                                mb_no,
                                is_tracker,
                                po_content,
                                po_point,
                                po_point_sum,
                                od_total_cost,
                                od_fee,
                                po_rel_id,
                                po_rel_action,
                                po_trade_dt,
                                po_reg_dt,
                                po_reg_ip 
                                FROM ".$this->dbTableName."{$sql_add} LIMIT ?,?";
                                
                        if($this->dbSlave) $stmt = $this->dbSlave->prepare($sql);
                        if($stmt){
                            $j=1;
                            $stmt->bindValue( $j++, $mb_no, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, parent::getListLimitStart(), PDO::PARAM_INT);
                            $stmt->bindValue( $j++, parent::getListLimitRow(), PDO::PARAM_INT);
                            $stmt->execute();

                            $i = 0;
                            while(list(
                                $dto->poNo,
                                $dto->mbNo,
                                $dto->isTracker,
                                $dto->poContent,
                                $dto->poPoint,
                                $dto->poPointSum,
                                $dto->odTotalCost,
                                $dto->odFee,
                                $dto->poRelId,
                                $dto->poRelAction,
                                $dto->poTradeDt,
                                $dto->poRegDt,
                                $dto->poRegIp) = $stmt->fetch()) {
                                    $dtoarray[$i] = new WebPointDTO();
                                    parent::setResult($i+1);
                                    $dtoarray[$i]->poNo = $dto->poNo;
                                    $dtoarray[$i]->mbNo = $dto->mbNo;
                                    $dtoarray[$i]->isTracker = $dto->isTracker;
                                    $dtoarray[$i]->poContent = $dto->poContent;
                                    $dtoarray[$i]->poPoint = $dto->poPoint;
                                    $dtoarray[$i]->poPointSum = $dto->poPointSum;
                                    $dtoarray[$i]->odTotalCost = $dto->odTotalCost;
                                    $dtoarray[$i]->odFee = $dto->odFee;
                                    $dtoarray[$i]->poRelId = $dto->poRelId;
                                    $dtoarray[$i]->poRelAction = $dto->poRelAction;
                                    $dtoarray[$i]->poTradeDt = $dto->poTradeDt;
                                    $dtoarray[$i]->poRegDt = $dto->poRegDt;
                                    $dtoarray[$i]->poRegIp = $dto->poRegIp;  
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
                switch($this->dbTableName){
                    case 'web_point_etc': $ctype = "etc"; break;
                    case 'web_point_btc': $ctype = "btc"; break;
                    case 'web_point_eth': $ctype = "eth"; break;
                    case 'web_point_ltc': $ctype = "ltc"; break;
                    case 'web_point_bch': $ctype = "bch"; break;
                    case 'web_point_krw': $ctype = "krw"; break;
                }
                if($this->db){
                    try{
                        $sql = "CALL proc_set_point(?,?,?,?,?,?,?);";
                        /*$sql = "INSERT INTO ".$this->dbTableName."(
				mb_no,
				is_tracker,
				po_content,
				po_point,
				po_point_sum,
				od_total_cost,
				od_fee,
				po_rel_id,
				po_rel_action,
				po_trade_dt,
				po_reg_ip)
                                VALUES(?,?,?,?,?,?,?,?,?,?,?)";*/

                        if($this->db) $stmt = $this->db->prepare($sql);
                        if($stmt){
                            $j=1;
                $stmt->bindValue( $j++, $ctype, PDO::PARAM_STR);
		$stmt->bindValue( $j++, $dto->mbNo, ($dto->mbNo)?PDO::PARAM_INT:PDO::PARAM_NULL);
                $stmt->bindValue( $j++, $dto->poContent, ($dto->poContent)?PDO::PARAM_STR:PDO::PARAM_NULL);
                $stmt->bindValue( $j++, $dto->poPoint, PDO::PARAM_STR);
                $stmt->bindValue( $j++, $dto->poRelId, ($dto->poRelId)?PDO::PARAM_STR:PDO::PARAM_NULL);
		$stmt->bindValue( $j++, $dto->poRelAction, ($dto->poRelAction)?PDO::PARAM_STR:PDO::PARAM_NULL);
                $stmt->bindValue( $j++, $dto->poRegIp, ($dto->poRegIp)?PDO::PARAM_STR:PDO::PARAM_NULL);
		/*$stmt->bindValue( $j++, $dto->mbNo, ($dto->mbNo)?PDO::PARAM_INT:PDO::PARAM_NULL);
		$stmt->bindValue( $j++, $dto->isTracker, PDO::PARAM_STR);
		$stmt->bindValue( $j++, $dto->poContent, ($dto->poContent)?PDO::PARAM_STR:PDO::PARAM_NULL);
		$stmt->bindValue( $j++, $dto->poPoint, PDO::PARAM_STR);
		$stmt->bindValue( $j++, $dto->poPointSum, PDO::PARAM_STR);
		$stmt->bindValue( $j++, $dto->odTotalCost, PDO::PARAM_STR);
		$stmt->bindValue( $j++, $dto->odFee, PDO::PARAM_STR);
		$stmt->bindValue( $j++, $dto->poRelId, ($dto->poRelId)?PDO::PARAM_STR:PDO::PARAM_NULL);
		$stmt->bindValue( $j++, $dto->poRelAction, ($dto->poRelAction)?PDO::PARAM_STR:PDO::PARAM_NULL);
		$stmt->bindValue( $j++, $dto->poTradeDt, ($dto->poTradeDt)?PDO::PARAM_STR:PDO::PARAM_NULL);
		$stmt->bindValue( $j++, $dto->poRegIp, ($dto->poRegIp)?PDO::PARAM_STR:PDO::PARAM_NULL);*/
                            $stmt->execute();
                            if($stmt->rowCount()==1) {
                                $result = $stmt->fetch();
                                parent::setResult($result[0]);
                            }else{
                                parent::setResult(0);
                            }
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
				mb_no=?,
				is_tracker=?,
				po_content=?,
				po_point=?,
				po_point_sum=?,
				od_total_cost=?,
				od_fee=?,
				po_rel_id=?,
				po_rel_action=?,
				po_trade_dt=?,
				po_reg_ip=? WHERE po_no=?";

                    if($this->db) $stmt = $this->db->prepare($sql);
                    if($stmt){
                        $j=1;
		$stmt->bindValue( $j++, $dto->mbNo, ($dto->mbNo)?PDO::PARAM_INT:PDO::PARAM_NULL);
		$stmt->bindValue( $j++, $dto->isTracker, PDO::PARAM_STR);
		$stmt->bindValue( $j++, $dto->poContent, ($dto->poContent)?PDO::PARAM_STR:PDO::PARAM_NULL);
		$stmt->bindValue( $j++, $dto->poPoint, PDO::PARAM_STR);
		$stmt->bindValue( $j++, $dto->poPointSum, PDO::PARAM_STR);
		$stmt->bindValue( $j++, $dto->odTotalCost, PDO::PARAM_STR);
		$stmt->bindValue( $j++, $dto->odFee, PDO::PARAM_STR);
		$stmt->bindValue( $j++, $dto->poRelId, ($dto->poRelId)?PDO::PARAM_STR:PDO::PARAM_NULL);
		$stmt->bindValue( $j++, $dto->poRelAction, ($dto->poRelAction)?PDO::PARAM_STR:PDO::PARAM_NULL);
		$stmt->bindValue( $j++, $dto->poTradeDt, ($dto->poTradeDt)?PDO::PARAM_STR:PDO::PARAM_NULL);
		$stmt->bindValue( $j++, $dto->poRegIp, ($dto->poRegIp)?PDO::PARAM_STR:PDO::PARAM_NULL);
			
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

                    $sql = "DELETE FROM ".$this->dbTableName." WHERE po_no=?";

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