<?php      
/**
* Description of AutoIncrementDAO
* @description FunHanSoft Co.,Ltd  PHP auto templet
* @date 2017-07-12
* @copyright (c)funhansoft.com
* @license 해당 사이트 이외의 사용은 엄격히 금지되어 있습니다.
* @version 4.0.0
*/
        class AutoIncrementDAO extends BaseDAO{

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

            public function getList($field='',$value='',$svdf='',$svdt=''){
                $dto = new AutoIncrementDTO();
                $dtoarray = array();
                if(!$this->dbSlave) $this->dbSlave=parent::getDatabaseTradeSlave($this->dbName);
                if($this->dbSlave){
                    try{
                        $sql = "SELECT 
                                    TABLE_NAME,
                                    AUTO_INCREMENT
                                FROM information_schema.tables
                                WHERE TABLE_SCHEMA = '".$this->dbName."' ";

                        if($this->dbSlave) $stmt = $this->dbSlave->prepare($sql);
                        if($stmt){
                            $stmt->execute();

                            $i = 0;
                            while(list(
                                $dto->tableName,
                                $dto->autoIncrement) = $stmt->fetch()) {
                                    $dtoarray[$i] = new AutoIncrementDTO();
                                    parent::setResult($i+1);
                                    $dtoarray[$i]->tableName = $dto->tableName;
                                    $dtoarray[$i]->autoIncrement = $dto->autoIncrement;
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

            function __destruct(){
                $this->db = NULL;
                $this->dto = NULL;
            }
        }