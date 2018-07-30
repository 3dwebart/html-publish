<?php
error_reporting(E_ALL);

ini_set("display_errors", 1);

class UpdateBuySell extends BaseModelBase{

    private $member;
    
    function __construct($dbconfname=null) {
        parent::__construct($dbconfname);
    }
    /*
     * 조회 후 없으면 삽입, 있으면 수정
     * @param url param
     */
    public function getExecute(){

        
         
        // psc 20180614 
        $master = JsonConfig::get('exchangemarket');
       
        foreach ($master as $key => $value) {
          
          $tmp = explode('_', $key);
          $currency = strtolower($tmp[1]);
         
          $remotedb = $this->res->ctrl->database->$currency;    
          //$this->setMoniterLogFile(7,array('remote'=>$remotedb));       
          // 코인별 거래 매수 금액 누적 

          $sumbuy = $this->execRemoteLists(
            $remotedb,
            array(
                'sql'=>$this->res->ctrl->sql_real_buy_sum,
                'mapkeys'=>$this->res->ctrl->mapkeys_real_buy_sum,    
                'rowlimit'=>1                  
                ),
             array(
               
            )
          );
            
          //$this->setMoniterLogFile(8,$sumbuy);
          $sum_sell_price = $sumbuy[0]['ret_sell_price'] == null ? 0 : $sumbuy[0]['ret_sell_price'];
          $sum_buy_price  = $sumbuy[0]['ret_buy_price']  == null ? 0 : $sumbuy[0]['ret_buy_price'];
          
          //$this->setMoniterLogFile(9,array('sumbuy'=>'data','sum_buy_price'=>$sum_buy_price,'sum_sell_price'=>$sum_sell_price));
           
          if(0 != $sum_buy_price || 0 != $sum_sell_price) {
             
            
            $db_params_acc = array(
          
                          'po_type'=>$currency,
                          'po_sell_price'=>$sum_sell_price,
                          'po_buy_price'=>$sum_buy_price);
            
            //$this->setMoniterLogFile(10,array('db_params_acc'=>$db_params_acc));
            
            $this->execRemoteUpdate(
                  $this->res->ctrl->database->point, 
                  array(
                      'sql'=>$this->res->ctrl->sql_real_point_accumulate,
                      'mapkeys'=>$this->res->ctrl->mapkeys_real_point_accumulate                      
                  )
                  ,$db_params_acc

           );
            
          //$this->setMoniterLogFile(11,array('sumbuy'=>'data','sum_buy_price'=>$sum_buy_price,'sum_sell_price'=>$sum_sell_price));  
          }
   
        }
      
        return array('success'=> true);
    }
        
     private function setLogFile($errorcode,$param){
        $log = new Logging();
        $log->lfile('../WebApp/Debug/UpdateBuySellException-'.$param['mb_no'].'-'.date('Ymd',time()).'.txt');
        $log->lwrite(__CLASS__ . "[".$errorcode."]:".' '.json_encode($param).' - '.Utils::getClientIP());
        $log->lclose();
    }
    
    private function setMoniterLogFile($errorcode,$param){
        $log = new Logging();
        $log->lfile('../WebApp/Debug/UpdateBuySellMoniterLog-'.date('Ymd',time()).'.txt');
        $log->lwrite(__CLASS__ . "[".$errorcode."]:".' '.json_encode($param).' - '.Utils::getClientIP());
        $log->lclose();
    }

    function __destruct(){

    }
}
