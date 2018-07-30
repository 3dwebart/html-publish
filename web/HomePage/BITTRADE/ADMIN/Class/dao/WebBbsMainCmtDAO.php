<?php      
/**
* Description of WebBbsMainCmtDAO
* @description FunHanSoft Co.,Ltd PHP auto templet
* @date 2015-09-20
* @copyright (c)funhansoft.com
* @license 해당 사이트 이외의 사용은 엄격히 금지되어 있습니다.
* @version 0.2.2
*/
        class WebBbsMainCmtDAO extends BaseDAO{

            private $db;
            private $dbSlave;

            function __construct() {
                parent::__construct();
            }

            public function getViewById($cmt_no){
                $dto = new WebBbsMainCmtDTO();
                if(!$this->dbSlave) $this->dbSlave=parent::getDatabaseSlave(); if($this->dbSlave){
                    try{
                        $sql = "SELECT
                                    cmt_no,
                                    sort,
                                    bbs_no,
                                    parent_cmt_no,
                                    mb_id,
                                    mb_level,
                                    mb_nick,
                                    content,
                                    image_url,
                                    reg_dt,
                                    mod_dt,
                                    reg_ip
                                FROM web_bbs_main_cmt WHERE cmt_no=?  limit 1";

                        if($this->dbSlave) $stmt = $this->dbSlave->prepare($sql);
                        if($stmt){
                            $stmt->bindValue( 1, $cmt_no, PDO::PARAM_STR);
                            $stmt->execute();
                            list(
                                $dto->cmtNo,
                                $dto->sort,
                                $dto->bbsNo,
                                $dto->parentCmtNo,
                                $dto->mbId,
                                $dto->mbLevel,
                                $dto->mbNick,
                                $dto->content,
                                $dto->imageUrl,
                                $dto->regDt,
                                $dto->modDt,
                                $dto->regIp) = $stmt->fetch();
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
                if(!$this->dbSlave) $this->dbSlave=parent::getDatabaseSlave(); if($this->dbSlave){
                    try{
                        $sql_add = '';
                        if($value){
                            $sql_add = " WHERE {$field} LIKE CONCAT('%',?,'%')";
                            if($field=='cmt_no'||$field=='sort'||$field=='bbs_no'||$field=='parent_cmt_no'||$field=='mb_level'){ $sql_add = " WHERE {$field}=?"; }
                        }
                        $sql = "SELECT count(*)  FROM web_bbs_main_cmt{$sql_add}";

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
                $dto = new WebBbsMainCmtDTO();
                $dtoarray = array();
                if(!$this->dbSlave) $this->dbSlave=parent::getDatabaseSlave(); if($this->dbSlave){
                    try{
                        $sql_add = '';
                        if($value){
                            $sql_add = " WHERE {$field} LIKE CONCAT('%',?,'%')";
                            if($field=='cmt_no'||$field=='sort'||$field=='bbs_no'||$field=='parent_cmt_no'||$field=='mb_level'){ $sql_add = " WHERE {$field}=?"; }
                        }
                        $sql = "SELECT
                                    cmt_no,
                                    sort,
                                    bbs_no,
                                    parent_cmt_no,
                                    mb_id,
                                    mb_level,
                                    mb_nick,
                                    LEFT(content,256),
                                    image_url,
                                    reg_dt,
                                    mod_dt,
                                    reg_ip
                                FROM web_bbs_main_cmt{$sql_add} ORDER BY cmt_no DESC LIMIT ?,?";

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
                                $dto->cmtNo,
                                $dto->sort,
                                $dto->bbsNo,
                                $dto->parentCmtNo,
                                $dto->mbId,
                                $dto->mbLevel,
                                $dto->mbNick,
                                $dto->content,
                                $dto->imageUrl,
                                $dto->regDt,
                                $dto->modDt,
                                $dto->regIp) = $stmt->fetch()) {
                                    $dtoarray[$i] = new WebBbsMainCmtDTO();
                                    parent::setResult($i+1);
                                    $dtoarray[$i]->cmtNo = $dto->cmtNo;
                                    $dtoarray[$i]->sort = $dto->sort;
                                    $dtoarray[$i]->bbsNo = $dto->bbsNo;
                                    $dtoarray[$i]->parentCmtNo = $dto->parentCmtNo;
                                    $dtoarray[$i]->mbId = $dto->mbId;
                                    $dtoarray[$i]->mbLevel = $dto->mbLevel;
                                    $dtoarray[$i]->mbNick = $dto->mbNick;
                                    $dtoarray[$i]->content = $dto->content;
                                    $dtoarray[$i]->imageUrl = $dto->imageUrl;
                                    $dtoarray[$i]->regDt = $dto->regDt;
                                    $dtoarray[$i]->modDt = $dto->modDt;
                                    $dtoarray[$i]->regIp = $dto->regIp;
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
                if(!$this->db) $this->db=parent::getDatabase(); if($this->db){
                    try{
                        $sql = "INSERT INTO web_bbs_main_cmt(
                                    sort,
                                    bbs_no,
                                    parent_cmt_no,
                                    mb_id,
                                    mb_level,
                                    mb_nick,
                                    content,
                                    image_url,
                                    mod_dt,
                                    reg_ip)
                                VALUES(?,?,?,?,?,?,?,?,?,?)";

                        if($this->db) $stmt = $this->db->prepare($sql);
                        if($stmt){
                            $j=1;
                            $stmt->bindValue( $j++, $dto->sort, PDO::PARAM_INT);
                            $stmt->bindValue( $j++, $dto->bbsNo, PDO::PARAM_INT);
                            $stmt->bindValue( $j++, $dto->parentCmtNo, PDO::PARAM_INT);
                            $stmt->bindValue( $j++, $dto->mbId, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, $dto->mbLevel, PDO::PARAM_INT);
                            $stmt->bindValue( $j++, $dto->mbNick, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, $dto->content, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, $dto->imageUrl, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, $dto->modDt, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, $dto->regIp, PDO::PARAM_STR);
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
                if(!$this->db) $this->db=parent::getDatabase(); if($this->db){

                    $sql = "UPDATE web_bbs_main_cmt SET
                                sort=?,
                                bbs_no=?,
                                parent_cmt_no=?,
                                mb_id=?,
                                mb_level=?,
                                mb_nick=?,
                                content=?,
                                image_url=?,
                                mod_dt=?,
                                reg_ip=? WHERE cmt_no=?";

                    if($this->db) $stmt = $this->db->prepare($sql);
                    if($stmt){
                        $j=1;
                        $stmt->bindValue( $j++, $dto->sort, PDO::PARAM_INT);
                        $stmt->bindValue( $j++, $dto->bbsNo, PDO::PARAM_INT);
                        $stmt->bindValue( $j++, $dto->parentCmtNo, PDO::PARAM_INT);
                        $stmt->bindValue( $j++, $dto->mbId, PDO::PARAM_STR);
                        $stmt->bindValue( $j++, $dto->mbLevel, PDO::PARAM_INT);
                        $stmt->bindValue( $j++, $dto->mbNick, PDO::PARAM_STR);
                        $stmt->bindValue( $j++, $dto->content, PDO::PARAM_STR);
                        $stmt->bindValue( $j++, $dto->imageUrl, PDO::PARAM_STR);
                        $stmt->bindValue( $j++, $dto->modDt, PDO::PARAM_STR);
                        $stmt->bindValue( $j++, $dto->regIp, PDO::PARAM_STR);

                        $stmt->bindValue( $j++, $dto->cmtNo, PDO::PARAM_INT);
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
                if(!$this->db) $this->db=parent::getDatabase(); if($this->db){

                    $sql = "DELETE FROM web_bbs_main_cmt WHERE cmt_no=?";

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