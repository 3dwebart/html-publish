<?php
/**
* Description of WebWalletWithdrawals Controller
* @description FunHanSoft Co.,Ltd  PHP auto templet
* @date 2017-08-19
* @copyright (c)funhansoft.com
* @license 해당 사이트 이외의 사용은 엄격히 금지되어 있습니다.
* @version 4.0.0
*/
        class WebWalletWithdrawals extends ControllerBase{

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
                $this->dto = new WebWalletWithdrawalsDTO();
            }
            
            //DB를 연결할 경우만
            private function initDAO(){
                if(!$this->dao){
                    $this->dao = new WebWalletWithdrawalsDAO();
                    $this->dao->setIsAdminConfirm(false);
                    $this->dao->setListLimitRow(30);
                    //$this->dao->setListOrderBy(array('odId'=>'DESC')); //정렬값
                }
            }

            public function getViewInsertPoint(){
                $this->getViewer()->setResponseType('JSON');
                $this->setSearchParam();

                if(parent::checkReferer()<0){
                    return array();
                }
                
                
                $odid = Utils::getUrlParam('odid',ResError::no);
                $this->initDAO();
                $this->dto = $this->dao->getViewById($odid);

                $pointdao = new WebPointDAO();
                $pointdao->setTableName('web_point_'.$this->dto->poType);
                $pointdao->setListLimitRow(100);

                $tmpdtocoin = array();
                $tmpdtocurrency = array();
                
                //삽입된 포인트가 있는지 확인한다.
                $tmpdtocurrency = $pointdao->getViewByRelId($this->dto->mbNo,$odid,null,null,'withdraw');

                return array('pointcurrency'=>$tmpdtocurrency);
            }
            
            public function main(){
                $this->setSearchParam();
                $resultArray = array();
                $this->initDAO();
                $dtfrom = Utils::getUrlParam('svdf',ResError::no);
                $dtto = Utils::getUrlParam('svdt',ResError::no);
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
                //Input Exception Field ,'odId','odStatus','odStatusMsg','mbNo','isUserConfirmYn','isUserConfirmDt','isUserConfirmIp','isAdminConfirmYn','isAdminConfirmDt','odTempAmount','odTempCurrencyTotal','odReceiptAmount','odAddr','odAddrMsg','odSendto','odFee','odTxid','poType','poAmount','poPayYn','poPayDt','odRegDt','odEtc1','odEtc2','odEtc3'
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
                    //Input Exception Field ,'odId','odStatus','odStatusMsg','mbNo','isUserConfirmYn','isUserConfirmDt','isUserConfirmIp','isAdminConfirmYn','isAdminConfirmDt','odTempAmount','odTempCurrencyTotal','odReceiptAmount','odAddr','odAddrMsg','odSendto','odFee','odTxid','poType','poAmount','poPayYn','poPayDt','odRegDt','odEtc1','odEtc2','odEtc3'
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
			$this->dto->odStatus = Utils::getPostParam('odStatus',ResError::no);
			$this->dto->odStatusMsg = Utils::getPostParam('odStatusMsg');
			$this->dto->mbNo = Utils::getPostParam('mbNo',ResError::no);
			$this->dto->isUserConfirmYn = Utils::getPostParam('isUserConfirmYn',ResError::no);
			$this->dto->isUserConfirmDt = Utils::getPostParam('isUserConfirmDt');
			$this->dto->isUserConfirmIp = Utils::getPostParam('isUserConfirmIp');
			$this->dto->isAdminConfirmYn = Utils::getPostParam('isAdminConfirmYn',ResError::no);
			$this->dto->isAdminConfirmDt = Utils::getPostParam('isAdminConfirmDt');
			$this->dto->odTempAmount = Utils::getPostParam('odTempAmount');
			$this->dto->odTempCurrencyTotal = Utils::getPostParam('odTempCurrencyTotal');
			$this->dto->odReceiptAmount = Utils::getPostParam('odReceiptAmount');
			$this->dto->odAddr = Utils::getPostParam('odAddr');
			$this->dto->odAddrMsg = Utils::getPostParam('odAddrMsg');
			$this->dto->odSendto = Utils::getPostParam('odSendto');
			$this->dto->odFee = Utils::getPostParam('odFee');
			$this->dto->odTxid = Utils::getPostParam('odTxid');
			$this->dto->poType = Utils::getPostParam('poType');
			$this->dto->poAmount = Utils::getPostParam('poAmount');
			$this->dto->poPayYn = Utils::getPostParam('poPayYn',ResError::no);
			$this->dto->poPayDt = Utils::getPostParam('poPayDt');
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
                
                $pointdao = new WebPointDAO();
                $pointdao->callBalanceSum($this->dto->mbNo,'admwithdraw');
                // 밸런스 메모리화로 인한 redis 값변경
                $this->setBalanceUpdate($this->dto->mbNo);
                
                return $this->returnarr;
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
                    $this->initModifyPostParam('isUserConfirmYn',ResError::no);
                    $this->initModifyPostParam('isUserConfirmDt');
                    $this->initModifyPostParam('isUserConfirmIp');
                    $this->initModifyPostParam('isAdminConfirmYn',ResError::no);
                    $this->initModifyPostParam('isAdminConfirmDt');
                    $this->initModifyPostParam('odTempAmount');
                    $this->initModifyPostParam('odTempCurrencyTotal');
                    $this->initModifyPostParam('odReceiptAmount');
                    $this->initModifyPostParam('odAddr');
                    $this->initModifyPostParam('odAddrMsg');
                    $this->initModifyPostParam('odSendto');
                    $this->initModifyPostParam('odFee');
                    $this->initModifyPostParam('odTxid');
                    $this->initModifyPostParam('poType');
                    $this->initModifyPostParam('poAmount');
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
                if($this->searchField=='od_id' || $this->searchField=='od_status' || $this->searchField=='mb_no' || $this->searchField=='is_user_confirm_yn' || $this->searchField=='is_user_confirm_ip' || $this->searchField=='is_admin_confirm_yn' || $this->searchField=='od_addr' || $this->searchField=='od_addr_msg' || $this->searchField=='od_sendto' || $this->searchField=='od_txid' || $this->searchField=='po_type' || $this->searchField=='po_pay_yn' || $this->searchField=='od_reg_dt'){

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