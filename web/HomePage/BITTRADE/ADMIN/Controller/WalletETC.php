<?php
/**
* Description of LogTradeserver Controller
* @description FunHanSoft Co.,Ltd  PHP auto templet
* @date 2017-06-23
* @copyright (c)funhansoft.com
* @license 해당 사이트 이외의 사용은 엄격히 금지되어 있습니다.
* @version 4.0.0
*/
class WalletETC extends ControllerBase{

    private $wRPC;
    private $admin_pwd;
    private $server_url = '';
    private $system_address = '';
    private $system_address_key = '';
    private $user_address_key = '';

    function __construct(){
        parent::__construct();
        $this->wRPC = new EthereumRPCDAO();
        
        
        $wsvDAO = new WebConfigWalletServerDAO();
        $dto = $wsvDAO->getViewByPoType('etc');
        
        $this->server_url = $dto->waRpcIp.':'.$dto->waRpcPort;
        $this->wRPC->initServer($dto->waRpcProto,$dto->waRpcIp.':'.$dto->waRpcPort);
        $this->system_address = $this->config['wallet']['system_send_addr_etc'];
        $this->system_address_key = $this->config['wallet']['system_send_addr_key_etc'];
        $this->user_address_key = $this->config['wallet']['user_addr_key_etc'];
    }

    public function main() {
        
    }
    public function getSystemAddress(){
        $this->getViewer()->setResponseType('JSON');

        $accounts = $this->wRPC->rpc('eth_coinbase');
        
        return json_encode($accounts);
        
    }
    
    public function blockNumber(){
        $this->getViewer()->setResponseType('JSON');
        $res = $this->wRPC->call()->eth_blockNumber();
        if($res)  $res = hexdec($res);
        return $res;
    }

//    public function systemBalancetest(){
//        $this->getViewer()->setResponseType('JSON');
//        $res = $isunlock = $this->wRPC->call()->eth_getBalance('0x3ff9048140d65392573882687812e0cac19696b3' ,"latest");
//        if($res)  $res = $this->convertDescToEther(hexdec($res));
//        return $res;
//    }

    public function systemBalance(){
        $this->getViewer()->setResponseType('JSON');

//        $this->system_address = $this->wRPC->call()->eth_coinbase();
        $accounts = $this->wRPC->rpc('eth_accounts');
        // account값을 모두 소문자로 변환 ( 트랜잭션에서 비교시 대소문자 차이로 비교가 안되는 경우 방지)
        $system_balance =  hexdec( $this->wRPC->call()->eth_getBalance($this->system_address ,"latest") );
//                $res = $isunlock = $this->wRPC->call()->eth_getBalance('0x3ff9048140d65392573882687812e0cac19696b3' ,"latest");
        //$res = $this->convertDescToEther($total_ether);
        $resjson = array('systemSender'=>$this->convertDescToEther($system_balance));
        return json_encode($resjson);
        
    }
    public function userBalance(){
        $this->getViewer()->setResponseType('JSON');

//        $this->system_address = $this->wRPC->call()->eth_coinbase();
        $accounts = $this->wRPC->rpc('eth_accounts');
        // account값을 모두 소문자로 변환 ( 트랜잭션에서 비교시 대소문자 차이로 비교가 안되는 경우 방지)
        $total_ether = 0;
        $system_balance = 0;
        for($i = 0; $i < count($accounts); $i++)
        {
            $accounts[$i] = strtolower($accounts[$i]);
            
            if($accounts[$i] == $this->system_address){
                $system_balance =  hexdec( $this->wRPC->call()->eth_getBalance($accounts[$i] ,"latest") );
            }else{
                $total_ether = $total_ether + hexdec( $this->wRPC->call()->eth_getBalance($accounts[$i] ,"latest") );
            }

        }
//                $res = $isunlock = $this->wRPC->call()->eth_getBalance('0x3ff9048140d65392573882687812e0cac19696b3' ,"latest");
        $res = $this->convertDescToEther($total_ether);
        $resjson = array('accountTotal'=>$res,'systemSender'=>$this->convertDescToEther($system_balance));
        return json_encode($resjson);
        
    }
    //Wei  to eth
    private function convertDescToEther($dec,$type='ether'){
        if($type == 'ether'){
            return $dec / 1000000000000000000;
        }
        $str = number_format($dec,8);
        $str = str_replace(',','',$dec);
        return $str;
    }
    
