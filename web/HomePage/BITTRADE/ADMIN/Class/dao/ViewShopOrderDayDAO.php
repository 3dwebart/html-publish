<?php      
/**
* Description of ViewShopOrderDayDAO
* @description Funhansoft PHP auto templet
* @date 2014-02-28
* @copyright (c)funhansoft.com
* @license http://funhansoft.com/license
* @version 4.0.0
*/
        class ViewShopOrderDayDAO extends BaseDAO{

            private $db;
            private $dbSlave;

            function __construct() {
                parent::__construct();
            }

            public function getViewById($odDate){
                $dto = new ViewShopOrderDayDTO();
                if(!$this->dbSlave) $this->dbSlave=parent::getDatabaseSlave();
                if($this->dbSlave){
                    try{
                        $sql = "SELECT 	odDate,
				cnt,
				odTempSum,
				odReceptSum,
				odRefundSum 
                                FROM view_shop_order_day WHERE odDate=?  limit 1";

                        if($this->dbSlave) $stmt = $this->dbSlave->prepare($sql);
                        if($stmt){
                            $stmt->bindValue( 1, $odDate, PDO::PARAM_STR);
                            $stmt->execute();
                            list(
				$dto->odDate,
				$dto->cnt,
				$dto->odTempSum,
				$dto->odReceptSum,
				$dto->odRefundSum) = $stmt->fetch();
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
                        $singleFields = array('odDate','cnt');
                        $sql_add = $this->getListSearchSQL($singleFields,$field,$value);
                        
                        $sql = "SELECT count(*)  FROM view_shop_order_day{$sql_add}";

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
                $dto = new ViewShopOrderDayDTO();
                $dtoarray = array();
                if(!$this->dbSlave) $this->dbSlave=parent::getDatabaseSlave();
                if($this->dbSlave){
                    try{
                        $sql = "SELECT 	odDate,
				cnt,
				odTempSum,
				odReceptSum,
				odRefundSum 
                                FROM view_shop_order_day LIMIT ?,?";

                        if($this->dbSlave) $stmt = $this->dbSlave->prepare($sql);
                        if($stmt){
                            $j=1;
                            $stmt->bindValue( $j++, parent::getListLimitStart(), PDO::PARAM_INT);
                            $stmt->bindValue( $j++, parent::getListLimitRow(), PDO::PARAM_INT);
                            $stmt->execute();

                            $i = 0;
                            while(list(
				$dto->odDate,
				$dto->cnt,
				$dto->odTempSum,
				$dto->odReceptSum,
				$dto->odRefundSum) = $stmt->fetch()) {
                                    $dtoarray[$i] = new ViewShopOrderDayDTO();
                                    parent::setResult($i+1);
                                        $dtoarray[$i]->odDate = substr($dto->odDate,2,6);
					$dtoarray[$i]->cnt = $dto->cnt;
					$dtoarray[$i]->odTempSum = $dto->odTempSum;
					$dtoarray[$i]->odReceptSum = $dto->odReceptSum;
					$dtoarray[$i]->odRefundSum = $dto->odRefundSum;  
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
                        $sql = "INSERT INTO view_shop_order_day(
				cnt,
				odTempSum,
				odReceptSum,
				odRefundSum)
                                VALUES(?,?,?,?)";

                        if($this->db) $stmt = $this->db->prepare($sql);
                        if($stmt){
                            $j=1;
                            
		$stmt->bindValue( $j++, $dto->cnt, PDO::PARAM_INT);
		$stmt->bindValue( $j++, $dto->odTempSum, PDO::PARAM_STR);
		$stmt->bindValue( $j++, $dto->odReceptSum, PDO::PARAM_STR);
		$stmt->bindValue( $j++, $dto->odRefundSum, PDO::PARAM_STR);
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

                    $sql = "UPDATE view_shop_order_day SET 
				cnt=?,
				odTempSum=?,
				odReceptSum=?,
				odRefundSum=? WHERE =?";

                    if($this->db) $stmt = $this->db->prepare($sql);
                    if($stmt){
                        $j=1;
                        
		$stmt->bindValue( $j++, $dto->cnt, PDO::PARAM_INT);
		$stmt->bindValue( $j++, $dto->odTempSum, PDO::PARAM_STR);
		$stmt->bindValue( $j++, $dto->odReceptSum, PDO::PARAM_STR);
		$stmt->bindValue( $j++, $dto->odRefundSum, PDO::PARAM_STR);
			
		$stmt->bindValue( $j++, $dto->odDate, PDO::PARAM_STR);
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

                    $sql = "DELETE FROM view_shop_order_day WHERE =?";

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