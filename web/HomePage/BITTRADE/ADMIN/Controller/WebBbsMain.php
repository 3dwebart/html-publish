<?php
/**
* Description of WebBbsMain Controller
* @description FunHanSoft Co.,Ltd PHP auto templet
* @date 2015-09-20
* @copyright (c)funhansoft.com
* @license 해당 사이트 이외의 사용은 엄격히 금지되어 있습니다.
* @version 0.2.2
*/
        class WebBbsMain extends ControllerBase{

            private $dao;
            private $dto;
            private $searchField;
            private $searchValue;
            private $cmtListRow = 15;

            /**
            * @brief
            **/
            function __construct(){
                parent::__construct();
                $this->dao = new WebBbsMainDAO();
                $this->dto = new WebBbsMainDTO();
                $this->dao->setListLimitRow(30);
                //$this->dao->setListOrderBy(array('is_'=>'ASC','bbs_no'=>'ASC')); //정렬값
            }

            private function getBbsName($ch_no){
                switch((int)$ch_no){
                    case 100:
                        return '공지사항';
                    break;
                    case 110:
                        return '1:1문의';
                    break;
                    case 120:
                        return '신고하기';
                    break;
                    case 130:
                        return '제안하기';
                    break;
                }
            }

            public function main(){
                $this->setSearchParam();

                $resultArray = array();
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
                $resultArray['link']['cmtdel'] = Utils::createLink('WebBbsMainCmt/delete');
                $resultArray['token'] = parent::createTocken();
                $resultArray['cmtdata'] = '';
                $resultArray['common'] = new WebBbsMainCmtDTO();
                $pri = Utils::getUrlParam('id',1);
                $this->dto = $this->dao->getViewById($pri);

                $this->dto->content = Utils::getHTMLParam($this->dto->content);

                if($this->dto->bbsNo){
                    if($this->dto->bbsNo!=$pri){
                        $resultArray['result'] = ResError::paramUnMatchPri;
                        $resultArray['resultMsg'] = ResString::paramUnMatchPri;
                    }

                }else{
                    $resultArray['result'] = 0;
                    $resultArray['resultMsg'] = ResString::dataNotResult;
                }

//                if($this->dto->cmtUseYn=='Y'){
                    $resultArray['token'] = parent::createTocken();
                    $cmtdao = new WebBbsMainCmtDAO();
                    $cmtdao->setListOrderBy(array('cmt_no'=>'DESC','sort'=>'ASC')); //정렬값
                    $cmtdao->setListLimitRow($this->cmtListRow);
                    $resultArray['cmtdata'] = $cmtdao->getList('bbs_no',$this->dto->bbsNo);
                    $resultArray['common'] = $cmtdao->getListCount('bbs_no',$this->dto->bbsNo);
//                }

                $resultArray['link']['done'] = $resultArray['link']['list'];
                $resultArray['data'] = $this->dto;

                return $resultArray;
            }

            /*
             * @brief 데이터 리스트
             * @return array object
             */
            public function cmtlists(){
                $this->getViewer()->setResponseType('JSON');
                $cmtdao = new WebBbsMainCmtDAO();
                $cmtdao->setListLimitRow($this->cmtListRow);

                if(parent::checkReferer()<0){
                    return array();
                }

                $page = (int)Utils::getUrlParam('page',ResError::no);
                $bbsNo = (int)Utils::getUrlParam('id',ResError::no);
                if($page){
                    if($page<1) $page=1;
                    $cmtdao->setListLimitStart($cmtdao->getListLimitRow() * ($page-1));
                }else{
                    $cmtdao->setListLimitStart(0);
                }
                return $cmtdao->getList('bbs_no',$bbsNo);
            }

            public function form(){
                $resultArray = array();
                $resultArray['result'] = ResError::no;
                $resultArray['link'] = parent::getLinkURL();
                $pri = Utils::getUrlParam('id',ResError::no);

                //수정모드
                if($pri) {
                    $this->dto = $this->dao->getViewById($pri);
                    $this->dto->subject = Utils::unescape($this->dto->subject);

                    if($this->dto->bbsNo){
                        if($this->dto->bbsNo!=$pri){
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
                try{

                    $this->dto->mbId = 'admin';
                    $this->dto->mbLevel = '10';
                    $this->dto->mbNick = '관리자';

                    $this->dto->chNo = Utils::getPostParam('chNo');
                    $this->dto->cateName = $this->getBbsName($this->dto->chNo);
                    $this->dto->parentBbsNo = '0';
                    $this->dto->isNotice = Utils::getPostParam('isNotice',ResError::no);
                    if($this->dto->isNotice!='1') $this->dto->isNotice='0';
                    $this->dto->isSecret = Utils::getPostParam('isSecret',ResError::no);
                    if($this->dto->isSecret!='1') $this->dto->isSecret='0';
                    $this->dto->viewPwd = Utils::getPostParam('viewPwd');
                    if($this->dto->isSecret!='1'){
                        $this->dto->isSecret='0';
                        $this->dto->viewPwd = '';
                    }
                    $this->dto->isHtml = '1';

                    $this->dto->subject = Utils::getPostParam('subject');
                    $this->dto->subjectKr = Utils::getPostParam('subjectKr',ResError::no);
                    $this->dto->subjectCn = Utils::getPostParam('subjectCn',ResError::no);
                    $this->dto->content = Utils::getPostHTMLParam('content');
                    $this->dto->contentKr = Utils::getPostHTMLParam('contentKr',ResError::no);
                    $this->dto->contentCn = Utils::getPostHTMLParam('contentCn',ResError::no);
                    $this->dto->imageUrl = Utils::getPostParam('imageUrl',ResError::no);
                    $this->dto->viewLevel = Utils::getPostParam('viewLevel');
                    $this->dto->cmtLevel = Utils::getPostParam('cmtLevel');
                    $this->dto->viewPoint = '0';

                    $this->dto->cmtUseYn = Utils::getPostParam('cmtUseYn',ResError::no);
                    if($this->dto->cmtUseYn!='Y') $this->dto->cmtUseYn='N';

                    $this->dto->scrapUseYn = Utils::getPostParam('scrapUseYn',ResError::no);
                    if($this->dto->scrapUseYn!='Y') $this->dto->scrapUseYn='N';
                    $this->dto->copyUseYn = Utils::getPostParam('copyUseYn',ResError::no);
                    if($this->dto->copyUseYn!='Y') $this->dto->copyUseYn='N';
                    $this->dto->searchUseYn = Utils::getPostParam('searchUseYn',ResError::no); //검색노출
                    if($this->dto->searchUseYn!='Y') $this->dto->searchUseYn='N';

                    $this->dto->hit = '0';
                    $this->dto->cmtCnt = '0';
                    $this->dto->modDt = '0000-00-00 00:00:00';
                    $this->dto->regIp = Utils::getClientIP();
                    $this->dto->etc1 = Utils::getPostParam('etc1',ResError::no);
                    $this->dto->etc2 = Utils::getPostParam('etc2',ResError::no);
                    $this->dto->etc3 = Utils::getPostParam('etc3',ResError::no);
                    $this->dto->etc4 = Utils::getPostParam('etc4',ResError::no);
                    $this->dto->etc5 = Utils::getPostParam('etc5',ResError::no);

                }catch(Exception $e){
                    $json = json_decode($e);
                    $this->dto->result = (int)$json->code;
                    return $this->dto;
                }

                //param값 유효성 검사
                $this->dto->result = parent::checkEmptyValue($this->dto,array('result','bbsNo','subjectKr','subjectCn','contentKr','contentCn','isNotice','isSecret','viewLevel','cmtLevel','viewPwd','imageUrl','cmtCnt','regDt','etc1','etc2','etc3','etc4','etc5'));
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
                    $pri = (int)Utils::getPostParam('bbsNo');
                }catch(Exception $e){
                    $json = json_decode($e);
                    $this->dto->result = (int)$json->code;
                    return $this->dto;
                }

                $this->dto = $this->dao->getViewById($pri);
                if($this->dto->bbsNo){
                    if($this->dto->bbsNo!=$pri){
                        $this->dto->result =  ResError::paramUnMatchPri;
                        return $this->dto;
                    }
                }else{
                    $this->dto->result = ResError::noResultById;
                    return $this->dto;
                }

                try{
                    $this->dto->chNo = Utils::getPostParam('chNo');  //int type
                    $this->dto->cateName = $this->getBbsName($this->dto->chNo);
                    $this->dto->isHtml = $this->initModifyPostParam('isHtml',ResError::no);  //int type
                    $this->dto->subject = Utils::getPostParam('subject');
                    $this->dto->subjectKr = Utils::getPostParam('subjectKr',ResError::no);
                    $this->dto->subjectCn = Utils::getPostParam('subjectCn',ResError::no);
                    $this->dto->viewLevel = $this->initModifyPostParam('viewLevel',ResError::no);
                    $this->dto->viewPoint = $this->initModifyPostParam('viewPoint',ResError::no);  //int type
                    $this->dto->viewPwd = $this->initModifyPostParam('viewPwd',ResError::no);
                    $this->dto->imageUrl = $this->initModifyPostParam('imageUrl',ResError::no);
                    $this->dto->cmtUseYn = $this->initModifyPostParam('cmtUseYn',ResError::no);
                    $this->dto->cmtLevel = Utils::getPostParam('cmtLevel',ResError::no);
                    $this->dto->hit = $this->initModifyPostParam('hit',ResError::no);  //int type
                    $this->dto->cmtCnt = $this->initModifyPostParam('cmtCnt',ResError::no);
                    $this->dto->etc1 = $this->initModifyPostParam('etc1',ResError::no);
                    $this->dto->etc2 = $this->initModifyPostParam('etc2',ResError::no);
                    $this->dto->etc3 = $this->initModifyPostParam('etc3',ResError::no);
                    $this->dto->etc4 = $this->initModifyPostParam('etc4',ResError::no);
                    $this->dto->etc5 = $this->initModifyPostParam('etc5',ResError::no);

                    $this->dto->isNotice = Utils::getPostParam('isNotice',ResError::no);
                    if($this->dto->isNotice!='1') $this->dto->isNotice='0';
                    $this->dto->isSecret = Utils::getPostParam('isSecret',ResError::no);
                    if($this->dto->isSecret!='1') $this->dto->isSecret='0';
                    if($this->dto->isSecret!='1'){
                        $this->dto->isSecret='0';
                        $this->dto->viewPwd = '';
                    }

                    $this->dto->cmtUseYn = Utils::getPostParam('cmtUseYn',ResError::no);
                    if($this->dto->cmtUseYn!='Y') $this->dto->cmtUseYn='N';

                    $this->dto->scrapUseYn = Utils::getPostParam('scrapUseYn',ResError::no);
                    if($this->dto->scrapUseYn!='Y') $this->dto->scrapUseYn='N';
                    $this->dto->copyUseYn = Utils::getPostParam('copyUseYn',ResError::no);
                    if($this->dto->copyUseYn!='Y') $this->dto->copyUseYn='N';
                    $this->dto->searchUseYn = Utils::getPostParam('searchUseYn',ResError::no); //검색노출
                    if($this->dto->searchUseYn!='Y') $this->dto->searchUseYn='N';

                    $this->dto->modDt = Utils::getDateNow();
                    $this->dto->content = Utils::getPostHTMLParam('content');
                    $this->dto->contentKr = Utils::getPostHTMLParam('contentKr',ResError::no);
                    $this->dto->contentCn = Utils::getPostHTMLParam('contentCn',ResError::no);

                }catch(Exception $e){
                    $json = json_decode($e);
                    $this->dto->result = (int)$json->code;
                    return $this->dto;
                }

                //param값 유효성 검사
                $this->dto->result = parent::checkEmptyValue($this->dto,array('result','bbsNo','subjectKr','subjectCn','contentKr','contentCn','isNotice','isSecret','viewLevel','cmtLevel','viewPwd','imageUrl','cmtCnt','regDt','etc1','etc2','etc3','etc4','etc5'));
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
                $this->getViewer()->setResponseType('JSON');
                //고유값으로 값을 가져온다.
                 try{
                    $pri = (int)Utils::getPostParam('bbsNo');
                }catch(Exception $e){
                    $json = json_decode($e);
                    $this->dto->result = (int)$json->code;
                    return $this->dto;
                }

                $this->dto = $this->dao->getViewById($pri);
                if($this->dto->bbsNo){
                    if($this->dto->bbsNo!=$pri){
                        $this->dto->result =  ResError::paramUnMatchPri;
                        return $this->dto;
                    }

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
                if($this->searchField=='bbs_no' || $this->searchField=='cate_name' || $this->searchField=='is_notice' || $this->searchField=='is_secret' || $this->searchField=='mb_id' || $this->searchField=='mb_nick' || $this->searchField=='subject' || $this->searchField=='view_level' || $this->searchField=='view_pwd' || $this->searchField=='image_url' || $this->searchField=='cmt_level' || $this->searchField=='cmt_cnt' || $this->searchField=='reg_dt' || $this->searchField=='reg_ip' || $this->searchField=='etc1' || $this->searchField=='etc2' || $this->searchField=='etc3' || $this->searchField=='etc4' || $this->searchField=='etc5'){

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