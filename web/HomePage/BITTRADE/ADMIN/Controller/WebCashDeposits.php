<?php
/**
* Description of WebCashDeposits Controller
* @description Bugnote PHP auto templet
* @date 2015-09-05
* @copyright (c)funhansoft.com
* @license http://funhansoft.com/license
* @version 0.2.1
*/
        class WebCashDeposits extends ControllerBase{

            private $dao;
            private $dto;
            private $searchField;
            private $searchValue;
            private $modifyHistory;
            private $returnarr = array('result'=>0);

            /**
            * @brief
            **/
            function __construct(){
                parent::__construct();
                $this->dto = new WebCashDepositsDTO();
            }

            //DB를 연결할 경우만
            private function initDAO(){
                if(!$this->dao){
                    $this->dao = new WebCashDepositsDAO();
                    $this->dao->setListLimitRow(15);
                    //$this->dao->setListOrderBy(array('poNo'=>'DESC')); //정렬값
                }
            }

            public function main(){
                $this->setSearchParam();
                $this->initDAO();
                $dtfrom = (isset($_GET['svdf']))?$_GET['svdf']:'';
                $dtto = (isset($_GET['svdt']))?$_GET['svdt']:'';
                $resultArray = array();
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
                $resultArray['data'] = $this->dto;
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

                    if($this->dto->odId){
                        if($this->dto->odId!=$pri){
                            $resultArray['result'] = ResError::paramUnMatchPri;
                            $resultArray['resultMsg'] = ResString::paramUnMatchPri;
                        }
                    }else{
                        $resultArray['result'] = 0;
                        $resultArray['resultMsg'] = ResString::dataNotResult;
                    }

                //쓰기모드
                }else{
                    $resultArray['link']['update'] = str_replace("update", "insert", $resultArray['link']['update']);
                }
                $resultArray['data'] = $this->dto;
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
                try{
                    $this->dto->odId = Utils::getPostParam('odId',ResError::no);
                    $this->dto->odPgId = Utils::getPostParam('odPgId',ResError::no);
                    $this->dto->itId = (int)Utils::getPostParam('itId',ResError::no);
                    $this->dto->itIdPay = Utils::getPostParam('itIdPay',ResError::no);
                    $this->dto->mbNo = Utils::getPostParam('mbNo');
                    $this->dto->mbId = Utils::getPostParam('mbId');
                    $this->dto->odPwd = '';
                    $this->dto->odName = Utils::getPostParam('odName');
                    $this->dto->odEmail = Utils::getPostParam('odEmail');
                    $this->dto->odTel = Utils::getPostParam('odTel');
                    $this->dto->odHp = Utils::getPostParam('odHp');
                    $this->dto->odMemo = ''.Utils::getPostParam('odMemo');
                    $this->dto->odTempBank = Utils::getPostParam('odTempBank',ResError::no);
                    $this->dto->odTempCard = Utils::getPostParam('odTempCard',ResError::no);
                    $this->dto->odTempMobile = Utils::getPostParam('odTempMobile',ResError::no);
                    $this->dto->odTempArs = Utils::getPostParam('odTempArs',ResError::no);
                    $this->dto->odTempPhonebill = Utils::getPostParam('odTempPhonebill',ResError::no);
                    $this->dto->odReceiptBank = Utils::getPostParam('odReceiptBank',ResError::no);
                    $this->dto->odReceiptCard = Utils::getPostParam('odReceiptCard',ResError::no);
                    $this->dto->odReceiptMobile = Utils::getPostParam('odReceiptMobile',ResError::no);
                    $this->dto->odReceiptArs = Utils::getPostParam('odReceiptArs',ResError::no);
                    $this->dto->odReceiptPhonebill = Utils::getPostParam('odReceiptPhonebill',ResError::no);
                    $this->dto->odBankTime = Utils::getPostParam('odBankTime');
                    $this->dto->odCardTime = Utils::getPostParam('odCardTime');
                    $this->dto->odPayTime = Utils::getPostParam('odPayTime');
                    $this->dto->odCancelCard = (int)Utils::getPostParam('odCancelCard');
                    $this->dto->odDcAmount = (int)Utils::getPostParam('odDcAmount',ResError::no);
                    $this->dto->odRefundAmount = (int)Utils::getPostParam('odRefundAmount');
                    $this->dto->odRefundDt = Utils::getPostParam('odRefundDt');
                    $this->dto->odShopMemo = '입금주문서 입력';
                    $this->dto->odModifyHistory = '';
                    $this->dto->odDepositName = Utils::getPostParam('odDepositName');
                    $this->dto->odBankAccount = Utils::getPostParam('odBankAccount');
                    $this->dto->odHopeDate = Utils::getPostParam('odHopeDate',ResError::no);
                    $this->dto->odDatetime = Utils::getDateNow();
                    $this->dto->odIp = Utils::getClientIP();
                    $this->dto->odSettleCase = Utils::getPostParam('odSettleCase');
                    $this->dto->odDelYn = 'N';
                    $this->dto->odScno = Utils::getPostParam('odScno',ResError::no);
                    $this->dto->odEtc = Utils::getPostParam('odEtc',ResError::no);
                    $this->dto->odEscrow1 = Utils::getPostParam('odEscrow1',ResError::no);
                    $this->dto->odEscrow2 = Utils::getPostParam('odEscrow2',ResError::no);
                    $this->dto->odEscrow3 = Utils::getPostParam('odEscrow3',ResError::no);
                    $this->dto->odProcDb = Utils::getPostParam('odProcDb',ResError::no);
                    $this->dto->partner = Utils::getPostParam('partner');

                    $odItemPut = Utils::getPostParam('odItemPut',ResError::no);

                }catch(Exception $e){
                    $json = json_decode($e);
                    $this->dto->result = (int)$json->code;
                    return $this->dto;
                }

                //param값 유효성 검사
                $this->dto->result = parent::checkValue($this->dto,
                        array('itId','mbNo','mbId','odName','odSettleCase'));
                if($this->dto->result<0){
                     return $this->dto;
                }

                //토큰 유효성 검사
                $this->dto->result = parent::checkToken();
                if($this->dto->result<0){
                     return $this->dto;
                }

                //레퍼러 도메인 체크
                $this->dto->result = parent::checkReferer();
                if($this->dto->result<0){
                     return $this->dto;
                }

                //포인트 지급 여부 검사 - 입금정보 입력과동시에 완료시
                if($odItemPut=='Y'){
                    $this->setMemberPoint();
                }

                $this->initDAO();
                $this->dto->result = $this->dao->setInsert($this->dto);
                return $this->dto;

            }

            /*
             * @brief 데이터 수정
             * @return object
             */
            public function update(){
                $this->getViewer()->setResponseType('JSON');

                //고유값으로 값을 가져온다.
                 try{
                    $pri = (int)Utils::getPostParam('odId');
                }catch(Exception $e){
                    $json = json_decode($e);
                    $this->dto->result = (int)$json->code;
                    return $this->dto;
                }

                $this->initDAO();
                $this->dto = $this->dao->getViewById($pri);
                if($this->dto->odId){
                    if($this->dto->odId!=$pri){
                        $this->dto->result =  ResError::paramUnMatchPri;
                        return $this->dto;
                    }
                }else{
                    $this->dto->result = ResError::noResultById;
                    return $this->dto;
                }

                $this->modifyHistory ='';

                    $this->initModifyPostParam('odPgId','PGID');
                    $this->initModifyPostParam('itId','상품코드');
                    $this->initModifyPostParam('itIdPay','아이템지급');
                    $this->initModifyPostParam('odName','주문자명');
                    $this->initModifyPostParam('odEmail','주문자이메일');
                    $this->initModifyPostParam('odTel','주문자전화번호');
                    $this->initModifyPostParam('odHp','주문자모바일번호');
                    $this->initModifyPostParam('odMemo','주문시요청사항');
                    $this->initModifyPostParam('odTempBank','무통장입금주문액');
                    $this->initModifyPostParam('odTempCard','카드주문액');
                    $this->initModifyPostParam('odTempMobile','모바일결재액');
                    $this->initModifyPostParam('odReceiptBank','무통장입금주문액');
                    $this->initModifyPostParam('odReceiptCard','카드입금');
                    $this->initModifyPostParam('odReceiptMobile','모바일입금');
                    $this->initModifyPostParam('odReceiptArs','ARS입금');
                    $this->initModifyPostParam('odReceiptPhonebill','폰빌입금');
                    $this->initModifyPostParam('odBankTime','입금시간');
                    $this->initModifyPostParam('odCardTime','카드승인');
                    $this->initModifyPostParam('odPayTime','승인시간');
                    $this->initModifyPostParam('odCancelCard','카드취소');
//                    $this->initModifyPostParam('odDcAmount','DC금액');
                    $this->initModifyPostParam('odRefundAmount','직접환불금액');
                    $this->initModifyPostParam('odRefundDt','직접환불일시');
                    $this->initModifyPostParam('odShopMemo','관리자메모');
                    $this->initModifyPostParam('odDepositName','입금자명');
                    $this->initModifyPostParam('odBankAccount','입금될계좌');
                    $this->initModifyPostParam('odHopeDate','입금예정일');
                    $this->initModifyPostParam('odSettleCase','결제방법');
                    $this->initModifyPostParam('odDelYn','삭제');
                    $this->initModifyPostParam('odScno','주민번호보안키');
                    $this->initModifyPostParam('odEscrow1','기타1');
                    $this->initModifyPostParam('odEscrow2','기타2');
                    $this->initModifyPostParam('odEscrow3','기타3');
                    $this->initModifyPostParam('odProcDb','완료DB실행');
                    $this->initModifyPostParam('partner','파트너코드');

                    //무통장 입금시 아이템 지급
                    $odItemPut = Utils::getPostParam('odItemPut',ResError::no);

                if($this->modifyHistory){
                    $this->dto->odModifyHistory = $this->dto->odModifyHistory . $this->modifyHistory = '<br />['.Utils::getDateNow().'] '.Session::getSession('admin_mb_id').$this->modifyHistory.'<br />';
                }

                //param값 유효성 검사
                $this->dto->result = parent::checkValue($this->dto,
                        array('itId','mbNo','mbId','odName','odSettleCase'));
                if($this->dto->result<0){
                     return $this->dto;
                }

                //토큰 유효성
                $this->dto->result = parent::checkToken();
                if($this->dto->result<0){
                     return $this->dto;
                }

                //레퍼러 도메인 체크
                $this->dto->result = parent::checkReferer();
                if($this->dto->result<0){
                     return $this->dto;
                }

                $this->dto->result = $this->dao->setUpdate($this->dto);

                //아이템 지급
                if($odItemPut=='Y'){
                    $this->setMemberPoint();
                    //$this->callMemberBalanceSum();
                }

                return $this->dto;
            }



            private function setMemberPoint(){
                //레퍼러 도메인 체크
                if(parent::checkReferer()<0){
                    return array();
                }
                
                $pointdao = new WebPointDAO();
                $point = new WebPointDTO();
                $pointdao->setTableName('web_point_krw');

                $point->mbNo = Utils::getPostParam('mbNo');
                $point->isTracker = 'N';
                $point->poContent = 'KRW '.Utils::getPostParam('odTempBank').'원 무통장입금 완료';
                $point->poPoint = Utils::getPostParam('odTempBank');
                $point->poPointSum = 0;
                $point->odTotalCost = 0;
                $point->odFee = 0;
                $point->poRelId = Utils::getPostParam('odId');
                $point->poRelAction = 'krwdeposit';
                $point->poTradeDt = '0000-00-00';
                $point->poRegIp = Utils::getClientIP();
                
                // 데이터 중복검사
                $over = $pointdao->getViewByRelId($point->mbNo, $point->poRelId, null, null, $point->poRelAction);
                if($over && $over[0]->poNo > 0){
                    $result = -1;
                }else{
                    $result = $pointdao->setInsert($point);
                    if($result > 0){
                        //프로시저콜
                        $pointdao->callBalanceSum($point->mbNo,'admdeposit');
                        // 밸런스 메모리화로 인한 redis 값변경
                        $this->setBalanceUpdate($point->mbNo);
                        $this->sendDepositKrwEmail();
                    }
                }

                return $result;
            }

            private function callMemberBalanceSum(){
                if(!isset($this->dto->mbNo) || !$this->dto->mbNo){
                    return false;
                }
                $memberdao = new WebMemberDAO();
                $result = $memberdao->getBalanceSum($this->dto->mbNo);
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



            function sendDepositKrwEmail(){
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
                    $email_subject = 'KRW 충전완료 안내 메일입니다.';
                    $email_type_country = '../WebApp/ViewHTML/request_deposit_complet_krw_email_kr.html';
                }else if($member->contryCode=='CN'){
                    $email_subject = 'KRW 충전완료 안내 메일입니다.';
                    $email_type_country = '../WebApp/ViewHTML/request_deposit_complet_krw_email_cn.html';
                }else{
                    $email_subject = 'KRW Deposit Completed';
                    $email_type_country = '../WebApp/ViewHTML/request_deposit_complet_krw_email_en.html';
                }

                //$logo_url = '/assets/img/main-logo.png';

                $html = file_get_contents($email_type_country);
                $html = str_replace("{user_name}", $member->mbName,$html);
                //$html = str_replace("{logo_url}", $this->config['url']['static'].$logo_url,$html);
                $html = str_replace("{site_name}", $this->config['html']['email_title'],$html);
                $html = str_replace("{od_bank_time}", $this->dto->odBankTime,$html);
                $html = str_replace("{od_receipt_bank}", number_format($this->dto->odReceiptBank),$html);
                $html = str_replace("{link_url}", $this->config['url']['site'].'/history/krwhistory/',$html);

                $mailresult = $emaildao->mailer($member->mbId, $email_subject, $html, 1, "", "", "");

                return array('result'=>$mailresult);
            }



            private function _redisNotifyData($mb_id){
                //redis에 값 다시 삽입확인
//                $memberdao = new WebMemberDAO();
//                $memberdao->getViewById($this->dto->mbNo);
                $this->initRedisServer($this->config['redis']['host'],$this->config['redis']['port'], null,  $this->config['redis']['db_member_noti'], null);
                $data = $this->getRedisData($mb_id);
                if($data){
                    $this->delRedisData($mb_id);
                }
                $this->setRedisData($mb_id,"balance", 3600);
            }



            public function downloadExcel(){

                if(parent::checkReferer()<0){
                    return array();
                }

                $page = (int)Utils::getUrlParam('page',ResError::no);
                $limit = (int)Utils::getUrlParam('limit',ResError::no);

                $this->setSearchParam();
                $this->initDAO();
                if($limit){
                    if($limit>1000) $limit = 1000;
                }else{
                    $limit = 1000;
                }
                $this->dao->setListLimitRow($limit);

                if($page){
                    if($page<1) $page=1;
                    $this->dao->setListLimitStart($this->dao->getListLimitRow() * ($page-1));
                }else{
                    $this->dao->setListLimitStart(0);
                    $page = 1;
                }

                $title = " 원화입금주문내역#".$page;
                $objPHPExcel = new PHPExcel();

                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A1', '주문ID')
                            ->setCellValue('B1', '결제방법')
                            ->setCellValue('C1', '회원번호')
                            ->setCellValue('D1', '회원아이디')
                            ->setCellValue('E1', '주문자명')
                            ->setCellValue('F1', '주문자이메일')
                            ->setCellValue('G1', '주문자모바일번호')
                            ->setCellValue('H1', '포인트지급여부')
                            ->setCellValue('I1', '입금될계좌')
                            ->setCellValue('J1', '입금자명')
                            ->setCellValue('K1', '입금예정일')
                            ->setCellValue('L1', '무통장주문액')
                            ->setCellValue('M1', '무통장입금금액')
                            ->setCellValue('N1', '무통장입금시간')
                            ->setCellValue('O1', '환불일시')
                            ->setCellValue('P1', '환불금액')
                            ->setCellValue('Q1', '주문시간')
                            ->setCellValue('R1', '등록IP')
                            ->setCellValue('S1', '삭제여부')
                            ->setCellValue('T1', '관리자메모')
                            ->setCellValue('U1', '수정내역');

                $objPHPExcel->setActiveSheetIndex(0)->getStyle('A1:U1')->getFont()->setBold(true);
                $objPHPExcel->getActiveSheet()->getRowDimension(1)->setRowHeight(15);
                $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(24.5);    //아이디
                $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(24.5);    //주문자이메일
                $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(18);      //주문자모바일번호
                $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(24.5);    //입금될 계좌
                $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(15);      //무통장주문액
                $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(15);      //무통장입금금액
                $objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(18);      //무통장입금시간
                $objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(18);      //환불일시
                $objPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth(15);      //환불금액
                $objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setWidth(15);      //주문시간
                $objPHPExcel->getActiveSheet()->getColumnDimension('T')->setWidth(30);      //관리자메모
                $objPHPExcel->getActiveSheet()->getColumnDimension('U')->setWidth(30);      //수정내역

                $n = 0;
                $dtfrom = (isset($_GET['svdf']))?$_GET['svdf']:'';
                $dtto = (isset($_GET['svdt']))?$_GET['svdt']:'';
                $dtoarray = $this->dao->getList($this->searchField,$this->searchValue,$dtfrom,$dtto);
                for($i=0;$i<count($dtoarray);$i++){
                    $n = $i+2;

                    $odModifyHistory = str_replace('<br />', '  ', $dtoarray[$i]->odModifyHistory);

                    $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A'.$n, $dtoarray[$i]->odId)
                            ->setCellValue('B'.$n, $dtoarray[$i]->odSettleCase)
                            ->setCellValue('C'.$n, $dtoarray[$i]->mbNo)
                            ->setCellValue('D'.$n, $dtoarray[$i]->mbId)
                            ->setCellValue('E'.$n, $dtoarray[$i]->odName)
                            ->setCellValue('F'.$n, $dtoarray[$i]->odEmail)
                            ->setCellValue('G'.$n, $dtoarray[$i]->odHp)
                            ->setCellValue('H'.$n, $dtoarray[$i]->itIdPay)
                            ->setCellValue('I'.$n, $dtoarray[$i]->odBankAccount)
                            ->setCellValue('J'.$n, $dtoarray[$i]->odDepositName)
                            ->setCellValue('K'.$n, $dtoarray[$i]->odHopeDate)
                            ->setCellValue('L'.$n, $dtoarray[$i]->odTempBank)
                            ->setCellValue('M'.$n, $dtoarray[$i]->odReceiptBank)
                            ->setCellValue('N'.$n, $dtoarray[$i]->odBankTime)
                            ->setCellValue('O'.$n, $dtoarray[$i]->odRefundDt)
                            ->setCellValue('P'.$n, $dtoarray[$i]->odRefundAmount)
                            ->setCellValue('Q'.$n, $dtoarray[$i]->odDatetime)
                            ->setCellValue('R'.$n, $dtoarray[$i]->odIp)
                            ->setCellValue('S'.$n, $dtoarray[$i]->odDelYn)
                            ->setCellValue('T'.$n, $dtoarray[$i]->odShopMemo)
                            ->setCellValue('U'.$n, $odModifyHistory);
                    $objPHPExcel->setActiveSheetIndex(0)->getStyle('A'.$n.':U2'.$n)->getFont()->setStrikethrough(false);
                    $objPHPExcel->setActiveSheetIndex(0)->getStyle('A'.$n.':U2'.$n)->getFont()->setColor(new PHPExcel_Style_Color( PHPExcel_Style_Color::COLOR_BLACK ));
                    $objPHPExcel->setActiveSheetIndex(0)->getStyle('A'.$n.':U2'.$n)->getFont()->setSize(9);

                    if($dtoarray[$i]->odRefundAmount > 0){
                        $objPHPExcel->setActiveSheetIndex(0)->getStyle('A'.$n.':U2'.$n)->getFont()->setColor(new PHPExcel_Style_Color( PHPExcel_Style_Color::COLOR_GRAY ));
                    }
                }
                $n++;
                //sum
                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('K'.$n, '합산==>')
                            ->setCellValue('L'.$n, '=SUM(L2:L'.($n-1).')')
                            ->setCellValue('M'.$n, '=SUM(M2:M'.($n-1).')')
                            ->setCellValue('P'.$n, '=SUM(P2:P'.($n-1).')');

                $objPHPExcel->setActiveSheetIndex(0)->getStyle('A'.$n.':U2'.$n)->getFill()->setStartColor(new PHPExcel_Style_Color( PHPExcel_Style_Color::COLOR_GRAY ));
                $objPHPExcel->setActiveSheetIndex(0)->getStyle('A'.$n.':U2'.$n)->getFont()->setStrikethrough(false);
                $objPHPExcel->setActiveSheetIndex(0)->getStyle('A'.$n.':U2'.$n)->getFont()->setColor(new PHPExcel_Style_Color( PHPExcel_Style_Color::COLOR_BLACK ));
                $objPHPExcel->getProperties()->setCreator("Funhansoft Bitcoin Solution")
                                             ->setTitle($title);

                // Rename worksheet
                $objPHPExcel->getActiveSheet()->setTitle($title.' page-'.$page);
                // Set active sheet index to the first sheet, so Excel opens this as the first sheet
                $objPHPExcel->setActiveSheetIndex(0);
                // Redirect output to a client’s web browser (Excel5)
                header('Content-Type: application/vnd.ms-excel');
                header('Content-Disposition: attachment;filename="'.$title.'.xls"');
                header('Cache-Control: max-age=0');
                // If you're serving to IE 9, then the following may be needed
                header('Cache-Control: max-age=1');
                // If you're serving to IE over SSL, then the following may be needed
                header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
                header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
                header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
                header ('Pragma: public'); // HTTP/1.0

                $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
                $objWriter->save('php://output');
                exit;
            }



            /*
             * 수정된 내역이 있으면 파라미터 값
             */
            private function initModifyPostParam($keyId,$explainKey){
                try{
                    if(Utils::getPostParam($keyId)!=$this->dto->$keyId){
                        $this->modifyHistory .= '<br />'.$explainKey . ' 수정됨 : '.$this->dto->$keyId.'=>'.Utils::getPostParam($keyId);
                        $this->dto->$keyId = Utils::getPostParam($keyId);
                    }
                }catch(Exception $e){
                    $json = json_decode($e);
                    $this->dto->result = (int)$json->code;
                }
            }



            /*
             * @brief 데이터 삭제
             * @return int
             */
            public function delete(){

            }



            /*
             * @brief 검색 파라미터 초기화
             * @return null
             */
            private function setSearchParam(){
                $this->searchField = Utils::getUrlParam('sf',1);
                $this->searchValue = Utils::getUrlParam('sv',1);
                if($this->searchField=='od_id' || $this->searchField=='od_pg_id' || $this->searchField=='it_id_pay' || $this->searchField=='mb_id' || $this->searchField=='od_pwd' || $this->searchField=='od_name' || $this->searchField=='od_email' || $this->searchField=='od_tel' || $this->searchField=='od_hp' || $this->searchField=='od_temp_bank' || $this->searchField=='od_temp_card' || $this->searchField=='od_temp_mobile' || $this->searchField=='od_temp_ars' || $this->searchField=='od_temp_phonebill' || $this->searchField=='od_receipt_bank' || $this->searchField=='od_receipt_card' || $this->searchField=='od_receipt_mobile' || $this->searchField=='od_receipt_ars' || $this->searchField=='od_receipt_phonebill' || $this->searchField=='od_deposit_name' || $this->searchField=='od_bank_account' || $this->searchField=='od_ip' || $this->searchField=='od_del_yn' || $this->searchField=='od_scno' || $this->searchField=='od_etc' || $this->searchField=='od_escrow1' || $this->searchField=='od_escrow2' || $this->searchField=='od_escrow3' || $this->searchField=='od_proc_db' || $this->searchField=='partner'){

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