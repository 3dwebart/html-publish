<?php
/**
* Description of WebAdminMember Controller
* @description Funhansoft PHP auto templet
* @date 2013-09-01
* @copyright (c)funhansoft.com
* @license http://funhansoft.com/license
* @version 0.2.1
*/
        class WebAdminMember extends ControllerBase{

            private $dao;
            private $dto;
            private $searchField;
            private $searchValue;

            /**
            * @brief
            **/
            function __construct(){
                parent::__construct();
                $this->dao = new WebAdminMemberDAO();
                $this->dto = new WebAdminMemberDTO();
            }

            private function initDAO(){
                if(!$this->dao){
                    $this->dao = new WebAdminMemberDAO();
                    $this->dao->setListLimitRow(30);
                }
            }

            public function main(){
                $this->setSearchParam();
                $this->dao->setListLimitRow(30);

                Session::startSession();
                $ss_mb_auth = Session::getSession('admin_mb_auth');

                $resultArray = array();
                $resultArray['data'] = $this->dao->getList($this->searchField,$this->searchValue);
                $resultArray['common'] = $this->dao->getListCount($this->searchField,$this->searchValue);
                $resultArray['link'] = parent::getLinkURL();
                $resultArray['auth'] = $ss_mb_auth;
                return $resultArray;
            }

            public function view(){
                $resultArray = array();
                $resultArray['result'] = ResError::no;
                $resultArray['link'] = parent::getLinkURL();
                $pri = Utils::getUrlParam('id',ResError::no);
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

                // 관리자 super 권한 체크 (super 변경은 super만 허용)
                Session::startSession();
                $ss_mb_auth = Session::getSession('admin_mb_auth');
                if( $ss_mb_auth!='super' && $this->dto->mbAuth=='super' ){
                    $resultArray['result'] = ResError::access;
                    $resultArray['resultMsg'] = ResString::authNotSuperResult;
                    return $resultArray;
                }

                // 관리자 일반 권한일 경우 내 정보만 액세스
                $ss_mb_no = Session::getSession('admin_mb_no');
                if($ss_mb_auth!='super' && $ss_mb_no!=$this->dto->mbNo){
                    $resultArray['result'] = ResError::access;
                    $resultArray['resultMsg'] = ResString::authNotSuperResult;
                    return $resultArray;
                }

                $resultArray['auth'] = $ss_mb_auth;

                if($this->dto->mbAuth=='super'){
                    $this->dto->mbAuth = '최고관리자권한';
                }else{
                    $this->dto->mbAuth = '기본권한';
                }

                if($this->dto->mbAccessIp=='0.0.0.0'){
                    $this->dto->mbAccessIp = '0.0.0.0(모든IP)';
                }

                $resultArray['link']['done'] = $resultArray['link']['list'];
                unset($this->dto->mbPassword);
                $resultArray['data'] = $this->dto;

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

                    $this->dto->mbAccessIp='0.0.0.0';
                }

                // 관리자 super 권한 체크 (super 변경은 super만 허용)
                Session::startSession();
                $ss_mb_auth = Session::getSession('admin_mb_auth');
                if( $ss_mb_auth!='super' && $this->dto->mbAuth=='super' ){
                    $resultArray['result'] = ResError::access;
                    $resultArray['resultMsg'] = ResString::authNotSuperResult;
                    return $resultArray;
                }

                // 관리자 일반 권한일 경우 내 정보만 액세스
                $ss_mb_no = Session::getSession('admin_mb_no');
                if($ss_mb_auth!='super' && $ss_mb_no!=$this->dto->mbNo){
                    $resultArray['result'] = ResError::access;
                    $resultArray['resultMsg'] = ResString::authNotSuperResult;
                    return $resultArray;
                }

                $resultArray['data'] = $this->dto;
                $resultArray['token'] = parent::createTocken();
                $resultArray['auth'] = $ss_mb_auth;

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
                    $this->dto->mbId = base64_decode(Utils::getPostParam('mbId'));
                    $this->dto->mbPassword = Utils::getPostParam('mbPassword');
                    $this->dto->mbName = Utils::getPostParam('mbName');
                    $this->dto->mbAuth = ''.Utils::getPostParam('mbAuth',ResError::no);
                    $this->dto->mbAccessIp = Utils::getPostParam('mbAccessIp');
                    $this->dto->mbTodayLogin = '0000-00-00 00:00:00';
                    $this->dto->mbLoginIp = '';
                }catch(Exception $e){
                    $json = json_decode($e);
                    $this->dto->result = (int)$json->code;
                    return $this->dto;
                }

                // 관리자 super 권한 체크 (super 변경은 super만 허용)
                Session::startSession();
                $ss_mb_auth = Session::getSession('admin_mb_auth');
                if( $ss_mb_auth!='super' && $this->dto->mbAuth=='super' ){
                    $resultArray['result'] = ResError::access;
                    $resultArray['resultMsg'] = ResString::authNotSuperResult;
                    return $resultArray;
                }

                //param값 유효성 검사
                $this->dto->result = parent::checkEmptyValue($this->dto,array('result','mbNo','mbAuth','mbAccessIp','mbTodayLogin','mbLoginIp','mbDatetime'));
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
             * super 권한 변경은 super 관리자만
             */
            public function update(){
                $this->getViewer()->setResponseType('JSON');

                //고유값으로 값을 가져온다.
                $pri = (int)Utils::getPostParam('mbNo');
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
                    $password = Utils::getPostParam('mbPassword',ResError::no);
                    $this->dto->mbName = Utils::getPostParam('mbName');
                    $mbauth = Utils::getPostParam('mbAuth',ResError::no);
                    $this->dto->mbAccessIp = Utils::getPostParam('mbAccessIp');
                }catch(Exception $e){
                    $json = json_decode($e);
                    $this->dto->result = (int)$json->code;
                    return $this->dto;
                }

                // 관리자 super 권한 체크 (super 변경은 super만 허용)
                Session::startSession();
                $ss_mb_auth = Session::getSession('admin_mb_auth');
                if( ($ss_mb_auth!='super' && $mbauth=='super') || ($ss_mb_auth!='super' && $this->dto->mbAuth=='super' && $mbauth=='') ){
                    $resultArray = array();
                    $resultArray['result'] = ResError::access;
                    $resultArray['msg'] = ResString::authNotSuperResult;
                    return $resultArray;
                }else{
                    $this->dto->mbAuth = $mbauth;
                }

                //param값 유효성 검사
                $this->dto->result = parent::checkEmptyValue($this->dto,array('result','mbNo','mbAuth','mbAccessIp','mbPassword','mbTodayLogin','mbLoginIp','mbDatetime'));
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
                //비밀번호
                if($password) $this->dto->mbPassword = ($password);
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
                    $pri = (int)Utils::getPostParam('mbNo');
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
                    $resultArray['resultMsg'] = ResString::authNotSuperResult;
                    return $resultArray;
                }

                $this->initDAO();
                $this->dto = $this->dao->getViewById($pri);

                if($this->dto->mbNo){
                    if($this->dto->mbNo!=$pri){
                        $this->dto->result =  ResError::paramUnMatchPri;
                        return $this->dto;
                    }

                    $this->dto->result = $this->dao->deleteFromPri($pri);
                }else{
                    $this->dto->result =  ResError::noResultById;
                }

                return $this->dto;
            }


            /*
             * @brief 검색 파라미터 초기화
             * @return null
             */
            private function setSearchParam(){
                $this->searchField = Utils::getUrlParam('sf',1);
                $this->searchValue = Utils::getUrlParam('sv',1);
                if($this->searchField=='mb_name' || $this->searchField=='mb_id' || $this->searchField=='mb_password' || $this->searchField=='mb_access_ip' || $this->searchField=='mb_login_ip'){

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