    function convertWeiToWeiHex($wei){
        $str = number_format($wei);
        $dec = str_replace(',', '', $str);


        $hex = '';
        do {    
            $last = bcmod($dec, 16);
            $hex = dechex($last).$hex;
            $dec = bcdiv(bcsub($dec, $last), 16);
        } while($dec>0);
        return $hex;
    }
    
    function convertEthererToWei($wei){
        $str = number_format($wei);
        $dec = str_replace(',', '', $str);
        $hex = '';
        do {    
            $last = bcmod($dec, 16);
            $hex = dechex($last).$hex;
            $dec = bcdiv(bcsub($dec, $last), 16);
        } while($dec>0);
        return $hex;
    }
    
    
    

    
    private function numberformat($dec){
        $dec = number_format($dec);
        $dec = str_replace(',','',$dec);
        return $dec;
    }
    
    private function bchexdec($hex) {
        $dec = 0;
        $len = strlen($hex);
        for ($i = 1; $i <= $len; $i++) {
            $dec = bcadd($dec, bcmul(strval(hexdec($hex[$i - 1])), bcpow('16', strval($len - $i))));
        }
        return $dec;
    }
//    private function getTransactionCost(&$raw_cost) {
//        try {
//            $gas = $this->wRPC->call()->eth_gasPrice();
//            if (hexdec($gas) <= 0) {
//                return 0;
//            }
//            $raw_cost = $gas;
//            $gas = (21000 * $this->bchexdec($gas));
//            return $gas / 1000000000000000000;
//        }
//        catch (\Exception $e) {
//            return 0;
//        }
//    }
    
    private function getTransactionCost_orgin($gasPrice) {
        try {
            $gas = $gasPrice;
            if (hexdec($gas) <= 0) {
                return 0;
            }
            $gas = (21000 * $this->bchexdec($gas));
            return $gas / 1000000000000000000;
        }
        catch (\Exception $e) {
            return 0;
        }
    }
    
    private function getTransactionCost($gas, $gasPrice) {
        try {
           
            $cost = ( $this->bchexdec($gas) * $this->bchexdec($gasPrice));
            return $cost;
        }
        catch (\Exception $e) {
            return 0;
        }
    }


    public function moveSystemBalance(){
        $this->getViewer()->setResponseType('JSON');
        
        //wei 단위
        $wei = 1000000000000000000;
        
        //최소 출금액
        $wMin_withdraw = 0.001 * $wei;        
        $resjson = array();
   
        $hxGas = "0x5208"; // 21000
        $hxGasPrice =  $this->wRPC->call()->eth_gasPrice();
        $wGas = $this->bchexdec($hxGas);
        $wGasPrice = $this->bchexdec($hxGasPrice);
        
        $accounts = $this->wRPC->rpc('eth_accounts');
        $wCost = $this->getTransactionCost($hxGas, $hxGasPrice);       
        
        $idx = 0;
        for($i = 0; $i < count($accounts); $i++)
        {
            $accounts[$i] = strtolower($accounts[$i]);            
            if($accounts[$i] != $this->system_address)
            {                    
                $hxUserbalance = $this->wRPC->call()->eth_getBalance($accounts[$i] ,"latest");
                $wUserbalance = $this->convertDescToEther(Utils::bigHexToBigDec( $hxUserbalance )) * $wei;

                if((float)$wUserbalance  >= $wMin_withdraw)
                {                                 
                    $wWithdrawcoin = $wUserbalance - $wCost;
                    $value = Utils::ethToWeiHex($wWithdrawcoin/$wei);     
                    
                    //echo 'ubl: '.  $wUserbalance."\n<br />";
                    //echo 'hxubl: '.  $hxUserbalance."\n<br />";
                    //echo 'cost: '. $wCost ."\n<br />";
                    //echo 'wid: '.  $wWithdrawcoin."\n<br />";


                    //echo 'from: ', $accounts[$i]."\n<br />";
                    //echo 'to  : ', $this->system_address."\n<br />";
                    //echo 'gas : ', $hxGas."\n<br />";
                    //echo 'gasPrice: ', $hxGasPrice."\n<br />";
                    //echo 'value:', Utils::ethToWeiHex($wWithdrawcoin/$wei)."\n<br />";


                    if($wWithdrawcoin  >= $wMin_withdraw)
                    {
                        $rpcresult = $this->wRPC->call()->personal_unlockAccount($accounts[$i],$this->user_address_key);
                        $txform = array('from'=>$accounts[$i],'to'=>$this->system_address,'gas'=>$hxGas,'gasPrice'=>$hxGasPrice,'value'=>$value);
                        $txid = $this->wRPC->call()->eth_sendTransaction($txform);
                        
                        echo $txid;
                        $resjson[$idx++] = $txid;                        
                    }
                }                
            }
            
        }
        return json_encode($resjson);
    }


