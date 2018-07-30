<?php
/**
* Description of IndexMain
* @description Funhansoft PHP auto templet
* @date 2013-08-31
* @copyright (c)funhansoft.com
* @license http://funhansoft.com/license
* @version 0.2.1
*/
        class IndexMain extends ControllerBase{

            function __construct(){
                parent::__construct();
            }

            public function main(){
                $returnArray = array();
                $mbcntdao = new ViewMbJoinDayDAO();
                $mbcntdao->setListLimitRow(100);
                $mbcnt = $mbcntdao->getList('','');
                $returnArray['result'] = count($mbcnt);
                $returnArray['data'] = $mbcnt;
                
                $krw_deposit_dao = new WebCashDepositsDAO();
                $krw_deposit_dao->setListLimitRow(5);
                $krw_deposit_arr = $krw_deposit_dao->getList();
                $returnArray['krwDeposit'] = $krw_deposit_arr;
                
                $krw_withdraw_dao = new WebCashWithdrawalsDAO();
                $krw_withdraw_dao->setListLimitRow(5);
                $krw_withdraw_arr = $krw_withdraw_dao->getList();
                $returnArray['krwWithdraw'] = $krw_withdraw_arr;
                
                $coin_deposit_dao = new WebWalletDepositsDAO();
                $coin_deposit_dao->setListLimitRow(5);
                $coin_deposit_arr = $coin_deposit_dao->getList();
                $returnArray['coinDeposit'] = $coin_deposit_arr;
                
                $coin_withdraw_dao = new WebWalletWithdrawalsDAO();
                $coin_withdraw_dao->setListLimitRow(5);
                $coin_withdraw_arr = $coin_withdraw_dao->getList();
                $returnArray['coinWithdraw'] = $coin_withdraw_arr;

                $reqdao = new WebMemberLevelRequestDAO();
                $reqdao->setListLimitRow(5);
                $reqarray = $reqdao->getList('admin_confirm','N');
                $returnArray['lastesLevelReq'] = $reqarray;

                $reqdao = new WebWalletWithdrawalsDAO();
                $reqdao->setListLimitRow(5);
                $reqarray = $reqdao->getList('','');
                $returnArray['lastesDepositOrder'] = $reqarray;


//                $reqdao = new WebTradeBitcoinDAO();
//                $reqdao->setListLimitRow(5);
//                $reqarray = $reqdao->getList('','');
//                $returnArray['lastesTradeOrder'] = $reqarray;
//
//
//                $reqdao = new WebPointRequestOrderDAO();
//                $reqdao->setListLimitRow(5);
//                $reqarray = $reqdao->getList('','');
//                $returnArray['lastesWithdrawOrder'] = $reqarray;

                return $returnArray;
            }

            private function blockIpCheck(){
                $dto = new WebAdminMemberDTO();
                $oLog = new WebAdminLoginHisDAO();
                $isBlockIp = $oLog->isBlockIp(Utils::getClientIP());
                if($isBlockIp){
                    $dto->mbId = 'none';
                    $dto->result = ResError::accessIp;
                    $dto->mbTodayLogin = '블랙리스트 아이피 접속 - 차단됨';
                    $this->loginLogReturn($dto);
                    $this->getViewer()->setResponseType('404');
                }
            }

            private function isAccessIp($ips){
                $is = false;
                $pos = strrpos($ips, "0.0.0.0");
                if ($pos === false) { // note: three equal signs
                    // not found...
                    $arrIps = explode(",", $ips);
                    for($i=0;$i<count($arrIps);$i++){
                        if(trim($arrIps[$i])==Utils::getClientIP()){
                           return $is = true;
                        }
                    }
                }else{
                    $is = true;
                }
                return $is;
            }
            private function isExceptionRecaptcha(){
                if(Utils::getClientIP() == '127.0.0.1' || 
                        Utils::getClientIP() == '121.173.110.67'){
                    return true;
                }
                return true;
            }

            public function login(){
                $this->blockIpCheck();
                $resultArray = array();
                $resultArray['result'] = ResError::no;
                //로그인 체크
                if(Utils::getUrlParam('proc',ResError::no)){
                    return $this->loginCheck();
                }else{
                    $resultArray['token'] = parent::createTocken();
                    if(!$this->isExceptionRecaptcha()) $resultArray['sitekey'] = $this->config['recaptcha']['sitekey'];
                }

                return $resultArray;
            }

            public function logout(){
                Session::startSession();
                Session::delSession('admin_mb_id');
                Session::delSession('admin_mb_name');
                Session::delSession('admin_mb_auth');
                Session::delSession('admin_mb_no');
                Session::delSession('login_ip');
                Session::destorySession();
                Utils::redirect( get_class());
            }

            private function loginCheck(){
                $this->getViewer()->setResponseType('JSON');
                $dto = new WebAdminMemberDTO();
                try{
                    $dto->mbId = base64_decode(Utils::getPostParam('username'));
                    $dto->mbPassword = Utils::getPostParam('password');
                    if(!$this->isExceptionRecaptcha()) $recaptchaResponse = Utils::getPostParam('g-recaptcha-response');
                }catch(Exception $e){
                    $json = json_decode($e);
                    $dto->result = (int)$json->code;
                    $dto->mbPassword = '';
                    $dto->mbTodayLogin = '아이디 혹은 비밀번호 파라미터 예외';
                    return $this->loginLogReturn($dto);
                }

                if(strlen($dto->mbId)<4){
                    $dto->result = -181;
                    $dto->mbTodayLogin = '아이디 파라미터 실패';
                    return $this->loginLogReturn($dto);
                }
                if(strlen($dto->mbPassword)<4){
                    $dto->result = -182;
                    $dto->mbTodayLogin = '비밀번호 파라미터 실패';
                    return $this->loginLogReturn($dto);
                }

                //값체크
                if(!$dto->mbId || !$dto->mbPassword){
                    $dto->result = ResError::paramRequiredValue;
                    $dto->mbTodayLogin = '아이디 혹은 비밀번호 파라미터 실패';
                    return $this->loginLogReturn($dto);
                }

                //토큰 유효성
                $dto->result = parent::checkToken();
                if($dto->result<0){
                     return $this->loginLogReturn($dto);
                }

                //레퍼러 도메인 체크
                $dto->result = parent::checkReferer();
                if($dto->result<0){
                     $dto->mbTodayLogin = '레퍼러 도메인 체크에서 실패';
                     return $this->loginLogReturn($dto);
                }

                // 로봇 체크 - Recaptcha
                if(!$this->isExceptionRecaptcha()){
                    $recaptchadao = new RecaptchaDAO();
                    $recaptchaResult = $recaptchadao->recaptcha($recaptchaResponse);
                    if($recaptchaResult['success']==false){
                        $dto->result = ResError::captcha;
                        return $this->loginLogReturn($dto);
                    }
                }
                

                $dao = new WebAdminMemberDAO();

                $member = $dao->getViewByMbId($dto->mbId);
                if($member->mbId){
                    $dto->mbNo = $member->mbNo;

                    if($member->mbId!=$dto->mbId){
                        $dto->result = ResError::paramUnMatchPri;
                        $dto->mbTodayLogin = '아이디 파라미터가 틀림('.$member->mbId.':'.$dto->mbId.')';
                        return $this->loginLogReturn($dto);
                    }

                    //접근 가능한 IP인지 체크
                    if(!$this->isAccessIp($member->mbAccessIp)){
                        $dto->result = ResError::accessIp;
                        $dto->mbTodayLogin = '접근가능 IP가 아닙니다.';
                        return $this->loginLogReturn($dto);
                    }

                //존재하지 않는 아이디
                }else{
                    $dto->result = ResError::noResultById;
                    $dto->mbTodayLogin = '존재하지 않는 아이디';
                    return $this->loginLogReturn($dto);
                }

                //비밀번호가 일치하지 않음
                if($member->mbPassword!=$dto->mbPassword){
                    $dto->result = -190;
                    $dto->mbTodayLogin = '비밀번호가 일치하지 않음';
                }else{
                    $member->mbLoginIp = Utils::getClientIP();
                    $member->mbTodayLogin = date("Y-m-d h:i:s",time());
                    $dao->setUpdate($member);

                    Session::startSession();
                    Session::setSession('admin_mb_id',$member->mbId);
                    Session::setSession('admin_mb_name',$member->mbName);
                    Session::setSession('admin_mb_auth',$member->mbAuth);
                    Session::setSession('admin_mb_no', $member->mbNo);
                    Session::setSession('login_ip', Utils::getClientIP());
                    $dto->result = ResError::no;
                    $dto->mbTodayLogin = '로그인 성공';
                }
                return $this->loginLogReturn($dto);
            }

            private function loginLogReturn($dto){
                $log = new WebAdminLoginHisDTO();
                $oLog = new WebAdminLoginHisDAO();
                try{
                    $log->lhType = 'LOGIN';
                    $log->mbNo = (int)$dto->mbNo;
                    $log->mbId = $dto->mbId;
                    $log->mbKey = $_SERVER['HTTP_USER_AGENT'];
                    $log->lhResultCode = $dto->result;
                    $log->lhResultMsg = $dto->mbTodayLogin;
                    $log->lhRegIp = Utils::getClientIP();
                    $log->lhIpBlock = 0;
                    $oLog->setInsert($log);
                }catch(Exception $e){
                    return $dto;
                }
                return $dto;
            }

            public function menu(){
                $cate = Utils::getUrlParam('cate',ResError::no);
                $menuArray = $this->getViewer()->getPageMenu();
                list($firstKey) = array_keys($menuArray['categorySub'][$cate]);
                Utils::redirect($firstKey);
            }
            public function getMenu(){
                $this->getViewer()->setResponseType('JSON');
                if(parent::checkReferer()<0){
                    return array();
                }
                $menuArr = $this->getViewer()->getPageMenu();
                Session::startSession();
                $menuArr['user'] = array('id'=>Session::getSession('admin_mb_id'),'name'=>Session::getSession('admin_mb_name'),'mbno'=>Session::getSession('admin_mb_no'));
                return $menuArr;
            }

            public function location(){
                $return = Utils::getUrlParam('return',ResError::no);
                if($return) Utils::redirect($return);
            }

            public function delete() {
                $this->getViewer()->setResponseType('404');
            }

            public function insert() {
                $this->getViewer()->setResponseType('404');
            }

            public function lists() {
                $this->getViewer()->setResponseType('404');
            }

            public function update() {
                $this->getViewer()->setResponseType('404');
            }

        }
