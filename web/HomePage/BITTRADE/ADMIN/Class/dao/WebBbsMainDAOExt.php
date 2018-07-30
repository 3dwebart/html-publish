<?php      
/**
* Description of WebBbsMainDAO
* @description FunHanSoft Co.,Ltd PHP auto templet
* @date 2013-09-22
* @copyright (c)funhansoft.com
* @license 해당 사이트 이외의 사용은 엄격히 금지되어 있습니다.
* @version 0.2.3
*/
        class WebBbsMainDAOExt extends BaseDAO{

            protected $db;
            protected $dbSlave;
            protected $mbId='';

            function __construct() {
                parent::__construct();
                
            }
            
            
            public function getViewById($ch_no,$bbs_no){
                $dto = new WebBbsMainDTO();
                unset($dto->modDt);
                unset($dto->regIp);
                unset($dto->etc1);
                unset($dto->etc2);
                unset($dto->etc3);
                unset($dto->etc4);
                unset($dto->etc5);
                if(!$this->dbSlave) $this->dbSlave=parent::getDatabaseSlave(); if($this->dbSlave){
                    try{
                        $sql = "SELECT 	bbs_no,
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
				content,
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
				reg_dt
                                FROM web_bbs_main WHERE ch_no=? and bbs_no=?  limit 1";

                        if($this->dbSlave) $stmt = $this->dbSlave->prepare($sql);
                        if($stmt){
                            $stmt->bindValue( 1, $ch_no, PDO::PARAM_STR);
                            $stmt->bindValue( 2, $bbs_no, PDO::PARAM_STR);
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
				$dto->content,
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
				$dto->regDt) = $stmt->fetch();
                            if($dto->viewPwd) $dto->viewPwd = md5($dto->viewPwd);
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

            public function getSimpleList($ch_no,$field='',$value=''){
                $dto = new WebBbsMainDTO();
                $dtoarray = array();
                if(!$this->dbSlave) $this->dbSlave=parent::getDatabaseSlave(); if($this->dbSlave){
                    try{
                        $singleFields = array('bbs_no','ch_no','parent_bbs_no','is_notice','is_secret','is_html','mb_level','view_level','view_point','cmt_use_yn','cmt_level','scrap_use_yn','copy_use_yn','search_use_yn','hit','cmt_cnt');
                        $sql_add = $this->getListSearchSQL($singleFields,$field,$value);
                        if($sql_add) $sql_add .= " and ch_no=?";
                        else $sql_add = " WHERE ch_no=?";
                        $sql_add .= $this->getListOrderSQL($singleFields,'bbs_no');
                        
                        $sql = "SELECT 	bbs_no,
				cate_name,
				parent_bbs_no,
				is_notice,
				is_secret,
				mb_id,
				mb_nick,
				subject,
				hit,
				cmt_cnt,
				reg_dt
                                FROM web_bbs_main{$sql_add} LIMIT ?,?";

                        if($this->dbSlave) $stmt = $this->dbSlave->prepare($sql);
                        if($stmt){
                            $j=1;
                            if($value)
                                $stmt->bindValue( $j++, $value, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, $ch_no, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, parent::getListLimitStart(), PDO::PARAM_INT);
                            $stmt->bindValue( $j++, parent::getListLimitRow(), PDO::PARAM_INT);
                            $stmt->execute();

                            $i = 0;
                            while(list(
				$dto->bbsNo,
				$dto->cateName,
				$dto->parentBbsNo,
				$dto->isNotice,
				$dto->isSecret,
				$dto->mbId,
				$dto->mbNick,
				$dto->subject,
				$dto->hit,
				$dto->cmtCnt,
				$dto->regDt) = $stmt->fetch()) {
                                    $dtoarray[$i] = new WebBbsMainDTO();
                                    parent::setResult($i+1);
                                    $dtoarray[$i]->bbsNo = $dto->bbsNo;
					unset($dtoarray[$i]->chNo);
					$dtoarray[$i]->cateName = $dto->cateName;
					$dtoarray[$i]->parentBbsNo = $dto->parentBbsNo;
					$dtoarray[$i]->isNotice = $dto->isNotice;
					$dtoarray[$i]->isSecret = $dto->isSecret;
					unset($dtoarray[$i]->isHtml);
					$dtoarray[$i]->mbId = $dto->mbId;
					unset($dtoarray[$i]->mbLevel);
					$dtoarray[$i]->mbNick = $dto->mbNick;
					$dtoarray[$i]->subject = $dto->subject;
					unset($dtoarray[$i]->content);
					unset($dtoarray[$i]->viewLevel);
					unset($dtoarray[$i]->viewPoint);
					unset($dtoarray[$i]->viewPwd);
					unset($dtoarray[$i]->imageUrl);
					unset($dtoarray[$i]->cmtUseYn);
					unset($dtoarray[$i]->cmtLevel);
					unset($dtoarray[$i]->scrapUseYn);
					unset($dtoarray[$i]->copyUseYn);
					unset($dtoarray[$i]->searchUseYn);
					unset($dtoarray[$i]->hit);
					$dtoarray[$i]->cmtCnt = $dto->cmtCnt;
					$dtoarray[$i]->regDt = $dto->regDt;
					unset($dtoarray[$i]->modDt);
					unset($dtoarray[$i]->regIp);
					unset($dtoarray[$i]->etc1);
					unset($dtoarray[$i]->etc2);
					unset($dtoarray[$i]->etc3);
					unset($dtoarray[$i]->etc4);
					unset($dtoarray[$i]->etc5); 
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
            
            public function getPrivateListCount($ch_no,$field='',$value=''){
                $dto = new CommonListDTO();
                if(!$this->dbSlave) $this->dbSlave=parent::getDatabaseSlave(); if($this->dbSlave){
                    try{
                        $singleFields = array('bbs_no','ch_no','parent_bbs_no','is_notice','is_secret','is_html','mb_level','view_level','view_point','cmt_use_yn','cmt_level','scrap_use_yn','copy_use_yn','search_use_yn','hit','cmt_cnt');
                        $sql_add = $this->getListSearchSQL($singleFields,$field,$value);
                        if($sql_add) $sql_add .= " and ch_no=? and mb_id=?";
                        else $sql_add = " WHERE ch_no=? and mb_id=?";
                        $sql = "SELECT count(*)  FROM web_bbs_main{$sql_add}";

                        if($this->dbSlave) $stmt = $this->dbSlave->prepare($sql);
                        if($stmt){
                            $j=1;
                            if($value){
                                $stmt->bindValue( $j++, $value, PDO::PARAM_STR);
                            }
                            $stmt->bindValue( $j++, $ch_no, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, $this->mbId, PDO::PARAM_STR);
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
            
            public function getSimplePrivateList($ch_no,$field='',$value=''){
                $dto = new WebBbsMainDTO();
                $dtoarray = array();
                if(!$this->dbSlave) $this->dbSlave=parent::getDatabaseSlave(); if($this->dbSlave){
                    try{
                        $singleFields = array('bbs_no','ch_no','parent_bbs_no','is_notice','is_secret','is_html','mb_level','view_level','view_point','cmt_use_yn','cmt_level','scrap_use_yn','copy_use_yn','search_use_yn','hit','cmt_cnt');
                        $sql_add = $this->getListSearchSQL($singleFields,$field,$value);
                        if($sql_add) $sql_add .= " and ch_no=? and mb_id=?";
                        else $sql_add = " WHERE ch_no=? and mb_id=?";
                        $sql_add .= $this->getListOrderSQL($singleFields,'bbs_no');
                        
                        $sql = "SELECT 	bbs_no,
				cate_name,
				parent_bbs_no,
				is_notice,
				is_secret,
				mb_id,
				mb_nick,
				subject,
				hit,
				cmt_cnt,
				reg_dt
                                FROM web_bbs_main{$sql_add} LIMIT ?,?";

                        if($this->dbSlave) $stmt = $this->dbSlave->prepare($sql);
                        if($stmt){
                            $j=1;
                            if($value)
                                $stmt->bindValue( $j++, $value, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, $ch_no, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, $this->mbId, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, parent::getListLimitStart(), PDO::PARAM_INT);
                            $stmt->bindValue( $j++, parent::getListLimitRow(), PDO::PARAM_INT);
                            $stmt->execute();

                            $i = 0;
                            while(list(
				$dto->bbsNo,
				$dto->cateName,
				$dto->parentBbsNo,
				$dto->isNotice,
				$dto->isSecret,
				$dto->mbId,
				$dto->mbNick,
				$dto->subject,
				$dto->hit,
				$dto->cmtCnt,
				$dto->regDt) = $stmt->fetch()) {
                                    $dtoarray[$i] = new WebBbsMainDTO();
                                    parent::setResult($i+1);
                                    $dtoarray[$i]->bbsNo = $dto->bbsNo;
					unset($dtoarray[$i]->chNo);
					$dtoarray[$i]->cateName = $dto->cateName;
					$dtoarray[$i]->parentBbsNo = $dto->parentBbsNo;
					$dtoarray[$i]->isNotice = $dto->isNotice;
					$dtoarray[$i]->isSecret = $dto->isSecret;
					unset($dtoarray[$i]->isHtml);
					$dtoarray[$i]->mbId = $dto->mbId;
					unset($dtoarray[$i]->mbLevel);
					$dtoarray[$i]->mbNick = $dto->mbNick;
					$dtoarray[$i]->subject = $dto->subject;
					unset($dtoarray[$i]->content);
					unset($dtoarray[$i]->viewLevel);
					unset($dtoarray[$i]->viewPoint);
					unset($dtoarray[$i]->viewPwd);
					unset($dtoarray[$i]->imageUrl);
					unset($dtoarray[$i]->cmtUseYn);
					unset($dtoarray[$i]->cmtLevel);
					unset($dtoarray[$i]->scrapUseYn);
					unset($dtoarray[$i]->copyUseYn);
					unset($dtoarray[$i]->searchUseYn);
					unset($dtoarray[$i]->hit);
					$dtoarray[$i]->cmtCnt = $dto->cmtCnt;
					$dtoarray[$i]->regDt = $dto->regDt;
					unset($dtoarray[$i]->modDt);
					unset($dtoarray[$i]->regIp);
					unset($dtoarray[$i]->etc1);
					unset($dtoarray[$i]->etc2);
					unset($dtoarray[$i]->etc3);
					unset($dtoarray[$i]->etc4);
					unset($dtoarray[$i]->etc5); 
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

            function __destruct(){
                $this->db = NULL;
                $this->dto = NULL;
            }
        }