    public function moveSystemBalance_orgin(){
        $this->getViewer()->setResponseType('JSON');
        
        //최소 출금액
        $min_withdraw = 0.001;
        
        $resjson = array();
        
        $gas = 0x76c0; // 30400
        $gasPrice =  $this->wRPC->call()->eth_gasPrice();
        
     
        $accounts = $this->wRPC->rpc('eth_accounts');
        $gas = $this->getTransactionCost($gasPrice);
//         echo 'b: '. $this->wRPC->call()->eth_coinbase() . ' :' .  Utils::bigHexToBigDec( $this->wRPC->call()->eth_getBalance($this->wRPC->call()->eth_coinbase() ,"latest") )."\n";
        
        $idx = 0;
        for($i = 0; $i < count($accounts); $i++)
        {
                $accounts[$i] = strtolower($accounts[$i]);
            
                if($accounts[$i] != $this->system_address){
                    
                    $userbalance = $this->wRPC->call()->eth_getBalance($accounts[$i] ,"latest");


                    
                    
                    $dec_balance = $this->convertDescToEther(Utils::bigHexToBigDec( $userbalance ));
                    if((float)$dec_balance  >= $min_withdraw){
                        
                        $gas = $gas * 
                        
                        $withdrawcoin = $dec_balance - $gas;
                        
                        echo 'ubl: ' .  $dec_balance."\n<br />";
                        echo 'gas: '.  $gas ."\n<br />";
                        echo 'wid: ' .  $withdrawcoin."\n<br />";

                        
                        if($withdrawcoin  >= $min_withdraw){
//                             $rpcresult = $this->wRPC->call()->personal_unlockAccount($accounts[$i],$this->user_address_key);
//                            $txform = array('from'=>$accounts[$i],'to'=>$this->system_address,'gas'=>'0x'.dechex($gas),'gasPrice'=>$gasPrice,'value'=> Utils::ethToWeiHex($withdrawcoin) );
//                            $txid = $this->wRPC->call()->eth_sendTransaction($txform);
//                            $resjson[$idx++] = $txid;
                            break;
                        }

                       
                        
                        
               
                        
                    }
                
            }
            
        }
        return json_encode($resjson);
    }
    
