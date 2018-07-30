<?php
/**
* Description of WebTradeOrderHistoryKrwEth Controller
* @description FunHanSoft Co.,Ltd  PHP auto templet
* @date 2017-07-12
* @copyright (c)funhansoft.com
* @license 해당 사이트 이외의 사용은 엄격히 금지되어 있습니다.
* @version 4.0.0
*/
        class WebTradeOrderHistoryKrwEth extends ControllerBase{

            private $dao;
            private $dto;
            private $searchField;
            private $searchValue;
            private $returnarr = array('result'=>0);
            private $po_type = 'eth';
            
            /**
            * @brief
            **/
            function __construct(){
                parent::__construct();
                $this->dto = new WebTradeOrderHistoryDTO();
            }
            
            //DB를 연결할 경우만
            private function initDAO(){
                if(!$this->dao){
                    $this->dao = new WebTradeOrderHistoryDAO();
                    $this->dao->setDbName('fns_trade_order_'.$this->po_type);
                    $this->dao->setTableName('web_trade_order_history_krw_'.$this->po_type);
                    $this->dao->setListLimitRow(30);
                    //$this->dao->setListOrderBy(array('odId'=>'DESC')); //정렬값
                }
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
                //Input Exception Field ,'odId','odAction','odPayStatus','odPayPoIds','odMarketPrice','odTotalCost','mbNo','mbId','odFeeRate','odTempCoin','odReceiptCoin','odReceiptFee','odReceiptAvg','odRegDt','odReceiptDt','odCancelDt','odSyncDt','odRegIp','odDelYn','tmpTrigerAc','partner'
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
                
                // 포인트 확인
                
                
                $pointdao = NULL;
                $pointdaostd = NULL;

                $pointdaostd = new WebPointDAO();
                $pointdaostd->setTableName('web_point_krw');
                $pointdaostd->setListLimitRow(100);

                $pointdao = new WebPointDAO();
                $pointdao->setTableName('web_point_'.$this->po_type);
                $pointdao->setListLimitRow(100);

                $tmpdtocoin = array();
                $tmpdtocurrency = array();
                if($this->dto->odAction=='sell'){

                }else if($this->dto->odAction=='buy'){

                   
                }
                
                //삽입된 포인트가 있는지 확인한다.
                $tmpdtocoin = $pointdao->getViewByOrderId($this->dto->mbNo,$this->dto->odId);
                $tmpdtocurrency = $pointdaostd->getViewByOrderId($this->dto->mbNo,$this->dto->odId,null,null,$this->dto->odAction.'_krw_'.$this->po_type);

                $resultArray['link']['done'] = $resultArray['link']['list'];
                $this->returnarr['po_type'] = strtoupper($this->po_type);
                $resultArray['data'] = $this->returnarr;
                $resultArray['datapointcoin'] = $tmpdtocoin;
                $resultArray['datapointcurrency'] = $tmpdtocurrency;
                $resultArray['token'] = '';

                return $resultArray;
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
                
                // 포인트 확인
                $pointdaostd = new WebPointDAO();
                $pointdaostd->setTableName('web_point_krw');
                $pointdaostd->setListLimitRow(100);

                $pointdao = new WebPointDAO();
                $pointdao->setTableName('web_point_'.$this->po_type);
                $pointdao->setListLimitRow(100);

                $tmpdtocoin = array();
                $tmpdtocurrency = array();
                
                //삽입된 포인트가 있는지 확인한다.
                $tmpdtocoin = $pointdao->getViewByOrderId($this->dto->mbNo,$odid);
                $tmpdtocurrency = $pointdaostd->getViewByOrderId($this->dto->mbNo,$odid,null,null,$this->dto->odAction.'_krw_'.$this->po_type);

                return array('pointcoin'=>$tmpdtocoin,'pointcurrency'=>$tmpdtocurrency);
            }
            
            public function getViewInsertMorePoint(){
                $this->getViewer()->setResponseType('JSON');
                $this->setSearchParam();

                if(parent::checkReferer()<0){
                    return array();
                }
                
                $odid = Utils::getUrlParam('odid',ResError::no);
                $page = Utils::getUrlParam('page',ResError::no);
                $startLimit = $page * 100;
                $this->initDAO();
                $this->dto = $this->dao->getViewById($odid);
                
                // 포인트 확인
                $pointdaostd = new WebPointDAO();
                $pointdaostd->setTableName('web_point_krw');
                $pointdaostd->setListLimitStart($startLimit);
                $pointdaostd->setListLimitRow(100);

                $pointdao = new WebPointDAO();
                $pointdao->setTableName('web_point_'.$this->po_type);
                $pointdao->setListLimitStart($startLimit);
                $pointdao->setListLimitRow(100);

                $tmpdtocoin = array();
                $tmpdtocurrency = array();
                
                //삽입된 포인트가 있는지 확인한다.
                $tmpdtocoin = $pointdao->getViewByOrderId($this->dto->mbNo,$odid);
                $tmpdtocurrency = $pointdaostd->getViewByOrderId($this->dto->mbNo,$odid,null,null,$this->dto->odAction.'_krw_'.$this->po_type);

                return array('pointcoin'=>$tmpdtocoin,'pointcurrency'=>$tmpdtocurrency);
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
                    //Input Exception Field ,'odId','odAction','odPayStatus','odPayPoIds','odMarketPrice','odTotalCost','mbNo','mbId','odFeeRate','odTempCoin','odReceiptCoin','odReceiptFee','odReceiptAvg','odRegDt','odReceiptDt','odCancelDt','odSyncDt','odRegIp','odDelYn','tmpTrigerAc','partner'
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
                $dtfrom = Utils::getUrlParam('svdf',ResError::no);
                $dtto = Utils::getUrlParam('svdt',ResError::no);
                if($page){
                    if($page<1) $page=1;
                    $this->dao->setListLimitStart($this->dao->getListLimitRow() * ($page-1));
                }else{
                    $this->dao->setListLimitStart(0);
                }
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
			$this->dto->odAction = Utils::getPostParam('odAction');
			$this->dto->odPayStatus = Utils::getPostParam('odPayStatus');
			$this->dto->odPayPoIds = Utils::getPostParam('odPayPoIds');
			$this->dto->odMarketPrice = Utils::getPostParam('odMarketPrice',ResError::no);
			$this->dto->odTotalCost = Utils::getPostParam('odTotalCost',ResError::no);
			$this->dto->mbNo = (int)Utils::getPostParam('mbNo');
			$this->dto->mbId = Utils::getPostParam('mbId');
			$this->dto->odFeeRate = Utils::getPostParam('odFeeRate',ResError::no);
			$this->dto->odTempCoin = Utils::getPostParam('odTempCoin',ResError::no);
			$this->dto->odReceiptCoin = Utils::getPostParam('odReceiptCoin',ResError::no);
			$this->dto->odReceiptFee = Utils::getPostParam('odReceiptFee',ResError::no);
			$this->dto->odReceiptAvg = Utils::getPostParam('odReceiptAvg',ResError::no);
			$this->dto->odReceiptDt = Utils::getPostParam('odReceiptDt');
			$this->dto->odCancelDt = Utils::getPostParam('odCancelDt');
			$this->dto->odSyncDt = Utils::getPostParam('odSyncDt');
			$this->dto->odRegIp = Utils::getPostParam('odRegIp');
			$this->dto->odDelYn = Utils::getPostParam('odDelYn',ResError::no);
			$this->dto->tmpTrigerAc = Utils::getPostParam('tmpTrigerAc');
			$this->dto->partner = Utils::getPostParam('partner');
                    $this->returnarr = $this->returnDTO($this->dto, array('result','odId','odMarketPrice','odTotalCost','odFeeRate','odTempCoin','odReceiptCoin','odReceiptFee','odReceiptAvg','odRegDt','odDelYn'));
                }catch(Exception $e){
                    $json = json_decode($e);
                    $this->returnarr['result'] = (int)$json->code;
                    return $this->returnarr;
                }

                //param값 유효성 검사
                $this->returnarr['result'] = parent::checkEmptyValue($this->dto,array('result','odId','odMarketPrice','odTotalCost','odFeeRate','odTempCoin','odReceiptCoin','odReceiptFee','odReceiptAvg','odRegDt','odDelYn'));
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
                    $pri = (int)Utils::getPostParam('odId');
                }catch(Exception $e){
                    $json = json_decode($e);
                    $this->returnarr['result'] = (int)$json->code;
                    return $this->returnarr;
                }
                
                $this->initDAO();
                $this->dto = $this->dao->getViewById($pri);
                $this->returnarr = $this->returnDTO($this->dto, array('result','odId','odMarketPrice','odTotalCost','odFeeRate','odTempCoin','odReceiptCoin','odReceiptFee','odReceiptAvg','odRegDt','odDelYn'));
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
			$this->initModifyPostParam('odAction');
			$this->initModifyPostParam('odPayStatus');
			$this->initModifyPostParam('odPayPoIds');
			$this->initModifyPostParam('odMarketPrice',ResError::no);
			$this->initModifyPostParam('odTotalCost',ResError::no);
			$this->initModifyPostParam('mbNo');  //int type
			$this->initModifyPostParam('mbId');
			$this->initModifyPostParam('odFeeRate',ResError::no);
			$this->initModifyPostParam('odTempCoin',ResError::no);
			$this->initModifyPostParam('odReceiptCoin',ResError::no);
			$this->initModifyPostParam('odReceiptFee',ResError::no);
			$this->initModifyPostParam('odReceiptAvg',ResError::no);
			$this->initModifyPostParam('odReceiptDt');
			$this->initModifyPostParam('odCancelDt');
			$this->initModifyPostParam('odSyncDt');
			$this->initModifyPostParam('odRegIp');
			$this->initModifyPostParam('odDelYn',ResError::no);
			$this->initModifyPostParam('tmpTrigerAc');
			$this->initModifyPostParam('partner');
                }catch(Exception $e){
                    $json = json_decode($e);
                    $this->returnarr['result'] = (int)$json->code;
                    return $this->returnarr;
                }

                //param값 유효성 검사
                $this->returnarr['result'] = parent::checkEmptyValue($this->dto,array('result','odId','odMarketPrice','odTotalCost','odFeeRate','odTempCoin','odReceiptCoin','odReceiptFee','odReceiptAvg','odRegDt','odDelYn'));
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
                //Input Exception Field ,'odId','odAction','odPayStatus','odPayPoIds','odMarketPrice','odTotalCost','mbNo','mbId','odFeeRate','odTempCoin','odReceiptCoin','odReceiptFee','odReceiptAvg','odRegDt','odReceiptDt','odCancelDt','odSyncDt','odRegIp','odDelYn','tmpTrigerAc','partner'
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
                if($this->searchField=='od_id' || $this->searchField=='mb_no' || $this->searchField=='od_market_price' || $this->searchField=='od_total_cost' || $this->searchField=='mb_id' || $this->searchField=='od_fee_rate' || $this->searchField=='od_temp_coin' || $this->searchField=='od_receipt_coin' || $this->searchField=='od_receipt_fee' || $this->searchField=='od_receipt_avg' || $this->searchField=='od_reg_dt' || $this->searchField=='od_reg_ip' || $this->searchField=='od_del_yn' || $this->searchField=='tmp_triger_ac' || $this->searchField=='partner'){

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