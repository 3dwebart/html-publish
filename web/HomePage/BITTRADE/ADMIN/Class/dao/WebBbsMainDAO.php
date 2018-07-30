<?php      
/**
* Description of WebBbsMainDAO
* @description FunHanSoft Co.,Ltd PHP auto templet
* @date 2015-09-20
* @copyright (c)funhansoft.com
* @license 해당 사이트 이외의 사용은 엄격히 금지되어 있습니다.
* @version 0.2.2
*/
        class WebBbsMainDAO extends BaseDAO{

            private $db;
            private $dbSlave;
            private $chNo;

            function __construct() {
                parent::__construct();
            }
            
            function setCateChno($ch){
                $this->chNo = $ch;
            }

            public function getViewById($bbs_no){
                $dto = new WebBbsMainDTO();
                if(!$this->dbSlave) $this->dbSlave=parent::getDatabaseSlave(); if($this->dbSlave){
                    try{
                        $sql = "SELECT
                                    bbs_no,
                                    ch_no,
                                    cate_name,
                                    parent_bbs_no,
                                    is_notice,
                                    is_secret,
                                    is_html,
                                    mb_id,
                                    mb_level,
                                    mb_nick,
                                    subject,
                                    subject_kr,
                                    subject_cn,
                                    content,
                                    content_kr,
                                    content_cn,
                                    view_level,
                                    view_point,
                                    view_pwd,
                                    image_url,
                                    cmt_use_yn,
                                    cmt_level,
                                    scrap_use_yn,
                                    copy_use_yn,
                                    search_use_yn,
                                    hit,
                                    cmt_cnt,
                                    reg_dt,
                                    mod_dt,
                                    reg_ip,
                                    etc1,
                                    etc2,
                                    etc3,
                                    etc4,
                                    etc5
                                FROM web_bbs_main WHERE bbs_no=?  limit 1";

                        if($this->dbSlave) $stmt = $this->dbSlave->prepare($sql);
                        if($stmt){
                            $stmt->bindValue( 1, $bbs_no, PDO::PARAM_STR);
                            $stmt->execute();
                            list(
                                $dto->bbsNo,
                                $dto->chNo,
                                $dto->cateName,
                                $dto->parentBbsNo,
                                $dto->isNotice,
                                $dto->isSecret,
                                $dto->isHtml,
                                $dto->mbId,
                                $dto->mbLevel,
                                $dto->mbNick,
                                $dto->subject,
                                $dto->subjectKr,
                                $dto->subjectCn,
                                $dto->content,
                                $dto->contentKr,
                                $dto->contentCn,
                                $dto->viewLevel,
                                $dto->viewPoint,
                                $dto->viewPwd,
                                $dto->imageUrl,
                                $dto->cmtUseYn,
                                $dto->cmtLevel,
                                $dto->scrapUseYn,
                                $dto->copyUseYn,
                                $dto->searchUseYn,
                                $dto->hit,
                                $dto->cmtCnt,
                                $dto->regDt,
                                $dto->modDt,
                                $dto->regIp,
                                $dto->etc1,
                                $dto->etc2,
                                $dto->etc3,
                                $dto->etc4,
                                $dto->etc5) = $stmt->fetch();
                            if($stmt->rowCount()==1){
                                //$dto->content = $dto->content;
                                parent::setResult(1);
                            }
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

                        $singleFields = array('bbs_no','ch_no','parent_bbs_no','is_notice','is_secret','is_html','mb_level','view_level','view_point','cmt_use_yn','cmt_level','scrap_use_yn','copy_use_yn','search_use_yn','hit','cmt_cnt');
                        $sql_add = $this->getListSearchSQL($singleFields,$field,$value);
                        if($svdf && $svdt){
                            if($sql_add){
                                $sql_add .= " AND ";
                            }else{
                                $sql_add .= " WHERE ";
                            }
                            $sql_add .= "(reg_dt BETWEEN ? AND DATE_ADD(?,INTERVAL 1 DAY) ) ";
                        }
                        
                        if($this->chNo){
                            if($sql_add){
                                $sql_add .= " AND ";
                            }else{
                                $sql_add .= " WHERE ";
                            }
                            $sql_add .= "ch_no='".$this->chNo."'";
                        }
                        
                        
                        
                        $sql = "SELECT count(*)  FROM web_bbs_main{$sql_add}";

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
                $dto = new WebBbsMainDTO();
                $dtoarray = array();
                if(!$this->dbSlave) $this->dbSlave=parent::getDatabaseSlave(); if($this->dbSlave){
                    try{
                        $singleFields = array('bbs_no','ch_no','parent_bbs_no','is_notice','is_secret','is_html','mb_level','view_level','view_point','cmt_use_yn','cmt_level','scrap_use_yn','copy_use_yn','search_use_yn','hit','cmt_cnt');
                        $sql_add = $this->getListSearchSQL($singleFields,$field,$value);
                        if($svdf && $svdt){
                            if($sql_add){
                                $sql_add .= " AND ";
                            }else{
                                $sql_add .= " WHERE ";
                            }
                            $sql_add .= "(reg_dt BETWEEN ? AND DATE_ADD(?,INTERVAL 1 DAY) ) ";
                        }
                        
                        if($this->chNo){
                            if($sql_add){
                                $sql_add .= " AND ";
                            }else{
                                $sql_add .= " WHERE ";
                            }
                            $sql_add .= "ch_no='".$this->chNo."'";
                        }
                        
                        
                        $sql_add .= $this->getListOrderSQL($singleFields,'bbs_no');

                        $sql = "SELECT
                                    bbs_no,
                                    ch_no,
                                    cate_name,
                                    parent_bbs_no,
                                    is_notice,
                                    is_secret,
                                    is_html,
                                    mb_id,
                                    mb_level,
                                    mb_nick,
                                    subject,
                                    subject_kr,
                                    subject_cn,
                                    view_level,
                                    view_point,
                                    view_pwd,
                                    image_url,
                                    cmt_use_yn,
                                    cmt_level,
                                    scrap_use_yn,
                                    copy_use_yn,
                                    search_use_yn,
                                    hit,
                                    cmt_cnt,
                                    reg_dt,
                                    etc1,
                                    etc2,
                                    etc3,
                                    etc4,
                                    etc5
                                FROM web_bbs_main{$sql_add} LIMIT ?,?";

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
                                $dto->bbsNo,
                                $dto->chNo,
                                $dto->cateName,
                                $dto->parentBbsNo,
                                $dto->isNotice,
                                $dto->isSecret,
                                $dto->isHtml,
                                $dto->mbId,
                                $dto->mbLevel,
                                $dto->mbNick,
                                $dto->subject,
                                $dto->subjectKr,
                                $dto->subjectCn,
                                $dto->viewLevel,
                                $dto->viewPoint,
                                $dto->viewPwd,
                                $dto->imageUrl,
                                $dto->cmtUseYn,
                                $dto->cmtLevel,
                                $dto->scrapUseYn,
                                $dto->copyUseYn,
                                $dto->searchUseYn,
                                $dto->hit,
                                $dto->cmtCnt,
                                $dto->regDt,
                                $dto->etc1,
                                $dto->etc2,
                                $dto->etc3,
                                $dto->etc4,
                                $dto->etc5) = $stmt->fetch()) {
                                    $dtoarray[$i] = new WebBbsMainDTO();
                                    parent::setResult($i+1);
                                    $dtoarray[$i]->bbsNo = $dto->bbsNo;
                                    $dtoarray[$i]->chNo = $dto->chNo;
                                    $dtoarray[$i]->cateName = $dto->cateName;
                                    $dtoarray[$i]->parentBbsNo = $dto->parentBbsNo;
                                    $dtoarray[$i]->isNotice = $dto->isNotice;
                                    $dtoarray[$i]->isSecret = $dto->isSecret;
                                    $dtoarray[$i]->isHtml = $dto->isHtml;
                                    $dtoarray[$i]->mbId = $dto->mbId;
                                    $dtoarray[$i]->mbLevel = $dto->mbLevel;
                                    $dtoarray[$i]->mbNick = $dto->mbNick;
                                    $dtoarray[$i]->subject = $dto->subject;
                                    $dtoarray[$i]->subjectKr = $dto->subjectKr;
                                    $dtoarray[$i]->subjectCn = $dto->subjectCn;
                                    $dtoarray[$i]->viewLevel = $dto->viewLevel;
                                    $dtoarray[$i]->viewPoint = $dto->viewPoint;
                                    $dtoarray[$i]->viewPwd = $dto->viewPwd;
                                    $dtoarray[$i]->imageUrl = $dto->imageUrl;
                                    $dtoarray[$i]->cmtUseYn = $dto->cmtUseYn;
                                    $dtoarray[$i]->cmtLevel = $dto->cmtLevel;
                                    $dtoarray[$i]->scrapUseYn = $dto->scrapUseYn;
                                    $dtoarray[$i]->copyUseYn = $dto->copyUseYn;
                                    $dtoarray[$i]->searchUseYn = $dto->searchUseYn;
                                    $dtoarray[$i]->hit = $dto->hit;
                                    $dtoarray[$i]->cmtCnt = $dto->cmtCnt;
                                    $dtoarray[$i]->regDt = $dto->regDt;
                                    $dtoarray[$i]->modDt = $dto->modDt;
                                    $dtoarray[$i]->regIp = $dto->regIp;
                                    $dtoarray[$i]->etc1 = $dto->etc1;
                                    $dtoarray[$i]->etc2 = $dto->etc2;
                                    $dtoarray[$i]->etc3 = $dto->etc3;
                                    $dtoarray[$i]->etc4 = $dto->etc4;
                                    $dtoarray[$i]->etc5 = $dto->etc5;
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
                        $sql = "INSERT INTO web_bbs_main(
                                    ch_no,
                                    cate_name,
                                    parent_bbs_no,
                                    is_notice,
                                    is_secret,
                                    is_html,
                                    mb_id,
                                    mb_level,
                                    mb_nick,
                                    subject,
                                    subject_kr,
                                    subject_cn,
                                    content,
                                    content_kr,
                                    content_cn,
                                    view_level,
                                    view_point,
                                    view_pwd,
                                    image_url,
                                    cmt_use_yn,
                                    cmt_level,
                                    scrap_use_yn,
                                    copy_use_yn,
                                    search_use_yn,
                                    hit,
                                    cmt_cnt,
                                    mod_dt,
                                    reg_ip,
                                    etc1,
                                    etc2,
                                    etc3,
                                    etc4,
                                    etc5)
                                VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";

                        if($this->db) $stmt = $this->db->prepare($sql);
                        if($stmt){
                            $j=1;

                            $stmt->bindValue( $j++, $dto->chNo, PDO::PARAM_INT);
                            $stmt->bindValue( $j++, $dto->cateName, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, $dto->parentBbsNo, PDO::PARAM_INT);
                            $stmt->bindValue( $j++, $dto->isNotice, PDO::PARAM_INT);
                            $stmt->bindValue( $j++, $dto->isSecret, PDO::PARAM_INT);
                            $stmt->bindValue( $j++, $dto->isHtml, PDO::PARAM_INT);
                            $stmt->bindValue( $j++, $dto->mbId, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, $dto->mbLevel, PDO::PARAM_INT);
                            $stmt->bindValue( $j++, $dto->mbNick, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, $dto->subject, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, $dto->subjectKr, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, $dto->subjectCn, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, $dto->content, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, $dto->contentKr, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, $dto->contentCn, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, $dto->viewLevel, PDO::PARAM_INT);
                            $stmt->bindValue( $j++, $dto->viewPoint, PDO::PARAM_INT);
                            $stmt->bindValue( $j++, $dto->viewPwd, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, $dto->imageUrl, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, $dto->cmtUseYn, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, $dto->cmtLevel, PDO::PARAM_INT);
                            $stmt->bindValue( $j++, $dto->scrapUseYn, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, $dto->copyUseYn, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, $dto->searchUseYn, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, $dto->hit, PDO::PARAM_INT);
                            $stmt->bindValue( $j++, $dto->cmtCnt, PDO::PARAM_INT);
                            $stmt->bindValue( $j++, $dto->modDt, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, $dto->regIp, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, $dto->etc1, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, $dto->etc2, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, $dto->etc3, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, $dto->etc4, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, $dto->etc5, PDO::PARAM_STR);
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

                    $sql = "UPDATE web_bbs_main SET
                                ch_no=?,
                                cate_name=?,
                                parent_bbs_no=?,
                                is_notice=?,
                                is_secret=?,
                                is_html=?,
                                mb_id=?,
                                mb_level=?,
                                mb_nick=?,
                                subject=?,
                                subject_kr=?,
                                subject_cn=?,
                                content=?,
                                content_kr=?,
                                content_cn=?,
                                view_level=?,
                                view_point=?,
                                view_pwd=?,
                                image_url=?,
                                cmt_use_yn=?,
                                cmt_level=?,
                                scrap_use_yn=?,
                                copy_use_yn=?,
                                search_use_yn=?,
                                hit=?,
                                cmt_cnt=?,
                                mod_dt=?,
                                reg_ip=?,
                                etc1=?,
                                etc2=?,
                                etc3=?,
                                etc4=?,
                                etc5=? WHERE bbs_no=?";

                    if($this->db) $stmt = $this->db->prepare($sql);
                    if($stmt){
                        $j=1;

                        $stmt->bindValue( $j++, $dto->chNo, PDO::PARAM_INT);
                        $stmt->bindValue( $j++, $dto->cateName, PDO::PARAM_STR);
                        $stmt->bindValue( $j++, $dto->parentBbsNo, PDO::PARAM_INT);
                        $stmt->bindValue( $j++, $dto->isNotice, PDO::PARAM_INT);
                        $stmt->bindValue( $j++, $dto->isSecret, PDO::PARAM_INT);
                        $stmt->bindValue( $j++, $dto->isHtml, PDO::PARAM_INT);
                        $stmt->bindValue( $j++, $dto->mbId, PDO::PARAM_STR);
                        $stmt->bindValue( $j++, $dto->mbLevel, PDO::PARAM_INT);
                        $stmt->bindValue( $j++, $dto->mbNick, PDO::PARAM_STR);
                        $stmt->bindValue( $j++, $dto->subject, PDO::PARAM_STR);
                        $stmt->bindValue( $j++, $dto->subjectKr, PDO::PARAM_STR);
                        $stmt->bindValue( $j++, $dto->subjectCn, PDO::PARAM_STR);
                        $stmt->bindValue( $j++, $dto->content, PDO::PARAM_STR);
                        $stmt->bindValue( $j++, $dto->contentKr, PDO::PARAM_STR);
                        $stmt->bindValue( $j++, $dto->contentCn, PDO::PARAM_STR);
                        $stmt->bindValue( $j++, $dto->viewLevel, PDO::PARAM_INT);
                        $stmt->bindValue( $j++, $dto->viewPoint, PDO::PARAM_INT);
                        $stmt->bindValue( $j++, $dto->viewPwd, PDO::PARAM_STR);
                        $stmt->bindValue( $j++, $dto->imageUrl, PDO::PARAM_STR);
                        $stmt->bindValue( $j++, $dto->cmtUseYn, PDO::PARAM_STR);
                        $stmt->bindValue( $j++, $dto->cmtLevel, PDO::PARAM_INT);
                        $stmt->bindValue( $j++, $dto->scrapUseYn, PDO::PARAM_STR);
                        $stmt->bindValue( $j++, $dto->copyUseYn, PDO::PARAM_STR);
                        $stmt->bindValue( $j++, $dto->searchUseYn, PDO::PARAM_STR);
                        $stmt->bindValue( $j++, $dto->hit, PDO::PARAM_INT);
                        $stmt->bindValue( $j++, $dto->cmtCnt, PDO::PARAM_INT);
                        $stmt->bindValue( $j++, $dto->modDt, PDO::PARAM_STR);
                        $stmt->bindValue( $j++, $dto->regIp, PDO::PARAM_STR);
                        $stmt->bindValue( $j++, $dto->etc1, PDO::PARAM_STR);
                        $stmt->bindValue( $j++, $dto->etc2, PDO::PARAM_STR);
                        $stmt->bindValue( $j++, $dto->etc3, PDO::PARAM_STR);
                        $stmt->bindValue( $j++, $dto->etc4, PDO::PARAM_STR);
                        $stmt->bindValue( $j++, $dto->etc5, PDO::PARAM_STR);

                        $stmt->bindValue( $j++, $dto->bbsNo, PDO::PARAM_INT);
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

            function setCmtCount($pri,$point){
                parent::setResult(-1);
                if(!$this->db) $this->db=parent::getDatabase(); if($this->db){

                    $sql = "UPDATE web_bbs_main SET cmt_cnt=cmt_cnt+? WHERE bbs_no=?";

                    if($this->db) $stmt = $this->db->prepare($sql);
                    if($stmt){
                        $stmt->bindValue( 1, $point, PDO::PARAM_INT);
                        $stmt->bindValue( 2, $pri, PDO::PARAM_INT);
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

            function setHitCount($pri){
                parent::setResult(-1);
                if(!$this->db) $this->db=parent::getDatabase(); if($this->db){

                    $sql = "UPDATE web_bbs_main SET hit=hit+1 WHERE bbs_no=?";

                    if($this->db) $stmt = $this->db->prepare($sql);
                    if($stmt){
                        $stmt->bindValue( 1, $pri, PDO::PARAM_INT);
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

            function deleteFromPri($pri){
                parent::setResult(-1);
                if(!$this->db) $this->db=parent::getDatabase(); if($this->db){

                    $sql = "DELETE FROM web_bbs_main WHERE bbs_no=?";

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