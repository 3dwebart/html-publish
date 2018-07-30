<?php
/**
* Description of WebPageMain Controller
* @description Funhansoft PHP auto templet
* @date 2013-10-01
* @copyright (c)funhansoft.com
* @license http://funhansoft.com/license
* @version 0.2.3
*/
        class WebPageMain extends ControllerBase{

            private $dao;
            private $dto;
            private $searchField;
            private $searchValue;

            /**
            * @brief
            **/
            function __construct(){
                parent::__construct();
                $this->dao = new WebPageMainDAO();
                $this->dto = new WebPageMainDTO();
                $this->dao->setListLimitRow(30);
                //$this->dao->setListOrderBy(array('pmNo'=>'DESC')); //정렬값
            }

            public function main(){
                $this->setSearchParam();
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

                if($this->dto->pmNo){
                    if($this->dto->pmNo!=$pri){
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

                    if($this->dto->pmNo){
                        if($this->dto->pmNo!=$pri){
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
                //$resultArray['link']['done'] = $resultArray['link']['list']; //완료 후 리스트로 보낼경우
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
                    $this->dto->pmId = Utils::getPostParam('pmId');
                    $this->dto->pmSubject= Utils::getPostParam('pmSubject');
                    $this->dto->pmContent = Utils::getPostHTMLParam('pmContent');
                    $this->dto->pmContentKr = Utils::getPostHTMLParam('pmContentKr');
                    $this->dto->pmContentCn = Utils::getPostHTMLParam('pmContentCn');
                    $this->dto->pmViewLevel = Utils::getPostParam('pmViewLevel',ResError::no);
                    $this->dto->pmModDt = NULL;
                }catch(Exception $e){
                    $json = json_decode($e);
                    $this->dto->result = (int)$json->code;
                    return $this->dto;
                }

                //param값 유효성 검사
                $this->dto->result = parent::checkEmptyValue($this->dto,array('result','pmNo','pmViewLevel','pmRegDt','pmModDt'));
                if($this->dto->result<0){
                     return $this->dto;
                }

                //토큰 유효성 검사 - 마지막에
                $this->dto->result = parent::checkToken();
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

                //레퍼러 도메인 체크
                if(parent::checkReferer()<0){
                    return array();
                }

                //고유값으로 값을 가져온다.
                try{
                    $pri = (int)Utils::getPostParam('pmNo');
                }catch(Exception $e){
                    $json = json_decode($e);
                    $this->dto->result = (int)$json->code;
                    return $this->dto;
                }

                $this->dto = $this->dao->getViewById($pri);
                if($this->dto->pmNo){
                    if($this->dto->pmNo!=$pri){
                        $this->dto->result =  ResError::paramUnMatchPri;
                        return $this->dto;
                    }
                }else{
                    $this->dto->result = ResError::noResultById;
                    return $this->dto;
                }

                try{
                    $this->initModifyPostParam('pmId');
                    $this->initModifyPostParam('pmSubject');
                    $this->dto->pmContent = Utils::getPostHTMLParam('pmContent');
                    $this->dto->pmContentKr = Utils::getPostHTMLParam('pmContentKr');
                    $this->dto->pmContentCn = Utils::getPostHTMLParam('pmContentCn');
                    $this->initModifyPostParam('pmViewLevel',ResError::no);
                    $this->dto->pmModDt = Utils::getDateNow();
                }catch(Exception $e){
                    $json = json_decode($e);
                    $this->dto->result = (int)$json->code;
                    return $this->dto;
                }

                //param값 유효성 검사
                $this->dto->result = parent::checkEmptyValue($this->dto,array('result','pmViewLevel','pmRegDt'));
                if($this->dto->result<0){
                     return $this->dto;
                }

                //토큰 유효성 검사 - 마지막에
                $this->dto->result = parent::checkToken();
                if($this->dto->result<0){
                     return $this->dto;
                }

                $this->dto->result = $this->dao->setUpdate($this->dto);
                return $this->dto;
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
                $this->getViewer()->setResponseType('JSON'); //사용시 JSON으로

                //레퍼러 도메인 체크
                if(parent::checkReferer()<0){
                    return array();
                }
                //고유값으로 값을 가져온다.
                 try{
                    $pri = (int)Utils::getPostParam('id');
                }catch(Exception $e){
                    $json = json_decode($e);
                    $this->dto->result = (int)$json->code;
                    return $this->dto;
                }

                $this->dto = $this->dao->getViewById($pri);
                if($this->dto->pmNo){
                    if($this->dto->pmNo!=$pri){
                        $this->dto->result =  ResError::paramUnMatchPri;
                        return $this->dto;
                    }

                    //삭제 권한이 있는지
                    //로직 부여

                    $this->dto->result = $this->dao->deleteFromPri($pri);
                    return $this->dto;
                }else{
                    $this->dto->result =  ResError::noResultById;
                    return $this->dto;
                }
            }


            /*
             * @brief 검색 파라미터 초기화
             * @return null
             */
            private function setSearchParam(){
                $this->searchField = Utils::getUrlParam('sf',1);
                $this->searchValue = Utils::getUrlParam('sv',1);
                if($this->searchField=='pm_no' || $this->searchField=='pm_id' || $this->searchField=='pm_view_level' || $this->searchField=='pm_reg_dt'){

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