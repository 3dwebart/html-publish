<?php
/**
* Description of WebConfigWalletServer Controller
* @description FunHanSoft Co.,Ltd  PHP auto templet
* @date 2017-08-18
* @copyright (c)funhansoft.com
* @license 해당 사이트 이외의 사용은 엄격히 금지되어 있습니다.
* @version 4.0.0
*/
        class WebConfigWalletServer extends ControllerBase{

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
                $this->dto = new WebConfigWalletServerDTO();
            }
            
            //DB를 연결할 경우만
            private function initDAO(){
                if(!$this->dao){
                    $this->dao = new WebConfigWalletServerDAO();
                    $this->dao->setListLimitRow(30);
                    //$this->dao->setListOrderBy(array('waNo'=>'DESC')); //정렬값
                }
            }

            public function main(){
                $this->setSearchParam();
                $resultArray = array();
                $this->initDAO();
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
                
                $this->initDAO();
                $this->dto = $this->dao->getViewById($pri);
                //Input Exception Field ,'waNo','coId','waExplain','waStaus','waRpcProto','waRpcIp','waRpcPort','waAccountCnt','waUser','waPass','waWalletPwd','waEtc1','waEtc2','waEtc3','trRegDt'
                $this->returnarr = $this->returnDTO($this->dto, array());

                if($this->dto->waNo){
                    if($this->dto->waNo!=$pri){
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
//                $this->getViewer()->setResponseType('404');
                $resultArray = array();
                $resultArray['result'] = ResError::no;
                $resultArray['link'] = parent::getLinkURL();
                $pri = Utils::getUrlParam('id',ResError::no);

                //수정모드
                if($pri) {
                    $this->initDAO();
                    $this->dto = $this->dao->getViewById($pri);
                    //Input Exception Field ,'waNo','coId','waExplain','waStaus','waRpcProto','waRpcIp','waRpcPort','waAccountCnt','waUser','waPass','waWalletPwd','waEtc1','waEtc2','waEtc3','trRegDt'
                    $this->returnarr = $this->returnDTO($this->dto, array());
                    if($this->dto->waNo){
                        if($this->dto->waNo!=$pri){
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
                return $this->dao->getList($this->searchField,$this->searchValue);
            }

            /*
             * @brief 데이터 삽입
             * @return object
             */
            public function insert(){
                $this->getViewer()->setResponseType('404');
                
                //레퍼러 도메인 체크
                if(parent::checkReferer()<0){
                    return array();
                }

                try{
			$this->dto->coId = (int)Utils::getPostParam('coId');
			$this->dto->waExplain = Utils::getPostParam('waExplain');
			$this->dto->waStaus = Utils::getPostParam('waStaus');
            $this->dto->waTxFee = Utils::getPostParam('waTxFee');
			$this->dto->waRpcProto = Utils::getPostParam('waRpcProto',ResError::no);
			$this->dto->waRpcIp = Utils::getPostParam('waRpcIp');
			$this->dto->waRpcPort = Utils::getPostParam('waRpcPort');
			$this->dto->waAccountCnt = (int)Utils::getPostParam('waAccountCnt');
			$this->dto->waUser = Utils::getPostParam('waUser');
			$this->dto->waPass = Utils::getPostParam('waPass');
			$this->dto->waWalletPwd = Utils::getPostParam('waWalletPwd');
			$this->dto->waEtc1 = Utils::getPostParam('waEtc1');
			$this->dto->waEtc2 = Utils::getPostParam('waEtc2');
			$this->dto->waEtc3 = Utils::getPostParam('waEtc3');
                    $this->returnarr = $this->returnDTO($this->dto, array('result','waNo','waRpcProto','trRegDt'));
                }catch(Exception $e){
                    $json = json_decode($e);
                    $this->returnarr['result'] = (int)$json->code;
                    return $this->returnarr;
                }

                //param값 유효성 검사
                $this->returnarr['result'] = parent::checkEmptyValue($this->dto,array('result','waNo','waRpcProto','trRegDt'));
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
                    $pri = (int)Utils::getPostParam('waNo');
                }catch(Exception $e){
                    $json = json_decode($e);
                    $this->returnarr['result'] = (int)$json->code;
                    return $this->returnarr;
                }
                
                $this->initDAO();
                $this->dto = $this->dao->getViewById($pri);
                $this->returnarr = $this->returnDTO($this->dto, array('result','waNo','waRpcProto','trRegDt'));
                if($this->dto->waNo){
                    if($this->dto->waNo!=$pri){
                        $this->returnarr['result'] =  ResError::paramUnMatchPri;
                        return $this->returnarr;
                    }
                    
                    //수정권한이 있는지 체크
                    
                }else{
                    $this->returnarr['result'] = ResError::noResultById;
                    return $this->returnarr;
                }

                try{
			$this->initModifyPostParam('waNo');
			$this->initModifyPostParam('coId');  //int type
			$this->initModifyPostParam('waExplain',ResError::no);
			$this->initModifyPostParam('waStaus');
            $this->initModifyPostParam('waTxFee',ResError::no);
			$this->initModifyPostParam('waRpcProto',ResError::no);
			$this->initModifyPostParam('waRpcIp',ResError::no);
			$this->initModifyPostParam('waRpcPort',ResError::no);
			$this->initModifyPostParam('waAccountCnt',ResError::no);  //int type
			$this->initModifyPostParam('waEtc1',ResError::no);
			$this->initModifyPostParam('waEtc2',ResError::no);
			$this->initModifyPostParam('waEtc3',ResError::no);
                }catch(Exception $e){
                    $json = json_decode($e);
                    $this->returnarr['result'] = (int)$json->code;
                    return $this->returnarr;
                }

                //param값 유효성 검사
//                $this->returnarr['result'] = parent::checkEmptyValue($this->dto,array('result','waNo','waRpcProto','trRegDt'));
//                if($this->returnarr['result']<0){
//                     return $this->returnarr;
//                }
                
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
                //Input Exception Field ,'waNo','coId','waExplain','waStaus','waRpcProto','waRpcIp','waRpcPort','waAccountCnt','waUser','waPass','waWalletPwd','waEtc1','waEtc2','waEtc3','trRegDt'
                $this->returnarr = $this->returnDTO($this->dto, array());
                if($this->dto->waNo){
                    if($this->dto->waNo!=$pri){
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
                if($this->searchField=='wa_no' || $this->searchField=='po_type' || $this->searchField=='wa_explain' || $this->searchField=='wa_rpc_proto' || $this->searchField=='wa_rpc_ip' || $this->searchField=='wa_rpc_port' || $this->searchField=='wa_user' || $this->searchField=='wa_pass' || $this->searchField=='wa_wallet_pwd' || $this->searchField=='wa_etc1' || $this->searchField=='wa_etc2' || $this->searchField=='wa_etc3' || $this->searchField=='tr_reg_dt'){

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