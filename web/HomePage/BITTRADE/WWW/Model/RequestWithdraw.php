<?php
class RequestWithdraw extends BaseModelBase{
    
    function __construct($dbconfname=null) {
        parent::__construct($dbconfname);
    }


    /*
     * 출금요청 (KRW, BTC) 모델
     * @param url param
     */
    public function getExecute($param){

        if(!$param){
            return array(
                "result"=>-5901,
                "success"=>false,
                "error"=>$this->res->lang->validator->required);
        }

        $type = isset($param['type'])?strtoupper($param['type']):'';

        if( $type == "EMAILCONFIRM" ){
            return $this->_confirmWithdraw($param);
        }else{
            return $this->_registWithdraw($param);
        }
    }



    // 출금요청 - 등록
    private function _registWithdraw($param){
        // 우선 입력값 비교를 위한 회원 잔고 조회
        $sql_getinfo = $this->execRemoteLists(
            $this->res->ctrl->database->web,
            array(
                'sql'=>$this->res->ctrl->sql_getinfo,
                'mapkeys'=>$this->res->ctrl->mapkeys_getinfo,
                'rowlimit'=>1
            )
            , array()
        );

        $mb_info = array($sql_getinfo[0]);
        if(isset($sql_getinfo[0])){
            $mb_info = $sql_getinfo[0];
        }

        $mb_id = $mb_info['mb_id'];

        if(!isset($mb_id)){
            return array(
                "result"=>-5902,
                "success"=>false,
                "error"=>$this->res->lang->validator->required);
        }
        
        /**********************
        * 레벨별 출금 한도
        **********************/
        $walletlimitconf = $this->execRemoteLists(
            $this->res->ctrl->database->web,
            array(
                'sql'=>$this->res->ctrl->sql_wallet_limit_conf,
                'mapkeys'=>$this->res->ctrl->mapkeys_wallet_limit_conf,
                'rowlimit'=>1
            ),$param
        );
        
        if(isset($walletlimitconf[0]) ){
            $walletlimitconf = $walletlimitconf[0];
        }
        
        if(!isset($walletlimitconf['result']) || $walletlimitconf['result']<1 || !isset($walletlimitconf['cf_max_withdraw']) || !isset($walletlimitconf['cf_max_day'])){
            $withdraw_min_limit = 0.01;
            $withdraw_max_limit = 1;
        }else{
            $withdraw_min_limit = (float)$walletlimitconf['cf_min_withdraw'];
            $withdraw_max_limit = (float)$walletlimitconf['cf_max_withdraw'];
        }
        
        /**********************
        * 최소 출금 금액
        **********************/
        if( $withdraw_min_limit > (float)$param['od_temp_amount'] ){
            return array(
            "result"=>-5911,
            "success"=>false,
            "error"=>$this->res->lang->trade->withdrawminimumkrw . '('.$withdraw_min_limit. ')');
        }

        /**********************
        * 최대 출금 금액
        **********************/
        if( $withdraw_max_limit < (float)$param['od_temp_amount'] ){
            return array(
            "result"=>-5922,
            "success"=>false,
            "error"=>$this->res->lang->trade->withdrawmaximumkrw . '('.$withdraw_max_limit. ')');
        }

        /**************************
        * 내 발란스 체크
        **************************/
        $mbsearchkey = 'MB'.$mb_info['mb_no'];
        $balancekey = $mbsearchkey . '-balance';
        $balancedata = $this->getRedisData($balancekey);
        $balances = json_decode($balancedata, true);

        $balance_krw_poss = 0.0000;
        
        for($t=0;$t<count($balances);$t++){
            $tmp = $balances[$t];
            if(isset($tmp['po_type']) && $tmp['po_type'] == 'krw'){
                $balance_krw_poss = (int)$tmp['poss'];

                $balances[$t]['on_etc'] = $this->toConvert($tmp['on_etc'] + $param['od_temp_amount'], 8);
                $balances[$t]['poss'] = $this->toConvert($tmp['poss'] - $param['od_temp_amount'], 8);
            }
        }
        
        if( (float)$balance_krw_poss < (float)$param['od_temp_amount'] ){
            return array(
            "result"=>-5923,
            "success"=>false,
            "error"=>$this->res->lang->validator->lowbalance);
        }
        
        $mk_explain = 'KRW Withdrawal Confirmation';

        // 데이터가 존재하니 인증키 삽입 처리
        // 출금요청 인증유효시간은 +1 month 로 정의 15.08.13
        $sql_regist_mail_key = $this->execRemoteUpdate(
            $this->res->ctrl->database->web,
            array(
                'sql'=>$this->res->ctrl->sql_regist_mail_key,
                'mapkeys'=>$this->res->ctrl->mapkeys_regist_mail_key,
                'rowlimit'=>1
            )
            , array(
                'mk_type'=>'WITHDC',
                'mk_explain'=>$mk_explain,
                'md5_mb_id'=>$mb_id
            )
        );

        // 인증키 정보 겟
        $sql_getinfo_mail_key = $this->execRemoteLists(
            $this->res->ctrl->database->web,
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
        if( !isset($mbemail_key['mk_email_key']) ){
            return array(
                "result"=>-5906,
                "success"=>false,
                "error"=>$this->res->lang->validator->required);
        }

        // 입금 요청 순번
        $withdraw_cnt = $this->execLists(
            array(
                'sql'=>$this->res->ctrl->sql_withdrawcnt,
                'mapkeys'=>$this->res->ctrl->mapkeys_withdrawcnt,
                'rowlimit'=>1
            )
            , $param
        );

        // 기본값 처리
        $withdraw_od_cnt = 1;
        if(isset($withdraw_cnt[0]) || isset($withdraw_cnt[0]['od_cnt'])){
            $withdraw_od_cnt = $withdraw_cnt[0]['od_cnt'] +1;
        }
        
        // 출금 수수료
        $param['od_fee'] = $this->res->config['fee']['withdraw_krw_fee'];

        // 출금요청 삽입
        $sql_regist = $this->execUpdate(
            array(
                'sql'=>$this->res->ctrl->sql_regist,
                'mapkeys'=>$this->res->ctrl->mapkeys_regist,
                'rowlimit'=>1
            )
            , array(
                'od_name'=>$mb_info['mb_name']
                ,'mk_no'=>$mbemail_key['mk_no']
                ,'od_bank_name'=>$param['od_bank_name']
                ,'od_bank_account'=>$param['od_bank_account']
                ,'od_bank_holder'=>$param['od_bank_holder']
                ,'od_temp_amount'=>$param['od_temp_amount']
                ,'od_receipt_amount'=>$param['od_receipt_amount']
                ,'od_fee'=>$param['od_fee']
                ,'od_reg_cnt'=>$withdraw_od_cnt
            )
        );

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
        }else if( isset($sql_regist['result']) && $sql_regist['result']<1 ) {
            return array(
                "result" => -5905,
                "success"=>false,
                "error"=>$this->res->lang->msgKrwWithdrawalRequestFailed);
        }

        //삽입이 안되면
        if((int)$sql_regist["result"]<1){
            return array(
                "result"=>0,
                "success"=>false,
                "error"=>"request fail");
        }
        
        // 출금요청 데이터 삽입 후 레디스
        //내 발란스 redis update
        /*$this->execUpdate(
            array(
                'sql'=>$this->res->ctrl->sql_callbalance_proc,
                'mapkeys'=>$this->res->ctrl->mapkeys_callbalance_proc 
            ) ,$param
        );*/
        $session_expire = (int)$this->res->config['session']['cache_expire'] * 60;
        $balancekey = 'MB'.$mb_info['mb_no'].'-balance';
        $tmpbalancedata = $this->getRedisData($balancekey);
        $jsonbalancedata = json_encode($balances);
        if($tmpbalancedata != $jsonbalancedata){
            $this->delRedisData($balancekey);
            $this->setRedisData($balancekey,$jsonbalancedata,$session_expire);
        }
        
        // redis에 데이터 삽입 후 balance update
        $balance_result = $this->balanceUpdateRetry($param, $mb_info);
            
        if($balance_result < 0){
            return array(
                "result" => -555,
                "success"=>false,
                "error"=>$this->res->lang->trade->balancefail);
            exit;
        }

        // 메일링을 위한 데이터 셋팅
        $mb_en_id       = base64_encode($mb_id);
        $mb_email_key   = $mbemail_key['mk_email_key'];
        $email_subject   = '';
        $email_type_country = '';
        $od_request     = '';
        $od_total_fee   = '';

        if($mb_info['contry_code']=='KR'){
            $email_subject          = 'KRW 출금요청 인증메일입니다.';
            $email_type_country     = 'request_withdraw_email_kr';
        }else if($mb_info['contry_code']=='CN'){
            $email_subject          = 'KRW 출금요청 인증메일입니다.';
            $email_type_country     = 'request_withdraw_email_cn';
        }else{
            $email_subject          = 'KRW Withdrawal Confirmation';
            $email_type_country     = 'request_withdraw_email_en';
        }
        $od_request     = number_format($param['od_temp_amount'], 0).' KRW';
        $od_total_fee   = number_format($param['od_fee'], 0).' KRW';

        $send_confirm_url   = "/returnemail/confirmwithdraw/type-emailconfirm/od_status-req/mk-".$mbemail_key['mk_no']."/po_type-krw/id-".$mb_en_id."/ekey-".$mb_email_key;
        $body = $this->getHTMLEmailBody($email_type_country,array(
            'name'=>$mb_info['mb_name'],
            'confirm_url'=>$send_confirm_url,
            'od_request'=>$od_request,
            'od_total_fee'=>$od_total_fee
        ));
        $mailresult = $this->mailer($mb_id, $email_subject, $body, 1, "", "", "");
        $sql_regist["mailsend"]=$mailresult;

        return $sql_regist;
    }


