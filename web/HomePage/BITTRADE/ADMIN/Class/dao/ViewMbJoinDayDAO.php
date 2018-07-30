<?php      
/**
* Description of ViewMbJoinDayDAO
* @description Funhansoft PHP auto templet
* @date 2013-12-14
* @copyright (c)funhansoft.com
* @license http://funhansoft.com/license
* @version 4.0.0
*/
        class ViewMbJoinDayDAO extends BaseDAO{
            private $dbSlave;

            function __construct() {
                parent::__construct();
		$this->dbSlave = parent::getDatabaseSlave();
            }

            

            public function getListCount($field='',$value='',$svdf='',$svdt=''){
                $dto = new CommonListDTO();
                if($this->dbSlave){
                    try{
                        $singleFields = array('date','cnt');
                        $sql_add = $this->getListSearchSQL($singleFields,$field,$value);
                        
                        $sql = "SELECT count(*)  FROM view_mb_join_day{$sql_add}";

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
                $dto = new ViewMbJoinDayDTO();
                $dtoarray = array();          
                if($this->dbSlave){
                    try{
                        $singleFields = array('date','cnt');
                        
                        $sql = "SELECT 	date,
				cnt 
                                FROM view_mb_join_day LIMIT ?,?";

                        if($this->dbSlave) $stmt = $this->dbSlave->prepare($sql);
                        if($stmt){
                            $j=1;
                            $stmt->bindValue( $j++, parent::getListLimitStart(), PDO::PARAM_INT);
                            $stmt->bindValue( $j++, parent::getListLimitRow(), PDO::PARAM_INT);
                            $stmt->execute();

                            $i = 0;
                            while(list(
				$dto->date,
				$dto->cnt) = $stmt->fetch()) {
                                    $dtoarray[$i] = new ViewMbJoinDayDTO();
                                    parent::setResult($i+1);
                                    $dtoarray[$i]->date = substr($dto->date,2,6);
                                    $dtoarray[$i]->cnt = $dto->cnt;  
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