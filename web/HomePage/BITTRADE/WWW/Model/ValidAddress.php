<?php
class ValidAddress extends BaseModelBase{

    function __construct($dbconfname=null) {
        parent::__construct($dbconfname);
    }

    /*
     * 조회 후 없으면 삽입, 있으면 수정
     * @param url param
     */
    public function getExecute($param){

        if(!$param){
            return array(
                "result" => -5009,
                "success"=>false,
                "error"=>$this->res->lang->trade->validator );
        }
        
        $config = $this->execLists(
            array(
                'sql'=>$this->res->ctrl->sql_walletconf,
                'mapkeys'=>$this->res->ctrl->mapkeys_walletconf,
                'rowlimit'=>1
            ),$param
        );
        $currency = $param['po_type'];

        // 비트코인
        if($currency == 'btc' || $currency == 'ltc' || $currency == 'bch'){
            require_once './Plugin/Wallet/BitcoinRPC.php';
            $walletRPC = new BitcoinRPC();
            $walletRPC->initServer($config[0]['wa_rpc_proto'],$config[0]['wa_rpc_ip'].':'.$config[0]['wa_rpc_port'],$config[0]['wa_user'],$config[0]['wa_pass']);
            $addrcheck = $walletRPC->getRPCValidateAddress($param['od_addr']);
            if(!isset($addrcheck['isvalid']) || !$addrcheck['isvalid']){
                 return array(
                "result"=>-5001,
                "success"=>false,
                "error"=>$this->res->lang->wallet->msgIncorrectBtcAddress);
            }
            $to_inner_mb = $walletRPC->getRPCAccountByAddress($param['od_addr']);           
            
        // 이더리움
        }else if($currency == 'eth' || $currency == 'etc'){
            require_once './Plugin/Wallet/EthereumRPC.php';
            $walletRPC = new EthereumRPC();
            $walletRPC->initServer($config[0]['wa_rpc_proto'],$config[0]['wa_rpc_ip'].':'.$config[0]['wa_rpc_port']);
            
            // 지갑
            $addressmember = $this->execLists(
                array(
                    'sql'=>$this->res->ctrl->sql_member_address,
                    'mapkeys'=>$this->res->ctrl->mapkeys_member_address,
                    'rowlimit'=>1
                ) , $param
            );
            if($addressmember && $addressmember[0]){
                if(isset($addressmember[0]['mb_no'])){
                    $to_inner_mb = 'MB'.$addressmember[0]['mb_no'];
                }
            }
        }else{
            
        }
        return array("result"=>1);
        
    }

    function __destruct(){

    }



}
