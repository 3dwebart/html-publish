<?php
/**
* Description of WebMemberDel Controller
* @description Funhansoft PHP auto templet
* @date 2013-09-05
* @copyright (c)funhansoft.com
* @license http://funhansoft.com/license
* @version 0.2.1
*/
        class WebMemberDel extends ControllerBase{

            private $dao;
            private $dto;
            private $searchField;
            private $searchValue;

            /**
            * @brief
            **/
            function __construct(){
                parent::__construct();
                $this->dao = new WebMemberDelDAO();
                $this->dto = new WebMemberDelDTO();
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

                if($this->dto->mbNo){
                    if($this->dto->mbNo!=$pri){
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

                    if($this->dto->mbNo){
                        if($this->dto->mbNo!=$pri){
                            $resultArray['result'] = ResError::paramUnMatchPri;
                            $resultArray['resultMsg'] = ResString::paramUnMatchPri;
                        }
                    }else{
                        $resultArray['result'] = 0;
                        $resultArray['resultMsg'] = ResString::dataNotResult;
                    }

                    $resultArray['link']['done'] = $resultArray['link']['list'];
                //쓰기모드
                }else{
                    $resultArray['link']['update'] = str_replace("update", "insert", $resultArray['link']['update']);
                    $resultArray['link']['done'] = $resultArray['link']['list'];
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
			$this->dto->mbId = Utils::getPostParam('mbId');
			$this->dto->mbNick = Utils::getPostParam('mbNick');
			$this->dto->mbPwd = Utils::getPostParam('mbPwd');
			$this->dto->mbKey = Utils::getPostParam('mbKey');
			$this->dto->mbLevel = Utils::getPostParam('mbLevel',ResError::no);
			$this->dto->mbPoint = Utils::getPostParam('mbPoint',ResError::no);
			$this->dto->mbGender = Utils::getPostParam('mbGender');
			$this->dto->mbAgent = Utils::getPostParam('mbAgent');
			$this->dto->mbRegIp = Utils::getPostParam('mbRegIp');
			$this->dto->mbUpDt = Utils::getPostParam('mbUpDt');
			$this->dto->mbDelYn = Utils::getPostParam('mbDelYn',ResError::no);
                }catch(Exception $e){
                    $json = json_decode($e);
                    $this->dto->result = (int)$json->code;
                    return $this->dto;
                }

                //param값 유효성 검사
                $this->dto->result = parent::checkEmptyValue($this->dto,array('result','mbNo','mbLevel','mbPoint','mbRegDt','mbDelYn'));
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
                    $pri = (int)Utils::getPostParam('mbNo');
                }catch(Exception $e){
                    $json = json_decode($e);
                    $this->dto->result = (int)$json->code;
                    return $this->dto;
                }
                
                $this->dto = $this->dao->getViewById($pri);
                if($this->dto->mbNo){
                    if($this->dto->mbNo!=$pri){
                        $this->dto->result =  ResError::paramUnMatchPri;
                        return $this->dto;
                    }
                }else{
                    $this->dto->result = ResError::noResultById;
                    return $this->dto;
                }

                try{
			$this->dto->mbId = Utils::getPostParam('mbId');
			$this->dto->mbNick = Utils::getPostParam('mbNick');
			$this->dto->mbPwd = Utils::getPostParam('mbPwd');
			$this->dto->mbKey = Utils::getPostParam('mbKey');
			$this->dto->mbLevel = Utils::getPostParam('mbLevel',ResError::no);
			$this->dto->mbPoint = Utils::getPostParam('mbPoint',ResError::no);
			$this->dto->mbGender = Utils::getPostParam('mbGender');
			$this->dto->mbAgent = Utils::getPostParam('mbAgent');
			$this->dto->mbRegIp = Utils::getPostParam('mbRegIp');
			$this->dto->mbUpDt = Utils::getPostParam('mbUpDt');
			$this->dto->mbDelYn = Utils::getPostParam('mbDelYn',ResError::no);
			$this->dto->mbNo = (int)Utils::getPostParam('mbNo');
			$this->dto->mbLevel = Utils::getPostParam('mbLevel',ResError::no);
			$this->dto->mbPoint = Utils::getPostParam('mbPoint',ResError::no);
			$this->dto->mbDelYn = Utils::getPostParam('mbDelYn',ResError::no);
                }catch(Exception $e){
                    $json = json_decode($e);
                    $this->dto->result = (int)$json->code;
                    return $this->dto;
                }

                //param값 유효성 검사
                $this->dto->result = parent::checkEmptyValue($this->dto,array('result','mbLevel','mbPoint','mbRegDt','mbDelYn'));
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
                return $this->dto;
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
                if($this->searchField=='mb_no' || $this->searchField=='mb_id' || $this->searchField=='mb_nick' || $this->searchField=='mb_pwd' || $this->searchField=='mb_key' || $this->searchField=='mb_level' || $this->searchField=='mb_point' || $this->searchField=='mb_agent' || $this->searchField=='mb_reg_ip' || $this->searchField=='mb_reg_dt' || $this->searchField=='mb_del_yn'){

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