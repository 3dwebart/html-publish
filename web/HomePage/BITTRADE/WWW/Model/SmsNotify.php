<?php
class SmsNotify extends BaseModelBase{

    function __construct($dbconfname=null) {
        parent::__construct($dbconfname);

    }
    /*
     * 실행
     * @param url param
     */
    public function getExecute($param){

        // 중복 방지 토큰 체크
        $token_result = parent::checkToken(false);
        if($token_result<0){
            return array(
                "result" => $token_result,
                "success"=>false,
                "error"=>$this->res->lang->validator->tokenTime);
        }

        if( !$param ){
            return array(
                "result" => -7001,
                "success"=>false,
                "error"=>$this->res->lang->validator->required);
        }
        if( !isset($param['result']) || !isset($param['action']) ){
            return array(
                "result" => -7002,
                "success"=>false,
                "error"=>$this->res->lang->logincheck->validator);
        }

        // sms 입력 기본 정보
        $sms = array('sms_provider'=>$this->res->config['sms']['sms_provider']);

        if($sms['sms_provider']==''){
            return false;
        }
        if($param['action']=='depositkrw'){
            // 입금요청 krw
            return $this->_depositKrw($param, $sms);
        }else if($param['action']=='withdrawkrw' || $param['action']=='withdrawbtc' || $param['action']=='sendtobtc'){
            // 출금요청 krw, btc
            return $this->_withdraw($param, $sms);
        }

        return array('result'=>-1);
    }

    private function _depositKrw($param, $sms){

        // 입금 요청 정보
        $deposit = $this->execLists(
            array(
                'sql'=>$this->res->ctrl->sql_depositkrw,
                'mapkeys'=>$this->res->ctrl->mapkeys_depositkrw,
                'rowlimit'=>1
            )
            , array('od_id'=>$param['result'])
        );

        if( !isset($deposit) || !isset($deposit[0]['od_id']) ){
            return false;
        }else{
            $deposit = $deposit[0];
        }

        // 입금 완료수 정보
        $deposit_complete_cnt = $this->execLists(
            array(
                'sql'=>$this->res->ctrl->sql_depositkrw_cnt,
                'mapkeys'=>$this->res->ctrl->mapkeys_depositkrw_cnt,
                'rowlimit'=>1
            )
            , array('mb_no'=>$deposit['mb_no'])
        );

        if( !isset($deposit_complete_cnt) || !isset($deposit_complete_cnt[0]['cnt']) ){
            return false;
        }else{
            $deposit_complete_cnt = $deposit_complete_cnt[0];
        }

        // 입금요청 SMS 알림 - 관리자 데이터
        $sms_notify = $this->execLists(
            array(
                'sql'=>$this->res->ctrl->sql_sms_notify_deposit,
                'mapkeys'=>$this->res->ctrl->mapkeys_sms_notify_deposit,
                'rowlimit'=>1
            )
            , array()
        );

        if(count($sms_notify)<=0){
            return false;
        }

        // sms 데이터 가공
        $member_deposit_krw_cnt = (int)$deposit_complete_cnt['cnt'] + 1;

        $sms_msg = '[입금요청] '.$deposit['od_name'].'님이 '.number_format($deposit['od_temp_bank']).'KRW 입금요청 '.number_format($member_deposit_krw_cnt).'번째 입금완료 고객';
        for($i=0; $i<count($sms_notify); $i++){
            // SMS 수신자 국내, 해외 여부
            // M : MMS, S : SMS, I : 국제문자, L : 국제 MMS
            $sms_notify[$i]['sn_country_dial_code'] = str_replace('+', '', $sms_notify[$i]['sn_country_dial_code']);
            $sms_notify[$i]['sn_country_dial_code'] = str_replace(' ', '', $sms_notify[$i]['sn_country_dial_code']);

            // 모바일 번호 0 붙이기
            if(substr($sms_notify[$i]['sn_hp'], 0, 1)!='0'){
                $sms_notify[$i]['sn_hp'] = '0'.$sms_notify[$i]['sn_hp'];
            }

            // sms 전송 처리 (web_sms_seder 로 삽입, 테이블 트리거로 provider에 따라 분기 처리)
            $sms_sender_result = $this->execUpdate(
                array(
                    'sql'=>$this->res->ctrl->sql_insert_sms_sender,
                    'mapkeys'=>$this->res->ctrl->mapkeys_insert_sms_sender,
                    'rowlimit'=>1
                )
                , array(
                    'mb_id'=>$sms_notify[$i]['sn_name']
                    ,'ss_is_mms'=>'N'
                    ,'ss_is_inter'=>''
                    ,'ss_tel_code'=>$sms_notify[$i]['sn_country_dial_code']
                    ,'ss_tel_num'=>$sms_notify[$i]['sn_hp']
                    ,'ss_content'=>$sms_msg
                    ,'ss_page_type'=>'NOTIFY'
                    ,'ss_provider'=>$sms['sms_provider']
                )
            );
        }
    }

