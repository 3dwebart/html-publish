<?php      
/**
* Description of WebPointBitcoinDAO
* @description Funhansoft PHP auto templet
* @date 2015-06-28
* @copyright (c)funhansoft.com
* @license http://funhansoft.com/license
* @version 4.0.0
*/
        class WebPointBitcoinDAO extends BaseDAO{

            private $db;
            private $dbSlave;

            function __construct() {
                parent::__construct();
            }

            public function getViewById($po_no){
                $dto = new WebPointBitcoinDTO();
                if(!$this->dbSlave) $this->dbSlave=parent::getDatabaseTradeSlave();
                if($this->dbSlave){
                    try{
                        $sql = "SELECT 	po_no,
                                    mb_no,
                                    mb_id,
                                    po_content,
                                    po_point,
                                    od_total_cost,
                                    po_refund_yn,
                                    po_rel_id,
                                    po_rel_action,
                                    po_reg_dt,
                                    po_reg_ip
                                FROM web_point_bitcoin WHERE po_no=?  limit 1";

                        if($this->dbSlave) $stmt = $this->dbSlave->prepare($sql);
                        if($stmt){
                            $stmt->bindValue( 1, $po_no, PDO::PARAM_STR);
                            $stmt->execute();
                            list(
                                $dto->poNo,
                                $dto->mbNo,
                                $dto->mbId,
                                $dto->poContent,
                                $dto->poPoint,
                                $dto->odTotalCost,
                                $dto->poRefundYn,
                                $dto->poRelId,
                                $dto->poRelAction,
                                $dto->poRegDt,
                                $dto->poRegIp) = $stmt->fetch();
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

            public function getViewByIds($mb_no,$po_nos=array(),$timest=null,$timedn=null,$pre_rel_action=null){
                $dto = new WebPointBitcoinDTO();
                $dtoarray = array();
                if(!$this->dbSlave) $this->dbSlave=parent::getDatabaseTradeSlave();
                if($this->dbSlave){
                    try{
                        $sql_add = " WHERE mb_no=? ";
                        if(count($po_nos)>0) $sql_add .= " AND po_no IN (" . (join(",", $po_nos)). ")";
                        if($timest && $timedn) $sql_add .= "AND po_reg_dt between '".$timest."' and '".$timedn."'";
                        if($pre_rel_action) $sql_add .= " AND po_rel_action ='".$pre_rel_action."'";

                        $sql = "SELECT 	po_no,
                                    mb_no,
                                    mb_id,
                                    po_content,
                                    po_point,
                                    od_total_cost,
                                    po_refund_yn,
                                    po_rel_id,
                                    po_rel_action,
                                    po_reg_dt,
                                    po_reg_ip
                                FROM web_point_bitcoin{$sql_add} LIMIT ?,?";

                        if($this->dbSlave) $stmt = $this->dbSlave->prepare($sql);
                        if($stmt){
                            $j=1;
                            $stmt->bindValue( $j++, $mb_no, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, parent::getListLimitStart(), PDO::PARAM_INT);
                            $stmt->bindValue( $j++, parent::getListLimitRow(), PDO::PARAM_INT);
                            $stmt->execute();

                            $i = 0;
                            while(list(
                                $dto->poNo,
                                $dto->mbNo,
                                $dto->mbId,
                                $dto->poContent,
                                $dto->poPoint,
                                $dto->odTotalCost,
                                $dto->poRefundYn,
                                $dto->poRelId,
                                $dto->poRelAction,
                                $dto->poRegDt,
                                $dto->poRegIp) = $stmt->fetch()) {
                                    $dtoarray[$i] = new WebPointBitcoinDTO();
                                    parent::setResult($i+1);
                                    $dtoarray[$i]->poNo = $dto->poNo;
                                    $dtoarray[$i]->mbNo = $dto->mbNo;
                                    $dtoarray[$i]->mbId = $dto->mbId;
                                    $dtoarray[$i]->poContent = $dto->poContent;
                                    $dtoarray[$i]->poPoint = $dto->poPoint;
                                    $dtoarray[$i]->odTotalCost = $dto->odTotalCost;
                                    $dtoarray[$i]->poRefundYn = $dto->poRefundYn;
                                    $dtoarray[$i]->poRelId = $dto->poRelId;
                                    $dtoarray[$i]->poRelAction = $dto->poRelAction;
                                    $dtoarray[$i]->poRegDt = $dto->poRegDt;
                                    $dtoarray[$i]->poRegIp = $dto->poRegIp;
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

            public function getViewByOrderId($mb_no,$order_id,$rel_id=array(),$timest=null,$timedn=null,$pre_rel_action='trade'){
                $dto = new WebPointBitcoinDTO();
                $dtoarray = array();
                if(!$this->dbSlave) $this->dbSlave=parent::getDatabaseTradeSlave();
                if($this->dbSlave){
                    try{
                        $sql_add = " WHERE mb_no=? AND po_rel_action like '".$pre_rel_action."%'";
                        if($timest && $timedn) $sql_add .= " AND (po_reg_dt between '".$timest."' AND '".$timedn."') ";
						$sql_add .= " AND (";
                        $sql_add .= "po_rel_id like '".$order_id."-%' OR po_rel_id='".$order_id."' ";
                        if(count($rel_id)>0){
							$sql_add .= " OR po_rel_id IN (". join(',', $rel_id) .") ";
						}
						$sql_add .= ")";

						$sql = "SELECT 	po_no,
                                    mb_no,
                                    mb_id,
                                    po_content,
                                    po_point,
                                    od_total_cost,
                                    po_refund_yn,
                                    po_rel_id,
                                    po_rel_action,
                                    po_reg_dt,
                                    po_reg_ip
                                FROM web_point_bitcoin{$sql_add} LIMIT ?,?";

                        if($this->dbSlave) $stmt = $this->dbSlave->prepare($sql);
                        if($stmt){
                            $j=1;
                            $stmt->bindValue( $j++, $mb_no, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, parent::getListLimitStart(), PDO::PARAM_INT);
                            $stmt->bindValue( $j++, parent::getListLimitRow(), PDO::PARAM_INT);
                            $stmt->execute();

                            $i = 0;
                            while(list(
                                $dto->poNo,
                                $dto->mbNo,
                                $dto->mbId,
                                $dto->poContent,
                                $dto->poPoint,
                                $dto->odTotalCost,
                                $dto->poRefundYn,
                                $dto->poRelId,
                                $dto->poRelAction,
                                $dto->poRegDt,
                                $dto->poRegIp) = $stmt->fetch()) {
                                    $dtoarray[$i] = new WebPointBitcoinDTO();
                                    parent::setResult($i+1);
                                    $dtoarray[$i]->poNo = $dto->poNo;
                                    $dtoarray[$i]->mbNo = $dto->mbNo;
                                    $dtoarray[$i]->mbId = $dto->mbId;
                                    $dtoarray[$i]->poContent = $dto->poContent;
                                    $dtoarray[$i]->poPoint = $dto->poPoint;
                                    $dtoarray[$i]->odTotalCost = $dto->odTotalCost;
                                    $dtoarray[$i]->poRefundYn = $dto->poRefundYn;
                                    $dtoarray[$i]->poRelId = $dto->poRelId;
                                    $dtoarray[$i]->poRelAction = $dto->poRelAction;
                                    $dtoarray[$i]->poRegDt = $dto->poRegDt;
                                    $dtoarray[$i]->poRegIp = $dto->poRegIp;
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

            public function getViewByIUniqueId($mb_no,$rel_action,$rel_id){
                $dto = new WebPointKrwDTO();
                if(!$this->dbSlave) $this->dbSlave=parent::getDatabaseTradeSlave();
                if($this->dbSlave){
                    try{
                        $sql = "SELECT 	po_no,
                                    mb_no,
                                    mb_id,
                                    po_content,
                                    po_point,
                                    od_total_cost,
                                    po_refund_yn,
                                    po_rel_id,
                                    po_rel_action,
                                    po_reg_dt,
                                    po_reg_ip
                                FROM web_point_bitcoin WHERE mb_no=? and po_rel_action=? and po_rel_id=? limit 1";

                        if($this->dbSlave) $stmt = $this->dbSlave->prepare($sql);
                        if($stmt){
                            $k=1;
                            $stmt->bindValue( $k++, $mb_no, PDO::PARAM_STR);
                            $stmt->bindValue( $k++, $rel_action, PDO::PARAM_STR);
                            $stmt->bindValue( $k++, $rel_id, PDO::PARAM_STR);
                            $stmt->execute();
                            list(
                                $dto->poNo,
                                $dto->mbNo,
                                $dto->mbId,
                                $dto->poContent,
                                $dto->poPoint,
                                $dto->odTotalCost,
                                $dto->poRefundYn,
                                $dto->poRelId,
                                $dto->poRelAction,
                                $dto->poRegDt,
                                $dto->poRegIp) = $stmt->fetch();
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
                if(!$this->dbSlave) $this->dbSlave=parent::getDatabaseTradeSlave();
                if($this->dbSlave){
                    try{
                        $singleFields = array('po_no','mb_no','po_refund_yn','po_rel_action');
                        $sql_add = $this->getListSearchSQL($singleFields,$field,$value);
                        if($svdf && $svdt){
                            if($sql_add){
                                $sql_add .= " AND ";
                            }else{
                                $sql_add .= " WHERE ";
                            }
                            $sql_add .= "(po_reg_dt BETWEEN ? AND DATE_ADD(?,INTERVAL 1 DAY) ) ";
                        }
                        $sql = "SELECT count(*)  FROM web_point_bitcoin{$sql_add}";

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
                $dto = new WebPointBitcoinDTO();
                $dtoarray = array();
                if(!$this->dbSlave) $this->dbSlave=parent::getDatabaseTradeSlave();
                if($this->dbSlave){
                    try{
                        $singleFields = array('po_no','mb_no','po_refund_yn','po_rel_action');
                        $sql_add = $this->getListSearchSQL($singleFields,$field,$value);
                        if($svdf && $svdt){
                            if($sql_add){
                                $sql_add .= " AND ";
                            }else{
                                $sql_add .= " WHERE ";
                            }
                            $sql_add .= "(po_reg_dt BETWEEN ? AND DATE_ADD(?,INTERVAL 1 DAY) ) ";
                        }
                        $sql_add .= $this->getListOrderSQL($singleFields,'po_no');

                        $sql = "SELECT 	po_no,
                                    mb_no,
                                    mb_id,
                                    po_content,
                                    po_point,
                                    od_total_cost,
                                    po_refund_yn,
                                    po_rel_id,
                                    po_rel_action,
                                    po_reg_dt,
                                    po_reg_ip
                                FROM web_point_bitcoin{$sql_add} LIMIT ?,?";

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
                                $dto->poNo,
                                $dto->mbNo,
                                $dto->mbId,
                                $dto->poContent,
                                $dto->poPoint,
                                $dto->odTotalCost,
                                $dto->poRefundYn,
                                $dto->poRelId,
                                $dto->poRelAction,
                                $dto->poRegDt,
                                $dto->poRegIp) = $stmt->fetch()) {
                                    $dtoarray[$i] = new WebPointBitcoinDTO();
                                    parent::setResult($i+1);
                                    $dtoarray[$i]->poNo = $dto->poNo;
                                    $dtoarray[$i]->mbNo = $dto->mbNo;
                                    $dtoarray[$i]->mbId = $dto->mbId;
                                    $dtoarray[$i]->poContent = $dto->poContent;
                                    $dtoarray[$i]->poPoint = $dto->poPoint;
                                    $dtoarray[$i]->odTotalCost = $dto->odTotalCost;
                                    $dtoarray[$i]->poRefundYn = $dto->poRefundYn;
                                    $dtoarray[$i]->poRelId = $dto->poRelId;
                                    $dtoarray[$i]->poRelAction = $dto->poRelAction;
                                    $dtoarray[$i]->poRegDt = $dto->poRegDt;
                                    $dtoarray[$i]->poRegIp = $dto->poRegIp;
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
                if(!$this->db) $this->db=parent::getDatabaseTradeSlave();
                if($this->db){
                    try{
                        $sql = "INSERT INTO web_point_bitcoin(
                                mb_no,
                                mb_id,
                                po_content,
                                po_point,
                                od_total_cost,
                                po_refund_yn,
                                po_rel_id,
                                po_rel_action,
                                po_reg_ip)
                                VALUES(?,?,?,?,?,?,?,?,?)";

                        if($this->db) $stmt = $this->db->prepare($sql);
                        if($stmt){
                            $j=1;

                            $stmt->bindValue( $j++, $dto->mbNo, PDO::PARAM_INT);
                            $stmt->bindValue( $j++, $dto->mbId, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, $dto->poContent, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, $dto->poPoint, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, $dto->odTotalCost, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, $dto->poRefundYn, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, $dto->poRelId, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, $dto->poRelAction, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, $dto->poRegIp, PDO::PARAM_STR);
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
                if(!$this->db) $this->db=parent::getDatabaseTradeSlave();
                if($this->db){

                    $sql = "UPDATE web_point_bitcoin SET
                            mb_no=?,
                            mb_id=?,
                            po_content=?,
                            po_point=?,
                            od_total_cost=?,
                            po_refund_yn=?,
                            po_rel_id=?,
                            po_rel_action=?,
                            po_reg_ip=? WHERE po_no=?";

                    if($this->db) $stmt = $this->db->prepare($sql);
                    if($stmt){
                        $j=1;
                        $stmt->bindValue( $j++, $dto->mbNo, PDO::PARAM_INT);
                        $stmt->bindValue( $j++, $dto->mbId, PDO::PARAM_STR);
                        $stmt->bindValue( $j++, $dto->poContent, PDO::PARAM_STR);
                        $stmt->bindValue( $j++, $dto->poPoint, PDO::PARAM_STR);
                        $stmt->bindValue( $j++, $dto->odTotalCost, PDO::PARAM_STR);
                        $stmt->bindValue( $j++, $dto->poRefundYn, PDO::PARAM_STR);
                        $stmt->bindValue( $j++, $dto->poRelId, PDO::PARAM_STR);
                        $stmt->bindValue( $j++, $dto->poRelAction, PDO::PARAM_STR);
                        $stmt->bindValue( $j++, $dto->poRegIp, PDO::PARAM_STR);
                        $stmt->bindValue( $j++, $dto->poNo, PDO::PARAM_INT);
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
                if(!$this->db) $this->db=parent::getDatabaseTradeSlave();
                if($this->db){

                    $sql = "DELETE FROM web_point_bitcoin WHERE po_no=?";

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