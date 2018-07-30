<?php

/**
 * Description of WebPointExchangeOrderDAO
 * @description Funhansoft PHP auto templet
 * @date 2015-06-28
 * @copyright (c)funhansoft.com
 * @license http://funhansoft.com/license
 * @version 4.0.0
 */
class WebPointExchangeOrderDAO extends BaseDAO {

    private $db;
    private $dbSlave;

    function __construct() {
        parent::__construct();
    }

    public function getViewById($od_id) {
        $dto = new WebPointExchangeOrderDTO();
        if (!$this->dbSlave)
            $this->dbSlave = parent::getDatabaseSlave();
        if ($this->dbSlave) {
            try {
                $sql = "SELECT
                            od_id,
                            it_name,
                            it_id_pay,
                            od_market_price,
                            od_total_cost,
                            mb_no,
                            mb_id,
                            od_temp_coin,
                            od_temp_krw,
                            od_receipt_coin,
                            od_receipt_krw,
                            od_del_yn,
                            od_reg_dt,
                            od_reg_ip,
                            partner
                        FROM web_point_exchange_order WHERE od_id=?  limit 1";

                if ($this->dbSlave)
                    $stmt = $this->dbSlave->prepare($sql);
                if ($stmt) {
                    $stmt->bindValue(1, $od_id, PDO::PARAM_STR);
                    $stmt->execute();
                    list(
                        $dto->odId,
                        $dto->itName,
                        $dto->itIdPay,
                        $dto->odMarketPrice,
                        $dto->odTotalCost,
                        $dto->mbNo,
                        $dto->mbId,
                        $dto->odTempCoin,
                        $dto->odTempKrw,
                        $dto->odReceiptCoin,
                        $dto->odReceiptKrw,
                        $dto->odDelYn,
                        $dto->odRegDt,
                        $dto->odRegIp,
                        $dto->partner) = $stmt->fetch();
                    if ($stmt->rowCount() == 1)
                        parent::setResult(1);
                    else
                        parent::setResult(0);
                }else {
                    parent::setResult(ResError::dbPrepare);
                }
            } catch (PDOException $e) {
                parent::setResult(ResError::dbPrepare);
            }
        } else {
            parent::setResult(ResError::dbConn);
        }
        $dto->result = parent::getResult();
        return $dto;
    }

    public function getListCount($field = '', $value = '',$svdf='',$svdt='') {
        $dto = new CommonListDTO();
        if (!$this->dbSlave)
            $this->dbSlave = parent::getDatabaseSlave();
        if ($this->dbSlave) {
            try {
                $singleFields = array('od_id', 'it_id_pay', 'mb_no', 'od_temp_krw', 'od_receipt_krw', 'od_del_yn');
                $sql_add = '';
                if($field=='it_name'){
                    $sql_add_od_name = '';
                    if($value=='buybtc') $sql_add_od_name = ' od_temp_coin > ? ';
                    else if($value=='sellbtc') $sql_add_od_name = ' od_temp_krw > ? ';
                    if($value!=''){
                        if($sql_add) $sql_add .= " and ".$sql_add_od_name;
                        else $sql_add = " WHERE ".$sql_add_od_name;
                        $value = "0.0";
                    }
                }else{
                $sql_add = $this->getListSearchSQL($singleFields, $field, $value);
                }
                if($svdf && $svdt){
                            if($sql_add){
                                $sql_add .= " AND ";
                            }else{
                                $sql_add .= " WHERE ";
                            }
                            $sql_add .= "(od_reg_dt BETWEEN ? AND DATE_ADD(?,INTERVAL 1 DAY) ) ";
                        }
                $sql_add .= $this->getListOrderSQL($singleFields, 'od_id');

                $sql = "SELECT COUNT(*) FROM web_point_exchange_order{$sql_add}";

                if ($this->dbSlave)
                    $stmt = $this->dbSlave->prepare($sql);
                if ($stmt) {
                    $j=1;
                    if ($value)
                        $stmt->bindValue($j++, $value, PDO::PARAM_STR);
                    if($svdf && $svdt){
                                $stmt->bindValue( $j++, $svdf, PDO::PARAM_STR);
                                $stmt->bindValue( $j++, $svdt, PDO::PARAM_STR);
                            }

                    $stmt->execute();
                    list($dto->totalCount) = $stmt->fetch();
                    if ($stmt->rowCount() == 1)
                        parent::setResult(1);
                    else
                        parent::setResult(0);
                }else {
                    parent::setResult(ResError::dbPrepare);
                }
            } catch (PDOException $e) {
                parent::setResult(ResError::dbPrepare);
            }
        } else {
            parent::setResult(ResError::dbConn);
        }
        $dto->result = parent::getResult();
        $dto->totalPage = ceil((int) $dto->totalCount / parent::getListLimitRow());
        $dto->limitRow = (int) parent::getListLimitRow();

        return $dto;
    }

