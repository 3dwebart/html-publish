<?php      
/**
* Description of WebCashDepositsDAO
* @description Funhansoft PHP auto templet
* @date 2015-09-20
* @copyright (c)funhansoft.com
* @license http://funhansoft.com/license
* @version 0.2.2
*/
        class WebSalesStatementDAO extends BaseDAO{

            private $db;
            private $dbSlave;
            private $dbName = 'fns_trade_order_';
            private $dbTableName = 'web_open_order_krw_';
            private $po_type = '';
            
            function __construct() {
                parent::__construct();
            }
            
            public function setDbName($nm){
                $this->dbName = $nm;
            }
            
            public function setTableName($nm){
                $this->dbTableName = $nm;
            }

            public function getListCount($field='',$value='',$svdf='',$svdt=''){
                $dto = new CommonListDTO();
                if(!$this->db) $this->db=parent::getDatabaseTrade($this->dbName); if($this->db){
                    try{
                        $sql_add = '';
                        if($value){
                            $sql_add = " WHERE {$field} LIKE CONCAT('%',?,'%')";
                            if($field=='od_id' || $field=='od_tatal_cost'){ $sql_add = " WHERE {$field}=?"; }
                        }
                        if($svdf && $svdt){
                            if($sql_add){
                                $sql_add .= " AND ";
                            }else{
                                $sql_add .= " WHERE ";
                            }
                            $sql_add .= "(od_datetime BETWEEN ? AND DATE_ADD(?,INTERVAL 1 DAY) ) ";
                        }
                        $sql = "SELECT COUNT(*)  FROM web_cash_deposits{$sql_add}";

                        if($this->db) $stmt = $this->db->prepare($sql);
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
                $dto = new WebSalesStatementDTO();
                $dtoarray = array();          
                if(!$this->db) $this->db=parent::getDatabaseTrade("fns_trade_order_btc"); if($this->db){
                    try{
                        $po_type = 'btc';
                        $singleFields = array('od_id','od_total_cost');
                        $sql_add = $this->getListSearchSQL($singleFields,$field,$value);
                        if($svdf && $svdt){
                            if($sql_add){
                                $sql_add .= " AND ";
                            }else{
                                $sql_add .= " WHERE ";
                            }
                            $sql_add .= "(od_reg_dt BETWEEN ? AND DATE_ADD(?,INTERVAL 1 DAY) ) ";
                        }
                        $sql_add .= $this->getListOrderSQL($singleFields,'od_id');
                        
                        $sql = "SELECT 	
                                    sum(od_total_cost) as od_total_cost
                                FROM ".$this->dbTableName."$po_type {$sql_add} LIMIT ?,?";
                                
                                

                        if($this->db) $stmt = $this->db->prepare($sql);
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
				$dto->odTotalCost,) = $stmt->fetch()) {
                                    $dtoarray[$i] = new WebSalesStatementDTO();
                                    parent::setResult($i+1);
                                    
                                    $dtoarray[$i]->odTotalCost = $dto->odTotalCost;
                                    
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

            public function getCostBTC($field='',$value='',$svdf='',$svdt=''){
                $dto = new WebSalesStatementDTO();
                $dtoarray = array();          
                if(!$this->db) $this->db=parent::getDatabaseTrade("fns_trade_order_btc"); if($this->db){
                    try{
                        $po_type = 'btc';
                        $singleFields = array('od_id','od_total_cost');
                        $sql_add = $this->getListSearchSQL($singleFields,$field,$value);
                        if($svdf && $svdt){
                            if($sql_add){
                                $sql_add .= " AND ";
                            }else{
                                $sql_add .= " WHERE ";
                            }
                            $sql_add .= "(od_reg_dt BETWEEN ? AND DATE_ADD(?,INTERVAL 1 DAY) ) ";
                        }
                        $sql_add .= $this->getListOrderSQL($singleFields,'od_id');
                        
                        $sql = "SELECT 	
                                    sum(od_total_cost) as od_total_cost
                                FROM ".$this->dbTableName."$po_type {$sql_add} LIMIT ?,?";

                        if($this->db) $stmt = $this->db->prepare($sql);
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
				$dto->odTotalCost,) = $stmt->fetch()) {
                                    $dtoarray[$i] = new WebSalesStatementDTO();
                                    parent::setResult($i+1);
                                    
                                    $dtoarray[$i]->odTotalCost = $dto->odTotalCost;
                                    
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
            
            public function getCostBCH($field='',$value='',$svdf='',$svdt=''){
                $dto = new WebSalesStatementDTO();
                $dtoarray = array();          
                if(!$this->db) $this->db=parent::getDatabaseTrade("fns_trade_order_bch"); if($this->db){
                    try{
                        $po_type = 'bch';
                        $singleFields = array('od_id','od_total_cost');
                        $sql_add = $this->getListSearchSQL($singleFields,$field,$value);
                        if($svdf && $svdt){
                            if($sql_add){
                                $sql_add .= " AND ";
                            }else{
                                $sql_add .= " WHERE ";
                            }
                            $sql_add .= "(od_reg_dt BETWEEN ? AND DATE_ADD(?,INTERVAL 1 DAY) ) ";
                        }
                        $sql_add .= $this->getListOrderSQL($singleFields,'od_id');
                        
                        $sql = "SELECT 	
                                    sum(od_total_cost) as od_total_cost
                                FROM ".$this->dbTableName."$po_type {$sql_add} LIMIT ?,?";

                        if($this->db) $stmt = $this->db->prepare($sql);
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
				$dto->odTotalCost,) = $stmt->fetch()) {
                                    $dtoarray[$i] = new WebSalesStatementDTO();
                                    parent::setResult($i+1);
                                    
                                    $dtoarray[$i]->odTotalCost = $dto->odTotalCost;
                                    
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
            
            public function getCostLTC($field='',$value='',$svdf='',$svdt=''){
                $dto = new WebSalesStatementDTO();
                $dtoarray = array();          
                if(!$this->db) $this->db=parent::getDatabaseTrade("fns_trade_order_ltc"); if($this->db){
                    try{
                        $po_type = 'ltc';
                        $singleFields = array('od_id','od_total_cost');
                        $sql_add = $this->getListSearchSQL($singleFields,$field,$value);
                        if($svdf && $svdt){
                            if($sql_add){
                                $sql_add .= " AND ";
                            }else{
                                $sql_add .= " WHERE ";
                            }
                            $sql_add .= "(od_reg_dt BETWEEN ? AND DATE_ADD(?,INTERVAL 1 DAY) ) ";
                        }
                        $sql_add .= $this->getListOrderSQL($singleFields,'od_id');
                        
                        $sql = "SELECT 	
                                    sum(od_total_cost) as od_total_cost
                                FROM ".$this->dbTableName."$po_type {$sql_add} LIMIT ?,?";

                        if($this->db) $stmt = $this->db->prepare($sql);
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
				$dto->odTotalCost,) = $stmt->fetch()) {
                                    $dtoarray[$i] = new WebSalesStatementDTO();
                                    parent::setResult($i+1);
                                    
                                    $dtoarray[$i]->odTotalCost = $dto->odTotalCost;
                                    
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
            
            public function getCostETH($field='',$value='',$svdf='',$svdt=''){
                $dto = new WebSalesStatementDTO();
                $dtoarray = array();          
                if(!$this->db) $this->db=parent::getDatabaseTrade("fns_trade_order_eth"); if($this->db){
                    try{
                        $po_type = 'eth';
                        $singleFields = array('od_id','od_total_cost');
                        $sql_add = $this->getListSearchSQL($singleFields,$field,$value);
                        if($svdf && $svdt){
                            if($sql_add){
                                $sql_add .= " AND ";
                            }else{
                                $sql_add .= " WHERE ";
                            }
                            $sql_add .= "(od_reg_dt BETWEEN ? AND DATE_ADD(?,INTERVAL 1 DAY) ) ";
                        }
                        $sql_add .= $this->getListOrderSQL($singleFields,'od_id');
                        
                        $sql = "SELECT 	
                                    sum(od_total_cost) as od_total_cost
                                FROM ".$this->dbTableName."$po_type {$sql_add} LIMIT ?,?";

                        if($this->db) $stmt = $this->db->prepare($sql);
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
				$dto->odTotalCost,) = $stmt->fetch()) {
                                    $dtoarray[$i] = new WebSalesStatementDTO();
                                    parent::setResult($i+1);
                                    
                                    $dtoarray[$i]->odTotalCost = $dto->odTotalCost;
                                    
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
            
            public function getCostETC($field='',$value='',$svdf='',$svdt=''){
                $dto = new WebSalesStatementDTO();
                $dtoarray = array();          
                if(!$this->db) $this->db=parent::getDatabaseTrade("fns_trade_order_etc"); if($this->db){
                    try{
                        $po_type = 'etc';
                        $singleFields = array('od_id','od_total_cost');
                        $sql_add = $this->getListSearchSQL($singleFields,$field,$value);
                        if($svdf && $svdt){
                            if($sql_add){
                                $sql_add .= " AND ";
                            }else{
                                $sql_add .= " WHERE ";
                            }
                            $sql_add .= "(od_reg_dt BETWEEN ? AND DATE_ADD(?,INTERVAL 1 DAY) ) ";
                        }
                        $sql_add .= $this->getListOrderSQL($singleFields,'od_id');
                        
                        $sql = "SELECT 	
                                    sum(od_total_cost) as od_total_cost
                                FROM ".$this->dbTableName."$po_type {$sql_add} LIMIT ?,?";

                        if($this->db) $stmt = $this->db->prepare($sql);
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
				$dto->odTotalCost,) = $stmt->fetch()) {
                                    $dtoarray[$i] = new WebSalesStatementDTO();
                                    parent::setResult($i+1);
                                    
                                    $dtoarray[$i]->odTotalCost = $dto->odTotalCost;
                                    
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

            function deleteFromPri($pri){
                parent::setResult(-1);
                if(!$this->db) $this->db=parent::getDatabase(); if($this->db){

                    $sql = "DELETE FROM web_cash_deposits WHERE od_id=?";

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