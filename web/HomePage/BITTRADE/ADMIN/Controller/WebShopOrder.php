<?php
/**
* Description of WebShopOrder Controller
* @description Funhansoft PHP auto templet
* @date 2013-09-05
* @copyright (c)funhansoft.com
* @license http://funhansoft.com/license
* @version 0.2.1
*/
        class WebShopOrder extends ControllerBase{

            private $dao;
            private $dto;
            private $searchField;
            private $searchValue;
            private $modifyHistory;
            private $returnarr;
            
            /**
            * @brief
            **/
            function __construct(){
                parent::__construct();
                $this->dao = new WebShopOrderDAO();
                $this->dto = new WebShopOrderDTO();
            }

            public function main(){
                $this->setSearchParam();
                $this->dao->setListLimitRow(30);

                $resultArray = array();
                $resultArray['data'] = $this->dao->getList($this->searchField,$this->searchValue);  
                $resultArray['common'] = $this->dao->getListCount($this->searchField,$this->searchValue);
                $resultArray['link'] = parent::getLinkURL();
                return $resultArray;
            }

            public function view(){
                $resultArray = array();
                $resultArray['result'] = ResError::no;
                $resultArray['link'] = parent::getLinkURL();
                $pri = Utils::getUrlParam('id',1);
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
                $this->dao->setListLimitRow(30);

                if(parent::checkReferer()<0){
                    return array();
                }

                $page = (int)Utils::getUrlParam('page',ResError::no);
                if($page){
                    if($page<1) $page=1;
                    $this->dao->setListLimitStart($this->dao->getListLimitRow() * ($page-1));
                }else{
                    $this->dao->setListLimitStart(0);
                }
                return $this->dao->getList($this->searchField,$this->searchValue);
            }

            /*
             * @brief 데이터 삽입
             * @return object
             */
            public function insert(){
                $this->getViewer()->setResponseType('JSON');
                try{
                        $this->dto->odId = Utils::getPostParam('odId',ResError::no);
			$this->dto->odPgId = Utils::getPostParam('odPgId');
			$this->dto->itId = (int)Utils::getPostParam('itId');
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
			$this->dto->odDcAmount = (int)Utils::getPostParam('odDcAmount');
			$this->dto->odRefundAmount = (int)Utils::getPostParam('odRefundAmount');
			$this->dto->odRefundDt = Utils::getPostParam('odRefundDt');
			$this->dto->odShopMemo = '주문서 강제생성됨';
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
                    $this->initModifyPostParam('odReceiptBank','무통장입금');
                    $this->initModifyPostParam('odReceiptCard','카드입금');
                    $this->initModifyPostParam('odReceiptMobile','모바일입금');
                    $this->initModifyPostParam('odReceiptArs','ARS입금');
                    $this->initModifyPostParam('odReceiptPhonebill','폰빌입금');
                    $this->initModifyPostParam('odBankTime','입금시간');
                    $this->initModifyPostParam('odCardTime','카드승인');
                    $this->initModifyPostParam('odPayTime','승인시간');
                    $this->initModifyPostParam('odCancelCard','카드취소');
                    $this->initModifyPostParam('odDcAmount','DC금액');
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
                
                //아이템 지급
                if($odItemPut=='Y'){
                    $this->setMemberItem();
                }

                $this->dto->result = $this->dao->setUpdate($this->dto);
                
                return $this->dto;
            }
            
            private function setMemberItem(){
                $itemdao = new WebItemDAO();
                $item = $itemdao->getViewById($this->dto->itId);
                if(!isset($item->itId) || !$item->itId){
                    //$this->returnarr['msg'] = '알수없는 상품입니다.';
                    return false;
                }
                $result = 0;

                //포인트 결제완료라면
                if($item->itType=='POINT'){
                    $pointdao = new WebPointDAO();
                    $point = new WebPointDTO();
                    $point->fromMbNo = '';
                    $point->fromMbId = '';
                    $point->poRelId = $this->dto->odId;
                    $point->poRelAction = 'order';
                    $point->mbNo = $this->dto->mbNo;
                    $point->mbId = $this->dto->mbId;
                    $point->poRefundYn = 'N';
                    $point->poRegIp = Utils::getClientIP();
                    $point->chNo = 0;
                    $point->poPoint = $item->itPoint;
                    $point->poContent = $item->itPoint.'개 무통장입금 충전 완료';
                    $result = $pointdao->setInsertPoint($point);
                    if($result>0){
                        $this->dto->itIdPay = 'Y';
                        //포인트 총합을 가져온다.
                        $this->returnarr['mbPoint'] = $totalPoint = $pointdao->getSumPointByMbNo($point->mbNo);
                    }
                }else{
                    $memberitemdao = new WebMemberItemDAO();
                    $memberitem = new WebMemberItemDTO();
                    $memberitem->orginMbNo = '';
                    $memberitem->orginMbId = '';
                    $memberitem->mbNo = $this->dto->mbNo;
                    $memberitem->mbId = $this->dto->mbId;
                    $memberitem->odId = $this->dto->odId;
                    $memberitem->itId = $item->itId;
                    $memberitem->itType = $item->itType;
                    $memberitem->itName = $item->itName;
                    $memberitem->itUseDay = $item->itLimitDay;
                    $memberitem->mitSdateCmd = '';
                    $memberitem->mitEdateCmd = '';
                    $memberitem->mitEdateCmdYn = 'N';
                    $memberitem->mitRegIp = Utils::getClientIP();
                    $result = $memberitemdao->setInsert($memberitem);
                    if($result>0){
                        $this->dto->itIdPay = 'Y';
                    }
                }
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