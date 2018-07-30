<?php
/**
* Description of ViewShopOrderDay Controller
* @description Funhansoft PHP auto templet
* @date 2014-02-28
* @copyright (c)funhansoft.com
* @license http://funhansoft.com/license
* @version 4.0.0
*/
        class ViewShopOrderDay extends ControllerBase{

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
                $this->dto = new ViewShopOrderDayDTO();
            }
            
            //DB를 연결할 경우만
            private function initDAO(){
                if(!$this->dao){
                    $this->dao = new ViewShopOrderDayDAO();
                    $this->dao->setListLimitRow(100);
                    //$this->dao->setListOrderBy(array(''=>'DESC')); //정렬값
                }
            }

            public function main(){

                $resultArray = array();
                $this->initDAO();
                $resultArray['data'] = $this->dao->getList();  
                $returnArray['result'] = count($resultArray['data']);
                
                return $resultArray;
            }

            public function view(){
                $this->getViewer()->setResponseType('404');
            }

            public function form(){
                $this->getViewer()->setResponseType('404');
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
			$this->dto->odDate = Utils::getPostParam('odDate');
			$this->dto->cnt = Utils::getPostParam('cnt',ResError::no);
			$this->dto->odTempSum = Utils::getPostParam('odTempSum');
			$this->dto->odReceptSum = Utils::getPostParam('odReceptSum');
			$this->dto->odRefundSum = Utils::getPostParam('odRefundSum');
                    $this->returnarr = $this->returnDTO($this->dto, array('result','','cnt'));
                }catch(Exception $e){
                    $json = json_decode($e);
                    $this->returnarr['result'] = (int)$json->code;
                    return $this->returnarr;
                }

                //param값 유효성 검사
                $this->returnarr['result'] = parent::checkEmptyValue($this->dto,array('result','','cnt'));
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
                $this->getViewer()->setResponseType('404');
            }
            

             /*
             * @brief 데이터 삭제
             * @return int
             */
            public function delete(){
                $this->getViewer()->setResponseType('404');
            }


            /*
             * @brief 검색 파라미터 초기화
             * @return null
             */
            private function setSearchParam(){
                $this->searchField = Utils::getUrlParam('sf',1);
                $this->searchValue = Utils::getUrlParam('sv',1);
                if($this->searchField=='odDate' || $this->searchField=='cnt'){

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