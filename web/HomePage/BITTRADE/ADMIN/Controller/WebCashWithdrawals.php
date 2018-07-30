<?php
/**
* Description of WebCashWithdrawals Controller
* @description Bugnote PHP auto templet
* @date 2015-07-02
* @copyright (c)bugnote.net
* @license http://funhansoft.com/license
* @version 0.3.0
*/
        class WebCashWithdrawals extends ControllerBase{

            private $dao;
            private $dto;
            private $searchField;
            private $searchValue;
            private $returnarr = array('result'=>0);



            /**
            * @brief
            **/
            function __construct(){
                parent::__construct();
                $this->dto = new WebCashWithdrawalsDTO();
            }



            //DB를 연결할 경우만
            private function initDAO(){
                if(!$this->dao){
                    $this->dao = new WebCashWithdrawalsDAO();
                    $this->dao->setListLimitRow(15);
                    //$this->dao->setListOrderBy(array('odId'=>'DESC')); //정렬값
                }
            }
            
            public function balanceSum() {
            	$this->getViewer ()->setResponseType ( 'JSON' );
            	$mb_no = Utils::getUrlParam ( 'param' );
            	$this->dao = new WebPointDAO ();
            	$result = $this->dao->callBalanceSum ( $mb_no, 'admin' );
            
            	// 밸런스 메모리화로 인한 redis 값변경
            	$this->setBalanceUpdate ( $mb_no );
            }
            
            public function getCashBalance(){
            	$this->getViewer ()->setResponseType ( 'JSON' );
            	$mb_no = Utils::getUrlParam ( 'param' );
            	$this->dao = new WebCashDepositsDAO ();
            	$result = $this->dao->getBalance ( $mb_no );
            	return $result;
            }



            public function main(){
                $this->setSearchParam();
                $resultArray = array();
                $this->initDAO();
                $dtfrom = (isset($_GET['svdf']))?$_GET['svdf']:'';
                $dtto = (isset($_GET['svdt']))?$_GET['svdt']:'';
                $resultArray['data'] = $this->dao->getList($this->searchField,$this->searchValue,$dtfrom,$dtto);
                $resultArray['common'] = $this->dao->getListCount($this->searchField,$this->searchValue,$dtfrom,$dtto);
                $resultArray['link'] = parent::getLinkURL();
                return $resultArray;
            }



            public function view(){
                $resultArray = array();
                $resultArray['result'] = ResError::no;
                $resultArray['link'] = parent::getLinkURL();
                $pri = Utils::getUrlParam('id',1);
                
                $this->initDAO();
                $this->dto = $this->dao->getViewById($pri);
                $this->returnarr = $this->returnDTO($this->dto, array());

                if($this->dto->odId){
                    if($this->dto->odId!=$pri){
                        $resultArray['result'] = ResError::paramUnMatchPri;
                        $resultArray['resultMsg'] = ResString::paramUnMatchPri;
                    }
                }else{
                    $resultArray['result'] = 0;
                    $resultArray['resultMsg'] = ResString::dataNotResult;
                }

                $resultArray['link']['done'] = $resultArray['link']['list'];
                $resultArray['data'] = $this->returnarr;
                $resultArray['token'] = '';

                return $resultArray;
            }



            public function form(){
                $resultArray = array();
                $resultArray['result'] = ResError::no;
                $resultArray['link'] = parent::getLinkURL();
                $pri = Utils::getUrlParam('id',ResError::no);

                //수정모드
                if($pri) {
                    $this->initDAO();
                    $this->dto = $this->dao->getViewById($pri);
                    //Input Exception Field ,'odId','odName','odPayStatus','odPayStatusMsg','mbNo','mbId','isUserConfirmYn','isUserConfirmDt','isUserConfirmIp','isAdminConfirmYn','isAdminConfirmDt','odRequestBtc','odRequestUsd','odRequestKrw','odReceiptBtc','odReceiptUsd','odReceiptKrw','odTotalFee','odBankAccount','odBankName','odBankHolder','odBtcAccount','odBtcComment','odBtcSendto','odDelYn','odRegDt','odRegIp','partner'

                    // 회원 밸런스
                    $memberdao = new WebMemberDAO();
                    $memberdao->getBalanceSum($this->dto->mbNo);
                    $member = $memberdao->getViewById($this->dto->mbNo);
                    $this->dto->mbKrw = $member->mbKrw;
                    $this->dto->mbUsedKrw = $member->mbUsedKrw;
                    $this->dto->mbBtc = $member->mbBtc;
                    $this->dto->mbUsedBtc = $member->mbUsedBtc;

                    $this->returnarr = $this->returnDTO($this->dto, array());
                    if($this->dto->odId && $member->mbNo){
                        if($this->dto->odId!=$pri){
                            $resultArray['result'] = ResError::paramUnMatchPri;
                            $resultArray['resultMsg'] = ResString::paramUnMatchPri;
                        }
                        //수정권한이 있는지 체크
                    }else{
                        $resultArray['result'] = 0;
                        $resultArray['resultMsg'] = ResString::dataNotResult;
                    }

                //쓰기모드
                }else{
                    $resultArray['link']['update'] = str_replace("update", "insert", $resultArray['link']['update']);
                }
                //$resultArray['link']['done'] = $resultArray['link']['list']; //완료 후 리스트로 보낼경우
                $resultArray['data'] = $this->returnarr;
                $resultArray['token'] = parent::createTocken();

                return $resultArray;
            }



            /*
             * @brief 데이터 리스트
             * @return array object
             */
            public function lists(){
                $this->getViewer()->setResponseType('JSON');
                $this->setSearchParam();

                if(parent::checkReferer()<0){
                    return array();
                }

                $this->initDAO();
                $page = (int)Utils::getUrlParam('page',ResError::no);
                if($page){
                    if($page<1) $page=1;
                    $this->dao->setListLimitStart($this->dao->getListLimitRow() * ($page-1));
                }else{
                    $this->dao->setListLimitStart(0);
                }
                $dtfrom = (isset($_GET['svdf']))?$_GET['svdf']:'';
                $dtto = (isset($_GET['svdt']))?$_GET['svdt']:'';
                return $this->dao->getList($this->searchField,$this->searchValue,$dtfrom,$dtto);
            }



            /*
             * @brief 데이터 삽입
             * @return object
             */
            public function insert(){
                $this->getViewer()->setResponseType('JSON');
                
                //레퍼러 도메인 체크
                if(parent::checkReferer()<0){
                    return array();
                }

                try{
			$this->dto->odStatus = Utils::getPostParam('odStatus',ResError::no);
			$this->dto->odStatusMsg = Utils::getPostParam('odStatusMsg');
			$this->dto->mbNo = Utils::getPostParam('mbNo',ResError::no);
			$this->dto->mbNo = Utils::getPostParam('mkNo',ResError::no);
			$this->dto->mbNo = Utils::getPostParam('mbId',ResError::no);
			$this->dto->mbNo = Utils::getPostParam('odName',ResError::no);
			$this->dto->isUserConfirmYn = Utils::getPostParam('isUserConfirmYn',ResError::no);
			$this->dto->isUserConfirmDt = Utils::getPostParam('isUserConfirmDt');
			$this->dto->isUserConfirmIp = Utils::getPostParam('isUserConfirmIp');
			$this->dto->isAdminConfirmYn = Utils::getPostParam('isAdminConfirmYn',ResError::no);
			$this->dto->isAdminConfirmDt = Utils::getPostParam('isAdminConfirmDt');
			$this->dto->odTempAmount = Utils::getPostParam('odTempAmount');
			$this->dto->odReceiptAmount = Utils::getPostParam('odReceiptAmount');
                        $this->dto->odFee = Utils::getPostParam('odFee');
			$this->dto->odAddr = Utils::getPostParam('odBankAccount');
			$this->dto->odAddrMsg = Utils::getPostParam('odBankName');
			$this->dto->odSendto = Utils::getPostParam('odBankHolder');
			$this->dto->poPayYn = Utils::getPostParam('poPayYn',ResError::no);
			$this->dto->poPayDt = Utils::getPostParam('poPayDt');
                        $this->dto->poPayDt = Utils::getPostParam('odRegDt');
                        $this->dto->poPayDt = Utils::getPostParam('odRegCnt');
                        $this->dto->poPayDt = Utils::getPostParam('odRegIp');
			$this->dto->odEtc1 = Utils::getPostParam('odEtc1');
			$this->dto->odEtc2 = Utils::getPostParam('odEtc2');
			$this->dto->odEtc3 = Utils::getPostParam('odEtc3');
                    $this->returnarr = $this->returnDTO($this->dto, array('result','odId','odStatus','mbNo','isUserConfirmYn','isAdminConfirmYn','poPayYn','odRegDt'));
                }catch(Exception $e){
                    $json = json_decode($e);
                    $this->returnarr['result'] = (int)$json->code;
                    return $this->returnarr;
                }

                //param값 유효성 검사
                $this->returnarr['result'] = parent::checkEmptyValue($this->dto,array('result','odId','odStatus','mbNo','isUserConfirmYn','isAdminConfirmYn','poPayYn','odRegDt'));
                if($this->returnarr['result']<0){
                     return $this->returnarr;
                }
                
                //토큰 유효성 검사 - 마지막에
                $this->returnarr['result'] = parent::checkToken();
                if($this->returnarr['result']<0){
                     return $this->returnarr;
                }

                $this->initDAO();
                $this->returnarr['result'] = $this->dao->setInsert($this->dto);
                return $this->returnarr;
            }
            
            /*
             * @brief 데이터 수정
             * @return object
             */
            public function updateAdminConfirm(){
                $this->getViewer()->setResponseType('JSON');
                
                //레퍼러 도메인 체크
                if(parent::checkReferer()<0){
                    return array();
                }

                //고유값으로 값을 가져온다.
                 try{
                    $pri = (int)Utils::getPostParam('odId');
                }catch(Exception $e){
                    $json = json_decode($e);
                    $this->returnarr['result'] = (int)$json->code;
                    return $this->returnarr;
                }
                
                $this->initDAO();
                $this->dto = $this->dao->getViewById($pri);
                if($this->dto->isAdminConfirmYn=='Y'){
                    $this->returnarr['result'] = -99;
                    return $this->returnarr;
                }
                
                if($this->dto->isUserConfirmYn=='N'){
                    $this->returnarr['result'] = -98; //이메일 인증 미완료
                    return $this->returnarr;
                }
                
                
                $this->returnarr = $this->returnDTO($this->dto, array('result','odId','odStatus','mbNo','isUserConfirmYn','isAdminConfirmYn','poPayYn','odRegDt'));
                if($this->dto->odId){
                    if($this->dto->odId!=$pri){
                        $this->returnarr['result'] =  ResError::paramUnMatchPri;
                        return $this->returnarr;
                    }
                    
                    //수정권한이 있는지 체크
                    
                }else{
                    $this->returnarr['result'] = ResError::noResultById;
                    return $this->returnarr;
                }

                try{
                    $this->initModifyPostParam('odId');
                    $this->initModifyPostParam('odEtc1',ResError::no);
                    $this->initModifyPostParam('odEtc2',ResError::no);
                    $this->initModifyPostParam('odEtc3',ResError::no);
                }catch(Exception $e){
                    $json = json_decode($e);
                    $this->returnarr['result'] = (int)$json->code;
                    return $this->returnarr;
                }

                $this->returnarr['result'] = $this->dao->setUpdateAdminconfirm($this->dto);
                return $this->returnarr;
            }
            
            /*
             * @brief 데이터 수정
             * @return object
             */
            public function updateStatusChange(){
                $this->getViewer()->setResponseType('JSON');
                
                //레퍼러 도메인 체크
                if(parent::checkReferer()<0){
                    return array();
                }

                //고유값으로 값을 가져온다.
                 try{
                    $pri = (int)Utils::getPostParam('odId');
                }catch(Exception $e){
                    $json = json_decode($e);
                    $this->returnarr['result'] = (int)$json->code;
                    return $this->returnarr;
                }
                
                $this->initDAO();
                $this->dto = $this->dao->getViewById($pri);
                
//                if($this->dto->isUserConfirmYn=='N'){
//                    $this->returnarr['result'] = -98; //이메일 인증 미완료
//                    return $this->returnarr;
//                }
                
                
                $this->returnarr = $this->returnDTO($this->dto, array('result','odId','odStatus','mbNo','isUserConfirmYn','isAdminConfirmYn','poPayYn','odRegDt'));
                if($this->dto->odId){
                    if($this->dto->odId!=$pri){
                        $this->returnarr['result'] =  ResError::paramUnMatchPri;
                        return $this->returnarr;
                    }
                    
                    //수정권한이 있는지 체크
                    
                }else{
                    $this->returnarr['result'] = ResError::noResultById;
                    return $this->returnarr;
                }

                try{
                    $this->initModifyPostParam('odId');
                    $this->initModifyPostParam('odStatus',ResError::no);
                    $this->initModifyPostParam('odStatusMsg',ResError::no);
                }catch(Exception $e){
                    $json = json_decode($e);
                    $this->returnarr['result'] = (int)$json->code;
                    return $this->returnarr;
                }

                $this->returnarr['result'] = $this->dao->setUpdateStatus($this->dto);
                
                if(Utils::getPostParam('odStatus') == 'OK'){
                    $this->setMemberPoint();
                }
                //프로시저콜
                $pointdao = new WebPointDAO();
                $pointdao->callBalanceSum($this->dto->mbNo,'admwithdraw');
                // 밸런스 메모리화로 인한 redis 값변경
                $this->setBalanceUpdate($this->dto->mbNo);
                
                return $this->returnarr;
            }
            
            private function setMemberPoint(){
                //레퍼러 도메인 체크
                if(parent::checkReferer()<0){
                    return array();
                }
                
                $pointdao = new WebPointDAO();
                $point = new WebPointDTO();
                $pointdao->setTableName('web_point_krw');

                $point->mbNo = $this->dto->mbNo;
                $point->isTracker = 'N';
                $point->poContent = 'KRW '.$this->dto->odTempAmount.'원 출금 완료';
                $point->poPoint = "-".$this->dto->odTempAmount;
                $point->poPointSum = 0;
                $point->odTotalCost = 0;
                $point->odFee = 0;
                $point->poRelId = Utils::getPostParam('odId');
                $point->poRelAction = 'krwwithdraw';
                $point->poTradeDt = '0000-00-00';
                $point->poRegIp = Utils::getClientIP();
                
                // 데이터 중복검사
                $over = $pointdao->getViewByRelId($point->mbNo, $point->poRelId, null, null, $point->poRelAction);
                if($over && $over[0]->poNo > 0){
                    $result = -1;
                }else{
                    $result = $pointdao->setInsert($point);
                    if($result > 0){
                        $this->sendWithdrawKrwEmail();
                    }
                }

                return $result;
            }
            
            public function setBalanceUpdate($mb_no)
            {
                $loginstatus = 0;

                $mbsearchkey = '';
                $mbsearchkey = 'MB'.$mb_no;
                $session_expire = ((int)$this->config['session']['cache_expire'] * 60);

                //balance
                $pointdao = new WebPointDAO();
                $balancedata = $pointdao->setRedisBalance($mb_no);

                //balance update
                if($mbsearchkey){
                    $this->initRedisServer($this->config['redis']['host'],$this->config['redis']['port'], null,  $this->config['redis']['db_member'], null);
                    $balancekey = $mbsearchkey . '-balance';
                    $tmpbalancedata = $this->getRedisData($balancekey);

                    if(isset($tmpbalancedata)){
                        $jsonbalancedata = json_encode($balancedata);
                        if($tmpbalancedata != $jsonbalancedata){
                            $this->delRedisData($balancekey);
                            $this->setRedisData($balancekey,$jsonbalancedata,$session_expire);
                        }
                    }
                }

                return $balancedata;
            }
            
            function sendWithdrawKrwEmail(){
                if(!isset($this->dto->odId) || !$this->dto->odId){
                    return false;
                }

                $memberdao = new WebMemberDAO();
                $member = $memberdao->getViewById($this->dto->mbNo);
                if($member->mbNotifyWithdrawals=='N'){
                    return false;
                }
                $emaildao = new EmailDAO();
                $email_subject = '';
                $email_type_country = '';

                if($member->contryCode=='KR'){
                    $email_subject = 'KRW 출금완료 안내 메일입니다.';
                    $email_type_country = '../WebApp/ViewHTML/request_withdraw_complet_krw_email_kr.html';
                }else if($member->contryCode=='CN'){
                    $email_subject = 'KRW 출금완료 안내 메일입니다.';
                    $email_type_country = '../WebApp/ViewHTML/request_withdraw_complet_krw_email_cn.html';
                }else{
                    $email_subject = 'KRW Deposit Completed';
                    $email_type_country = '../WebApp/ViewHTML/request_withdraw_complet_krw_email_en.html';
                }

                $receipt_krw = $this->dto->odTempAmount - $this->dto->odFee;

                //$logo_url = '/assets/img/main-logo.png';

                $html = file_get_contents($email_type_country);
                $html = str_replace("{user_name}", $member->mbName,$html);
                //$html = str_replace("{logo_url}", $this->config['url']['static'].$logo_url,$html);
                $html = str_replace("{site_name}", $this->config['html']['email_title'],$html);
                $html = str_replace("{is_admin_confirm_dt}", date("Y-m-d H:i:s"),$html);
                $html = str_replace("{od_receipt_krw}", number_format($receipt_krw),$html);
                $html = str_replace("{od_total_fee}", number_format($this->dto->odFee),$html);
                $html = str_replace("{od_bank_name}", $this->dto->odBankName,$html);
                $html = str_replace("{od_bank_account}", $this->dto->odBankAccount,$html);
                $html = str_replace("{od_bank_holder}", $this->dto->odBankHolder,$html);
                $html = str_replace("{link_url}", $this->config['url']['site'].'/history/krwhistory/',$html);

                $mailresult = $emaildao->mailer($member->mbId, $email_subject, $html, 1, "", "", "");

                return array('result'=>$mailresult);
            }



            /*
             * @brief 데이터 수정
             * @return object
             */
            public function update(){
                $this->getViewer()->setResponseType('JSON');
                
                //레퍼러 도메인 체크
                if(parent::checkReferer()<0){
                    return array();
                }

                //고유값으로 값을 가져온다.
                 try{
                    $pri = (int)Utils::getPostParam('odId');
                }catch(Exception $e){
                    $json = json_decode($e);
                    $this->returnarr['result'] = (int)$json->code;
                    return $this->returnarr;
                }
                
                $this->initDAO();
                $this->dto = $this->dao->getViewById($pri);
                $this->returnarr = $this->returnDTO($this->dto, array('result','odId','odStatus','mbNo','isUserConfirmYn','isAdminConfirmYn','poPayYn','odRegDt'));
                if($this->dto->odId){
                    if($this->dto->odId!=$pri){
                        $this->returnarr['result'] =  ResError::paramUnMatchPri;
                        return $this->returnarr;
                    }
                    
                    //수정권한이 있는지 체크
                    
                }else{
                    $this->returnarr['result'] = ResError::noResultById;
                    return $this->returnarr;
                }

                try{
                    $this->initModifyPostParam('odId');
                    $this->initModifyPostParam('odStatus',ResError::no);
                    $this->initModifyPostParam('odStatusMsg');
                    $this->initModifyPostParam('mbNo',ResError::no);
                    $this->initModifyPostParam('mkNo',ResError::no);
                    $this->initModifyPostParam('mbId',ResError::no);
                    $this->initModifyPostParam('odName');
                    $this->initModifyPostParam('isUserConfirmYn',ResError::no);
                    $this->initModifyPostParam('isUserConfirmDt');
                    $this->initModifyPostParam('isUserConfirmIp');
                    $this->initModifyPostParam('isAdminConfirmYn',ResError::no);
                    $this->initModifyPostParam('isAdminConfirmDt');
                    $this->initModifyPostParam('odTempAmount');
                    $this->initModifyPostParam('odReceiptAmount');
                    $this->initModifyPostParam('odFee');
                    $this->initModifyPostParam('odBankAccount');
                    $this->initModifyPostParam('odBankName');
                    $this->initModifyPostParam('odBankHolder');
                    $this->initModifyPostParam('poPayYn',ResError::no);
                    $this->initModifyPostParam('poPayDt');
                    $this->initModifyPostParam('odEtc1');
                    $this->initModifyPostParam('odEtc2');
                    $this->initModifyPostParam('odEtc3');
                }catch(Exception $e){
                    $json = json_decode($e);
                    $this->returnarr['result'] = (int)$json->code;
                    return $this->returnarr;
                }

                //param값 유효성 검사
                $this->returnarr['result'] = parent::checkEmptyValue($this->dto,array('result','odId','odStatus','mbNo','isUserConfirmYn','isAdminConfirmYn','poPayYn','odRegDt'));
                if($this->returnarr['result']<0){
                     return $this->returnarr;
                }
                
                //토큰 유효성 검사 - 마지막에
                $this->returnarr['result'] = parent::checkToken();
                if($this->returnarr['result']<0){
                     return $this->returnarr;
                }

                $this->returnarr['result'] = $this->dao->setUpdate($this->dto);
                return $this->returnarr;
            }


            /*
             * @brief 수정된 내역이 있으면 파라미터 값으로 init
             */
            private function initModifyPostParam($keyId,$noerror=0){
                try{
                    if(Utils::getPostParam($keyId,$noerror)!==0 && Utils::getPostParam($keyId,$noerror)!=$this->dto->$keyId){
                        $this->dto->$keyId = Utils::getPostParam($keyId,$noerror);
                    }
                }catch(Exception $e){
                    throw new NotParam('POST "' . $keyId . '" not found. - '.  get_class() .':initModifyPostParam',ResError::paramEmptyPost);
                }
            }

            /*
             * @brief 데이터 삭제
             * @return int
             */
            public function delete(){
                $this->getViewer()->setResponseType('404'); //사용시 JSON으로
            }



            /*
             * @brief 검색 파라미터 초기화
             * @return null
             */
            private function setSearchParam(){
                $this->searchField = Utils::getUrlParam('sf',1);
                $this->searchValue = Utils::getUrlParam('sv',1);
                if($this->searchField=='od_id' || $this->searchField=='od_status' || $this->searchField=='mb_id' || $this->searchField=='mb_no'){

                }else{
                    $this->searchField = '';
                    $this->searchValue = '';
                }
            }



            function __destruct(){
                unset($this->dao);
                unset($this->dto);
            }



        }
