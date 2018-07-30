<?php      
/**
* Description of WebPointBtcDAO
* @description FunHanSoft Co.,Ltd  PHP auto templet
* @date 2017-07-12
* @copyright (c)funhansoft.com
* @license 해당 사이트 이외의 사용은 엄격히 금지되어 있습니다.
* @version 4.0.0
*/
        class WebPointBtcDAO extends BaseDAO{

            private $db;
            private $dbSlave;

            function __construct() {
                parent::__construct();
            }

            public function getViewById($po_no){
                $dto = new WebPointBtcDTO();
                if(!$this->dbSlave) $this->dbSlave=parent::getDatabaseTradeSlave();
                if($this->dbSlave){
                    try{
                        $sql = "SELECT 	po_no,
				mem_po_no,
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
                                FROM web_point_btc WHERE po_no=?  limit 1";

                        if($this->dbSlave) $stmt = $this->dbSlave->prepare($sql);
                        if($stmt){
                            $stmt->bindValue( 1, $po_no, PDO::PARAM_STR);
                            $stmt->execute();
                            list(
				$dto->poNo,
				$dto->memPoNo,
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

            public function getListCount($field='',$value=''){
                $dto = new CommonListDTO();
                if(!$this->dbSlave) $this->dbSlave=parent::getDatabaseTradeSlave();
                if($this->dbSlave){
                    try{
                        $singleFields = array('po_no','mem_po_no','mb_no','is_tracker');
                        $sql_add = $this->getListSearchSQL($singleFields,$field,$value);
                        
                        $sql = "SELECT count(*)  FROM web_point_btc{$sql_add}";

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

            public function getList($field='',$value=''){
                $dto = new WebPointBtcDTO();
                $dtoarray = array();
                if(!$this->dbSlave) $this->dbSlave=parent::getDatabaseTradeSlave();
                if($this->dbSlave){
                    try{
                        $singleFields = array('po_no','mem_po_no','mb_no','is_tracker');
                        $sql_add = $this->getListSearchSQL($singleFields,$field,$value);
                        $sql_add .= $this->getListOrderSQL($singleFields,'po_no');
                        
                        $sql = "SELECT 	po_no,
				mem_po_no,
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
                                FROM web_point_btc{$sql_add} LIMIT ?,?";

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
				$dto->poNo,
				$dto->memPoNo,
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
                                    $dtoarray[$i] = new WebPointBtcDTO();
                                    parent::setResult($i+1);
                                    $dtoarray[$i]->poNo = $dto->poNo;
					$dtoarray[$i]->memPoNo = $dto->memPoNo;
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
                if(!$this->db) $this->db=parent::getDatabaseTrade();
                if($this->db){
                    try{
                        $sql = "INSERT INTO web_point_btc(
				mem_po_no,
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
                                VALUES(?,?,?,?,?,?,?,?,?,?,?,?)";

                        if($this->db) $stmt = $this->db->prepare($sql);
                        if($stmt){
                            $j=1;
                            
		$stmt->bindValue( $j++, $dto->memPoNo, PDO::PARAM_INT);
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

                    $sql = "UPDATE web_point_btc SET 
				mem_po_no=?,
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
                        
		$stmt->bindValue( $j++, $dto->memPoNo, PDO::PARAM_INT);
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
                if(!$this->db) $this->db=parent::getDatabaseTrade();
                if($this->db){

                    $sql = "DELETE FROM web_point_btc WHERE po_no=?";

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