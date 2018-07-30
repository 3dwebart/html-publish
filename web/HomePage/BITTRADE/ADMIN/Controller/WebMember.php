<?php
/**
* Description of WebMember Controller
* @description Funhansoft PHP auto templet
* @date 2013-09-05
* @copyright (c)funhansoft.com
* @license http://funhansoft.com/license
* @version 0.2.1
*/
        class WebMember extends ControllerBase{

            private $dao;
            private $dto;
            private $searchField;
            private $searchValue;

            /**
            * @brief
            **/
            function __construct(){
                parent::__construct();
                $this->dao = new WebMemberDAO();
                $this->dto = new WebMemberDTO();
            }

            /*
            * @brief 중복검사
            * @return array object
            */
            public function ajaxIsMember(){
                $this->getViewer()->setResponseType('JSON');
                if(parent::checkReferer()<0){
                    return array();
                }
                $mbId = Utils::getPostParam('mbId',ResError::no);
                $mbName = Utils::getPostParam('mbName',ResError::no);
                if($mbId){
                    return $this->checkMember('mbId',$mbId);
                }else if($mbName){
                    return $this->checkMember('mbName',$mbName);
                }
            }
            /*
             * @brief SMS보내기
             * @return array object
             */
            public function ajaxIsMemberSms(){
                $this->getViewer()->setResponseType('JSON');
                if(parent::checkReferer()<0){
                    return array();
                }
                $mbId = Utils::getPostParam('mbId',ResError::no);
                $mbHp = Utils::getPostParam('mbHp',ResError::no);
                if($mbId){
                    return $this->checkMember('mbId',$mbId);
                }
            }

            private function checkMember($param,$val){
                $this->dao->setListLimitRow(1);
                $this->dao->setListLimitStart(0);

                $resultArray = array();
                $resultArray['result'] = ResError::ok;
                $resultArray['mbNo'] = '';
                $resultArray['mbId'] = '';
                $resultArray['mbName'] = '';

                if($param == 'mbId'){
                    $tmparr = $this->dao->getListByKey('mb_id',$val);
                    if(count($tmparr)>0 && $tmparr[0]->mbNo){
                        $resultArray['result'] = ResError::no;
                        $resultArray['msg'] = '확인되었습니다.';
                        $resultArray['mbNo'] = $tmparr[0]->mbNo;
                        $resultArray['mbId'] = $tmparr[0]->mbId;
                        $resultArray['mbName'] = $tmparr[0]->mbName;
                        $resultArray['mbHp'] = $tmparr[0]->mbHp;
                    }else{
                        $resultArray['result'] = ResError::ok;
                        $resultArray['msg'] = '존재하지 않는 회원입니다.';
                    }
                }else if($param == 'mbName'){
                    $tmparr = $this->dao->getListByKey('mb_name',$val);
                    if(count($tmparr)>0 && $tmparr[0]->mbNo){
                        $resultArray['result'] = ResError::no;
                        $resultArray['msg'] = '확인되었습니다.';
                        $resultArray['mbNo'] = $tmparr[0]->mbNo;
                        $resultArray['mbId'] = $tmparr[0]->mbId;
                        $resultArray['mbName'] = $tmparr[0]->mbName;
                    }else{
                        $resultArray['result'] = ResError::ok;
                        $resultArray['msg'] = '존재하지 않는 회원입니다.';
                    }
                }
                return $resultArray;
            }

            public function main(){
                $this->setSearchParam();
                $this->dao->setListLimitRow(30);

                $resultArray = array();
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

                if($this->dto->mbLevel==0) $this->dto->mbLevel = '일반회원(이메일미인증) (LV.'.$this->dto->mbLevel.')';
                else if($this->dto->mbLevel==1) $this->dto->mbLevel = '일반회원 (LV.'.$this->dto->mbLevel.')';
                else if($this->dto->mbLevel==2) $this->dto->mbLevel = '인증회원 (LV.'.$this->dto->mbLevel.')';
                else  $this->dto->mbLevel = '회원 (LV.'.$this->dto->mbLevel.')';

                if($this->dto->mbGender=='M') $this->dto->mbGender = '남성';
                else if($this->dto->mbGender=='W') $this->dto->mbGender = '여성';
                else if($this->dto->mbGender=='E') $this->dto->mbGender = '기타';
                
                /**************************
                 * 회원밸런스
                 ************************/
                $mbbalanceDAO = new WebMemberDAO();
                $mbbalance = $mbbalanceDAO->getListExtend('mb_no',$this->dto->mbNo);
                
                /**************************
                 * 회원지갑
                 ************************/
                $mbwalletaddrDAO = new WebMemberDespositWalletDAO();
                $mbwalletaddr = $mbwalletaddrDAO->getList('mb_no',$this->dto->mbNo);
                

                $resultArray['link']['done'] = $resultArray['link']['list'];
                $resultArray['data'] = $this->dto;
                $resultArray['datawalletaddr'] = $mbwalletaddr;
                $resultArray['databalance'] = $mbbalance;
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
                    $this->dto->mbNickDt = Utils::getDateNow();
                    $this->dto->mbLevel = 1;
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
                $this->getViewer()->setResponseType('JSON');
                try{
                    $this->dto->mbId = Utils::getPostParam('mbId');
                    $this->dto->mbLastName = Utils::getPostParam('mbLastName');
                    $this->dto->mbFirstName = Utils::getPostParam('mbFirstName');
                    $this->dto->mbPwd = hash('sha256', $this->dto->mbId.base64_encode(Utils::getPostParam('mbPwd')) );
                    $this->dto->mbKey = md5($this->dto->mbPwd);
                    $this->dto->mbLevel = (int)Utils::getPostParam('mbLevel',ResError::no);
                    
                    $this->dto->mbPoint = 0;
                    $this->dto->mbGender = (Utils::getPostParam('mbGender'))?Utils::getPostParam('mbGender'):'E';
                    $this->dto->mbBirth = Utils::getPostParam('mbBirth');
                    if($this->dto->mbBirth==''){
                        $this->dto->mbBirth = '0000-00-00';
                    }
                    $this->dto->mbEmail = Utils::getPostParam('mbEmail');
                    if($this->dto->mbEmail==''){
                        $this->dto->mbEmail = '';
                    }
                    $this->dto->mbCountryDialCode = Utils::getPostParam('mbCountryDialCode');
                    $this->dto->mbHp = Utils::getPostParam('mbHp');
                    $this->dto->mbPasswordQ = Utils::getPostParam('mbPasswordQ',ResError::no);
                    $this->dto->mbPasswordA = Utils::getPostParam('mbPasswordA',ResError::no);
                    $this->dto->mbCertificate = Utils::getPostParam('mbCertificate',ResError::no);
                    $this->dto->contryCode = Utils::getPostParam('contryCode');
                    $this->dto->mbRegIp = Utils::getClientIP();
                    $this->dto->mbDelYn = Utils::getPostParam('mbDelYn',ResError::no);
                    $this->dto->mbLogindAlert = Utils::getPostParam('mbLogindAlert',ResError::no);
                    $this->dto->mbAdminMemo = Utils::getPostParam('mbAdminMemo',ResError::no);

                    if($this->dto->contryCode=='KR' || $this->dto->contryCode=='KP'){
                        $this->dto->mbName = Utils::getPostParam('mbLastName').''.Utils::getPostParam('mbFirstName');
                    }else if($this->dto->contryCode=='JP' || $this->dto->contryCode=='CN'){
                        $this->dto->mbName = Utils::getPostParam('mbLastName').' '.Utils::getPostParam('mbFirstName');
                    }else{
                        $this->dto->mbName = Utils::getPostParam('mbFirstName').' '.Utils::getPostParam('mbLastName');
                    }
                }catch(Exception $e){
                    $json = json_decode($e);
                    $this->dto->result = (int)$json->code;
                    return $this->dto;
                }

                //param값 유효성 검사
                $this->dto->result = parent::checkEmptyValue($this->dto,array('result','mbNo','mbLevel','mbPoint','mbHp','mbCertificate','mbEmail','mbRegDt','mbRegIp','mbUpDt','mbDelYn','mbLogindAlert','mbPasswordQ','mbPasswordA','mbAdminMemo'));
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

                    $this->initModifyPostParam('mbNick',ResError::no);
                    $this->dto->mbNickDt = Utils::getPostParam('mbNickDt',ResError::no);
                    if(!$this->dto->mbNickDt) $this->dto->mbNickDt = '0000-00-00';

                    //비밀번호 변경 못하게
//                    if(Utils::getPostParam('mbPwd',ResError::no)){
//                        $this->dto->mbPwd = hash('sha256', $this->dto->mbId.base64_encode(Utils::getPostParam('mbPwd')) );
//                        $this->dto->mbKey = md5($this->dto->mbPwd);
//                    }
                    $this->initModifyPostParam('mbLastName',ResError::no);
                    $this->initModifyPostParam('mbFirstName',ResError::no);
//					$this->initModifyPostParam('mbLevel',ResError::no);
                    $this->dto->mbLevel = (int)Utils::getPostParam('mbLevel',ResError::no);
					$this->initModifyPostParam('mbGender',ResError::no);
					$this->initModifyPostParam('mbBirth',ResError::no);
					$this->initModifyPostParam('mbEmail',ResError::no);
                    $this->initModifyPostParam('mbCountryDialCode',ResError::no);
                    $this->dto->mbHp = Utils::getPostParam('mbHp');
//					$this->initModifyPostParam('mbHp',ResError::no);

					$this->initModifyPostParam('mbPasswordQ',ResError::no);
					$this->initModifyPostParam('mbPasswordA',ResError::no);
					$this->initModifyPostParam('mbCertificate',ResError::no);
                    $this->initModifyPostParam('contryCode',ResError::no);
					$this->initModifyPostParam('mbDelYn',ResError::no);
					$this->initModifyPostParam('mbLogindAlert',ResError::no);
					$this->initModifyPostParam('mbAdminMemo',ResError::no);
                }catch(Exception $e){
                    $json = json_decode($e);
                    $this->dto->result = (int)$json->code;
                    return $this->dto;
                }

                //param값 유효성 검사
                $this->dto->result = parent::checkEmptyValue($this->dto,array('result','mbNick','mbNickDt','mbLevel','mbPwd','mbPoint','mbGender','mbBirth','mbEmail','mbHp','mbCertificate','mbAgent','mbRegDt','mbDelYn','mbLogindAlert','mbAdminMemo','mbPasswordQ','mbPasswordA'));
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
                    $pri = (int)Utils::getPostParam('mbNo');
                }catch(Exception $e){
                    $json = json_decode($e);
                    $this->dto->result = (int)$json->code;
                    return $this->dto;
                }

                $this->initDAO();
                $this->dto = $this->dao->getViewById($pri);
                if($this->dto->mbNo){
                    if($this->dto->mbNo!=$pri){
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
             * @brief 검색 파라미터 초기화
             * @return null
             */
            private function setSearchParam(){
                $this->searchField = Utils::getUrlParam('sf',1);
                $this->searchValue = Utils::getUrlParam('sv',1);
                if($this->searchField=='mb_no' || $this->searchField=='mb_id' || $this->searchField=='mb_name' || $this->searchField=='mb_nick' || $this->searchField=='mb_pwd' || $this->searchField=='mb_key' || $this->searchField=='mb_level' || $this->searchField=='mb_point' || $this->searchField=='mb_bit_wallet' || $this->searchField=='mb_birth' || $this->searchField=='mb_email' || $this->searchField=='mb_hp' || $this->searchField=='mb_password_q' || $this->searchField=='mb_password_a' || $this->searchField=='mb_certificate' || $this->searchField=='mb_agent' || $this->searchField=='contry_code' || $this->searchField=='mb_reg_ip' || $this->searchField=='mb_reg_dt' || $this->searchField=='mb_del_yn' || $this->searchField=='mb_gender'){

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