<?php
class WalletWithdrawalView extends BaseModelBase{

    private $member; 
   
    
    
    function __construct($dbconfname=null) {
        parent::__construct($dbconfname);
    }
    /*
     * 조회 후 없으면 삽입, 있으면 수정
     * @param url param
     */
    public function getExecute($param){
        
        /******************************
         *  회원 검사
         ****************************/
        $this->member = json_decode($this->getMemberDataFromRedis(),TRUE);       
        if(!isset($this->member['mb_id']) || !$this->member['mb_id']){
            return array(
                "result" => -401,
                "success"=>false, "error"=>$this->res->lang->logincheck->fail);
        }
               
        /**********************
        * 레벨별 출금 한도
        **********************/
        $walletlimitconf = $this->execLists(array(),$param);
        if(isset($walletlimitconf[0]) ){
            $walletlimitconf = $walletlimitconf[0];
        }
        
        if(!isset($walletlimitconf['result']) || $walletlimitconf['result']<1 || !isset($walletlimitconf['max_withdraw']) || !isset($walletlimitconf['min_withdraw'])){
            $withdraw_coin_min_limit = 0.01;
            $withdraw_coin_max_limit = 1;
        }else{
            $withdraw_coin_min_limit = (float)$walletlimitconf['min_withdraw'];
            $withdraw_coin_max_limit = (float)$walletlimitconf['max_withdraw'];
        }

        /**************************
        * 일일 최대 금액(BTC)기준
        **************************/
        $std_day = (isset($walletlimitconf['cf_max_day']))?(int)$walletlimitconf['cf_max_day']:1;
        $withdraw_btc_max_limit = 0;
        $withdrawInfo = $this->execRemoteLists($this->res->ctrl->database->wallet,
            array(
                'sql'=>$this->res->ctrl->sql_daywithdrawsumamount,
                'mapkeys'=>$this->res->ctrl->mapkeys_daywithdrawsumamount,
                'rowlimit'=>1
            ) , array('day'=>(int)$std_day-1)
        );
        

        if(isset($withdrawInfo[0]) && isset($withdrawInfo[0]['sum_std_coin'])){
            $withdrawInfo = $withdrawInfo[0];
        }
        $withdraw_remainbtc = 0;
        if(isset($withdrawInfo['sum_std_coin'])){
            $withdraw_remainbtc = number_format($withdraw_coin_max_limit,8) - number_format($withdrawInfo['sum_std_coin'],8);
        }
        
        
        $walletlimitconf['withdraw_remainbtc_per'] = ($withdraw_remainbtc * 100) / $withdraw_coin_max_limit;
        $walletlimitconf['withdraw_remainbtc'] = number_format($withdraw_remainbtc,8);

        /**************************
        * 출금 수수료
        **************************/
        $withdrawfee = $this->execLists(
            array(
                'sql'=>$this->res->ctrl->sql_serverconf,
                'mapkeys'=>array(),
                'rowlimit'=>100
            ),$param
        );
        
        $walletlimitconf['withdraw_fees'] = $withdrawfee;

        return $walletlimitconf;
        
    }
    

    function __destruct(){

    }



}
