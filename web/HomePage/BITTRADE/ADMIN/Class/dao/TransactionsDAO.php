<?php      
/**
* Description of TransactionsDAO
* @description Funhansoft PHP auto templet
* @date 2015-07-01
* @copyright (c)funhansoft.com
* @license http://funhansoft.com/license
* @version 4.0.0
*/
        class TransactionsDAO extends BaseDAO{

            private $db;
            private $dbSlave;

            function __construct() {
                parent::__construct();
            }

            public function getViewById($tr_no){
                $dto = new TransactionsDTO();
                if(!$this->dbSlave) $this->dbSlave=parent::getDatabaseSlave();
                if($this->dbSlave){
                    try{
                        $sql = "SELECT 	tr_no,
                                txid,
                                server,
                                account,
                                category,
                                address,
                                amount,
                                fee,
                                confirmations,
                                blockhash,
                                blockindex,
                                blocktime,
                                walletconflicts,
                                is_point_pay_yn,
                                point_pay_dt,
                                reg_dt 
                                FROM bitcoin_transactions WHERE tr_no=?  limit 1";

                        if($this->dbSlave) $stmt = $this->dbSlave->prepare($sql);
                        if($stmt){
                            $stmt->bindValue( 1, $tr_no, PDO::PARAM_STR);
                            $stmt->execute();
                            list(
				$dto->trNo,
				$dto->txid,
				$dto->server,
				$dto->account,
				$dto->category,
				$dto->address,
				$dto->amount,
				$dto->fee,
				$dto->confirmations,
				$dto->blockhash,
				$dto->blockindex,
				$dto->blocktime,
				$dto->walletconflicts,
				$dto->isPointPayYn,
				$dto->pointPayDt,
				$dto->regDt) = $stmt->fetch();
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
            
            public function getViewByTxId($txid){
                $dto = new TransactionsDTO();
                if(!$this->dbSlave) $this->dbSlave=parent::getDatabaseSlave();
                if($this->dbSlave){
                    try{
                        $sql = "SELECT 	tr_no,
                                mb_no,
                                txid,
                                server,
                                account,
                                category,
                                address,
                                amount,
                                fee,
                                confirmations,
                                blockhash,
                                blockindex,
                                blocktime,
                                walletconflicts,
                                is_point_pay_yn,
                                point_pay_dt,
                                reg_dt 
                                FROM bitcoin_transactions WHERE txid=?  limit 1";

                        if($this->dbSlave) $stmt = $this->dbSlave->prepare($sql);
                        if($stmt){
                            $stmt->bindValue( 1, $txid, PDO::PARAM_STR);
                            $stmt->execute();
                            list(
                                $dto->trNo,
                                $dto->mbNo,
                                $dto->txid,
                                $dto->server,
                                $dto->account,
                                $dto->category,
                                $dto->address,
                                $dto->amount,
                                $dto->fee,
                                $dto->confirmations,
                                $dto->blockhash,
                                $dto->blockindex,
                                $dto->blocktime,
                                $dto->walletconflicts,
                                $dto->isPointPayYn,
                                $dto->pointPayDt,
                                $dto->regDt) = $stmt->fetch();
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
            
            public function getListsByTxId($txid){
                $dto = new TransactionsDTO();
                $dtoarray = array();
                if(!$this->dbSlave) $this->dbSlave=parent::getDatabaseSlave();
                if($this->dbSlave){
                    try{
                       
                        $sql = "SELECT 	tr_no,
                        mb_no,
                        txid,
                        server,
                        account,
                        category,
                        address,
                        amount,
                        fee,
                        confirmations,
                        blockhash,
                        blockindex,
                        blocktime,
                        walletconflicts,
                        is_point_pay_yn,
                        point_pay_dt,
                        reg_dt FROM bitcoin_transactions WHERE txid=? LIMIT ?,?";

                        if($this->dbSlave) $stmt = $this->dbSlave->prepare($sql);
                        if($stmt){
                            $j=1;
                            $stmt->bindValue( $j++, $txid, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, parent::getListLimitStart(), PDO::PARAM_INT);
                            $stmt->bindValue( $j++, parent::getListLimitRow(), PDO::PARAM_INT);
                            $stmt->execute();

                            $i = 0;
                            while(list(
                                $dto->trNo,
                                $dto->mbNo,
                                $dto->txid,
                                $dto->server,
                                $dto->account,
                                $dto->category,
                                $dto->address,
                                $dto->amount,
                                $dto->fee,
                                $dto->confirmations,
                                $dto->blockhash,
                                $dto->blockindex,
                                $dto->blocktime,
                                $dto->walletconflicts,
                                $dto->isPointPayYn,
                                $dto->pointPayDt,
                                $dto->regDt) = $stmt->fetch()) {
                                    $dtoarray[$i] = new TransactionsDTO();
                                    parent::setResult($i+1);
                                    $dtoarray[$i]->trNo = $dto->trNo;
                                    $dtoarray[$i]->mbNo = $dto->mbNo;
                                    $dtoarray[$i]->txid = $dto->txid;
                                    $dtoarray[$i]->server = $dto->server;
                                    $dtoarray[$i]->account = $dto->account;
                                    $dtoarray[$i]->category = $dto->category;
                                    $dtoarray[$i]->address = $dto->address;
                                    $dtoarray[$i]->amount = $dto->amount;
                                    $dtoarray[$i]->fee = $dto->fee;
                                    $dtoarray[$i]->confirmations = $dto->confirmations;
                                    $dtoarray[$i]->blockhash = $dto->blockhash;
                                    $dtoarray[$i]->blockindex = $dto->blockindex;
                                    $dtoarray[$i]->blocktime = $dto->blocktime;
                                    $dtoarray[$i]->walletconflicts = $dto->walletconflicts;
                                    $dtoarray[$i]->isPointPayYn = $dto->isPointPayYn;
                                    $dtoarray[$i]->pointPayDt = $dto->pointPayDt;
                                    $dtoarray[$i]->regDt = $dto->regDt;  
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

            public function getListCount($field='',$value='',$svdf='',$svdt=''){
                $dto = new CommonListDTO();
                if(!$this->dbSlave) $this->dbSlave=parent::getDatabaseSlave();
                if($this->dbSlave){
                    try{
                        $singleFields = array('tr_no','confirmations','blockindex','is_point_pay_yn');
                        $sql_add = $this->getListSearchSQL($singleFields,$field,$value);
                        
                        $sql = "SELECT count(*)  FROM bitcoin_transactions{$sql_add}";

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
                $dto = new TransactionsDTO();
                $dtoarray = array();
                if(!$this->dbSlave) $this->dbSlave=parent::getDatabaseSlave();
                if($this->dbSlave){
                    try{
                        $singleFields = array('tr_no','confirmations','blockindex','is_point_pay_yn');
                        $sql_add = $this->getListSearchSQL($singleFields,$field,$value);
                        $sql_add .= $this->getListOrderSQL($singleFields,'tr_no');
                        
                        $sql = "SELECT 	tr_no,
                        txid,
                        server,
                        account,
                        category,
                        address,
                        amount,
                        fee,
                        confirmations,
                        blockhash,
                        blockindex,
                        blocktime,
                        walletconflicts,
                        is_point_pay_yn,
                        point_pay_dt,
                        reg_dt 
                                FROM bitcoin_transactions{$sql_add} LIMIT ?,?";

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
				$dto->trNo,
				$dto->txid,
				$dto->server,
				$dto->account,
				$dto->category,
				$dto->address,
				$dto->amount,
				$dto->fee,
				$dto->confirmations,
				$dto->blockhash,
				$dto->blockindex,
				$dto->blocktime,
				$dto->walletconflicts,
				$dto->isPointPayYn,
				$dto->pointPayDt,
				$dto->regDt) = $stmt->fetch()) {
                                    $dtoarray[$i] = new TransactionsDTO();
                                    parent::setResult($i+1);
                                    $dtoarray[$i]->trNo = $dto->trNo;
					$dtoarray[$i]->txid = $dto->txid;
					$dtoarray[$i]->server = $dto->server;
					$dtoarray[$i]->account = $dto->account;
					$dtoarray[$i]->category = $dto->category;
					$dtoarray[$i]->address = $dto->address;
					$dtoarray[$i]->amount = $dto->amount;
					$dtoarray[$i]->fee = $dto->fee;
					$dtoarray[$i]->confirmations = $dto->confirmations;
					$dtoarray[$i]->blockhash = $dto->blockhash;
					$dtoarray[$i]->blockindex = $dto->blockindex;
					$dtoarray[$i]->blocktime = $dto->blocktime;
					$dtoarray[$i]->walletconflicts = $dto->walletconflicts;
					$dtoarray[$i]->isPointPayYn = $dto->isPointPayYn;
					$dtoarray[$i]->pointPayDt = $dto->pointPayDt;
					$dtoarray[$i]->regDt = $dto->regDt;  
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
            public function getListMember($field='',$value=''){
                $dto = new TransactionsDTO();
                $dtoarray = array();
                if(!$this->dbSlave) $this->dbSlave=parent::getDatabaseSlave();
                if($this->dbSlave){
                    try{
                        $singleFields = array('tr_no','confirmations','blockindex','is_point_pay_yn');
                        $sql_add = $this->getListSearchSQL($singleFields,$field,$value);
                        if($sql_add) $sql_add .= ' and LENGTH(account)>1';
                        $sql_add .= $this->getListOrderSQL($singleFields,'tr_no');
                        
                        $sql = "SELECT 	tr_no,
                        txid,
                        server,
                        account,
                        category,
                        address,
                        amount,
                        fee,
                        confirmations,
                        blockhash,
                        blockindex,
                        blocktime,
                        walletconflicts,
                        is_point_pay_yn,
                        point_pay_dt,
                        reg_dt 
                                FROM bitcoin_transactions{$sql_add} LIMIT ?,?";

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
				$dto->trNo,
				$dto->txid,
				$dto->server,
				$dto->account,
				$dto->category,
				$dto->address,
				$dto->amount,
				$dto->fee,
				$dto->confirmations,
				$dto->blockhash,
				$dto->blockindex,
				$dto->blocktime,
				$dto->walletconflicts,
				$dto->isPointPayYn,
				$dto->pointPayDt,
				$dto->regDt) = $stmt->fetch()) {
                                    $dtoarray[$i] = new TransactionsDTO();
                                    parent::setResult($i+1);
                                    $dtoarray[$i]->trNo = $dto->trNo;
					$dtoarray[$i]->txid = $dto->txid;
					$dtoarray[$i]->server = $dto->server;
					$dtoarray[$i]->account = $dto->account;
					$dtoarray[$i]->category = $dto->category;
					$dtoarray[$i]->address = $dto->address;
					$dtoarray[$i]->amount = $dto->amount;
					$dtoarray[$i]->fee = $dto->fee;
					$dtoarray[$i]->confirmations = $dto->confirmations;
					$dtoarray[$i]->blockhash = $dto->blockhash;
					$dtoarray[$i]->blockindex = $dto->blockindex;
					$dtoarray[$i]->blocktime = $dto->blocktime;
					$dtoarray[$i]->walletconflicts = $dto->walletconflicts;
					$dtoarray[$i]->isPointPayYn = $dto->isPointPayYn;
					$dtoarray[$i]->pointPayDt = $dto->pointPayDt;
					$dtoarray[$i]->regDt = $dto->regDt;  
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
            public function getListUnComfirm($field='',$value=''){
                $dto = new TransactionsDTO();
                $dtoarray = array();
                if(!$this->dbSlave) $this->dbSlave=parent::getDatabaseSlave();
                if($this->dbSlave){
                    try{
                        $singleFields = array('tr_no','confirmations','blockindex','is_point_pay_yn');
                        $sql_add = $this->getListSearchSQL($singleFields,$field,$value);
                        if($sql_add) $sql_add .= " and LENGTH(account)>1 and category='receive'";
                        $sql_add .= $this->getListOrderSQL($singleFields,'tr_no');
                        
                        $sql = "SELECT 	tr_no,
                        txid,
                        server,
                        account,
                        category,
                        address,
                        amount,
                        fee,
                        confirmations,
                        blockhash,
                        blockindex,
                        blocktime,
                        walletconflicts,
                        is_point_pay_yn,
                        point_pay_dt,
                        reg_dt 
                                FROM bitcoin_transactions{$sql_add} LIMIT ?,?";

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
				$dto->trNo,
				$dto->txid,
				$dto->server,
				$dto->account,
				$dto->category,
				$dto->address,
				$dto->amount,
				$dto->fee,
				$dto->confirmations,
				$dto->blockhash,
				$dto->blockindex,
				$dto->blocktime,
				$dto->walletconflicts,
				$dto->isPointPayYn,
				$dto->pointPayDt,
				$dto->regDt) = $stmt->fetch()) {
                                    $dtoarray[$i] = new TransactionsDTO();
                                    parent::setResult($i+1);
                                    $dtoarray[$i]->trNo = $dto->trNo;
					$dtoarray[$i]->txid = $dto->txid;
					$dtoarray[$i]->server = $dto->server;
					$dtoarray[$i]->account = $dto->account;
					$dtoarray[$i]->category = $dto->category;
					$dtoarray[$i]->address = $dto->address;
					$dtoarray[$i]->amount = $dto->amount;
					$dtoarray[$i]->fee = $dto->fee;
					$dtoarray[$i]->confirmations = $dto->confirmations;
					$dtoarray[$i]->blockhash = $dto->blockhash;
					$dtoarray[$i]->blockindex = $dto->blockindex;
					$dtoarray[$i]->blocktime = $dto->blocktime;
					$dtoarray[$i]->walletconflicts = $dto->walletconflicts;
					$dtoarray[$i]->isPointPayYn = $dto->isPointPayYn;
					$dtoarray[$i]->pointPayDt = $dto->pointPayDt;
					$dtoarray[$i]->regDt = $dto->regDt;  
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
                        $sql = "INSERT INTO bitcoin_transactions(
				txid,
				server,
				account,
				category,
				address,
				amount,
				fee,
				confirmations,
				blockhash,
				blockindex,
				blocktime,
				walletconflicts,
				is_point_pay_yn,
				point_pay_dt)
                                VALUES(?,?,?,?,?,?,?,?,?,?,?,?,'N',?)";

                        if($this->db) $stmt = $this->db->prepare($sql);
                        if($stmt){
                            $j=1;
                            
		$stmt->bindValue( $j++, $dto->txid, PDO::PARAM_STR);
		$stmt->bindValue( $j++, $dto->server, PDO::PARAM_STR);
		$stmt->bindValue( $j++, $dto->account, PDO::PARAM_STR);
		$stmt->bindValue( $j++, $dto->category, PDO::PARAM_STR);
		$stmt->bindValue( $j++, $dto->address, PDO::PARAM_STR);
		$stmt->bindValue( $j++, $dto->amount, PDO::PARAM_STR);
		$stmt->bindValue( $j++, $dto->fee, PDO::PARAM_STR);
		$stmt->bindValue( $j++, $dto->confirmations, PDO::PARAM_INT);
		$stmt->bindValue( $j++, $dto->blockhash, PDO::PARAM_STR);
		$stmt->bindValue( $j++, $dto->blockindex, PDO::PARAM_INT);
		$stmt->bindValue( $j++, $dto->blocktime, PDO::PARAM_STR);
		$stmt->bindValue( $j++, $dto->walletconflicts, PDO::PARAM_STR);
		$stmt->bindValue( $j++, $dto->pointPayDt, PDO::PARAM_STR);
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
            
            public function setInsertFromMember($dto){
                parent::setResult(-1);
                if(!$this->db) $this->db=parent::getDatabase();
                if($this->db){
                    try{
                        $sql = "INSERT INTO bitcoin_transactions(
                            mb_no,
                            txid,
                            server,
                            account,
                            category,
                            address,
                            amount,
                            fee,
                            confirmations,
                            walletconflicts,
                            is_point_pay_yn,
                            point_pay_dt)  VALUES(?,?,?,?,?,?,?,?,?,?,'Y',now())";

                        if($this->db) $stmt = $this->db->prepare($sql);
                        if($stmt){
                            $j=1;
                            
                            $stmt->bindValue( $j++, $dto->mbNo, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, $dto->txid, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, $dto->server, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, $dto->account, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, $dto->category, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, $dto->address, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, $dto->amount, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, $dto->fee, PDO::PARAM_STR);
                            $stmt->bindValue( $j++, $dto->confirmations, PDO::PARAM_INT);
                            $stmt->bindValue( $j++, $dto->walletconflicts, PDO::PARAM_STR);
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

                    $sql = "UPDATE bitcoin_transactions SET 
				txid=?,
				server=?,
				account=?,
				category=?,
				address=?,
				amount=?,
				fee=?,
				confirmations=?,
				blockhash=?,
				blockindex=?,
				blocktime=?,
				walletconflicts=?,
				is_point_pay_yn=?,
				point_pay_dt=? WHERE tr_no=?";

                    if($this->db) $stmt = $this->db->prepare($sql);
                    if($stmt){
                        $j=1;
                        
		$stmt->bindValue( $j++, $dto->txid, PDO::PARAM_STR);
		$stmt->bindValue( $j++, $dto->server, PDO::PARAM_STR);
		$stmt->bindValue( $j++, $dto->account, PDO::PARAM_STR);
		$stmt->bindValue( $j++, $dto->category, PDO::PARAM_STR);
		$stmt->bindValue( $j++, $dto->address, PDO::PARAM_STR);
		$stmt->bindValue( $j++, $dto->amount, PDO::PARAM_STR);
		$stmt->bindValue( $j++, $dto->fee, PDO::PARAM_STR);
		$stmt->bindValue( $j++, $dto->confirmations, PDO::PARAM_INT);
		$stmt->bindValue( $j++, $dto->blockhash, PDO::PARAM_STR);
		$stmt->bindValue( $j++, $dto->blockindex, PDO::PARAM_INT);
		$stmt->bindValue( $j++, $dto->blocktime, PDO::PARAM_STR);
		$stmt->bindValue( $j++, $dto->walletconflicts, PDO::PARAM_STR);
		$stmt->bindValue( $j++, $dto->isPointPayYn, PDO::PARAM_STR);
		$stmt->bindValue( $j++, $dto->pointPayDt, PDO::PARAM_STR);
			
		$stmt->bindValue( $j++, $dto->trNo, PDO::PARAM_INT);
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
            
            public function setUpdateConfirm($tranid,$confirm){
                parent::setResult(-1);
                if(!$this->db) $this->db=parent::getDatabase();
                
                if($this->db){

                    $sql = "UPDATE bitcoin_transactions SET confirmations=?  WHERE txid=?";
                    if($this->db) $stmt = $this->db->prepare($sql);
                    if($stmt){
                        $j=1;
                        $stmt->bindValue( $j++, $confirm, PDO::PARAM_INT);
                        $stmt->bindValue( $j++, $tranid, PDO::PARAM_STR);
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

                    $sql = "DELETE FROM bitcoin_transactions WHERE tr_no=?";

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