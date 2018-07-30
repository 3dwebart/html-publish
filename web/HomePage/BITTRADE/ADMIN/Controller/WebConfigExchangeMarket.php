<?php
/**
* Description of WebConfigExchangeMarket Controller
* @description FunHanSoft Co.,Ltd  PHP auto templet
* @date 2017-07-24
* @copyright (c)funhansoft.com
* @license 해당 사이트 이외의 사용은 엄격히 금지되어 있습니다.
* @version 4.0.0
*/
        class WebConfigExchangeMarket extends ControllerBase{

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
                $this->dto = new WebConfigExchangeMarketDTO();
            }
            
            //DB를 연결할 경우만
            private function initDAO(){
                if(!$this->dao){
                    $this->dao = new WebConfigExchangeMarketDAO();
                    $this->dao->setListLimitRow(30);
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
                //Input Exception Field ,'itNo','itMarketId','itName','itExplain','itStdCoId','itExcCoId','itSort','itUse','itServerIp','itServerPort','itServerSignIp','itServerSignPort','itRegDt'
                $this->returnarr = $this->returnDTO($this->dto, array());

                if($this->dto->itNo){
                    if($this->dto->itNo!=$pri){
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
                    //Input Exception Field ,'itNo','itMarketId','itName','itExplain','itStdCoId','itExcCoId','itSort','itUse','itServerIp','itServerPort','itServerSignIp','itServerSignPort','itRegDt'
                    $this->returnarr = $this->returnDTO($this->dto, array());
                    if($this->dto->itNo){
                        if($this->dto->itNo!=$pri){
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
                $this->getViewer()->setResponseType('JSON');
                
                //레퍼러 도메인 체크
                if(parent::checkReferer()<0){
                    return array();
                }

                try{
			$this->dto->itNo = Utils::getPostParam('itNo');
			$this->dto->itMarketId = Utils::getPostParam('itMarketId');
			$this->dto->itName = Utils::getPostParam('itName');
			$this->dto->itExplain = Utils::getPostParam('itExplain');
			$this->dto->itStdCoId = (int)Utils::getPostParam('itStdCoId');
			$this->dto->itExcCoId = (int)Utils::getPostParam('itExcCoId');
			$this->dto->itSort = (int)Utils::getPostParam('itSort');
			$this->dto->itUse = Utils::getPostParam('itUse');
			$this->dto->itServerIp = Utils::getPostParam('itServerIp',ResError::no);
			$this->dto->itServerPort = Utils::getPostParam('itServerPort',ResError::no);
			$this->dto->itServerSignIp = Utils::getPostParam('itServerSignIp',ResError::no);
			$this->dto->itServerSignPort = Utils::getPostParam('itServerSignPort',ResError::no);
                        $this->dto->itWalletUse = Utils::getPostParam('itWalletUse',ResError::no);
                    $this->returnarr = $this->returnDTO($this->dto, array('result','itNo','itServerIp','itServerPort','itServerSignIp','itServerSignPort','itRegDt'));
                }catch(Exception $e){
                    $json = json_decode($e);
                    $this->returnarr['result'] = (int)$json->code;
                    return $this->returnarr;
                }

                //param값 유효성 검사
                $this->returnarr['result'] = parent::checkEmptyValue($this->dto,array('result','itNo','itServerIp','itServerPort','itServerSignIp','itServerSignPort','itRegDt'));
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
                    $pri = (int)Utils::getPostParam('itNo');
                }catch(Exception $e){
                    $json = json_decode($e);
                    $this->returnarr['result'] = (int)$json->code;
                    return $this->returnarr;
                }
                
                $this->initDAO();
                $this->dto = $this->dao->getViewById($pri);
                $this->returnarr = $this->returnDTO($this->dto, array('result','itNo','itServerIp','itServerPort','itServerSignIp','itServerSignPort','itRegDt'));
                if($this->dto->itNo){
                    if($this->dto->itNo!=$pri){
                        $this->returnarr['result'] =  ResError::paramUnMatchPri;
                        return $this->returnarr;
                    }
                    
                    //수정권한이 있는지 체크
                    
                }else{
                    $this->returnarr['result'] = ResError::noResultById;
                    return $this->returnarr;
                }

                try{
			$this->initModifyPostParam('itNo');
			$this->initModifyPostParam('itMarketId');
			$this->initModifyPostParam('itName');
			$this->initModifyPostParam('itExplain');
			$this->initModifyPostParam('itStdCoId');  //int type
			$this->initModifyPostParam('itExcCoId');  //int type
			$this->initModifyPostParam('itSort');  //int type
			$this->initModifyPostParam('itUse');
                        $this->initModifyPostParam('itWalletUse');
			$this->initModifyPostParam('itServerIp',ResError::no);
			$this->initModifyPostParam('itServerPort',ResError::no);
			$this->initModifyPostParam('itServerSignIp',ResError::no);
			$this->initModifyPostParam('itServerSignPort',ResError::no);
                }catch(Exception $e){
                    $json = json_decode($e);
                    $this->returnarr['result'] = (int)$json->code;
                    return $this->returnarr;
                }

                //param값 유효성 검사
                $this->returnarr['result'] = parent::checkEmptyValue($this->dto,array('result','itNo','itServerIp','itServerPort','itServerSignIp','itServerSignPort','itRegDt'));
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
            
            
            function makeSiteJson(){
                $this->getViewer()->setResponseType('JSON'); //사용시 JSON으로
                $resultArray = array();
                //레퍼러 도메인 체크
                if(parent::checkReferer()<0){
                    return array();
                }
                $this->initDAO();
                $this->dao->setListLimitStart(0);
                $this->dao->setListLimitRow(1000);
                $this->dao->setListOrderBy(array('it_sort'=>'DESC')); //정렬값
                $list = $this->lists();
                
                $json = array();
                for($i=0;$i<count($list);$i++){
                    
                    $dto = $list[$i];
                    if($dto->itUse != 'Y' && $dto->itUse != 'P' && $dto->itUse != 'R'){
                        continue;
                    }else if($dto->itUse == 'P' || $dto->itUse == 'R'){
                        $json[$dto->itMarketId]['name'] = 'Stoped';
                    }
                    
                    $json[$dto->itMarketId]['name'] = $dto->itName;
                    $json[$dto->itMarketId]['ch'] = strtolower($dto->itMarketId);
                    $json[$dto->itMarketId]['last'] = '0.0000000';
                    $json[$dto->itMarketId]['ask'] = '0.0000000';
                    $json[$dto->itMarketId]['bid'] = '0.0000000';
                    $json[$dto->itMarketId]['percentChange'] ='0.00';
                    $json[$dto->itMarketId]['volume24h'] = '0.0000000';
                    $json[$dto->itMarketId]['baseVolume'] = '0.0000000';
                    $json[$dto->itMarketId]['quoteVolume'] = '0.0000000';
                    $json[$dto->itMarketId]['itUse'] = $dto->itUse;
                    $json[$dto->itMarketId]['itServerIp'] =  $dto->itServerIp;
                    $json[$dto->itMarketId]['itServerPort'] =  $dto->itServerPort;
                    $json[$dto->itMarketId]['itServerSignIp'] =  $dto->itServerSignIp;
                    $json[$dto->itMarketId]['itServerSignPort'] =  $dto->itServerSignPort;
                    $json[$dto->itMarketId]['itWalletUse'] =  $dto->itWalletUse;
                }

                $makeFeeJsonFile = @fopen("../WebApp/Defined/config/exchangemarket.json", "w");
                $resFeeJson = @fwrite($makeFeeJsonFile, Utils::jsonEncode($json));
                @fclose($makeFeeJsonFile);

                $this->returnarr['result'] = -1;
                $this->returnarr['msg'] = '[로컬]사이트 적용에 실패하였습니다. 폴더 퍼미션을 확인해 보세요.';
                if($resFeeJson) {
                    $this->returnarr['result'] = 1;
                    $this->returnarr['msg'] = '[로컬]사이트 적용에 성공하였습니다.';
                }
                
                $shareurls = $this->config['configsync']['urls'];
                
               
                if(trim($shareurls)){
                    $arr = explode(',', $shareurls);
                    for($i=0;$i<count($arr);$i++){
                        if($arr[$i]){
                            $response = file_get_contents($arr[$i].__FUNCTION__);
                            $json = json_decode($response,TRUE);
                            $this->returnarr['msg'] = $this->returnarr['msg'] . "\n\n[원격서버". ($i + 1) .']'.$json['msg'];
                        }
                        

                    }
                }
                
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
                //Input Exception Field ,'itNo','itMarketId','itName','itExplain','itStdCoId','itExcCoId','itSort','itUse','itServerIp','itServerPort','itServerSignIp','itServerSignPort','itRegDt'
                $this->returnarr = $this->returnDTO($this->dto, array());
                if($this->dto->itNo){
                    if($this->dto->itNo!=$pri){
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
                if($this->searchField=='it_no' || $this->searchField=='it_market_id' || $this->searchField=='it_name' || $this->searchField=='it_explain' || $this->searchField=='it_server_ip' || $this->searchField=='it_server_port' || $this->searchField=='it_server_sign_ip' || $this->searchField=='it_server_sign_port' || $this->searchField=='it_reg_dt'){

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