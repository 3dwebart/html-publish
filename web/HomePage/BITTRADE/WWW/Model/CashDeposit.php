<?php
class CashDeposit extends BaseModelBase{

    private $member;

    function __construct($dbconfname=null) {
        parent::__construct($dbconfname);

    }
    /*
     * 실행
     * @param url param
     */
    public function getExecute($param){


        // parameter check
        if(!$param){
            return array(
                "result"=>-6001,
                "success"=>false,
                "error"=>$this->res->lang->validator->required);
        }
        
        if( !isset($param['od_name']) || !isset($param['od_temp_bank']) ||
            !isset($param['od_bank_name']) || !isset($param['od_bank_owner']) || !isset($param['od_bank_account'])){
            return array(
                "result" => -6001,
                "success"=>false,
                "error"=>$this->res->lang->validator->required);
        }
        
        /***************************************
        // 회원정보
        ***************************************/
        $member = json_decode($this->getMemberDataFromRedis(),TRUE); 
        
        if (!$member || !$member['mb_no'] || !isset($member['mb_no'])) {
            return array(
                "result" => -6002,
                "success" => false,
                "error" => $this->res->lang->logincheck->notLogin);
        }
        

        // 입금 제한금액 조회
        // 입금 제한금액 최소 금액 - config
        $deposit_krw_min_limit = 10000;
        if(isset($this->res->config['trade']['deposit_krw_min_limit'])){
            $deposit_krw_min_limit = (int)$this->res->config['trade']['deposit_krw_min_limit'];
        }

        if((int)$param['od_temp_bank'] < $deposit_krw_min_limit){
            return array(
                "result" => -6003,
                "success" => false,
                "error" => $this->res->lang->trade->depositminimumkrw);
        }

        // 입금제한 금액 디폴트값
        $deposit_krw_max_limit = 0;
        $deposit_krw_max_day   = 1;    // 기본 1일

        $deposit_limit = $this->execRemoteLists(
            $this->res->ctrl->database->wallet,
            array(
                'sql'=>$this->res->ctrl->sql_depositlimit,
                'mapkeys'=>$this->res->ctrl->mapkeys_depositlimit,
                'rowlimit'=>1
            )
            , array(
                "countrycode"=>"kr"
                ,"wallettype"=>"CASH"
                ,"mblevel"=>$member['mb_level']
            )
        );

        if(isset($deposit_limit[0]) ){
            $deposit_limit = $deposit_limit[0];
        }

        // 입금 최대 제한금액 데이터 체크
        if(isset($deposit_limit['result']) && $deposit_limit['result']>0 && isset($deposit_limit['cf_max_deposit']) && isset($deposit_limit['cf_max_day'])){
            // unlimited, -1 체크
            if(isset($deposit_limit['cf_max_deposit']) && $deposit_limit['cf_max_deposit']!='unlimited' && $deposit_limit['cf_max_deposit']>0){
                $deposit_krw_max_limit  = $deposit_limit['cf_max_deposit'];
                $deposit_krw_max_day    = $deposit_limit['cf_max_day'];
            }

            // 입금 완료 기준일 -1 처리
            if($deposit_krw_max_day>0){
                $deposit_krw_max_day = (int)$deposit_krw_max_day-1;
            }

            // 입금 완료 금액 조회
            $deposit_sum_krw = $this->execLists(
                array(
                    'sql'=>$this->res->ctrl->sql_depositsumkrw,
                    'mapkeys'=>$this->res->ctrl->mapkeys_depositsumkrw,
                    'rowlimit'=>1
                )
                , array(
                    "day"=>$deposit_krw_max_day
                )
            );

            if(!isset($deposit_sum_krw) || !isset($deposit_sum_krw[0]['result']) || $deposit_sum_krw[0]['result']<1 || !isset($deposit_sum_krw[0]['sum_krw']) ){
                return array(
                    "result" => -6004,
                    "success"=>false,
                    "error"=>$this->res->lang->logincheck->referer);
            }

            if(isset($deposit_sum_krw[0]) ){
                $deposit_sum_krw = $deposit_sum_krw[0];
            }

            // 입금 한도 체크 - (입금 한도-(입금한금액+입금요청금액))
            if( ($deposit_krw_max_limit - ($deposit_sum_krw['sum_krw']+$param['od_temp_bank']) )<0 && $deposit_limit['cf_max_deposit'] > 0 ){
                return array(
                    "result" => -6005,
                    "success"=>false,
                    "error"=>$this->res->lang->trade->depositmaximumkrw);
            }
        }

        // 입금 요청 순번
        $deposit_cnt = $this->execLists(
            array(
                'sql'=>$this->res->ctrl->sql_depositcnt,
                'mapkeys'=>$this->res->ctrl->mapkeys_depositcnt,
                'rowlimit'=>1
            )
            , array()
        );

        // 기본값 처리
        $deposit_od_cnt = 1;
        if(isset($deposit_cnt[0]) || isset($deposit_cnt[0]['od_cnt'])){
            $deposit_od_cnt = $deposit_cnt[0]['od_cnt'] +1;
        }

        // 입금 데이터 삽입
        $deposit_regist = $this->execUpdate(
            array(
                'sql'=>$this->res->ctrl->sql_regist,
                'mapkeys'=>$this->res->ctrl->mapkeys_regist,
                'rowlimit'=>1
            )
            , array(
                'it_id'=>$param['it_id']
                ,'it_name'=>$param['it_name']
                ,'od_name'=>$member['mb_name']
                ,'od_email'=>$member['mb_id']
                ,'od_hp'=>$member['mb_hp']
                ,'od_temp_bank'=>$param['od_temp_bank']
                ,'od_deposit_name'=>$param['od_name']
                ,'od_bank_account'=>$param['od_bank_name'].' / '.$param['od_bank_account']
                ,'od_shop_memo'=>$param['od_shop_memo']
                ,'od_settle_case'=>$param['od_settle_case']
                ,'od_cnt'=>$deposit_od_cnt
            )
        );

        if( !isset($deposit_regist) || !isset($deposit_regist['result']) || $deposit_regist['result']<1){
            return array(
                "result"=>-6006,
                "success"=>false,
                "error"=>$this->res->lang->validator->required);
        }
        return array('result'=>(int)$deposit_regist['result']); // idx로 변경
        }


        
    

    //주문 완료 후 새로고침 페이지라면 아래 호출하여 잔액 동기화
    private function _getBalanceData(){
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
        $balancedata = file_get_contents($this->res->config['url']['site'].'/webmember/getbalance', false, $context);
        return $balancedata;
    }



    function __destruct() {

    }



}
