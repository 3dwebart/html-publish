<?php      
/**
* Description of CronWalletBlockCheckDAO
* @description FunHanSoft Co.,Ltd  PHP auto templet
* @date 2017-09-12
* @copyright (c)funhansoft.com
* @license 해당 사이트 이외의 사용은 엄격히 금지되어 있습니다.
* @version 0.3.0
*/
        class CronWalletBlockCheckDAO extends BaseDAO{

            private $db;
            private $dbSlave;

            function __construct() {
                parent::__construct();
            }

            public function getLatestBlock($po_type){
                $dto = new CronWalletBlockCheckDTO();
                if(!$this->dbSlave) $this->dbSlave=parent::getDatabaseSlave();
                if($this->dbSlave){
                    try{
                        $sql = "SELECT 	
                                wc_start_block,
                                wc_end_block,
                                wc_find_cnt,
                                wc_reg_dt 
                                FROM cron_wallet_block_check WHERE po_type=? ORDER BY wc_no DESC limit 1";

                        if($this->dbSlave) $stmt = $this->dbSlave->prepare($sql);
                        if($stmt){
                            $stmt->bindValue( 1, $po_type, PDO::PARAM_STR);
                            $stmt->execute();
                            list(
                                $dto->wcStartBlock,
                                $dto->wcEndBlock,
                                $dto->wcFindCnt,
                                $dto->wcRegDt) = $stmt->fetch();
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
            
            public function setBlockInsert($dto){
                parent::setResult(-1);
                if(!$this->db) $this->db=parent::getDatabase();
                if($this->db){
                    try{
                        $sql = "INSERT INTO cron_wallet_block_check(
                                po_type,
                                wc_start_block,
                                wc_end_block,
                                wc_find_cnt )
                                VALUES(?,?,?,?)";

                        if($this->db) $stmt = $this->db->prepare($sql);
                        if($stmt){
                            $j=1;
                            
                            $stmt->bindValue( $j++, $dto->poType, ($dto->poType)?PDO::PARAM_STR:PDO::PARAM_NULL);
                            $stmt->bindValue( $j++, $dto->wcStartBlock, PDO::PARAM_INT);
                            $stmt->bindValue( $j++, $dto->wcEndBlock, PDO::PARAM_INT);
                            $stmt->bindValue( $j++, $dto->wcFindCnt, PDO::PARAM_INT);
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


            

            function __destruct(){
                $this->db = NULL;
                $this->dto = NULL;
            }
        }