<?php
/**
* Description of CronSchedule Controller
* @description Funhansoft PHP auto templet
* @date 2013-12-15
* @copyright (c)funhansoft.com
* @license http://funhansoft.com/license
* @version 4.0.0
*/
        class CronSchedule extends ControllerBase{

            private $dao;
            private $dto;
            private $searchField;
            private $searchValue;
            private $returnarr = array('result'=>0);
            private $PluginBitcoin;
            private $confirmCnt = 1;

            /**
            * @brief
            **/
            function __construct(){
                parent::__construct();
                $this->dto = new CronScheduleDTO();
                set_time_limit(3600); //sec 10분
            }

            //DB를 연결할 경우만
            private function initDAO(){
                if(!$this->dao){
                    $this->dao = new CronScheduleDAO();
                    $this->dao->setListLimitRow(30);
                    //$this->dao->setListOrderBy(array('csNo'=>'DESC')); //정렬값
                }
            }
            public function getToken(){
                $this->getViewer()->setResponseType('JSON');
                $dao = new WebAdminMemberDAO();
                $member = $dao->getViewByMbId('system');
                return Utils::getAppLoginToken($member);
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
                //Input Exception Field ,'csNo','csSubject','csContent','csProcYn','csProcTime','csProcQuery','csRegDt'
                $this->returnarr = $this->returnDTO($this->dto, array());

                if($this->dto->csNo){
                    if($this->dto->csNo!=$pri){
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
                    //Input Exception Field ,'csNo','csSubject','csContent','csProcYn','csProcTime','csProcQuery','csRegDt'
                    $this->returnarr = $this->returnDTO($this->dto, array());
                    if($this->dto->csNo){
                        if($this->dto->csNo!=$pri){
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
                    $this->dto->csSubject = Utils::getPostParam('csSubject');
                    $this->dto->csContent = Utils::getPostParam('csContent');
                    $this->dto->csProcYn = Utils::getPostParam('csProcYn',ResError::no);
                    $this->dto->csProcTime = Utils::getPostParam('csProcTime');
                    $this->dto->csProcQuery = Utils::getPostParam('csProcQuery');
                    $this->returnarr = $this->returnDTO($this->dto, array('result','csNo','csProcYn','csRegDt'));
                }catch(Exception $e){
                    $json = json_decode($e);
                    $this->returnarr['result'] = (int)$json->code;
                    return $this->returnarr;
                }


                //param값 유효성 검사
                $this->returnarr['result'] = parent::checkEmptyValue($this->dto,array('result','csNo','csProcYn','csRegDt'));
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
                    $pri = (int)Utils::getPostParam('csNo');
                }catch(Exception $e){
                    $json = json_decode($e);
                    $this->returnarr['result'] = (int)$json->code;
                    return $this->returnarr;
                }

                $this->initDAO();
                $this->dto = $this->dao->getViewById($pri);
                $this->returnarr = $this->returnDTO($this->dto, array('result','csNo','csProcYn','csRegDt'));
                if($this->dto->csNo){
                    if($this->dto->csNo!=$pri){
                        $this->returnarr['result'] =  ResError::paramUnMatchPri;
                        return $this->returnarr;
                    }

                    //수정권한이 있는지 체크

                }else{
                    $this->returnarr['result'] = ResError::noResultById;
                    return $this->returnarr;
                }

                try{
                    $this->initModifyPostParam('csNo');
                    $this->initModifyPostParam('csSubject');
                    $this->initModifyPostParam('csContent');
                    $this->initModifyPostParam('csProcYn',ResError::no);
                    $this->initModifyPostParam('csProcTime');
                    $this->initModifyPostParam('csProcQuery');
                }catch(Exception $e){
                    $json = json_decode($e);
                    $this->returnarr['result'] = (int)$json->code;
                    return $this->returnarr;
                }

                //param값 유효성 검사
                $this->returnarr['result'] = parent::checkEmptyValue($this->dto,array('result','csNo','csProcYn','csRegDt'));
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



            private function setCronComplet($subject,$content){

                $this->dto->csSubject = $subject;
                $this->dto->csContent = $content;
                $this->dto->csProcYn = 'Y';
                $this->dto->csProcTime = Utils::getDateNow();
                $this->dto->csProcQuery = '';
                $this->initDAO();
                $res = $this->dao->setInsert($this->dto);
                return $res;
            }



            public function initLoadUnConfirmation(){
                $this->getViewer()->setResponseType('JSON'); //사용시 JSON으로
                $this->initRedisServer($this->config['redis']['host'],$this->config['redis']['port'], null,  $this->config['redis']['db_member'], null);
                $this->redis->select($this->config['redis']['db_bittran']);

                $trandao = new TransactionsDAO();
                $trandao->setListLimitRow(100);
                $lists = $trandao->getListUnComfirm("is_point_pay_yn", "N");
                $success = 0;
                for($i=0;$i<count($lists);$i++){
                    $txdata = $this->getRedisData($lists[$i]->txid);
                    if(!$txdata || $txdata==null){
                        $trtime = 60 * 60 * 24; //24시간 대기
                        $this->setRedisData($lists[$i]->txid,  (int)$lists[$i]->confirmations,$trtime);
                        $success++;
                    }
                }

                return array('result'=>$success);

            }
            /*
             * 컨펌이 완료되었는지 체크
             */
            public function bitCheckConfirmation(){

                $success=0;

                $pointdao = null;
                $dao = null;
                //레디스에 승인안된 트렌젝션 올리기
                $this->initRedisServer($this->config['redis']['host'],$this->config['redis']['port'], null,  $this->config['redis']['db_bittran'], null);
                $keys = $this->redis->keys('*');

                $cron_content = '';

                for($i=0;$i<count($keys);$i++){

                    if(!$this->PluginBitcoin) $this->PluginBitcoin = new PluginBitcoin();
                    $rpctran = $this->PluginBitcoin->rpc('gettransaction',$keys[$i]);
                    $confirmations = (int)$rpctran['confirmations'];
                    $amount = $rpctran['amount'];

                    $details = $rpctran['details'];
                    $arrAccount = array();
                    for($tmp=0;$tmp<count($details);$tmp++){
                        if($details[$tmp]['account']) array_push($arrAccount, $details[$tmp]['account']);
                    }
                    

                    if($confirmations>=$this->confirmCnt){
                         $this->delRedisData($keys[$i]);

                    }else if($confirmations>0){
                        if($confmdata = $this->getRedisData($keys[$i])){
                            $this->delRedisData($keys[$i]);
                        }
                        $this->setRedisData($keys[$i], $confirmations,600);
                    }

                    //컨펌 수 업데이트
                    if($confirmations>0){
                        $dao = new TransactionsDAO();

                        //예외 처리 - 이미 Y값으로 되어 있는 경우 move로 인해 금액이 안맞는 현상 방지
                        //1컨펌 이고 회원값이 있고 아직 업데이트 전이라면 MOVE를 시켜놓는다.
                        if($confirmations>=$this->confirmCnt){

//                            $trandto = $dao->getViewByTxId($keys[$i]);
                            $tranlistdto = $dao->getListsByTxId($keys[$i]);
//                            var_dump($tranlistdto);
                            for($tidx=0;$tidx<count($tranlistdto);$tidx++){

                                $trandto = $tranlistdto[$tidx];
                                $account = $trandto->account;
//                                echo json_encode($trandto);

                                if($trandto && isset($trandto->isPointPayYn) && $trandto->isPointPayYn!='Y' && strlen($trandto->account)>3){
                                    //bitcoin move
                                    $this->PluginBitcoin->move($account,$this->config['bitcoin']['system_account'],$trandto->amount);

                                    //회원이 로그인되어 있을 경우
                                    //notify , time = 10분
                                    $this->redis->select($this->config['redis']['db_member_noti']);
                                    $acc_notice = $this->getRedisData($account);

                                    $json_event_data = array('bitcoin'=>array('receive'=>true,'confirmations'=>$confirmations));
                                    $eventtime = 60 * 60 * 60; //60분
                                    if(!$acc_notice){
                                        //키가 없으면 만든다.
                                        $this->setRedisData($account, json_encode($json_event_data),$eventtime);
                                    }else{
                                        //키가 있으면 bitcoin 있는지 확인 후 만든다.
                                        $arraydata = json_decode($acc_notice,true);

                                        //bitcoin 키가 없으면 만들어 준다.
                                        if(!isset($arraydata['bitcoin'])){
                                            $arraydata['bitcoin'] = $json_event_data['bitcoin'];
                                            $this->delRedisData($account);
                                            $this->setRedisData($account,json_encode($arraydata),$eventtime);
                                        }
                                    }
                                    //  입금완료 메일링
                                    $this->sendDepositBtcEmail($trandto);
                                }
                            } // $tranlistdto
                        }

                        //컨펌 수는 무조건 처리
                        $upresult = $dao->setUpdateConfirm($keys[$i],$confirmations);
                        if($upresult>0){
                            $success++;
                            $cron_content .= json_encode($arrAccount)."<br />";
                        }

                    }
                }
                if($success>0){
                    $this->setCronComplet('비트코인 입금 1컨펌이상 ('.$success.'건)',$success."건 업데이트 완료<br />".$cron_content);
                }
                echo $confirmations;



            }



            function sendDepositBtcEmail($trandto){

                if(!isset($trandto->mbNo) || !$trandto->mbNo){
                    return false;
                }

                $memberdao = new WebMemberDAO();
                $member = $memberdao->getViewById($trandto->mbNo);
                // 회원 이메일 알림 수신 여부
                if($member->mbNotifyWithdrawals=='N'){
                    return false;
                }

                // 이메일 템플릿 타입 - 언어
                $email_subject = '';
                $email_type_country = '';

                if($member->contryCode=='KR'){
                    $email_subject = 'BTC 충전완료 안내메일입니다.';
                    $email_type_country = '../WebApp/ViewHTML/request_deposit_complet_btc_email_kr.html';
                }else if($member->contryCode=='CN'){
                    $email_subject = 'BTC 충전완료 안내메일입니다.';
                    $email_type_country = '../WebApp/ViewHTML/request_deposit_complet_btc_email_cn.html';
                }else{
                    $email_subject = 'BTC Deposit Completed';
                    $email_type_country = '../WebApp/ViewHTML/request_deposit_complet_btc_email_en.html';
                }

                $logo_url = '/assets/img/common/logo_email.png';

                $emaildao = new EmailDAO();
                $html = file_get_contents($email_type_country);
                $html = str_replace("{user_name}", $member->mbName,$html);
                $html = str_replace("{site_name}", $this->config['html']['email_title'],$html);
                $html = str_replace("{logo_url}", $this->config['url']['static'].$logo_url,$html);
                $html = str_replace("{point_pay_dt}", $trandto->pointPayDt,$html);
                $html = str_replace("{amount}", $trandto->amount,$html);
                $html = str_replace("{link_url}", $this->config['url']['site'].'//balance/history',$html);

                $mailresult = $emaildao->mailer($member->mbId, $email_subject, $html, 1, "", "", "");

                return array('result'=>$mailresult);
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
                //Input Exception Field ,'csNo','csSubject','csContent','csProcYn','csProcTime','csProcQuery','csRegDt'
                $this->returnarr = $this->returnDTO($this->dto, array());
                if($this->dto->csNo){
                    if($this->dto->csNo!=$pri){
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
                if($this->searchField=='cs_no' || $this->searchField=='cs_subject' || $this->searchField=='cs_proc_yn' || $this->searchField=='cs_reg_dt'){

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