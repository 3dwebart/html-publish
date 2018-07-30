<?php
class WalletRPC extends BaseModelBase{

    private $member;
    private $oMcrypt;

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
                "error"=>$this->res->lang->logincheck->validator );
        }
        
        if( !isset($param['currency']) || !$param['currency'] ){
            return array(
                "result" => -5010,
                "success"=>false,
                "error"=>$this->res->lang->logincheck->validator);
        }
        
        /******************************
         *  회원 검사
         ****************************/
        $member = json_decode($this->getMemberDataFromRedis(),TRUE);       
        if(!isset($member['mb_id']) || !$member['mb_id']){
            return array(
                "result" => -401,
                "success"=>false, "error"=>$this->res->lang->logincheck->fail);
        }

        
        
        $config = $this->execLists(
            array(
                'sql'=>$this->res->ctrl->sql_conf,
                'mapkeys'=>$this->res->ctrl->mapkeys_conf,
                'rowlimit'=>1
            ),$param
        );
        
        if(count($config) > 0){
            $config = $config[0];
        }
        
        if(!isset($config['wa_staus']) || $config['wa_staus']!='running'){
            //지갑을 일시적으로 사용할 수 없습니다.
            return array('result'=>-901,'success'=>false,'error'=>$this->res->lang->wallet->disabled);
        }
        
        /******************************
         *  회원 지갑이 있는지
         ****************************/
        $is_exist = false;
        $mbwallet = $this->execLists(
            array(
                'sql'=>$this->res->ctrl->sql_mb,
                'mapkeys'=>$this->res->ctrl->mapkeys_mb,
                'rowlimit'=>1
            ),$param
        );
        if(count($mbwallet) > 0){
            $mbwallet = $mbwallet[0];
        }
        
        if(isset($mbwallet['mw_address']) && $mbwallet['mw_address']){
            $is_exist = true;
        }

        $currency = strtolower($param['currency']);
        
        /******************************
         *  비트코인
         ****************************/
        $mb_wallet = '';
        if($currency == 'btc' || $currency == 'ltc' || $currency == 'bch'){
            require_once './Plugin/Wallet/BitcoinRPC.php';
            $walletRPC = new BitcoinRPC();
            $walletRPC->initServer($config['wa_rpc_proto'],$config['wa_rpc_ip'].':'.$config['wa_rpc_port'],$config['wa_user'],$config['wa_pass']);
            $mb_wallet = $walletRPC->getDistinctCreateAddress('MB'.$member['mb_no']);
            
        /******************************
        *  이더리움
        ****************************/   
        }else if($currency == 'eth' || $currency == 'etc'){
            if(!$is_exist){
                 require_once './Plugin/Wallet/EthereumRPC.php';
                $walletRPC = new EthereumRPC();
                $walletRPC->initServer($config['wa_rpc_proto'],$config['wa_rpc_ip'].':'.$config['wa_rpc_port']);
                $pass = (!isset($this->res->config['wallet']['user_addr_key_'.$currency]))? "fun": $this->res->config['wallet']['user_addr_key_'.$currency] ;
                $mb_wallet = $walletRPC->rpc('personal_newAccount',$pass);
            }else{
                $mb_wallet = $mbwallet['mw_address'];
            }
        }

        if(!$is_exist){
            $param['address'] = $mb_wallet;
            $param['wa_tx_fee'] = $config['wa_tx_fee'];
            $param['mw_server_host'] = $config['wa_rpc_hostname'];
            $param['mw_etc1'] = '';
            $param['mw_etc2'] = '';

            return $this->insertMemberWallet($param);
        }else{
            return array(
                "result" => 1,
                "success"=>true,
                "address"=>$mb_wallet,
                "wa_tx_fee"=>$config['wa_tx_fee'],
                "error"=>'');
        }

        
    }
    
    private function insertMemberWallet($param){
        
        
        // 등록
        $resigt_result = $this->execUpdate(
            array(
                'sql'=>$this->res->ctrl->sql_regist,
                'mapkeys'=>$this->res->ctrl->mapkeys_regist,
                'rowlimit'=>1
            )
            , $param
        );
        
        // 등록 실패
        if( isset($resigt_result['success']) && (string)$resigt_result['success']=="false"){
            $errormsg = $this->res->lang->model->err21;
            if( isset($resigt_result['error']) ){
                if(isset($resigt_result['error'][0]['message'])){
                    $errormsg = $resigt_result['error'][0]['message'];
                }
            }
            return array(
                "result" => -5103,
                "success"=>false,
                "address"=>"",
                "wa_tx_fee"=>$param['wa_tx_fee'],
                "error"=>$this->res->lang->wallet->createFail);
        }else if( isset($resigt_result['result']) && $resigt_result['result']<1 ) {
            return array(
                "result" => -5104,
                "success"=>false,
                "address"=>"",
                "wa_tx_fee"=>$param['wa_tx_fee'],
                "error"=>$this->res->lang->wallet->createFail);
        }
        
        return array(
                "result" => (int)$resigt_result['result'],
                "success"=>true,
                "address"=>$param['address'],
                "wa_tx_fee"=>$param['wa_tx_fee'],
                "error"=>''); 
    }
            

    function __destruct(){

    }



}
