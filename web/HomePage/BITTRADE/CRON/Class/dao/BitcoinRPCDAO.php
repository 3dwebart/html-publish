<?php      
/**
* Description of WebBbsMainCmtDAO
* @description Funhansoft PHP auto templet
* @date 2014-01-06
* @copyright (c)funhansoft.com
* @license http://funhansoft.com/license
* @version 0.3.0
*/
class BitcoinRPCDAO extends BaseDAO{


    private $rpcserver;
    private $filepath = '../WebApp/QrImage';

    function __construct() {
        $this->cfg = Config::getConfig();
    }
    
    public function initServer($proto,$server,$user_id=null,$user_pwd=null){
        try {
            if($user_id && $user_pwd){
                $this->rpcserver = new jsonRPCClient($proto.'://'.$user_id.':'.$user_pwd.'@'.$server.'/',true);
            }else{
                $this->rpcserver = new jsonRPCClient($proto.'://'.$server.'/',true);
            }
        }
        catch (Exception $e) {
            new RPCException("<p>Bitcoin RPC Server error! ".$e);
        }
    }
    
//    public function initBitcoin(){
//        try {
//                $this->rpcserver = new jsonRPCClient('http://'.$this->serverinfo['serverId'].':'.$this->serverinfo['serverPwd'].'@'.$this->serverinfo['server'].'/',true);
//        }
//        catch (Exception $e) {
//            new RPCException("<p>Bitcoin Server error! ".$e);
//        }
//    }
    
    public function rpc($function,$param=null){
        if($param) return $this->rpcserver->$function($param);
        return $this->rpcserver->$function();
    }
    //금액이동
    public function move($fromaccount,$toaccount,$amount){
        //move "fromaccount" "toaccount" amount ( minconf "comment" )
//        return json_decode(array("{$fromaccount}","{$toaccount}",(float)($amount)));
        return $this->rpcserver->move("{$fromaccount}","{$toaccount}",(float)($amount));
    }
    
    public function getJsonData($mb_id){
//        $marketdao = new WebMarketOuterWonDAO();
//        $marketdto = $marketdao->getViewLastMaxAmount();
        $btcwon = 0;
        $btctotal = $this->getRPCBalance($mb_id, 0);
        $balance = sprintf("%01.8f", $btctotal);
        
        
 
        $myadd = $this->getRPCAddress($mb_id);
    
        
        $resultArray = array('bitaddress' => $myadd,
                            'bitqrimg' => '/Page/Image/loadQRAddressImg&mb='.$myadd,
                            'bitbalance' => $balance,
                            'btcToWon' => $btcwon,
                            'wonbalance' => number_format($btcwon * $btctotal)
                );
        return $resultArray;
    }
    
    /*
     * 주기적으로 주소가 생김
     */
    private function getRPCAddress($mb_id){
        $resaddress = $this->rpcserver->getaddressesbyaccount($mb_id);
        if(count($resaddress)==0){
            $myadd = $this->rpcserver->getaccountaddress($mb_id);
        }else{
            $myadd = $resaddress[0];
        }
        
        return $myadd;
    }
    
    public function getRPCAddressByAccount($mb_id){
        return $this->rpcserver->getaddressesbyaccount($mb_id);
    }
    
    public function getRPCNewAddress($mb_id){
        return $this->rpcserver->getnewaddress($mb_id);
    }
    
    /*
     * 트렌젝션
     */
    
