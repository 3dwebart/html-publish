<?php
class TradeCompleteHistory extends BaseModelBase{

    private $tradeserv;

    function __construct($dbconfname=null) {
        parent::__construct($dbconfname);

    }

    private function initTradeServer($serverinfo=null){
        if(!$serverinfo){
            $serverinfo = '127.0.0.1';
        }
        try {
            $this->tradeserv = new jsonRPCClient('http://'.$serverinfo, false);
        }
        catch (Exception $e) {
            new RPCException("<p>Trade Server error! ".$e);
        }
    }

	 public function getExecute($param){
		 
		 $p_mb_no    = $param['mb_no'];// 페이지 구분값
		 
		 return $this->execLists(array(),$param,$paramPage);
	 }


    function __destruct(){

    }
}
