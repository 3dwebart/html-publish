<?php      
/**
* Description of WebConfigWalletServerDAO
* @description FunHanSoft Co.,Ltd  PHP auto templet
* @date 2017-08-19
* @copyright (c)funhansoft.com
* @license 해당 사이트 이외의 사용은 엄격히 금지되어 있습니다.
* @version 4.0.0
*/
        class WebConfigWalletServerDAO extends BaseDAO{

            private $db;
            private $dbSlave;

            function __construct() {
                parent::__construct();
            }

            public function getViewById($wa_no){
                $dto = new WebConfigWalletServerDTO();
                if(!$this->dbSlave) $this->dbSlave=parent::getDatabaseSlave();
                if($this->dbSlave){
                    try{
                        $sql = "SELECT 	wa_no,
				co_id,
				po_type,
				wa_explain,
				wa_staus,
				wa_tx_fee,
				wa_rpc_proto,
				wa_rpc_hostname,
				wa_rpc_ip,
				wa_rpc_port,
				wa_account_cnt,
				wa_html,
				wa_etc1,
				wa_etc2,
				wa_etc3,
				tr_reg_dt 
                                FROM web_config_wallet_server WHERE wa_no=?  limit 1";

                        if($this->dbSlave) $stmt = $this->dbSlave->prepare($sql);
                        if($stmt){
                            $stmt->bindValue( 1, $wa_no, PDO::PARAM_STR);
                            $stmt->execute();
                            list(
				$dto->waNo,
				$dto->coId,
				$dto->poType,
				$dto->waExplain,
				$dto->waStaus,
				$dto->waTxFee,
				$dto->waRpcProto,
				$dto->waRpcHostname,
				$dto->waRpcIp,
				$dto->waRpcPort,
				$dto->waAccountCnt,
				$dto->waHtml,
				$dto->waEtc1,
				$dto->waEtc2,
				$dto->waEtc3,
				$dto->trRegDt) = $stmt->fetch();
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
            
            public function getViewByPoType($po_type){
                $dto = new WebConfigWalletServerDTO();
                if(!$this->dbSlave) $this->dbSlave=parent::getDatabaseSlave();
                if($this->dbSlave){
                    try{
                        $sql = "SELECT 	wa_no,
				co_id,
				po_type,
				wa_explain,
				wa_staus,
				wa_tx_fee,
				wa_rpc_proto,
				wa_rpc_hostname,
				wa_rpc_ip,
				wa_rpc_port,
				wa_account_cnt,
				wa_user,
				wa_pass,
				wa_wallet_pwd,
				wa_html,
				wa_etc1,
				wa_etc2,
				wa_etc3,
				tr_reg_dt 
                                FROM web_config_wallet_server WHERE po_type=?  limit 1";

                        if($this->dbSlave) $stmt = $this->dbSlave->prepare($sql);
                        if($stmt){
                            $stmt->bindValue( 1, $po_type, PDO::PARAM_STR);
                            $stmt->execute();
                            list(
				$dto->waNo,
				$dto->coId,
				$dto->poType,
				$dto->waExplain,
				$dto->waStaus,
				$dto->waTxFee,
				$dto->waRpcProto,
				$dto->waRpcHostname,
				$dto->waRpcIp,
				$dto->waRpcPort,
				$dto->waAccountCnt,
				$dto->waUser,
				$dto->waPass,
				$dto->waWalletPwd,
				$dto->waHtml,
				$dto->waEtc1,
				$dto->waEtc2,
				$dto->waEtc3,
				$dto->trRegDt) = $stmt->fetch();
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
                        $singleFields = array('wa_no','co_id','wa_staus','wa_rpc_proto','wa_account_cnt');
                        $sql_add = $this->getListSearchSQL($singleFields,$field,$value);
                        
                        $sql = "SELECT count(*)  FROM web_config_wallet_server{$sql_add}";

                        if($this->dbSlave) $stmt = $this->dbSlave->prepare($sql);
                        if($stmt){
                            if($value)
                                $stmt->bindValue( 1, $value, PDO::PARAM_STR);
                            
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
                $dto = new WebConfigWalletServerDTO();
                $dtoarray = array();
                if(!$this->dbSlave) $this->dbSlave=parent::getDatabaseSlave();
                if($this->dbSlave){
                    try{
                        $singleFields = array('wa_no','co_id','wa_staus','wa_rpc_proto','wa_account_cnt');
                        $sql_add = $this->getListSearchSQL($singleFields,$field,$value);
                        $sql_add .= $this->getListOrderSQL($singleFields,'wa_no');
                        
                        $sql = "SELECT 	wa_no,
				co_id,
				po_type,
				wa_explain,
				wa_staus,
				wa_tx_fee,
				wa_rpc_proto,
				wa_rpc_hostname,
				wa_rpc_ip,
				wa_rpc_port,
				wa_account_cnt,
				LEFT(wa_html,256),
				wa_etc1,
				wa_etc2,
				wa_etc3,
				tr_reg_dt 
                                FROM web_config_wallet_server{$sql_add} LIMIT ?,?";

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
				$dto->waNo,
				$dto->coId,
				$dto->poType,
				$dto->waExplain,
				$dto->waStaus,
				$dto->waTxFee,
				$dto->waRpcProto,
				$dto->waRpcHostname,
				$dto->waRpcIp,
				$dto->waRpcPort,
				$dto->waAccountCnt,
				$dto->waHtml,
				$dto->waEtc1,
				$dto->waEtc2,
				$dto->waEtc3,
				$dto->trRegDt) = $stmt->fetch()) {
                                    $dtoarray[$i] = new WebConfigWalletServerDTO();
                                    parent::setResult($i+1);
                                    $dtoarray[$i]->waNo = $dto->waNo;
					$dtoarray[$i]->coId = $dto->coId;
					$dtoarray[$i]->poType = $dto->poType;
					$dtoarray[$i]->waExplain = $dto->waExplain;
					$dtoarray[$i]->waStaus = $dto->waStaus;
					$dtoarray[$i]->waTxFee = $dto->waTxFee;
					$dtoarray[$i]->waRpcProto = $dto->waRpcProto;
					$dtoarray[$i]->waRpcHostname = $dto->waRpcHostname;
					$dtoarray[$i]->waRpcIp = $dto->waRpcIp;
					$dtoarray[$i]->waRpcPort = $dto->waRpcPort;
					$dtoarray[$i]->waAccountCnt = $dto->waAccountCnt;
					$dtoarray[$i]->waHtml = $dto->waHtml;
					$dtoarray[$i]->waEtc1 = $dto->waEtc1;
					$dtoarray[$i]->waEtc2 = $dto->waEtc2;
					$dtoarray[$i]->waEtc3 = $dto->waEtc3;
					$dtoarray[$i]->trRegDt = $dto->trRegDt;  
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
                        $sql = "INSERT INTO web_config_wallet_server(
				co_id,
				po_type,
				wa_explain,
				wa_staus,
				wa_tx_fee,
				wa_rpc_proto,
				wa_rpc_hostname,
				wa_rpc_ip,
				wa_rpc_port,
				wa_account_cnt,
				wa_user,
				wa_pass,
				wa_wallet_pwd,
				wa_html,
				wa_etc1,
				wa_etc2,
				wa_etc3)
                                VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";

                        if($this->db) $stmt = $this->db->prepare($sql);
                        if($stmt){
                            $j=1;
                            
		$stmt->bindValue( $j++, $dto->coId, ($dto->coId)?PDO::PARAM_INT:PDO::PARAM_NULL);
		$stmt->bindValue( $j++, $dto->poType, ($dto->poType)?PDO::PARAM_STR:PDO::PARAM_NULL);
		$stmt->bindValue( $j++, $dto->waExplain, ($dto->waExplain)?PDO::PARAM_STR:PDO::PARAM_NULL);
		$stmt->bindValue( $j++, $dto->waStaus, ($dto->waStaus)?PDO::PARAM_STR:PDO::PARAM_NULL);
		$stmt->bindValue( $j++, $dto->waTxFee, ($dto->waTxFee)?PDO::PARAM_STR:PDO::PARAM_NULL);
		$stmt->bindValue( $j++, $dto->waRpcProto, PDO::PARAM_STR);
		$stmt->bindValue( $j++, $dto->waRpcHostname, ($dto->waRpcHostname)?PDO::PARAM_STR:PDO::PARAM_NULL);
		$stmt->bindValue( $j++, $dto->waRpcIp, ($dto->waRpcIp)?PDO::PARAM_STR:PDO::PARAM_NULL);
		$stmt->bindValue( $j++, $dto->waRpcPort, ($dto->waRpcPort)?PDO::PARAM_STR:PDO::PARAM_NULL);
		$stmt->bindValue( $j++, $dto->waAccountCnt, ($dto->waAccountCnt)?PDO::PARAM_INT:PDO::PARAM_NULL);
		$stmt->bindValue( $j++, $dto->waUser, ($dto->waUser)?PDO::PARAM_STR:PDO::PARAM_NULL);
		$stmt->bindValue( $j++, $dto->waPass, ($dto->waPass)?PDO::PARAM_STR:PDO::PARAM_NULL);
		$stmt->bindValue( $j++, $dto->waWalletPwd, ($dto->waWalletPwd)?PDO::PARAM_STR:PDO::PARAM_NULL);
		$stmt->bindValue( $j++, $dto->waHtml, ($dto->waHtml)?PDO::PARAM_STR:PDO::PARAM_NULL);
		$stmt->bindValue( $j++, $dto->waEtc1, ($dto->waEtc1)?PDO::PARAM_STR:PDO::PARAM_NULL);
		$stmt->bindValue( $j++, $dto->waEtc2, ($dto->waEtc2)?PDO::PARAM_STR:PDO::PARAM_NULL);
		$stmt->bindValue( $j++, $dto->waEtc3, ($dto->waEtc3)?PDO::PARAM_STR:PDO::PARAM_NULL);
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

                    $sql = "UPDATE web_config_wallet_server SET 
				co_id=?,
				po_type=?,
				wa_explain=?,
				wa_staus=?,
				wa_tx_fee=?,
				wa_rpc_proto=?,
				wa_rpc_hostname=?,
				wa_rpc_ip=?,
				wa_rpc_port=?,
				wa_account_cnt=?,
				wa_html=?,
				wa_etc1=?,
				wa_etc2=?,
				wa_etc3=? WHERE wa_no=?";

                    if($this->db) $stmt = $this->db->prepare($sql);
                    if($stmt){
                        $j=1;
                        
		$stmt->bindValue( $j++, $dto->coId, ($dto->coId)?PDO::PARAM_INT:PDO::PARAM_NULL);
		$stmt->bindValue( $j++, $dto->poType, ($dto->poType)?PDO::PARAM_STR:PDO::PARAM_NULL);
		$stmt->bindValue( $j++, $dto->waExplain, ($dto->waExplain)?PDO::PARAM_STR:PDO::PARAM_NULL);
		$stmt->bindValue( $j++, $dto->waStaus, ($dto->waStaus)?PDO::PARAM_STR:PDO::PARAM_NULL);
		$stmt->bindValue( $j++, $dto->waTxFee, ($dto->waTxFee)?PDO::PARAM_STR:PDO::PARAM_NULL);
		$stmt->bindValue( $j++, $dto->waRpcProto, PDO::PARAM_STR);
		$stmt->bindValue( $j++, $dto->waRpcHostname, ($dto->waRpcHostname)?PDO::PARAM_STR:PDO::PARAM_NULL);
		$stmt->bindValue( $j++, $dto->waRpcIp, ($dto->waRpcIp)?PDO::PARAM_STR:PDO::PARAM_NULL);
		$stmt->bindValue( $j++, $dto->waRpcPort, ($dto->waRpcPort)?PDO::PARAM_STR:PDO::PARAM_NULL);
		$stmt->bindValue( $j++, $dto->waAccountCnt, ($dto->waAccountCnt)?PDO::PARAM_INT:PDO::PARAM_NULL);
		$stmt->bindValue( $j++, $dto->waHtml, ($dto->waHtml)?PDO::PARAM_STR:PDO::PARAM_NULL);
		$stmt->bindValue( $j++, $dto->waEtc1, ($dto->waEtc1)?PDO::PARAM_STR:PDO::PARAM_NULL);
		$stmt->bindValue( $j++, $dto->waEtc2, ($dto->waEtc2)?PDO::PARAM_STR:PDO::PARAM_NULL);
		$stmt->bindValue( $j++, $dto->waEtc3, ($dto->waEtc3)?PDO::PARAM_STR:PDO::PARAM_NULL);
			
		$stmt->bindValue( $j++, $dto->waNo, ($dto->waNo)?PDO::PARAM_INT:PDO::PARAM_NULL);
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

                    $sql = "DELETE FROM web_config_wallet_server WHERE wa_no=?";

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