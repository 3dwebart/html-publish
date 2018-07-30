<?php
/**
* Description of WebChartStatDAO
* @description Funhansoft PHP auto templet
* @date 2014-01-06
* @copyright (c)funhansoft.com
* @license http://funhansoft.com/license
* @version 4.0.0
*/
class WebChartStatDAO extends BaseDAO{

    private $db;
    private $dbSlave;

    function __construct() {
        parent::__construct();
        $this->cfg = Config::getConfig();

    }

    public function setInsertChartStat($value=''){
        $dto = new CommonListDTO();
        if(!$this->dbSlave) $this->dbSlave=parent::getDatabaseTradeSlave(); if($this->dbSlave){
            try{

                $sql = "CALL web_chart_stat_in_date_insert('{$value}')";

                if($this->dbSlave) $stmt = $this->dbSlave->prepare($sql);
                if($stmt){

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

        return $dto;
    }



    function __destruct(){

    }
}