    public function getList($field = '', $value = '',$svdf='',$svdt='') {
        $dto = new WebPointExchangeOrderDTO();
        $dtoarray = array();
        if (!$this->dbSlave)
            $this->dbSlave = parent::getDatabaseSlave();
        if ($this->dbSlave) {
            try {
                $singleFields = array('od_id', 'it_id_pay', 'mb_no', 'od_temp_krw', 'od_receipt_krw', 'od_del_yn');
                $sql_add = '';
                if($field=='it_name'){
                    $sql_add_od_name = '';
                    if($value=='buybtc') $sql_add_od_name = ' od_temp_coin > ? ';
                    else if($value=='sellbtc') $sql_add_od_name = ' od_temp_krw > ? ';
                    if($value!=''){
                        if($sql_add) $sql_add .= " and ".$sql_add_od_name;
                        else $sql_add = " WHERE ".$sql_add_od_name;
                        $value = "0.0";
                    }
                }else{
                    $sql_add = $this->getListSearchSQL($singleFields, $field, $value);
                }
                if($svdf && $svdt){
                            if($sql_add){
                                $sql_add .= " AND ";
                            }else{
                                $sql_add .= " WHERE ";
                            }
                            $sql_add .= "(od_reg_dt BETWEEN ? AND DATE_ADD(?,INTERVAL 1 DAY) ) ";
                        }
                
                $sql_add .= $this->getListOrderSQL($singleFields, 'od_id');

                $sql = "SELECT 	
                            od_id,
                            it_name,
                            it_id_pay,
                            od_market_price,
                            od_total_cost,
                            mb_no,
                            mb_id,
                            od_temp_coin,
                            od_temp_krw,
                            od_receipt_coin,
                            od_receipt_krw,
                            od_del_yn,
                            od_reg_dt,
                            od_reg_ip,
                            partner 
                        FROM web_point_exchange_order{$sql_add} LIMIT ?,?";

                if ($this->dbSlave)
                    $stmt = $this->dbSlave->prepare($sql);
                if ($stmt) {
                    $j = 1;
                    if ($value)
                        $stmt->bindValue($j++, $value, PDO::PARAM_STR);
                    if($svdf && $svdt){
                        $stmt->bindValue( $j++, $svdf, PDO::PARAM_STR);
                        $stmt->bindValue( $j++, $svdt, PDO::PARAM_STR);
                    }
                    $stmt->bindValue($j++, parent::getListLimitStart(), PDO::PARAM_INT);
                    $stmt->bindValue($j++, parent::getListLimitRow(), PDO::PARAM_INT);
                    $stmt->execute();

                    $i = 0;
                    while (list(
                    $dto->odId,
                    $dto->itName,
                    $dto->itIdPay,
                    $dto->odMarketPrice,
                    $dto->odTotalCost,
                    $dto->mbNo,
                    $dto->mbId,
                    $dto->odTempCoin,
                    $dto->odTempKrw,
                    $dto->odReceiptCoin,
                    $dto->odReceiptKrw,
                    $dto->odDelYn,
                    $dto->odRegDt,
                    $dto->odRegIp,
                    $dto->partner) = $stmt->fetch()) {
                        $dtoarray[$i] = new WebPointExchangeOrderDTO();
                        parent::setResult($i + 1);
                        $dtoarray[$i]->odId = $dto->odId;
                        $dtoarray[$i]->itName = $dto->itName;
                        $dtoarray[$i]->itIdPay = $dto->itIdPay;
                        $dtoarray[$i]->odMarketPrice = $dto->odMarketPrice;
                        $dtoarray[$i]->odTotalCost = $dto->odTotalCost;
                        $dtoarray[$i]->mbNo = $dto->mbNo;
                        $dtoarray[$i]->mbId = $dto->mbId;
                        $dtoarray[$i]->odTempCoin = $dto->odTempCoin;
                        $dtoarray[$i]->odTempKrw = $dto->odTempKrw;
                        $dtoarray[$i]->odReceiptCoin = $dto->odReceiptCoin;
                        $dtoarray[$i]->odReceiptKrw = $dto->odReceiptKrw;
                        $dtoarray[$i]->odDelYn = $dto->odDelYn;
                        $dtoarray[$i]->odRegDt = $dto->odRegDt;
                        $dtoarray[$i]->odRegIp = $dto->odRegIp;
                        $dtoarray[$i]->partner = $dto->partner;
                        $i++;
                    }

                    if ($i == 0)
                        parent::setResult(0);
                }
            } catch (PDOException $e) {
                parent::setResult(ResError::dbPrepare);
            }
        } else {
            parent::setResult(ResError::dbConn);
        }
        $dto = null;
        return $dtoarray;
    }