    public function moveSystemBalance2(){
        $this->getViewer()->setResponseType('JSON');
        
        $resjson = array();

        $accounts = $this->wRPC->rpc('eth_accounts');
        // account값을 모두 소문자로 변환 ( 트랜잭션에서 비교시 대소문자 차이로 비교가 안되는 경우 방지)
        $total_ether = 0;
        $system_balance = 0;
        
        $gas = 0x76c0; // 30400
        $gasPrice =  $this->wRPC->call()->eth_gasPrice();
        
        $wei_send_gas = 30400;
        $wei_send_gasPrice = hexdec($gasPrice);
        
        
//        $this->system_address = $this->wRPC->call()->eth_coinbase();
        
//      Insufficient funds for gas * price + value
        
        
        echo 'coinbase:' . $this->convertDescToEther((float)$this->wRPC->call()->eth_getBalance($this->wRPC->call()->eth_coinbase(),"latest"))."\n";
        
        for($i = 0; $i < count($accounts); $i++)
        {
            $accounts[$i] = strtolower($accounts[$i]);
            
            if($accounts[$i] != $this->wRPC->call()->eth_coinbase()){
                $userbalance =  $this->wRPC->call()->eth_getBalance($accounts[$i] ,"latest");
                if(hexdec($userbalance) > 0 ){

                   
                                        
                    $gastot = number_format( ($wei_send_gas * $wei_send_gasPrice) );
                    
                    $gastot = str_replace(',', '', $gastot);
                    echo '$gastot:' . $gastot ."\n";
                    
                    $sendtot = number_format( hexdec(($userbalance)) );
                    $sendtot = str_replace(',', '', $sendtot);
                    $wei_sendtot  = str_replace(',', '',number_format($sendtot ) );
                    $withdrawcoin = (float)$wei_sendtot - (float)$gastot;
                    $withdrawcoin = str_replace(',', '',number_format($withdrawcoin));
//                    $withdrawcoin =  $this->convertDescToEther((float)$wei_sendtot);
                    
                    echo 'wei_sendtot:' . $accounts[$i].' ===> '.$wei_sendtot."\n";
                    echo 'orgin:' . $accounts[$i].' ===> '.$withdrawcoin."\n";
                    echo 'total:' . $accounts[$i].' ===> '."\n";
                    echo 'gasPrice:' . $accounts[$i].' ===> '.$gasPrice ."\n";
                     
                    
                     //lock해제
//                    $rpcresult = $this->wRPC->call()->personal_unlockAccount($accounts[$i],$this->user_address_key);
//                    $txform = array('from'=>$accounts[$i],'to'=>$this->wRPC->call()->eth_coinbase(),  'value'=> Utils::ethToWeiHex($withdrawcoin,false) );
//                    $resjson = $this->wRPC->call()->eth_sendTransaction($txform);
                    
//                    if($resjson)
//                        $resjson = array_push($resjson,$resjson);
                    break;
                }
            }
        }
        
        return $resjson;
    }

    

    public function getaccounts() {
        $this->getViewer()->setResponseType('JSON');
        return $this->wRPC->rpc('eth_accounts') ;
    }
     public function getbalance() {
        $this->getViewer()->setResponseType('JSON');
//        return $this->wRPC->rpc('eth_getBalance') ;
    }
    public function latesttransactions() {
//        $this->getViewer()->setResponseType('JSON');
//        
//        return $this->wRPC->wallet_transactions(0,100) ;
    }
    
