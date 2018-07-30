<?php
/**
* Description of WebAdminAuth Controller
* @description Funhansoft PHP auto templet
* @date 2013-09-02
* @copyright (c)funhansoft.com
* @license http://funhansoft.com/license
* @version 0.2.1
*/
        class WebAdminAuth extends ControllerBase{

            private $dao;
            private $dto;
            private $searchField;
            private $searchValue;

            /**
            * @brief
            **/
            function __construct(){
                parent::__construct();
                $this->dao = new WebAdminAuthDAO();
                $this->dto = new WebAdminAuthDTO();
            }

            public function main(){
                $this->setSearchParam();
                $this->dao->setListLimitRow(30);

                //메뉴관리
                $menuArr = $this->getViewer()->getPageMenu();
                unset($menuArr['result']);
                
                $resultArray = array();
                $resultArray['data'] = $this->dao->getList($this->searchField,$this->searchValue);  
                $resultArray['menu'] = $menuArr;
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

                if($this->dto->auNo){
                    if($this->dto->auNo!=$pri){
                        $resultArray['result'] = ResError::paramUnMatchPri;
                        $resultArray['resultMsg'] = ResString::paramUnMatchPri;
                    }
                    
                    //메뉴관리
                    $menuArr = $this->getViewer()->getPageMenu();
                    $menuName = '';
                    while ($parent = current($menuArr['category'])) {
                        $pkey = key($menuArr['category']);
                        while ($control = current($menuArr['categorySub'][$pkey])) {
                            if (key($menuArr['categorySub'][$pkey]) == $this->dto->auMenu) {
                                $menuName = '['.$parent.'] '.$control;
                                break;
                            }
                            next($menuArr['categorySub'][$pkey]);
                        }
                        if($menuName!='') break;
                        next($menuArr['category']);
                    }
                    
                    $this->dto->auMenu = $menuName;
                    
                    $this->dto->auAuth = str_replace('4', '읽기', $this->dto->auAuth);
                    $this->dto->auAuth = str_replace('2', '등록/수정', $this->dto->auAuth);
                    $this->dto->auAuth = str_replace('1', '삭제', $this->dto->auAuth);
                    
                }else{
                    $resultArray['result'] = 0;
                    $resultArray['resultMsg'] = ResString::dataNotResult;
                }

                $resultArray['link']['done'] = $resultArray['link']['list'];
                $resultArray['data'] = $this->dto;
                $resultArray['menu'] = $menuArr;
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
                    
                    $mb_id = Session::getSession('admin_mb_id');

                    if($this->dto->mbId){
                        if($this->dto->mbId==$mb_id){
                            $resultArray['result'] =  ResError::access;
                            $resultArray['resultMsg'] =  '본인의 권한은 변경할 수 없습니다.';
                            return $resultArray;
                        }
                    }
                    

                    if($this->dto->auNo){
                        if($this->dto->auNo!=$pri){
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
                Session::startSession();
                $ss_mb_auth = Session::getSession('admin_mb_auth');
                if( $ss_mb_auth!='super'){
                    $resultArray['result'] = ResError::access;
                    $resultArray['resultMsg'] = ResString::authNotSuperResult;
                    return $resultArray;
                }
                try{
                    $this->dto->mbId = Utils::getPostParam('mbId');
                    $this->dto->auMenu = Utils::getPostParam('auMenu',ResError::no);
                    $this->dto->auAuth = implode(',',Utils::getPostParam('auAuth'));
                }catch(Exception $e){
                    $json = json_decode($e);
                    $this->dto->result = (int)$json->code;
                    return $this->dto;
                }

                //param값 유효성 검사
                $this->dto->result = parent::checkEmptyValue($this->dto,array('result','auNo'));
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

                if($this->dto->auAuth==null){
//                    $this->dto->result = $this->dao->($this->dto);
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
                Session::startSession();
                $ss_mb_auth = Session::getSession('admin_mb_auth');
                if( $ss_mb_auth!='super'){
                    $resultArray['result'] = ResError::access;
                    $resultArray['resultMsg'] = ResString::authNotSuperResult;
                    return $resultArray;
                }
                //고유값으로 값을 가져온다.
                try{
                    $pri = Utils::getPostParam('auNo');
                }catch(Exception $e){
                    $json = json_decode($e);
                    $this->dto->result = (int)$json->code;
                    return $this->dto;
                }
                $this->dto = $this->dao->getViewById($pri);
                if($this->dto->auNo){
                    if($this->dto->auNo!=$pri){
                        $this->dto->result =  ResError::paramUnMatchPri;
                        return $this->dto;
                    }
                }else{
                    $this->dto->result = ResError::noResultById;
                    return $this->dto;
                }

                try{
                    $this->dto->auMenu = Utils::getPostParam('auMenu');
                    $this->dto->auAuth = implode(',',Utils::getPostParam('auAuth'));
                    $this->dto->mbId = Utils::getPostParam('mbId');
                }catch(Exception $e){
                    $json = json_decode($e);
                    $this->dto->result = (int)$json->code;
                    return $this->dto;
                }

                //param값 유효성 검사
                $this->dto->result = parent::checkEmptyValue($this->dto,array('result'));
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
                $this->getViewer()->setResponseType('JSON');
                //고유값으로 값을 가져온다.
                try{
                    $pri = (int)Utils::getPostParam('id');
                }catch(Exception $e){
                    $json = json_decode($e);
                    $this->dto->result = (int)$json->code;
                    return $this->dto;
                }

                // 관리자 super 권한 체크 (super 변경은 super만 허용)
                Session::startSession();
                $ss_mb_auth = Session::getSession('admin_mb_auth');
                if( $ss_mb_auth!='super'){
                    $resultArray['result'] = ResError::access;
                    $resultArray['resultMsg'] = '최고 관리자만 변경할 수 있습니다.';
                    return $resultArray;
                }

 
                $this->dto = $this->dao->getViewById($pri);
                $mb_id = Session::getSession('admin_mb_id');

                if($this->dto->mbId){
                    if($this->dto->mbId==$mb_id){
                        $resultArray['result'] =  ResError::access;
                        $resultArray['resultMsg'] =  '본인의 권한은 변경할 수 없습니다.';
                        return $resultArray;
                    }
                }


                $resultArray['result'] = $this->dao->deleteFromPri($pri);
                return $resultArray;
                
            }


            /*
             * @brief 검색 파라미터 초기화
             * @return null
             */
            private function setSearchParam(){
                $this->searchField = Utils::getUrlParam('sf',1);
                $this->searchValue = Utils::getUrlParam('sv',1);
                if($this->searchField=='mb_id' || $this->searchField=='au_menu' || $this->searchField=='au_auth'){

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