    public function setInsert($dto) {
        parent::setResult(-1);
        if (!$this->db)
            $this->db = parent::getDatabase();
        if ($this->db) {
            try {
                $sql = "INSERT INTO web_point_exchange_order
                        (
                            it_name,
                            it_id_pay,
                            od_market_price,
                            od_total_cost,
                            mb_no,
                            mb_id,
                            od_temp_coin,
                            od_temp_krw,
                            od_receipt_coin,
                            od_receipt_krw,
                            od_del_yn,
                            od_reg_ip,
                            partner
                        )
                        VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?)";

                if ($this->db)
                    $stmt = $this->db->prepare($sql);
                if ($stmt) {
                    $j = 1;

                    $stmt->bindValue($j++, $dto->itName, PDO::PARAM_STR);
                    $stmt->bindValue($j++, $dto->itIdPay, PDO::PARAM_STR);
                    $stmt->bindValue($j++, $dto->odMarketPrice, PDO::PARAM_STR);
                    $stmt->bindValue($j++, $dto->odTotalCost, PDO::PARAM_STR);
                    $stmt->bindValue($j++, $dto->mbNo, PDO::PARAM_INT);
                    $stmt->bindValue($j++, $dto->mbId, PDO::PARAM_STR);
                    $stmt->bindValue($j++, $dto->odTempCoin, PDO::PARAM_STR);
                    $stmt->bindValue($j++, $dto->odTempKrw, PDO::PARAM_INT);
                    $stmt->bindValue($j++, $dto->odReceiptCoin, PDO::PARAM_STR);
                    $stmt->bindValue($j++, $dto->odReceiptKrw, PDO::PARAM_INT);
                    $stmt->bindValue($j++, $dto->odDelYn, PDO::PARAM_STR);
                    $stmt->bindValue($j++, $dto->odRegIp, PDO::PARAM_STR);
                    $stmt->bindValue($j++, $dto->partner, PDO::PARAM_STR);
                    $stmt->execute();
                    if ($stmt->rowCount() == 1)
                        parent::setResult($this->db->lastInsertId());
                    else
                        parent::setResult(0);
                }
            } catch (PDOException $e) {
                parent::setResult(ResError::dbPrepare);
            }
        } else {
            parent::setResult(ResError::dbConn);
        }
        return parent::getResult();
    }



    public function setUpdate($dto) {
        parent::setResult(-1);
        if (!$this->db)
            $this->db = parent::getDatabase();
        if ($this->db) {

            $sql = "UPDATE web_point_exchange_order SET
                        it_name=?,
                        it_id_pay=?,
                        od_market_price=?,
                        od_total_cost=?,
                        mb_no=?,
                        mb_id=?,
                        od_temp_coin=?,
                        od_temp_krw=?,
                        od_receipt_coin=?,
                        od_receipt_krw=?,
                        od_del_yn=?,
                        od_reg_ip=?,
                        partner=?
                    WHERE od_id=?";

            if ($this->db)
                $stmt = $this->db->prepare($sql);
            if ($stmt) {
                $j = 1;
                $stmt->bindValue($j++, $dto->itName, PDO::PARAM_STR);
                $stmt->bindValue($j++, $dto->itIdPay, PDO::PARAM_STR);
                $stmt->bindValue($j++, $dto->odMarketPrice, PDO::PARAM_STR);
                $stmt->bindValue($j++, $dto->odTotalCost, PDO::PARAM_STR);
                $stmt->bindValue($j++, $dto->mbNo, PDO::PARAM_INT);
                $stmt->bindValue($j++, $dto->mbId, PDO::PARAM_STR);
                $stmt->bindValue($j++, $dto->odTempCoin, PDO::PARAM_STR);
                $stmt->bindValue($j++, $dto->odTempKrw, PDO::PARAM_INT);
                $stmt->bindValue($j++, $dto->odReceiptCoin, PDO::PARAM_STR);
                $stmt->bindValue($j++, $dto->odReceiptKrw, PDO::PARAM_INT);
                $stmt->bindValue($j++, $dto->odDelYn, PDO::PARAM_STR);
                $stmt->bindValue($j++, $dto->odRegIp, PDO::PARAM_STR);
                $stmt->bindValue($j++, $dto->partner, PDO::PARAM_STR);

                $stmt->bindValue($j++, $dto->odId, PDO::PARAM_INT);
                $stmt->execute();
                if ($stmt->rowCount() == 1)
                    parent::setResult(1);
                else
                    parent::setResult(0);
            }else {
                parent::setResult(ResError::dbPrepare);
            }
        } else {
            parent::setResult(ResError::dbConn);
        }
        return parent::getResult();
    }



    function deleteFromPri($pri) {
        parent::setResult(-1);
        if (!$this->db)
            $this->db = parent::getDatabase();
        if ($this->db) {

            $sql = "DELETE FROM web_point_exchange_order WHERE od_id=?";

            if ($this->db)
                $stmt = $this->db->prepare($sql);
            if ($stmt) {
                $stmt->bindValue(1, $pri, PDO::PARAM_STR);
                $stmt->execute();
                if ($stmt->rowCount() > 0)
                    parent::setResult($stmt->rowCount());
                else
                    parent::setResult(0);
            }else {
                parent::setResult(ResError::dbPrepare);
            }
        } else {
            parent::setResult(ResError::dbConn);
        }
        return parent::getResult();
    }



    function __destruct() {
        $this->db = NULL;
        $this->dto = NULL;
    }

}