    // 출금요청
    private function _withdraw($param, $sms){
        // 출금 요청 정보
        $withdraw = $this->execLists(
            array(
                'sql'=>$this->res->ctrl->sql_withdrawkrw,
                'mapkeys'=>$this->res->ctrl->mapkeys_withdrawkrw,
                'rowlimit'=>1
            )
            , array('od_id'=>$param['result'])
        );
        if( !isset($withdraw) || !isset($withdraw[0]['od_id']) ){
            return false;
        }else{
            $withdraw = $withdraw[0];
        }

        // 출금 완료수 정보
        $withdraw_complete_cnt = $this->execLists(
            array(
                'sql'=>$this->res->ctrl->sql_withdrawkrw_cnt,
                'mapkeys'=>$this->res->ctrl->mapkeys_withdrawkrw_cnt,
                'rowlimit'=>1
            )
            , array('mb_no'=>$withdraw['mb_no'],
                'od_action'=>$param['action'])
        );

        if( !isset($withdraw_complete_cnt) || !isset($withdraw_complete_cnt[0]['cnt']) ){
            return false;
        }else{
            $withdraw_complete_cnt = $withdraw_complete_cnt[0];
        }

        // 출금요청 SMS 알림 - 관리자 데이터
        $sms_notify = $this->execLists(
            array(
                'sql'=>$this->res->ctrl->sql_sms_notify_withdraw,
                'mapkeys'=>$this->res->ctrl->mapkeys_sms_notify_withdraw,
                'rowlimit'=>1
            )
            , array()
        );

        if(count($sms_notify)<=0){
            return false;
        }

        // sms 데이터 가공
        $member_withdraw_cnt = (int)$withdraw_complete_cnt['cnt'] + 1;

        $sms_smg = '';
        if($param['action']=='withdrawkrw'){
            $sms_msg = '[출금요청] '.$withdraw['od_name'].'님이 '.number_format($withdraw['od_request_krw']).'KRW 출금요청 '.number_format($member_withdraw_cnt).'번째 출금 고객';
        }else if($param['action']=='withdrawbtc'){
            $sms_msg = '[출금요청] '.$withdraw['od_name'].'님이 '.$withdraw['od_request_coin'].'코인 출금요청 '.number_format($member_withdraw_cnt).'번째 출금 고객';
        }else if($param['action']=='sendtobtc'){
            $sms_msg = '[출금요청] '.$withdraw['od_name'].'님이 '.$withdraw['od_request_coin'].'회원에게전송요청 '.number_format($member_withdraw_cnt).'번째 회원에게전송 요청 고객';
        }

        for($i=0; $i<count($sms_notify); $i++){
            // SMS 수신자 국내, 해외 여부
            // M : MMS, S : SMS, I : 국제문자, L : 국제 MMS
            $sms_notify[$i]['sn_country_dial_code'] = str_replace('+', '', $sms_notify[$i]['sn_country_dial_code']);
            $sms_notify[$i]['sn_country_dial_code'] = str_replace(' ', '', $sms_notify[$i]['sn_country_dial_code']);

            // 모바일 번호 0 붙이기
            if(substr($sms_notify[$i]['sn_hp'], 0, 1)!='0'){
                $sms_notify[$i]['sn_hp'] = '0'.$sms_notify[$i]['sn_hp'];
            }

            // sms 전송 처리 (web_sms_seder 로 삽입, 테이블 트리거로 provider에 따라 분기 처리)
            $sms_sender_result = $this->execUpdate(
                array(
                    'sql'=>$this->res->ctrl->sql_insert_sms_sender,
                    'mapkeys'=>$this->res->ctrl->mapkeys_insert_sms_sender,
                    'rowlimit'=>1
                )
                , array(
                    'mb_id'=>$sms_notify[$i]['sn_name']
                    ,'ss_is_mms'=>'N'
                    ,'ss_is_inter'=>''
                    ,'ss_tel_code'=>$sms_notify[$i]['sn_country_dial_code']
                    ,'ss_tel_num'=>$sms_notify[$i]['sn_hp']
                    ,'ss_content'=>$sms_msg
                    ,'ss_page_type'=>'NOTIFY'
                    ,'ss_provider'=>$sms['sms_provider']
                )
            );
        }
    }

    function __destruct(){

    }



}
