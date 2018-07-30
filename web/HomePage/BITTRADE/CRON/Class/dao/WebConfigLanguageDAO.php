<?php      
/**
* Description of WebConfigLanguageDAO
* @description FunHanSoft Co.,Ltd  PHP auto templet
* @date 2016-10-24
* @copyright (c)funhansoft.com
* @license 해당 사이트 이외의 사용은 엄격히 금지되어 있습니다.
* @version 0.3.0
*/
        class WebConfigLanguageDAO extends BaseDAO{

            private $db;
            private $dbSlave;

            function __construct() {
                parent::__construct();
            }

            public function getViewById($cf_no){
                $dto = new WebConfigLanguageDTO();
                if(!$this->dbSlave) $this->dbSlave=parent::getDatabaseSlave();
                if($this->dbSlave){
                    try{
                        $sql = "SELECT 	cf_no,
                                    cf_key1,
                                    cf_key2,
                                    cf_view_type,
                                    cf_ko,
                                    cf_en,
                                    cf_zh,
                                    cf_ja
                                FROM web_config_language WHERE cf_no=?  limit 1";

                        if($this->dbSlave) $stmt = $this->dbSlave->prepare($sql);
                        if($stmt){
                            $stmt->bindValue( 1, $cf_no, PDO::PARAM_STR);
                            $stmt->execute();
                            list(
                                $dto->cfNo,
                                $dto->cfKey1,
                                $dto->cfKey2,
                                $dto->cfViewType,
                                $dto->cfKo,
                                $dto->cfEn,
                                $dto->cfZh,
                                $dto->cfJa) = $stmt->fetch();
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
                        $singleFields = array('cf_no','cf_view_type');
                        $sql_add = $this->getListSearchSQL($singleFields,$field,$value);

                        $sql = "SELECT count(*)  FROM web_config_language{$sql_add}";

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

            public function getList($field='',$value='',$svdf='',$svdt=''){
                $dto = new WebConfigLanguageDTO();
                $dtoarray = array();
                if(!$this->dbSlave) $this->dbSlave=parent::getDatabaseSlave();
                if($this->dbSlave){
                    try{
                        $singleFields = array('cf_no','cf_view_type');
                        $sql_add = $this->getListSearchSQL($singleFields,$field,$value);
                        $sql_add .= $this->getListOrderSQL($singleFields,'cf_no');

                        $sql = "SELECT 	cf_no,
                                    cf_key1,
                                    cf_key2,
                                    cf_view_type,
                                    cf_ko,
                                    cf_en,
                                    cf_zh,
                                    cf_ja
                                FROM web_config_language{$sql_add} LIMIT ?,?";

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
                                $dto->cfKey1,
                                $dto->cfKey2,
                                $dto->cfViewType,
                                $dto->cfKo,
                                $dto->cfEn,
                                $dto->cfZh,
                                $dto->cfJa) = $stmt->fetch()) {
                                    $dtoarray[$i] = new WebConfigLanguageDTO();
                                    parent::setResult($i+1);
                                    $dtoarray[$i]->cfNo = $dto->cfNo;
                                    $dtoarray[$i]->cfKey1 = $dto->cfKey1;
                                    $dtoarray[$i]->cfKey2 = $dto->cfKey2;
                                    $dtoarray[$i]->cfViewType = $dto->cfViewType;
                                    $dtoarray[$i]->cfKo = $dto->cfKo;
                                    $dtoarray[$i]->cfEn = $dto->cfEn;
                                    $dtoarray[$i]->cfZh = $dto->cfZh;
                                    $dtoarray[$i]->cfJa = $dto->cfJa;
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

            public function getListAll($field='',$value=''){
                $dto = new WebConfigLanguageDTO();
                $dtoarray = array();
                if(!$this->dbSlave) $this->dbSlave=parent::getDatabaseSlave();
                if($this->dbSlave){
                    try{
                        $singleFields = array('cf_no','cf_view_type');
                        $sql_add = $this->getListSearchSQL($singleFields,$field,$value);
//                        $sql_add .= $this->getListOrderSQL($singleFields,'ln_no');
                        $sql_add .= ' ORDER BY cf_key1 ASC, cf_key2 ASC ';

                        $sql = "SELECT 	cf_no,
                                    cf_key1,
                                    cf_key2,
                                    cf_view_type,
                                    cf_ko,
                                    cf_en,
                                    cf_zh,
                                    cf_ja
                                FROM web_config_language{$sql_add} ";// LIMIT ?,?

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
                                $dto->cfKey1,
                                $dto->cfKey2,
                                $dto->cfViewType,
                                $dto->cfKo,
                                $dto->cfEn,
                                $dto->cfZh,
                                $dto->cfJa) = $stmt->fetch()) {
                                    $dtoarray[$i] = new WebConfigLanguageDTO();
                                    parent::setResult($i+1);
                                    $dtoarray[$i]->cfNo = $dto->cfNo;
                                    $dtoarray[$i]->cfKey1 = $dto->cfKey1;
                                    $dtoarray[$i]->cfKey2 = $dto->cfKey2;
                                    $dtoarray[$i]->cfViewType = $dto->cfViewType;
                                    $dtoarray[$i]->cfKo = $dto->cfKo;
                                    $dtoarray[$i]->cfEn = $dto->cfEn;
                                    $dtoarray[$i]->cfZh = $dto->cfZh;
                                    $dtoarray[$i]->cfJa = $dto->cfJa;
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
                        $sql = "INSERT INTO web_config_language(
                                    cf_key1,
                                    cf_key2,
                                    cf_view_type,
                                    cf_ko,
                                    cf_en,
                                    cf_zh,
                                    cf_ja)
                                VALUES(?,?,?,?,?,?,?)";

                        if($this->db) $stmt = $this->db->prepare($sql);
                        if($stmt){
                            $j=1;

                            $stmt->bindValue( $j++, $dto->cfKey1, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, $dto->cfKey2, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, $dto->cfViewType, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, $dto->cfKo, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, $dto->cfEn, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, $dto->cfZh, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, $dto->cfJa, PDO::PARAM_STR);
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

                    $sql = "UPDATE web_config_language SET
                                cf_key1=?,
                                cf_key2=?,
                                cf_view_type=?,
                                cf_ko=?,
                                cf_en=?,
                                cf_zh=?,
                                cf_ja=? WHERE cf_no=?";

                    if($this->db) $stmt = $this->db->prepare($sql);
                    if($stmt){
                        $j=1;

                        $stmt->bindValue( $j++, $dto->cfKey1, PDO::PARAM_STR);
                        $stmt->bindValue( $j++, $dto->cfKey2, PDO::PARAM_STR);
                        $stmt->bindValue( $j++, $dto->cfViewType, PDO::PARAM_STR);
                        $stmt->bindValue( $j++, $dto->cfKo, PDO::PARAM_STR);
                        $stmt->bindValue( $j++, $dto->cfEn, PDO::PARAM_STR);
                        $stmt->bindValue( $j++, $dto->cfZh, PDO::PARAM_STR);
                        $stmt->bindValue( $j++, $dto->cfJa, PDO::PARAM_STR);

                        $stmt->bindValue( $j++, $dto->cfNo, PDO::PARAM_INT);
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

                    $sql = "DELETE FROM web_config_language WHERE cf_no=?";

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