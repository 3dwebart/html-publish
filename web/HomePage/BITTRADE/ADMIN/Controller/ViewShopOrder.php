<?php
/**
* Description of ViewShopOrder Controller
* @description Funhansoft PHP auto templet
* @date 2014-01-10
* @copyright (c)funhansoft.com
* @license http://funhansoft.com/license
* @version 4.0.0
*/
        class ViewShopOrder extends ControllerBase{

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
                $this->dto = new ViewShopOrderDTO();
            }

            //DB를 연결할 경우만
            private function initDAO(){
                if(!$this->dao){
                    $this->dao = new ViewShopOrderDAO();
                    $this->dao->setListLimitRow(30);
                    //$this->dao->setListOrderBy(array(''=>'DESC')); //정렬값
                }
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
                //Input Exception Field ,'odId','odPgId','itId','itName','itIdPay','mbNo','mbId','odPwd','odName','odEmail','odTel','odHp','odMemo','odTempBank','odTempCard','odTempMobile','odTempArs','odTempPhonebill','odReceiptBank','odReceiptCard','odReceiptMobile','odReceiptArs','odReceiptPhonebill','odBankTime','odCardTime','odPayTime','odCancelCard','odDcAmount','odRefundAmount','odRefundDt','odShopMemo','odModifyHistory','odDepositName','odBankAccount','odHopeDate','odDatetime','odIp','odSettleCase','odDelYn','odScno','odEtc','odEscrow1','odEscrow2','odEscrow3','odProcDb','partner','odTempTotal','odReceiptTotal'
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
                    //Input Exception Field ,'odId','odPgId','itId','itName','itIdPay','mbNo','mbId','odPwd','odName','odEmail','odTel','odHp','odMemo','odTempBank','odTempCard','odTempMobile','odTempArs','odTempPhonebill','odReceiptBank','odReceiptCard','odReceiptMobile','odReceiptArs','odReceiptPhonebill','odBankTime','odCardTime','odPayTime','odCancelCard','odDcAmount','odRefundAmount','odRefundDt','odShopMemo','odModifyHistory','odDepositName','odBankAccount','odHopeDate','odDatetime','odIp','odSettleCase','odDelYn','odScno','odEtc','odEscrow1','odEscrow2','odEscrow3','odProcDb','partner','odTempTotal','odReceiptTotal'
                    $this->returnarr = $this->returnDTO($this->dto, array());
                    if($this->dto->odId){
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
                    $this->dto->odId = Utils::getPostParam('odId',ResError::no);
                    $this->dto->odPgId = Utils::getPostParam('odPgId');
                    $this->dto->itId = (int)Utils::getPostParam('itId');
                    $this->dto->itName = Utils::getPostParam('itName');
                    $this->dto->itIdPay = Utils::getPostParam('itIdPay',ResError::no);
                    $this->dto->mbNo = (int)Utils::getPostParam('mbNo');
                    $this->dto->mbId = Utils::getPostParam('mbId');
                    $this->dto->odPwd = Utils::getPostParam('odPwd');
                    $this->dto->odName = Utils::getPostParam('odName');
                    $this->dto->odEmail = Utils::getPostParam('odEmail');
                    $this->dto->odTel = Utils::getPostParam('odTel');
                    $this->dto->odHp = Utils::getPostParam('odHp');
                    $this->dto->odMemo = Utils::getPostParam('odMemo');
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
                    $this->dto->odCancelCard = Utils::getPostParam('odCancelCard',ResError::no);
                    $this->dto->odDcAmount = Utils::getPostParam('odDcAmount',ResError::no);
                    $this->dto->odRefundAmount = Utils::getPostParam('odRefundAmount',ResError::no);
                    $this->dto->odRefundDt = Utils::getPostParam('odRefundDt');
                    $this->dto->odShopMemo = Utils::getPostParam('odShopMemo');
                    $this->dto->odModifyHistory = Utils::getPostParam('odModifyHistory');
                    $this->dto->odDepositName = Utils::getPostParam('odDepositName');
                    $this->dto->odBankAccount = Utils::getPostParam('odBankAccount');
                    $this->dto->odHopeDate = Utils::getPostParam('odHopeDate');
                    $this->dto->odDatetime = Utils::getPostParam('odDatetime',ResError::no);
                    $this->dto->odIp = Utils::getPostParam('odIp');
                    $this->dto->odSettleCase = Utils::getPostParam('odSettleCase');
                    $this->dto->odDelYn = Utils::getPostParam('odDelYn',ResError::no);
                    $this->dto->odScno = Utils::getPostParam('odScno');
                    $this->dto->odEtc = Utils::getPostParam('odEtc');
                    $this->dto->odEscrow1 = Utils::getPostParam('odEscrow1');
                    $this->dto->odEscrow2 = Utils::getPostParam('odEscrow2');
                    $this->dto->odEscrow3 = Utils::getPostParam('odEscrow3');
                    $this->dto->odProcDb = Utils::getPostParam('odProcDb',ResError::no);
                    $this->dto->partner = Utils::getPostParam('partner');
                    $this->dto->odTempTotal = Utils::getPostParam('odTempTotal',ResError::no);
                    $this->dto->odReceiptTotal = Utils::getPostParam('odReceiptTotal',ResError::no);
                    $this->returnarr = $this->returnDTO($this->dto, array('result','','odId','itIdPay','odTempBank','odTempCard','odTempMobile','odTempArs','odTempPhonebill','odReceiptBank','odReceiptCard','odReceiptMobile','odReceiptArs','odReceiptPhonebill','odCancelCard','odDcAmount','odRefundAmount','odDatetime','odDelYn','odProcDb','odTempTotal','odReceiptTotal'));
                }catch(Exception $e){
                    $json = json_decode($e);
                    $this->returnarr['result'] = (int)$json->code;
                    return $this->returnarr;
                }

                //param값 유효성 검사
                $this->returnarr['result'] = parent::checkEmptyValue($this->dto,array('result','','odId','itIdPay','odTempBank','odTempCard','odTempMobile','odTempArs','odTempPhonebill','odReceiptBank','odReceiptCard','odReceiptMobile','odReceiptArs','odReceiptPhonebill','odCancelCard','odDcAmount','odRefundAmount','odDatetime','odDelYn','odProcDb','odTempTotal','odReceiptTotal'));
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
            public function update(){
                $this->getViewer()->setResponseType('JSON');

                //레퍼러 도메인 체크
                if(parent::checkReferer()<0){
                    return array();
                }

                //고유값으로 값을 가져온다.
                 try{
                    $pri = (int)Utils::getPostParam('');
                }catch(Exception $e){
                    $json = json_decode($e);
                    $this->returnarr['result'] = (int)$json->code;
                    return $this->returnarr;
                }

                $this->initDAO();
                $this->dto = $this->dao->getViewById($pri);
                $this->returnarr = $this->returnDTO($this->dto, array('result','','odId','itIdPay','odTempBank','odTempCard','odTempMobile','odTempArs','odTempPhonebill','odReceiptBank','odReceiptCard','odReceiptMobile','odReceiptArs','odReceiptPhonebill','odCancelCard','odDcAmount','odRefundAmount','odDatetime','odDelYn','odProcDb','odTempTotal','odReceiptTotal'));
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
                    $this->initModifyPostParam('odId',ResError::no);
                    $this->initModifyPostParam('odPgId');
                    $this->initModifyPostParam('itId');  //int type
                    $this->initModifyPostParam('itName');
                    $this->initModifyPostParam('itIdPay',ResError::no);
                    $this->initModifyPostParam('mbNo');  //int type
                    $this->initModifyPostParam('mbId');
                    $this->initModifyPostParam('odPwd');
                    $this->initModifyPostParam('odName');
                    $this->initModifyPostParam('odEmail');
                    $this->initModifyPostParam('odTel');
                    $this->initModifyPostParam('odHp');
                    $this->initModifyPostParam('odMemo');
                    $this->initModifyPostParam('odTempBank',ResError::no);
                    $this->initModifyPostParam('odTempCard',ResError::no);
                    $this->initModifyPostParam('odTempMobile',ResError::no);
                    $this->initModifyPostParam('odTempArs',ResError::no);
                    $this->initModifyPostParam('odTempPhonebill',ResError::no);
                    $this->initModifyPostParam('odReceiptBank',ResError::no);
                    $this->initModifyPostParam('odReceiptCard',ResError::no);
                    $this->initModifyPostParam('odReceiptMobile',ResError::no);
                    $this->initModifyPostParam('odReceiptArs',ResError::no);
                    $this->initModifyPostParam('odReceiptPhonebill',ResError::no);
                    $this->initModifyPostParam('odBankTime');
                    $this->initModifyPostParam('odCardTime');
                    $this->initModifyPostParam('odPayTime');
                    $this->initModifyPostParam('odCancelCard',ResError::no);
                    $this->initModifyPostParam('odDcAmount',ResError::no);
                    $this->initModifyPostParam('odRefundAmount',ResError::no);
                    $this->initModifyPostParam('odRefundDt');
                    $this->initModifyPostParam('odShopMemo');
                    $this->initModifyPostParam('odModifyHistory');
                    $this->initModifyPostParam('odDepositName');
                    $this->initModifyPostParam('odBankAccount');
                    $this->initModifyPostParam('odHopeDate');
                    $this->initModifyPostParam('odDatetime',ResError::no);
                    $this->initModifyPostParam('odIp');
                    $this->initModifyPostParam('odSettleCase');
                    $this->initModifyPostParam('odDelYn',ResError::no);
                    $this->initModifyPostParam('odScno');
                    $this->initModifyPostParam('odEtc');
                    $this->initModifyPostParam('odEscrow1');
                    $this->initModifyPostParam('odEscrow2');
                    $this->initModifyPostParam('odEscrow3');
                    $this->initModifyPostParam('odProcDb',ResError::no);
                    $this->initModifyPostParam('partner');
                    $this->initModifyPostParam('odTempTotal',ResError::no);
                    $this->initModifyPostParam('odReceiptTotal',ResError::no);
                }catch(Exception $e){
                    $json = json_decode($e);
                    $this->returnarr['result'] = (int)$json->code;
                    return $this->returnarr;
                }

                //param값 유효성 검사
                $this->returnarr['result'] = parent::checkEmptyValue($this->dto,array('result','','odId','itIdPay','odTempBank','odTempCard','odTempMobile','odTempArs','odTempPhonebill','odReceiptBank','odReceiptCard','odReceiptMobile','odReceiptArs','odReceiptPhonebill','odCancelCard','odDcAmount','odRefundAmount','odDatetime','odDelYn','odProcDb','odTempTotal','odReceiptTotal'));
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

                $title = " 원화입금완료내역#".$page;
                $objPHPExcel = new PHPExcel();

                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A1', '주문서번호')
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

                //레퍼러 도메인 체크
                if(parent::checkReferer()<0){
                    return array();
                }
                //고유값으로 값을 가져온다.
                 try{
                    $pri = (int)Utils::getPostParam('id');
                }catch(Exception $e){
                    $json = json_decode($e);
                    $this->returnarr['result'] = (int)$json->code;
                    return $this->returnarr;
                }
                
                $this->initDAO();
                $this->dto = $this->dao->getViewById($pri);
                //Input Exception Field ,'odId','odPgId','itId','itName','itIdPay','mbNo','mbId','odPwd','odName','odEmail','odTel','odHp','odMemo','odTempBank','odTempCard','odTempMobile','odTempArs','odTempPhonebill','odReceiptBank','odReceiptCard','odReceiptMobile','odReceiptArs','odReceiptPhonebill','odBankTime','odCardTime','odPayTime','odCancelCard','odDcAmount','odRefundAmount','odRefundDt','odShopMemo','odModifyHistory','odDepositName','odBankAccount','odHopeDate','odDatetime','odIp','odSettleCase','odDelYn','odScno','odEtc','odEscrow1','odEscrow2','odEscrow3','odProcDb','partner','odTempTotal','odReceiptTotal'
                $this->returnarr = $this->returnDTO($this->dto, array());
                if($this->dto->odId){
                    if($this->dto->odId!=$pri){
                        $this->returnarr['result'] =  ResError::paramUnMatchPri;
                        return $this->returnarr;
                    }
                    
                    //삭제 권한이 있는지
                    
                    $this->returnarr['result'] = $this->dao->deleteFromPri($pri);
                    return $this->returnarr;
                }else{
                    $this->returnarr['result'] =  ResError::noResultById;
                    return $this->returnarr;
                }
            }


            /*
             * @brief 검색 파라미터 초기화
             * @return null
             */
            private function setSearchParam(){
                $this->searchField = Utils::getUrlParam('sf',1);
                $this->searchValue = Utils::getUrlParam('sv',1);
                if($this->searchField=='od_pg_id' || $this->searchField=='it_name' || $this->searchField=='it_id_pay' || $this->searchField=='mb_id' || $this->searchField=='od_pwd' || $this->searchField=='od_name' || $this->searchField=='od_email' || $this->searchField=='od_tel' || $this->searchField=='od_hp' || $this->searchField=='od_temp_bank' || $this->searchField=='od_temp_card' || $this->searchField=='od_temp_mobile' || $this->searchField=='od_temp_ars' || $this->searchField=='od_temp_phonebill' || $this->searchField=='od_receipt_bank' || $this->searchField=='od_receipt_card' || $this->searchField=='od_receipt_mobile' || $this->searchField=='od_receipt_ars' || $this->searchField=='od_receipt_phonebill' || $this->searchField=='od_bank_time' || $this->searchField=='od_card_time' || $this->searchField=='od_pay_time' || $this->searchField=='od_cancel_card' || $this->searchField=='od_dc_amount' || $this->searchField=='od_refund_amount' || $this->searchField=='od_refund_dt' || $this->searchField=='od_deposit_name' || $this->searchField=='od_bank_account' || $this->searchField=='od_hope_date' || $this->searchField=='od_datetime' || $this->searchField=='od_ip' || $this->searchField=='od_del_yn' || $this->searchField=='od_scno' || $this->searchField=='od_etc' || $this->searchField=='od_escrow1' || $this->searchField=='od_escrow2' || $this->searchField=='od_escrow3' || $this->searchField=='od_proc_db' || $this->searchField=='partner' || $this->searchField=='od_temp_total' || $this->searchField=='od_receipt_total'){

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