    // 출금요청 인증 -- 상태값 - WAIT => REQ로 변경요망
    private function _confirmWithdraw($param){

        if( !isset($param['id']) || !isset($param['ekey']) ){
            return array(
                "result"=>-5912,
                "success"=>false,
                "error"=>$this->res->lang->validator->required);
        }
        $mb_id = base64_decode($param['id']);
        $mb_email_key = $param['ekey'];

        //이메일 키 정보를 가져온다.
        $getinfo_mail_key = $this->execRemoteLists(
            $this->res->ctrl->database->web,
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
                "error"=>$this->res->lang->logincheck->referer);
        // 인증키 체크
        }else if( ($mbemail_key['mk_email_key'])!=$mb_email_key ){
            return array(
                "result" => -5914,
                "success"=>false,
                "error"=>$this->res->lang->logincheck->referer);
        // 유효시간 체크 -- 유효시간이 지났습니다. 재 인증 요청을 진행하세요.
        }else if( strtotime($mbemail_key['mk_expire_dt']) < time() ){
            return array(
                "result" => -5915,
                "success"=>false,
                "error"=>$this->res->lang->logincheck->expireDt);
        }

        // 출금요청 취소후 메일 인증할 경우를 대비한 체크
        $mb_getinfo_request_order = $this->execLists(
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

        $param['new_od_pay_status'] ='REQ';
        // 이상이 없다면 request_order 값 WAIT = > REQ로 업데이트 처리
        $request_order_update = $this->execUpdate(
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
        $mb_mail_key_update = $this->execRemoteUpdate(
            $this->res->ctrl->database->web,
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


    //합산
    private function _getBalanceSum($ac){
        $cookies = '';
        if (isset($_COOKIE)) {
            foreach ($_COOKIE as $name => $value) {
                $name = htmlspecialchars($name);
                $value = htmlspecialchars($value);
                $cookies .= "$name=$value; ";
            }
        }
        $opts = array(
            'http'=>array(
              'method'=>"GET",
              'header'=>"Accept-language: en\r\n" .
                        "Cookie: '.$cookies.'"
            )
        );
        $context = stream_context_create($opts);
        $json = file_get_contents($this->res->config['url']['site'].'/getbalance/server/cmd-sum/ac-'.$ac, false, $context);
        $balancedata = json_decode($json,TRUE);
        if(isset($balancedata['balance'])){
            return $balancedata['balance'];
        }
        return array();
    }

    private function getHTMLEmailBody($skin_html_file,$user=array('name'=>'','confirm_url'=>'', 'od_request'=>'', 'od_fee'=>'')){
        $logo_url = '/assets/img/common/logo_email.png';

        $html = file_get_contents('../WebApp/ViewHTML/'.$skin_html_file.'.html');
        $html = str_replace("{site_name}", $this->res->config['html']['title'],$html);
        $html = str_replace("{logo_url}", $this->res->config['url']['static'].$logo_url,$html);
        $html = str_replace("{user_name}", $user['name'],$html);
        $html = str_replace("{confirm_url}", $this->res->config['url']['site'].$user['confirm_url'],$html);
        $html = str_replace("{od_request}", $user['od_request'],$html);
        $html = str_replace("{od_total_fee}", $user['od_total_fee'],$html);
        //$html = str_replace("{requesttime}", $user['requesttime'],$html);
        
        return $html;
    }
    
    private function setLogFile($errorcode,$param){
        $log = new Logging();
        $log->lfile('../WebApp/Debug/WalletWithdrawException'.'.txt');
        $log->lwrite(__CLASS__ . "[".$errorcode."]:".' '.json_encode($param).' - '.Utils::getClientIP());
        $log->lclose();
    }
    
    private function balanceUpdateRetry($param, $mb_info){
        $resultCode = -1;
        
        for($i=0;$i<3;$i++){
            $result = $this->execUpdate(
                array(
                    'sql'=>$this->res->ctrl->sql_balance_update,
                    'mapkeys'=>$this->res->ctrl->mapkeys_balance_update
                ),
                array(
                    'po_on_etc'=>(float)$param['od_temp_amount'],
                    'mb_no'=>$mb_info['mb_no']
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