    public function sysCrontest(){
        set_time_limit(50);
        
        //personal_unlockAccount
        
        $accounts = $this->wRPC->rpc('eth_accounts');
        for($i=0;$i<count($accounts);$i++){
            $chbalance = $this->wRPC->call()->eth_getBalance($accounts[$i] ,"latest");
//            $chbalance = $this->wRPC->call()->eth_getBalance($accounts[$i] ,"latest"); //earliest , pending
            echo $chbalance.'<br />';
            $desc_amount = hexdec($chbalance);
            echo $accounts[$i].' ==> ' . $desc_amount . '<br />';
            if($desc_amount > 0){
                
            }
        }
        $bn = '0x'.dechex(10591);
        //$accounts[0]
//        echo '<br />live blocknum:' . json_encode($this->wRPC->call()->eth_getBlockByNumber("pending", true)); //ok
//        echo '<br />trcount:' . $this->wRPC->call()->eth_newPendingTransactionFilter();
        
        

        echo '<br />eth_blockNumber:' . json_encode($this->wRPC->call()->eth_blockNumber());
        echo '<br />eth_getBlockByNumber:' . json_encode($this->wRPC->call()->eth_getBlockByNumber("latest", false));
        
         echo '<br />eth_getTransactionCount:' . json_encode($this->wRPC->call()->eth_getTransactionCount("0x3ff9048140d65392573882687812e0cac19696b3", "latest"));
        
        
        //0xb495a1d7e6663152ae92708da4843337b958146015a2802f4193a410044698c9
        echo '<br />eth_blockNumber:' . json_encode($this->wRPC->call()->eth_blockNumber()); 
        
        echo '<br />eth_getTransactionReceipt:' . json_encode($this->wRPC->call()->eth_getTransactionReceipt('0x9bfd0e6ad89657f30f5f19e1f2d729f17cafbf2fc166aa923665e02c0e9cbafd')); 
        
        
        echo '<br />eth_getBlockByHash:' . json_encode($this->wRPC->call()->eth_getBlockByHash('0xd6610cec88cbfad8263ba7757fe083571e2257826934237065a612692609e389',true)); 
//        
//        echo '<br />eth_getBlockTransactionCountByNumber:' . json_encode($this->wRPC->call()->eth_getBlockTransactionCountByNumber('0x0')); //ok
//        echo '<br />eth_getTransactionByHash:' . json_encode($this->wRPC->call()->eth_getBlockTransactionCountByHash('0xd6610cec88cbfad8263ba7757fe083571e2257826934237065a612692609e389')); 
        
        
        exit;
    }
    
 
    
    
    
    
    
    
    
    
    
    
    
    public function getValidateAddress(){
        $this->getViewer()->setResponseType('JSON');
    }
    
    
    

    
    
    

    public function settxfee() {
        $this->getViewer()->setResponseType('JSON');
        $fee = (float)Utils::getUrlParam('fee');


        if($fee<0 || $fee==0){
            return array('result'=>false,'error'=>'not input fee');
        }

        return $this->wRPC->rpc('settxfee',$fee);
    }

    private function isMember($mb_id){
        $memberdao = new WebMemberDAO();
//        $dto = $memberdao->getViewByMbId($mb_id);
//        if($dto && isset($dto->mbNo)){
//            return true;
//        }else{
//            return false;
//        }
        
        $mbno = str_replace('MB', '',$mb_id);
        $dto = $memberdao->getViewById($mbno);
        if($dto && isset($dto->mbNo)){
            return true;
        }else{
            return false;
        }
    }
    /*
     * 외부주소로 보내기
     */
    public function bitsend() {
        $this->getViewer()->setResponseType('JSON');
        $address = Utils::getUrlParam('address');
        $pwd = ''.Utils::getUrlParam('pwd') ;
        $amount = (float)(Utils::getUrlParam('amount'));
        $tolabal = Utils::getUrlParam('tolabal',ResError::no);

        if($amount<0 || $amount==0){
            return array('result'=>false,'error'=>'unknow amount');
        }

        //pwd check
        if(md5($this->admin_pwd) != $pwd){
            return array('result'=>false,'error'=>'unmatch admin password');
        }

        $validate = $this->wRPC->getRPCValidateAddress($address);

        if(!isset($validate['isvalid']) || $validate['isvalid']!=true){
            return array('result'=>false,'error'=>'unknow bitcoin address','json'=>$validate);
        }

        $jstring = $this->wRPC->getRPCSendfrom($this->config['bitcoin']['system_account'],$address,$amount,'1',$this->config['url']['site'],$tolabal);

        return $jstring;

    }



    public function form() {
        $this->getViewer()->setResponseType('404');
    }
    public function view(){
        $this->getViewer()->setResponseType('404');
    }

    public function update() {
        $this->getViewer()->setResponseType('404');

    }

    public function delete() {
        $this->getViewer()->setResponseType('404');
    }

    public function insert() {
        $this->getViewer()->setResponseType('404');
    }

    public function lists() {
        $this->getViewer()->setResponseType('404');
    }





}
