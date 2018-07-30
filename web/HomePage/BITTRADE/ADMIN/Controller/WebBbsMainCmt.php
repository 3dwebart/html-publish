<?php
/**
* Description of WebBbsMainCmt Controller
* @description FunHanSoft Co.,Ltd PHP auto templet
* @date 2013-09-21
* @copyright (c)funhansoft.com
* @license 해당 사이트 이외의 사용은 엄격히 금지되어 있습니다.
* @version 0.2.2
*/
        class WebBbsMainCmt extends ControllerBase{

            private $dao;
            private $dto;
            private $searchField;
            private $searchValue;

            /**
            * @brief
            **/
            function __construct(){
                parent::__construct();
                $this->dao = new WebBbsMainCmtDAO();
                $this->dto = new WebBbsMainCmtDTO();
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
                
                $this->dto->content = Utils::getHTMLParam($this->dto->content);

                if($this->dto->cmtNo){
                    if($this->dto->cmtNo!=$pri){
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

                    if($this->dto->cmtNo){
                        if($this->dto->cmtNo!=$pri){
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

                if(parent::checkReferer()<0){
                    return array();
                }

                try{
                    $this->dto->mbId = 'admin';
                    $this->dto->mbLevel = '10';
                    $this->dto->mbNick = '관리자';
                    $this->dto->sort = 0;
                    $this->dto->bbsNo = (int)Utils::getPostParam('bbsNo');
                    $this->dto->parentCmtNo = (int)Utils::getPostParam('parentCmtNo');
                    $this->dto->imageUrl = Utils::getPostParam('imageUrl',ResError::no);
                    $this->dto->regIp = Utils::getClientIP();
                    $this->dto->content = Utils::getPostHTMLParam('textarea-cmtcontent');
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

                //param값 유효성 검사
                $this->dto->result = parent::checkEmptyValue($this->dto,array('result','cmtNo','imageUrl','regDt','modDt'));
                if($this->dto->result<0){
                     return $this->dto;
                }

                //토큰 유효성 검사 - 마지막에
                $this->dto->result = parent::checkToken();
                if($this->dto->result<0){
                     return $this->dto;
                }

                $this->dto->result = $this->dao->setInsert($this->dto);
                if($this->dto->result>0){
                    $bbsdao = new WebBbsMainDAO();
                    $bbsdao->setCmtCount($this->dto->bbsNo,+1);
                }
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
                    $pri = (int)Utils::getPostParam('cmtNo');
                }catch(Exception $e){
                    $json = json_decode($e);
                    $this->dto->result = (int)$json->code;
                    return $this->dto;
                }

                $this->dto = $this->dao->getViewById($pri);
                if($this->dto->cmtNo){
                    if($this->dto->cmtNo!=$pri){
                        $this->dto->result =  ResError::paramUnMatchPri;
                        return $this->dto;
                    }
                }else{
                    $this->dto->result = ResError::noResultById;
                    return $this->dto;
                }

                try{
                    $this->initModifyPostParam('sort');  //int type
                    $this->initModifyPostParam('imageUrl',ResError::no);
                    $this->dto->modDt = Utils::getDateNow();
                    $this->dto->content = Utils::getPostHTMLParam('content');
                }catch(Exception $e){
                    $json = json_decode($e);
                    $this->dto->result = (int)$json->code;
                    return $this->dto;
                }

                //param값 유효성 검사
                $this->dto->result = parent::checkEmptyValue($this->dto,array('result','regDt','imageUrl'));
                if($this->dto->result<0){
                     return $this->dto;
                }

                //레퍼러 도메인 체크
                $this->dto->result = parent::checkReferer();
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
                    $this->returnarr['result'] = (int)$json->code;
                    return $this->returnarr;
                }

                //$this->initDAO();
                $this->dto = $this->dao->getViewById($pri);
                //Input Exception Field ,'cmtNo','sort','bbsNo','parentCmtNo','mbId','mbLevel','mbNick','content','imageUrl','regDt','modDt','regIp'
                $this->returnarr = $this->returnDTO($this->dto, array('sort','parentCmtNo','mbId','content','imageUrl','regDt','modDt','regIp'));
                if($this->dto->cmtNo){
                    if($this->dto->cmtNo!=$pri){
                        $this->returnarr['result'] =  ResError::paramUnMatchPri;
                        return $this->returnarr;
                    }

                    $this->returnarr['result'] = $this->dao->deleteFromPri($pri);
                    if($this->returnarr['result']>0){
                        $bbsdao = new WebBbsMainDAO();
                        $bbsdao->setCmtCount($this->dto->bbsNo, -1);
                    }
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
                if($this->searchField=='cmt_no' || $this->searchField=='mb_id' || $this->searchField=='mb_nick' || $this->searchField=='image_url' || $this->searchField=='reg_dt' || $this->searchField=='reg_ip'){

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