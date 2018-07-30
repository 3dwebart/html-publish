<?php
class WalletWithdrawal extends BaseModelBase{

    private $member; 

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

        if( !isset($param['od_status']) || !$param['od_status'] || !isset($param['po_type']) || !$param['po_type']){
            return array(
                "result" => -5010,
                "success"=>false,
                "error"=>$this->res->lang->trade->validator);
        }

        $od_status  = $param['od_status'];
        $currency   = strtolower($param['po_type']);

        /******************************
         *  회원 검사
         ****************************/
        $this->member = json_decode($this->getMemberDataFromRedis(),TRUE);       
        if(!isset($this->member['mb_id']) || !$this->member['mb_id']){
            return array(
                "result" => -401,
                "success"=>false, "error"=>$this->res->lang->logincheck->fail);
        }

        $type = isset($param['type'])?strtoupper($param['type']):'';
        if( $type == "EMAILCONFIRM" ){
            return $this->_confirmWithdraw($param, $type, $od_status);
        }else{
            return $this->_registWithdraw($param, $type, $od_status);
        }
        
    }
    
    // 출금요청 - 등록
    private function _registWithdraw($param, $type, $od_status){

        $currency   = strtolower($param['po_type']);
        
        //파라이터 조작으로 잘못 들어왔을 경우 Return
        if(!isset($this->res->ctrl->sql_wallet_limit_conf)){
            return array(
                "result" => -5011,
                "success"=>false,
                "error"=>$this->res->lang->trade->validator);
        }        
        
        /**********************
        * 레벨별 출금 한도
        **********************/
        $walletlimitconf = $this->execLists(
            array(
                'sql'=>$this->res->ctrl->sql_wallet_limit_conf,
                'mapkeys'=>$this->res->ctrl->mapkeys_wallet_limit_conf,
                'rowlimit'=>1
            )
            , array('wallettype'=>$currency)
        );
        
        if(isset($walletlimitconf[0]) ){
            $walletlimitconf = $walletlimitconf[0];
        }
        
        if(!isset($walletlimitconf['result']) || $walletlimitconf['result']<1 || !isset($walletlimitconf['cf_max_withdraw']) || !isset($walletlimitconf['cf_max_day'])){
            $withdraw_coin_min_limit = 0.01;
            $withdraw_coin_max_limit = 1;
        }else{
            $withdraw_coin_min_limit = (float)$walletlimitconf['cf_min_withdraw'];
            $withdraw_coin_max_limit = (float)$walletlimitconf['cf_max_withdraw'];
        }
        
        /**********************
        * 최소 출금 금액
        **********************/
        if( $withdraw_coin_min_limit > (float)$param['od_temp_amount'] ){
            return array(
            "result"=>-5911,
            "success"=>false,
            "error"=>$this->res->lang->trade->withdrawminimum . '('.$withdraw_coin_min_limit. ')');
        }
        
        /*********************************
        * 주소 유효성 체크 & 내부주소체크
        **********************************/
        $to_inner_mb = '';
        $config = $this->execLists(
            array(
                'sql'=>$this->res->ctrl->sql_walletconf,
                'mapkeys'=>$this->res->ctrl->mapkeys_walletconf,
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
        
        // 자기 주소 체크
        $addressmember = $this->execLists(
            array(
                'sql'=>$this->res->ctrl->sql_member_address,
                'mapkeys'=>$this->res->ctrl->mapkeys_member_address,
                'rowlimit'=>1
            ) , $param
        );
        
        if(isset($addressmember[0]['mb_no']) && ($addressmember[0]['mb_no'] == $this->member['mb_no'])){
            return array(
                "result"=>-5002,
                "success"=>false,
                "error"=>$this->res->lang->wallet->msgIncorrectBtcAddress);
        }
        
        // 비트코인
        $mb_wallet = '';
        if($currency == 'btc' || $currency == 'ltc' || $currency == 'bch'){
            require_once './Plugin/Wallet/BitcoinRPC.php';
            $walletRPC = new BitcoinRPC();
            $walletRPC->initServer($config['wa_rpc_proto'],$config['wa_rpc_ip'].':'.$config['wa_rpc_port'],$config['wa_user'],$config['wa_pass']);
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
            $walletRPC->initServer($config['wa_rpc_proto'],$config['wa_rpc_ip'].':'.$config['wa_rpc_port']);
            
            // 지갑
            if($addressmember && $addressmember[0]){
                if(isset($addressmember[0]['mb_no'])){
                    $to_inner_mb = 'MB'.$addressmember[0]['mb_no'];
                }
            }

        }else{
            
        }
        
        /**************************
        * 내 발란스 체크
        **************************/
        $mbsearchkey = 'MB'.$this->member['mb_no'];
        $balancekey = $mbsearchkey . '-balance';
        $balancedata = $this->getRedisData($balancekey);
        $balances = json_decode($balancedata, true);

        $balance_coin_poss = 0.0000;
        
        for($t=0;$t<count($balances);$t++){
            $tmp = $balances[$t];
            if(isset($tmp['po_type']) && $tmp['po_type'] == $currency){
                $balance_coin_poss = (float)$tmp['poss'];

                $balances[$t]['on_etc'] = $this->toConvert($tmp['on_etc'] + $param['od_temp_amount'], 8);
                $balances[$t]['poss'] = $this->toConvert($tmp['poss'] - $param['od_temp_amount'], 8);
            }
        }
        
        if( (float)$balance_coin_poss < (float)$param['od_temp_amount'] ){
            return array(
            "result"=>-5813,
            "success"=>false,
            "error"=>$this->res->lang->validator->lowbalance);
        }
        
        
        /**************************
        * 일일 최대 금액
        **************************/
        $std_day = (isset($walletlimitconf['cf_max_day']))?(int)$walletlimitconf['cf_max_day']:1;
        
        $withdrawInfo = $this->execRemoteLists($this->res->ctrl->database->wallet,
            array(
                'sql'=>$this->res->ctrl->sql_daywithdrawsumamount,
                'mapkeys'=>$this->res->ctrl->mapkeys_daywithdrawsumamount,
                'rowlimit'=>1
            )
            , array('day'=>(int)$std_day-1)
        );

        if(isset($withdrawInfo[0]) && isset($withdrawInfo[0]['sum_std_coin'])){
            $withdrawInfo = $withdrawInfo[0];
        }

        $withdraw_remainbtc = 0;
        if(isset($withdrawInfo['sum_std_coin'])){
            $withdraw_remainbtc = $withdraw_coin_max_limit - (float)$withdrawInfo['sum_std_coin'];
            if( (float)$withdraw_remainbtc < (float)$param['od_temp_amount'] ){
                return array(
                "result"=>-5812,
                "success"=>false,
                "error"=>$this->res->lang->withdrawal->maxbtclimit);
            }
        }  

        // 입금 요청 횟수
        $withdraw_cnt = $this->execRemoteLists($this->res->ctrl->database->wallet,
            array(
                'sql'=>$this->res->ctrl->sql_withdrawcnt,
                'mapkeys'=>$this->res->ctrl->mapkeys_withdrawcnt,
                'rowlimit'=>1
            )
            , array(
                'od_status'=>$param['od_status']
            )
        );

        //주문요청 수
        $withdraw_od_reg_cnt = 1;
        if(isset($withdraw_cnt[0]) || isset($withdraw_cnt[0]['od_reg_cnt'])){
            $withdraw_od_reg_cnt = $withdraw_cnt[0]['od_reg_cnt'] +1;
        }

        /**************************
        * 출금 수수료
        **************************/
        $withdrawfee = $this->execLists(
            array(
                'sql'=>$this->res->ctrl->sql_serverconf,
                'mapkeys'=>$this->res->ctrl->mapkeys_serverconf,
                'rowlimit'=>100
            ),$param
        );

        $param['od_fee'] = 0;
        if($withdrawfee && isset($withdrawfee[0]['tx_fee'])){
            $param['od_fee'] = $withdrawfee[0]['tx_fee'];
        }

        $coin_symbol = strtoupper($param['po_type']);
        /*****************************
         * 이메일 발송용 키 삽입
         *****************************/
         $param['mk_type'] = 'WITHDC';
        // 데이터가 존재하니 인증키 삽입 처리
        // 출금요청 인증유효시간은 +1 hout 로 정의
        $sql_regist_mail_key = $this->execUpdate(
            array(
                'sql'=>$this->res->ctrl->sql_regist_mail_key,
                'mapkeys'=>$this->res->ctrl->mapkeys_regist_mail_key,
                'rowlimit'=>1
            )
            , array(
                'mk_type'=>$param['mk_type'],
                'mk_explain'=>$coin_symbol.' Withdrawal Confirmation',
                'md5_mb_id'=>$this->member['mb_id']
            )
        );
        /******************************
        * 이메일 인증키 불러오기
        ******************************/
        $sql_getinfo_mail_key = $this->execLists(
            array('sql'=>$this->res->ctrl->sql_getinfo_mail_key,
                'mapkeys'=>$this->res->ctrl->mapkeys_getinfo_mail_key,
                'rowlimit'=>1
            ) ,$param
        );
        
        $mbemail_key = array();
        if(isset($sql_getinfo_mail_key[0])){
            $mbemail_key = $sql_getinfo_mail_key[0];
        }
        // 메일링 인증 위한 키 - 필수입력 값이 입력되지 않았습니다
        if( !isset($mbemail_key['mk_no']) || !isset($mbemail_key['mk_email_key']) ){
            return array(
                "result"=>-5907,
                "success"=>false,
                "error"=>$this->res->lang->validator->required);
        }
       
        /*****************************
        * 출금요청 등록
        * **************************/   
        $param['od_reg_cnt']    = $withdraw_od_reg_cnt;
        $param['po_amount']     = (float)$param['od_temp_amount'] - (float)$param['od_fee'];
        $param['mk_no']         = $mbemail_key['mk_no']; //이메일 컨펌 키 번호
        $param['od_sendto']     = $to_inner_mb; //회원에게 전송
        
        $sql_regist = $this->execRemoteUpdate($this->res->ctrl->database->wallet,array() ,$param);
        $sql_regist['withdraw_remainbtc'] = $withdraw_remainbtc;
        
        
        // 출금요청 데이터가 삽입되지 않았다면.. - 필수입력 값이 입력되지 않았습니다
        if( isset($sql_regist['success']) && (string)$sql_regist['success']=="false"){
            $errormsg = $this->res->lang->model->err21;
            if( isset($sql_regist['error']) ){
                if(isset($sql_regist['error'][0]['message'])){
                    $errormsg = $sql_regist['error'][0]['message'];
                }
            }
            return array(
                "result" => -5905,
                "success"=>false,
                "error"=>$errormsg);
        }else if( isset($sql_regist['result']) && (int)$sql_regist['result']<1 ) {
            return array(
                "result" => -5906,
                "success"=>false,
                "error"=>$this->res->lang->withdrawal->msgWithdrawalRequestFail);
        }

        //삽입이 안되면
        if((int)$sql_regist["result"]<1){
            return array(
                "result"=>0,
                "success"=>false,
                "error"=>"request fail");
        }
        
        //발란스 합산
        $session_expire = (int)$this->res->config['session']['cache_expire'] * 60;
        $balancekey = 'MB'.$this->member['mb_no'].'-balance';
        $tmpbalancedata = $this->getRedisData($balancekey);
        $jsonbalancedata = json_encode($balances);
        if($tmpbalancedata != $jsonbalancedata){
            $this->delRedisData($balancekey);
            $this->setRedisData($balancekey,$jsonbalancedata,$session_expire);
        }
        
        // redis에 데이터 삽입 후 balance update
        $balance_result = $this->balanceUpdateRetry($param, $this->member, $currency);
            
        if($balance_result < 0){
            return array(
                "result" => -555,
                "success"=>false,
                "error"=>$this->res->lang->trade->balancefail);
            exit;
        }

        // 메일링을 위한 데이터 셋팅
        $mb_en_id       = base64_encode($this->member['mb_id']);
        $mb_email_key   = $mbemail_key['mk_email_key'];
        $email_subject   = '';
        $email_type_country = '';
        $od_request     = '';
        $od_fee   = '';
        $potype = $coin_symbol;
        $requesttime = date( 'Y-m-d H:i:s', time() );
        
        

        if($this->member['contry_code']=='KR'){
            $email_subject          = $coin_symbol.' 출금 요청';
            $email_type_country     = 'request_withdraw_email_kr';
        }else{
            $email_subject          = $coin_symbol.' Withdrawal Confirmation';
            $email_type_country     = 'request_withdraw_email_en';
        }
        $od_request     = $param['od_temp_amount'].' '. $coin_symbol;
        $od_fee         = $param['od_fee'].' '. $coin_symbol;
        
        $send_confirm_url   = "/returnemail/confirmwithdraw/type-emailconfirm/od_status-req/mk-".$mbemail_key['mk_no']."/po_type-".$param['po_type']."/id-".$mb_en_id."/ekey-".$mb_email_key;
        $body = $this->getHTMLEmailBody($email_type_country,array(
            'name'=>$this->member['mb_name'],
            'confirm_url'=>$send_confirm_url,
            'od_request'=>$od_request,
            'od_fee'=>$od_fee,
            'potype'=>$potype,
            'requesttime'=>$requesttime                
        ));

        $mailresult = $this->mailer($this->member['mb_id'], $email_subject, $body, 1, "", "", "");
        $sql_regist["mailsend"]=$mailresult;

        return $sql_regist;
    }


    // 출금요청 인증 -- 상태값 - WAIT => REQ로 변경요망
    private function _confirmWithdraw($param, $type, $od_status){

        if( !isset($param['id']) || !isset($param['ekey']) ){
            return array(
                "result"=>-5912,
                "success"=>false,
                "error"=>$this->res->lang->validator->required);
        }
        $mb_id = base64_decode($param['id']);
        $mb_email_key = $param['ekey'];

        $mk_explain = 'Withdrawal Confirmation';
        if($od_status=="sendtobtc"){
            $mk_explain = "BTC Send";
        }

        //이메일 키 정보를 가져온다.
        $getinfo_mail_key = $this->execLists(
            array(
                'sql'=>$this->res->ctrl->sql_getinfo_mail_key,
                'mapkeys'=>$this->res->ctrl->mapkeys_getinfo_mail_key,
                'rowlimit'=>1
            ) , $param
        );

        $mbemail_key = array();
        if(isset($getinfo_mail_key[0])){
            $mbemail_key = $getinfo_mail_key[0];
        }

        // mb_no 체크
        // 데이터 유효성 체크
        // mb_id 체크
        if(!isset($mbemail_key['mb_id'])){
            return array(
                "result" => -5913,
                "success"=>false,
                "error"=>$this->res->lang->emailconfirm->invalidkey);
        // 인증키 체크
        }else if( ($mbemail_key['mk_email_key'])!=$mb_email_key ){
            return array(
                "result" => -5914,
                "success"=>false,
                "error"=>$this->res->lang->emailconfirm->invalidkey);
        // 유효시간 체크 -- 유효시간이 지났습니다. 재 인증 요청을 진행하세요.
        }else if( strtotime($mbemail_key['mk_expire_dt']) < time() ){
            return array(
                "result" => -5915,
                "success"=>false,
                "error"=>$this->res->lang->logincheck->expireDt);
        }

        // 출금요청 취소후 메일 인증할 경우를 대비한 체크
        $mb_getinfo_request_order = $this->execRemoteLists($this->res->ctrl->database->wallet,
            array(
                'sql'=>$this->res->ctrl->sql_getinfo_request_order,
                'mapkeys'=>$this->res->ctrl->mapkeys_getinfo_request_order,
                'rowlimit'=>1
            ), array()
        );

        $mb_info_request_order = array();
        if(isset($mb_getinfo_request_order[0])){
            $mb_info_request_order = $mb_getinfo_request_order[0];
        }

        if(!isset($mb_info_request_order['od_id'])){
            return array(
                "result" => -5916,
                "success"=>false,
                "error"=>$this->res->lang->logincheck->referer);
        }
        
        /******************************
         * 주문서 업데이트
         *****************************/
        $param['new_od_pay_status'] ='REQ';
//        $param['mk_no'] =   $param['mk'];

        $is_user_confirm_ip = Utils::getClientIP();
        // 이상이 없다면 request_order 값 WAIT = > REQ로 업데이트 처리
        $request_order_update = $this->execRemoteUpdate($this->res->ctrl->database->wallet,
            array(
                'sql'=>$this->res->ctrl->sql_request_order_update,
                'mapkeys'=>$this->res->ctrl->mapkeys_request_order_update,
                'rowlimit'=>1
            ) , $param
        );

        if( (int)$request_order_update < 0){
            return array(
                "result" => -5917,
                "success"=>false,
                "error"=>$this->res->lang->response->err21);
        }
        // email key use_yn update
        $mb_mail_key_update = $this->execUpdate(
            array(
                'sql'=>$this->res->ctrl->sql_mail_key_update,
                'mapkeys'=>$this->res->ctrl->mapkeys_mail_key_update,
                'rowlimit'=>1
            ),$param
        );

        if( (int)$mb_mail_key_update < 0){
            return array(
                "result" => -5918,
                "success"=>false,
                "error"=>$this->res->lang->response->err21);
        }

        return array('result'=>$mb_info_request_order['od_id']);
    }

    private function getHTMLEmailBody($skin_html_file,$user=array('name'=>'','confirm_url'=>'', 'od_request'=>'', 'od_fee'=>'')){
        $logo_url = '/assets/img/common/logo_email.png';

        $html = file_get_contents('../WebApp/ViewHTML/'.$skin_html_file.'.html');
        $html = str_replace("{site_name}", $this->res->config['html']['title'],$html);
        $html = str_replace("{logo_url}", $this->res->config['url']['static'].$logo_url,$html);
        $html = str_replace("{user_name}", $user['name'],$html);
        $html = str_replace("{confirm_url}", $this->res->config['url']['site'].$user['confirm_url'],$html);
        $html = str_replace("{od_request}", $user['od_request'],$html);
        $html = str_replace("{od_total_fee}", $user['od_fee'],$html);
        $html = str_replace("{potype}", $user['potype'],$html);
        $html = str_replace("{requesttime}", $user['requesttime'],$html);
        
        return $html;
    }
    
    private function setLogFile($errorcode,$param){
        $log = new Logging();
        $log->lfile('../WebApp/Debug/WalletWithdrawException'.'.txt');
        $log->lwrite(__CLASS__ . "[".$errorcode."]:".' '.json_encode($param).' - '.Utils::getClientIP());
        $log->lclose();
    }
    
    private function balanceUpdateRetry($param, $member, $currency){
        $resultCode = -1;
        
        for($i=0;$i<3;$i++){
            $result = $this->execRemoteUpdate(
                $this->res->ctrl->database->wallet,
                array(
                    'sql'=>$this->res->ctrl->sql_balance_update,
                    'mapkeys'=>$this->res->ctrl->mapkeys_balance_update
                ),
                array(
                    'po_on_etc'=>$this->toConvert($param['od_temp_amount'], 8),
                    'mb_no'=>$member['mb_no'],
                    'po_type'=>$currency
                )
            );
            if((int)$result['result']==0){
                sleep(1);
            }else{
                $resultCode = 1;
                $i = 99;
            }
        }
        
        return $resultCode;
    }
    
    // 자바스크립트 toFixed와 같은역활
    private function toConvert($number, $decimals){
        return number_format($number, $decimals, ".", "");
    }

    function __destruct(){

    }



}
