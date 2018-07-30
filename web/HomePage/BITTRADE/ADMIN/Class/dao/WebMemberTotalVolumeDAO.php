<?php      
/**
* Description of WebMemberTotalVolumeDAO
* @description Funhansoft PHP auto templet
* @date 2015-07-01
* @copyright (c)funhansoft.com
* @license http://funhansoft.com/license
* @version 4.0.0
*/
        class WebMemberTotalVolumeDAO extends BaseDAO{

            private $db;
            private $dbSlave;

            function __construct() {
                parent::__construct();
            }

            public function getViewById($mb_no){
                $dto = new WebMemberTotalVolumeDTO();
                if(!$this->dbSlave) $this->dbSlave=parent::getDatabase();
                if($this->dbSlave){
                    try{
                        $sql = "SELECT 	ta_no,
				mb_no,
				ta_volume,
				reg_dt 
                                FROM web_member_total_volume WHERE mb_no=?  limit 1";

                        if($this->dbSlave) $stmt = $this->dbSlave->prepare($sql);
                        if($stmt){
                            $stmt->bindValue( 1, $mb_no, PDO::PARAM_STR);
                            $stmt->execute();
                            list(
				$dto->taNo,
				$dto->mbNo,
				$dto->taVolume,
				$dto->regDt) = $stmt->fetch();
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
            
            function __destruct(){
                $this->db = NULL;
                $this->dto = NULL;
            }
        }