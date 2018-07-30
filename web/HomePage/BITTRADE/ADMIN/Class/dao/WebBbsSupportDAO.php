<?php      
/**
* Description of WebBbsSupportDAO
* @description FunHanSoft Co.,Ltd  PHP auto templet
* @date 2017-09-10
* @copyright (c)funhansoft.com
* @license 해당 사이트 이외의 사용은 엄격히 금지되어 있습니다.
* @version 4.0.0
*/
        class WebBbsSupportDAO extends BaseDAO{

            private $db;
            private $dbSlave;

            function __construct() {
                parent::__construct();
            }

            public function getViewById($bbs_no){
                $dto = new WebBbsSupportDTO();
                if(!$this->dbSlave) $this->dbSlave=parent::getDatabaseSlave();
                if($this->dbSlave){
                    try{
                        $sql = "SELECT 	bbs_no,
				cate_no,
				subject,
				subject_kr,
				subject_cn,
				subject_jp,
				content,
				content_kr,
				content_cn,
				content_jp,
				image_url,
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
                                FROM web_bbs_support WHERE bbs_no=?  limit 1";

                        if($this->dbSlave) $stmt = $this->dbSlave->prepare($sql);
                        if($stmt){
                            $stmt->bindValue( 1, $bbs_no, PDO::PARAM_STR);
                            $stmt->execute();
                            list(
				$dto->bbsNo,
				$dto->cateNo,
				$dto->subject,
				$dto->subjectKr,
				$dto->subjectCn,
				$dto->subjectJp,
				$dto->content,
				$dto->contentKr,
				$dto->contentCn,
				$dto->contentJp,
				$dto->imageUrl,
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

            public function getListCount($field='',$value='',$svdf='',$svdt=''){
                $dto = new CommonListDTO();
                if(!$this->dbSlave) $this->dbSlave=parent::getDatabaseSlave();
                if($this->dbSlave){
                    try{
                        $singleFields = array('bbs_no','cate_no','hit','cmt_cnt');
                        $sql_add = $this->getListSearchSQL($singleFields,$field,$value);
                        if($svdf && $svdt){
                            if($sql_add){
                                $sql_add .= " AND ";
                            }else{
                                $sql_add .= " WHERE ";
                            }
                            $sql_add .= "(reg_dt BETWEEN ? AND DATE_ADD(?,INTERVAL 1 DAY) ) ";
                        }
                        
                        $sql = "SELECT count(*)  FROM web_bbs_support{$sql_add}";

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

            public function getList($field='',$value='',$svdf='',$svdt=''){
                $dto = new WebBbsSupportDTO();
                $dtoarray = array();
                if(!$this->dbSlave) $this->dbSlave=parent::getDatabaseSlave();
                if($this->dbSlave){
                    try{
                        $singleFields = array('bbs_no','cate_no','hit','cmt_cnt');
                        $sql_add = $this->getListSearchSQL($singleFields,$field,$value);
                        if($svdf && $svdt){
                            if($sql_add){
                                $sql_add .= " AND ";
                            }else{
                                $sql_add .= " WHERE ";
                            }
                            $sql_add .= "(reg_dt BETWEEN ? AND DATE_ADD(?,INTERVAL 1 DAY) ) ";
                        }
                        $sql_add .= $this->getListOrderSQL($singleFields,'bbs_no');
                        
                        $sql = "SELECT 	bbs_no,
				cate_no,
				subject,
				subject_kr,
				subject_cn,
				subject_jp,
				image_url,
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
                                FROM web_bbs_support{$sql_add} LIMIT ?,?";

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
				$dto->cateNo,
				$dto->subject,
				$dto->subjectKr,
				$dto->subjectCn,
				$dto->subjectJp,
				$dto->imageUrl,
				$dto->hit,
				$dto->cmtCnt,
				$dto->regDt,
				$dto->modDt,
				$dto->regIp,
				$dto->etc1,
				$dto->etc2,
				$dto->etc3,
				$dto->etc4,
				$dto->etc5) = $stmt->fetch()) {
                                    $dtoarray[$i] = new WebBbsSupportDTO();
                                    parent::setResult($i+1);
                                    $dtoarray[$i]->bbsNo = $dto->bbsNo;
					$dtoarray[$i]->cateNo = $dto->cateNo;
					$dtoarray[$i]->subject = $dto->subject;
					$dtoarray[$i]->subjectKr = $dto->subjectKr;
					$dtoarray[$i]->subjectCn = $dto->subjectCn;
					$dtoarray[$i]->subjectJp = $dto->subjectJp;
					$dtoarray[$i]->imageUrl = $dto->imageUrl;
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
                    }catch(DBException $e){ parent::setResult(Error::dbPrepare);}
                }else{
                    parent::setResult(Error::dbConn);
                }
                $dto = null;
                return $dtoarray;
            }

            public function setInsert($dto){
                parent::setResult(-1);
                if(!$this->db) $this->db=parent::getDatabase();
                if($this->db){
                    try{
                        $sql = "INSERT INTO web_bbs_support(
				cate_no,
				subject,
				subject_kr,
				subject_cn,
				subject_jp,
				content,
				content_kr,
				content_cn,
				content_jp,
				image_url,
				hit,
				cmt_cnt,
				reg_ip,
				etc1,
				etc2,
				etc3,
				etc4,
				etc5)
                                VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";

                        if($this->db) $stmt = $this->db->prepare($sql);
                        if($stmt){
                            $j=1;
                            
		$stmt->bindValue( $j++, $dto->cateNo, ($dto->cateNo)?PDO::PARAM_INT:PDO::PARAM_NULL);
		$stmt->bindValue( $j++, $dto->subject, ($dto->subject)?PDO::PARAM_STR:PDO::PARAM_NULL);
		$stmt->bindValue( $j++, $dto->subjectKr, ($dto->subjectKr)?PDO::PARAM_STR:PDO::PARAM_NULL);
		$stmt->bindValue( $j++, $dto->subjectCn, ($dto->subjectCn)?PDO::PARAM_STR:PDO::PARAM_NULL);
		$stmt->bindValue( $j++, $dto->subjectJp, ($dto->subjectJp)?PDO::PARAM_STR:PDO::PARAM_NULL);
		$stmt->bindValue( $j++, $dto->content, ($dto->content)?PDO::PARAM_STR:PDO::PARAM_NULL);
		$stmt->bindValue( $j++, $dto->contentKr, ($dto->contentKr)?PDO::PARAM_STR:PDO::PARAM_NULL);
		$stmt->bindValue( $j++, $dto->contentCn, ($dto->contentCn)?PDO::PARAM_STR:PDO::PARAM_NULL);
		$stmt->bindValue( $j++, $dto->contentJp, ($dto->contentJp)?PDO::PARAM_STR:PDO::PARAM_NULL);
		$stmt->bindValue( $j++, $dto->imageUrl, ($dto->imageUrl)?PDO::PARAM_STR:PDO::PARAM_NULL);
		$stmt->bindValue( $j++, $dto->hit, PDO::PARAM_INT);
		$stmt->bindValue( $j++, $dto->cmtCnt, PDO::PARAM_INT);
		$stmt->bindValue( $j++, $dto->regIp, ($dto->regIp)?PDO::PARAM_STR:PDO::PARAM_NULL);
		$stmt->bindValue( $j++, $dto->etc1, ($dto->etc1)?PDO::PARAM_STR:PDO::PARAM_NULL);
		$stmt->bindValue( $j++, $dto->etc2, ($dto->etc2)?PDO::PARAM_STR:PDO::PARAM_NULL);
		$stmt->bindValue( $j++, $dto->etc3, ($dto->etc3)?PDO::PARAM_STR:PDO::PARAM_NULL);
		$stmt->bindValue( $j++, $dto->etc4, ($dto->etc4)?PDO::PARAM_STR:PDO::PARAM_NULL);
		$stmt->bindValue( $j++, $dto->etc5, ($dto->etc5)?PDO::PARAM_STR:PDO::PARAM_NULL);
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
                if(!$this->db) $this->db=parent::getDatabase();
                if($this->db){

                    $sql = "UPDATE web_bbs_support SET 
				cate_no=?,
				subject=?,
				subject_kr=?,
				subject_cn=?,
				subject_jp=?,
				content=?,
				content_kr=?,
				content_cn=?,
				content_jp=?,
				image_url=?,
				hit=?,
				cmt_cnt=?,
				mod_dt=now() WHERE bbs_no=?";

                    if($this->db) $stmt = $this->db->prepare($sql);
                    if($stmt){
                        $j=1;
                        
		$stmt->bindValue( $j++, $dto->cateNo, ($dto->cateNo)?PDO::PARAM_INT:PDO::PARAM_NULL);
		$stmt->bindValue( $j++, $dto->subject, ($dto->subject)?PDO::PARAM_STR:PDO::PARAM_NULL);
		$stmt->bindValue( $j++, $dto->subjectKr, ($dto->subjectKr)?PDO::PARAM_STR:PDO::PARAM_NULL);
		$stmt->bindValue( $j++, $dto->subjectCn, ($dto->subjectCn)?PDO::PARAM_STR:PDO::PARAM_NULL);
		$stmt->bindValue( $j++, $dto->subjectJp, ($dto->subjectJp)?PDO::PARAM_STR:PDO::PARAM_NULL);
		$stmt->bindValue( $j++, $dto->content, ($dto->content)?PDO::PARAM_STR:PDO::PARAM_NULL);
		$stmt->bindValue( $j++, $dto->contentKr, ($dto->contentKr)?PDO::PARAM_STR:PDO::PARAM_NULL);
		$stmt->bindValue( $j++, $dto->contentCn, ($dto->contentCn)?PDO::PARAM_STR:PDO::PARAM_NULL);
		$stmt->bindValue( $j++, $dto->contentJp, ($dto->contentJp)?PDO::PARAM_STR:PDO::PARAM_NULL);
		$stmt->bindValue( $j++, $dto->imageUrl, ($dto->imageUrl)?PDO::PARAM_STR:PDO::PARAM_NULL);
		$stmt->bindValue( $j++, $dto->hit, PDO::PARAM_INT);
		$stmt->bindValue( $j++, $dto->cmtCnt, PDO::PARAM_INT);

			
		$stmt->bindValue( $j++, $dto->bbsNo, ($dto->bbsNo)?PDO::PARAM_INT:PDO::PARAM_NULL);
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
                if(!$this->db) $this->db=parent::getDatabase();
                if($this->db){

                    $sql = "DELETE FROM web_bbs_support WHERE bbs_no=?";

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