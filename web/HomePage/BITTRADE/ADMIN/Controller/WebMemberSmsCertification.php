<?php
/**
* Description of WebMemberSmsCertification Controller
* @description FunHanSoft Co.,Ltd  PHP auto templet
* @date 2016-08-24
* @copyright (c)funhansoft.com
* @license 해당 사이트 이외의 사용은 엄격히 금지되어 있습니다.
* @version 4.0.0
*/
        class WebMemberSmsCertification extends ControllerBase{

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
                $this->dto = new WebMemberSmsCertificationDTO();
            }

            //DB를 연결할 경우만
            private function initDAO(){
                if(!$this->dao){
                    $this->dao = new WebMemberSmsCertificationDAO();
                    $this->dao->setListLimitRow(30);
                    //$this->dao->setListOrderBy(array('scNo'=>'DESC')); //정렬값
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
                //Input Exception Field ,'scNo','mbNo','mbId','mbName','mbHp','contryCode','mbCountryDialCode','scRegIp','scRegDt'
                $this->returnarr = $this->returnDTO($this->dto, array());

                if($this->dto->scNo){
                    if($this->dto->scNo!=$pri){
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
                    //Input Exception Field ,'scNo','mbNo','mbId','mbName','mbHp','contryCode','mbCountryDialCode','scRegIp','scRegDt'
                    $this->returnarr = $this->returnDTO($this->dto, array());
                    if($this->dto->scNo){
                        if($this->dto->scNo!=$pri){
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
                    $this->dto->mbNo = (int)Utils::getPostParam('mbNo');
                    $this->dto->mbId = Utils::getPostParam('mbId');
                    $this->dto->mbName = Utils::getPostParam('mbName');
                    $this->dto->mbHp = Utils::getPostParam('mbHp');
                    $this->dto->contryCode = Utils::getPostParam('contryCode');
                    $this->dto->mbCountryDialCode = Utils::getPostParam('mbCountryDialCode');
                    $this->dto->scRegIp = Utils::getPostParam('scRegIp');
                    $this->returnarr = $this->returnDTO($this->dto, array('result','scNo','scRegDt'));
                }catch(Exception $e){
                    $json = json_decode($e);
                    $this->returnarr['result'] = (int)$json->code;
                    return $this->returnarr;
                }

                //param값 유효성 검사
                $this->returnarr['result'] = parent::checkEmptyValue($this->dto,array('result','scNo','scRegDt'));
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
                    $pri = (int)Utils::getPostParam('scNo');
                }catch(Exception $e){
                    $json = json_decode($e);
                    $this->returnarr['result'] = (int)$json->code;
                    return $this->returnarr;
                }

                $this->initDAO();
                $this->dto = $this->dao->getViewById($pri);
                $this->returnarr = $this->returnDTO($this->dto, array('result','scNo','scRegDt'));
                if($this->dto->scNo){
                    if($this->dto->scNo!=$pri){
                        $this->returnarr['result'] =  ResError::paramUnMatchPri;
                        return $this->returnarr;
                    }

                    //수정권한이 있는지 체크

                }else{
                    $this->returnarr['result'] = ResError::noResultById;
                    return $this->returnarr;
                }

                try{
                    $this->initModifyPostParam('scNo');
                    $this->initModifyPostParam('mbNo');  //int type
                    $this->initModifyPostParam('mbId');
                    $this->initModifyPostParam('mbName');
                    $this->initModifyPostParam('mbHp');
                    $this->initModifyPostParam('contryCode');
                    $this->initModifyPostParam('mbCountryDialCode');
                    $this->initModifyPostParam('scRegIp');
                }catch(Exception $e){
                    $json = json_decode($e);
                    $this->returnarr['result'] = (int)$json->code;
                    return $this->returnarr;
                }

                //param값 유효성 검사
                $this->returnarr['result'] = parent::checkEmptyValue($this->dto,array('result','scNo','scRegDt'));
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
                //Input Exception Field ,'scNo','mbNo','mbId','mbName','mbHp','contryCode','mbCountryDialCode','scRegIp','scRegDt'
                $this->returnarr = $this->returnDTO($this->dto, array());
                if($this->dto->scNo){
                    if($this->dto->scNo!=$pri){
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
                if($this->searchField=='sc_no' || $this->searchField=='mb_no' || $this->searchField=='mb_id' || $this->searchField=='mb_name' || $this->searchField=='mb_hp' || $this->searchField=='contry_code' || $this->searchField=='mb_country_dial_code' || $this->searchField=='sc_reg_ip' || $this->searchField=='sc_reg_dt'){

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