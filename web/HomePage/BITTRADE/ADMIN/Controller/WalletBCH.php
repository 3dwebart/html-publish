<?php
/**
* Description of LogTradeserver Controller
* @description FunHanSoft Co.,Ltd  PHP auto templet
* @date 2017-06-23
* @copyright (c)funhansoft.com
* @license 해당 사이트 이외의 사용은 엄격히 금지되어 있습니다.
* @version 4.0.0
*/
class WalletBCH extends ControllerBase{

    private $bitcoinRPC;
    private $admin_pwd;

    function __construct(){
        parent::__construct();
        $this->bitcoinRPC = new BitcoinRPCDAO();
        
        
        $wsvDAO = new WebConfigWalletServerDAO();
        $dto = $wsvDAO->getViewByPoType('bch');
        
        $this->bitcoinRPC->initServer($dto->waRpcProto,$dto->waRpcIp.':'.$dto->waRpcPort,$dto->waUser,$dto->waPass);
        
        $this->admin_pwd = $this->config['wallet']['bit_send_pwd'];
    }

    public function main() {

    }
    
    public function getValidateAddress(){
        $this->getViewer()->setResponseType('JSON');
        $address = Utils::getUrlParam('address');
        return $this->bitcoinRPC->validateaddress($address);
    }
    
    public function getValidateAddresss(){
        $this->getViewer()->setResponseType('JSON');
        $address = $_POST['addrs'];
        $tmparr = explode(',', $address);
        $is_error = false;
        for($i=0; $i< count($tmparr);$i++){
            try{
                $rpcres = $this->bitcoinRPC->rpc('validateaddress',$address);
                if(!$rpcres['isvalid']){
                     $is_error = true;
                     break;
                }
            } catch (Exception $ex) {
                $is_error = true;
                break;
            }
        }
        return array('iserror'=>$is_error);
    }
 

    public function getsystemaddress(){
        $this->getViewer()->setResponseType('JSON');
        return $this->bitcoinRPC->rpc('getaddressesbyaccount',$this->config['wallet']['bit_system_account']);
    }

    public function bitgetinfo() {
        $this->getViewer()->setResponseType('JSON');
        $param = Utils::getUrlParam('param',  ResError::no);
        return $this->bitcoinRPC->rpc('getblockchaininfo',$param) ;
    }
    public function getbalance() {
        $this->getViewer()->setResponseType('JSON');
        $param = Utils::getUrlParam('param',  ResError::no);
        return $this->bitcoinRPC->rpc('getbalance',$param) ;
    }
    public function listtransactions() {
        $this->getViewer()->setResponseType('JSON');
        $param = Utils::getUrlParam('param',  ResError::no);
        return $this->bitcoinRPC->rpc('listtransactions',$param) ;
    }
    public function gettransaction() {
        $this->getViewer()->setResponseType('JSON');
        $param = Utils::getUrlParam('param',  ResError::no);
        return $this->bitcoinRPC->rpc('gettransaction',$param) ;
    }
 
     public function getaddressesbyaccount() {
        $this->getViewer()->setResponseType('JSON');
        $param = Utils::getUrlParam('param',  ResError::no);
        return $this->bitcoinRPC->rpc('getaddressesbyaccount',$param) ;
    }

    public function bitmoveto() {
        $this->getViewer()->setResponseType('JSON');
        $account = Utils::getUrlParam('account');
        $amount = (float)(Utils::getUrlParam('amount'));

        if($amount<0 || $amount==0){
            return array('result'=>false,'error'=>'unknow amount');
        }

        if(!$this->isMember($account)){
            return array('result'=>false,'error'=>'unknow user');
        }
        return $this->bitcoinRPC->move($this->config['bitcoin']['system_account'],$account,  floatval($amount) );
    }

    public function bitmove() {
        $this->getViewer()->setResponseType('JSON');
        $fromaccount = Utils::getUrlParam('fromaccount');
        $account = Utils::getUrlParam('account') ;
        $amount = (float)(Utils::getUrlParam('amount'));

        if($amount<0 || $amount==0){
            return array('result'=>false,'error'=>'unknow amount');
        }

        if(!$this->isMember($fromaccount)){
            return array('result'=>false,'error'=>'unknow user');
        }
        if(!$this->isMember($account) && $account!=$this->config['bitcoin']['system_account'] ){
            return array('result'=>false,'error'=>'unknow user');
        }

        return $this->bitcoinRPC->move($fromaccount,$account,$amount);
    }
    public function settxfee() {
        $this->getViewer()->setResponseType('JSON');
        $fee = (float)Utils::getUrlParam('fee');


        if($fee<0 || $fee==0){
            return array('result'=>false,'error'=>'not input fee');
        }

        return $this->bitcoinRPC->rpc('settxfee',$fee);
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

        $validate = $this->bitcoinRPC->getRPCValidateAddress($address);

        if(!isset($validate['isvalid']) || $validate['isvalid']!=true){
            return array('result'=>false,'error'=>'unknow bitcoin address','json'=>$validate);
        }

        $jstring = $this->bitcoinRPC->getRPCSendfrom($this->config['bitcoin']['system_account'],$address,$amount,'1',$this->config['url']['site'],$tolabal);

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
