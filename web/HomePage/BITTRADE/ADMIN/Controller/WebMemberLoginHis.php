<?php
/**
* Description of WebMemberLoginHis Controller
* @description Funhansoft PHP auto templet
* @date 2013-09-05
* @copyright (c)funhansoft.com
* @license http://funhansoft.com/license
* @version 0.2.1
*/
        class WebMemberLoginHis extends ControllerBase{

            private $dao;
            private $dto;
            private $searchField;
            private $searchValue;

            /**
            * @brief
            **/
            function __construct(){
                parent::__construct();
                $this->dao = new WebMemberLoginHisDAO();
                $this->dto = new WebMemberLoginHisDTO();
            }

            public function main(){
                $this->setSearchParam();
                $this->dao->setListLimitRow(30);
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
                $this->dto = $this->dao->getViewById($pri);

                if($this->dto->lhNo){
                    if($this->dto->lhNo!=$pri){
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
                $this->getViewer()->setResponseType('404');
                $resultArray = array();
                $resultArray['result'] = ResError::no;
                $resultArray['link'] = parent::getLinkURL();
                $pri = Utils::getUrlParam('id',ResError::no);

                //수정모드
                if($pri) {
                    $this->dto = $this->dao->getViewById($pri);

                    if($this->dto->lhNo){
                        if($this->dto->lhNo!=$pri){
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
                $dtfrom = (isset($_GET['svdf']))?$_GET['svdf']:'';
                $dtto = (isset($_GET['svdt']))?$_GET['svdt']:'';
                return $this->dao->getList($this->searchField,$this->searchValue,$dtfrom,$dtto);
            }

            /*
             * @brief 데이터 삽입
             * @return object
             */
            public function insert(){
                $this->getViewer()->setResponseType('404');
                try{
                    $this->dto->lhType = Utils::getPostParam('lhType');
                    $this->dto->mbNo = Utils::getPostParam('mbNo',ResError::no);
                    $this->dto->mbId = Utils::getPostParam('mbId');
                    $this->dto->mbKey = Utils::getPostParam('mbKey');
                    $this->dto->lhResultCode = (int)Utils::getPostParam('lhResultCode');
                    $this->dto->lhResultMsg = Utils::getPostParam('lhResultMsg');
                    $this->dto->lhRegIp = Utils::getClientIP();
                }catch(Exception $e){
                    $json = json_decode($e);
                    $this->dto->result = (int)$json->code;
                    return $this->dto;
                }

                //param값 유효성 검사
                $this->dto->result = parent::checkEmptyValue($this->dto,array('result','lhNo','mbNo','lhRegDt','lhIpBlock'));
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
                try{
                    $this->dto->lhNo = (int)Utils::getPostParam('lhNo');
                    $this->dto->lhBlockYn = Utils::getPostParam('mode');
                    $this->dto->lhRegIp = Utils::getPostParam('lhRegIp');

                    if($this->dto->lhBlockYn=='1'){
                        $this->dto->lhBlockYn = 'N';
                    }else{
                        $this->dto->lhBlockYn = 'Y';
                    }
                }catch(Exception $e){
                    $json = json_decode($e);
                    $this->dto->result = (int)$json->code;
                    return $this->dto;
                }
                //레퍼러 도메인 체크
                $this->dto->result = parent::checkReferer();
                if($this->dto->result<0){
                     return $this->dto;
                }
                $this->dto->result = $this->dao->setUpdateTempBlock($this->dto->lhNo,$this->dto->lhBlockYn);
                // 레디스 로그인 아이피 차단 아이피 삭제
                if((int)$this->dto->result==1 && $this->dto->lhBlockYn=='N'){
                    $this->initRedisServer($this->config['redis']['host'],$this->config['redis']['port'], null,  $this->config['redis']['db_tmp'], null);
                    $data = $this->getRedisData($this->dto->lhRegIp);
                    if($data){
                        $this->delRedisData($this->dto->lhRegIp);
                    }
                }
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
                if($this->searchField=='lh_no' || $this->searchField=='mb_no' || $this->searchField=='mb_id' || $this->searchField=='mb_key' || $this->searchField=='lh_result_msg' || $this->searchField=='lh_reg_dt' || $this->searchField=='lh_reg_ip'){

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