    public function getRPCTransactionList($mb_id){
        $returnArr = array();
        $response = $this->rpcserver->listtransactions($mb_id, parent::getListLimitRow(),parent::getListLimitStart());
        //[address] => 1FtBjXxsQKeZh7ArnD9Yz5akP31RQaPbXf [category] => send [amount] => -0.1 [fee] => -0.0001 [confirmations] => 6
        $j=0;
        for($k=count($response)-1;$k>=0;$k--){
            $returnArr[$j]=array();
            $returnArr[$j]=$response[$k];
            $returnArr[$j]['address'] = (isset($response[$k]['address']))?$response[$k]['address']:'';
            $returnArr[$j]['category'] = (isset($response[$k]['category']))?$response[$k]['category']:'';
            $returnArr[$j]['amount'] = (isset($response[$k]['amount']))?$response[$k]['amount']:0;
            $returnArr[$j]['fee'] = (isset($response[$k]['fee']))?$response[$k]['fee']:0;
            $returnArr[$j]['confirmations'] = (isset($response[$k]['confirmations']))?$response[$k]['confirmations']:0;
            $returnArr[$j]['time'] = (isset($response[$k]['time']))?$response[$k]['time']:0;
            $returnArr[$j]['otheraccount'] = (isset($response[$k]['otheraccount']))?$response[$k]['otheraccount']:0;
            $j++;
        }
        
        return $returnArr; 
    }
    public function getRPCTransactionListAll(){
        $returnArr = array();
        $response = $this->rpcserver->listtransactions("*", parent::getListLimitRow(),parent::getListLimitStart());
        $j=0;
        for($k=count($response)-1;$k>=0;$k--){
            $returnArr[$j]=array();
            $returnArr[$j]=$response[$k];
            $returnArr[$j]['address'] = (isset($response[$k]['address']))?$response[$k]['address']:'';
            $returnArr[$j]['category'] = (isset($response[$k]['category']))?$response[$k]['category']:'';
            $returnArr[$j]['amount'] = (isset($response[$k]['amount']))?$response[$k]['amount']:0;
            $returnArr[$j]['fee'] = (isset($response[$k]['fee']))?$response[$k]['fee']:0;
            $returnArr[$j]['confirmations'] = (isset($response[$k]['confirmations']))?$response[$k]['confirmations']:0;
            $returnArr[$j]['time'] = (isset($response[$k]['time']))?$response[$k]['time']:0;
            $returnArr[$j]['otheraccount'] = (isset($response[$k]['otheraccount']))?$response[$k]['otheraccount']:0;
            $j++;
        }
        
        return $returnArr;
    }
    
    /*
     * BTC금액
     */
    public function getRPCBalance($mb_id){
        return $this->rpcserver->getbalance($mb_id);
    }
    /*
     * 
     * 올바른 주소형식인지
     * return {"isvalid":true,"address":"","ismine":false}
     */
    public function getRPCValidateAddress($address){
        return $this->rpcserver->validateaddress($address);
    }
    
    /*
     * 
     * 트렌젝션 아이디로 데이터 가져오기
     * return {"isvalid":true,"address":"","ismine":false}
     */
    public function getRPCTransaction($txid){
        return $this->rpcserver->gettransaction($txid);
    }
    
    public function getRPCSendMany($from_mb_id,$to_address,$bitcoin,$minconf = 1, $comment = null, $commentto = null){
        
        //$resultArray = array('result'=>ResError::ok,'msg'=>'','txid'=>'');
        $btctotal = $this->rpcserver->getbalance($from_mb_id);
        $btctotal = $btctotal - $this->tranfee; //네트워크 수수료
        if($btctotal<$bitcoin){
            $resultArray = array('result'=>-1000,
                                'msg'=>'보유한 비트코인 수량이 부족합니다.',
                                'txid'=>'');
            return $resultArray;
        }
        try {
            //$param = array($from_mb_id,$to_address,$bitcoin);
            //$param = $from_mb_id.','.$to_address.','.$bitcoin;
            //$param = '{\''.$from_mb_id.'\',\''.$to_address.'\',\''.$bitcoin.'\'}';
            //$txid = $this->rpcserver->sendfrom($param);
            $txid = $this->rpcserver->sendmany($from_mb_id,$to_address,(float)$bitcoin,(int)$minconf,$comment,$commentto);
            $resultArray = array('result'=>ResError::no,
                                'msg'=>'',
                                'txid'=>$txid);
        }
        catch (Exception $e) {
            $resultArray = array('result'=>ResError::exception,
                                'msg'=>'예외발생',
                                'txid'=>'');
        }
        return $resultArray;
    }
    
    
    /*
     * 
     */
    public function getRPCSendfrom($from_mb_id,$to_address,$bitcoin,$minconf = 1, $comment = null, $commentto = null){
        
        //$resultArray = array('result'=>ResError::ok,'msg'=>'','txid'=>'');
        $btctotal = $this->rpcserver->getbalance($from_mb_id);
        $btctotal = $btctotal - $this->tranfee; //네트워크 수수료
        if($btctotal<$bitcoin){
            $resultArray = array('result'=>-1000,
                                'msg'=>'보유한 비트코인 수량이 부족합니다.',
                                'txid'=>'');
            return $resultArray;
        }
        try {
            //$param = array($from_mb_id,$to_address,$bitcoin);
            //$param = $from_mb_id.','.$to_address.','.$bitcoin;
            //$param = '{\''.$from_mb_id.'\',\''.$to_address.'\',\''.$bitcoin.'\'}';
            //$txid = $this->rpcserver->sendfrom($param);
            $txid = $this->rpcserver->sendfrom($from_mb_id,$to_address,(float)$bitcoin,(int)$minconf,$comment,$commentto);
            $resultArray = array('result'=>ResError::no,
                                'msg'=>'',
                                'txid'=>$txid);
        }
        catch (Exception $e) {
            $resultArray = array('result'=>ResError::exception,
                                'msg'=>'예외발생',
                                'txid'=>'');
        }
        return $resultArray;
    }
    
    public function getRPCMove($from_mb_id,$to_mb_id,$bitcoin,$minconf = 1, $comment = null){
        
        //$resultArray = array('result'=>ResError::ok,'msg'=>'','txid'=>'');
        $btctotal = $this->rpcserver->getbalance($from_mb_id);
        //$btctotal = $btctotal - $this->tranfee; //네트워크 수수료
        if($btctotal<$bitcoin){
            $resultArray = array('result'=>-1000,
                                'msg'=>'보유한 비트코인 수량이 부족합니다.',
                                'txid'=>'');
            return $resultArray;
        }
        try {
            //$param = array($from_mb_id,$to_address,$bitcoin);
            //$param = $from_mb_id.','.$to_address.','.$bitcoin;
            //$param = '{\''.$from_mb_id.'\',\''.$to_address.'\',\''.$bitcoin.'\'}';
            //$txid = $this->rpcserver->sendfrom($param);
            $txid = $this->rpcserver->move($from_mb_id,$to_mb_id,(float)$bitcoin);
            $resultArray = array('result'=>ResError::no,
                                'msg'=>'',
                                'txid'=>$txid);
        }
        catch (Exception $e) {
            $resultArray = array('result'=>ResError::exception,
                                'msg'=>'예외발생',
                                'txid'=>'');
        }
        return $resultArray;
    }

    public function getAddressQRdir($filename){
        $qrdir = $this->cfg['path']['qrfile'];
        $predir = substr($filename,0,2);
        return $qrdir.DIRECTORY_SEPARATOR.$predir.DIRECTORY_SEPARATOR;
    }
    
    public function isQRFile($filename){
//        $file = $this->getAddressQRdir($filename).$filename.'.png';
        if (file_exists($file)){
            return true;
        }else{
            return false;
        }
    }
    
    public function createQRImg($address){
        if(!$address) return;
        $filename = $address;
        $qrdir = $this->cfg['path']['qrfile'];
        $predir = substr($filename,0,2);

        //set it to writable location, a place for temp generated PNG files
        $PNG_TEMP_DIR = $qrdir.DIRECTORY_SEPARATOR.$predir.DIRECTORY_SEPARATOR;
        include $this->cfg['path']['plugin'].'phpqrcode'.DIRECTORY_SEPARATOR.'qrlib.php';
        //ofcourse we need rights to create temp dir
        if (!file_exists($PNG_TEMP_DIR)){
            @mkdir($PNG_TEMP_DIR,0707);
            //@chmod($PNG_TEMP_DIR, 0707);
        }
        
        $errorCorrectionLevel = 'H';
        $matrixPointSize = 7;
        $filename = $PNG_TEMP_DIR.''.$filename.'.png';
        QRcode::png($address, $filename, $errorCorrectionLevel, $matrixPointSize, 2);
        // benchmark
        //QRtools::timeBenchmark();
    }
    
    


    function __destruct(){

    }
}