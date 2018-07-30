<?php
/**
* Description of CronSchedule Controller
* @description Funhansoft PHP auto templet
* @date 2013-12-15
* @copyright (c)funhansoft.com
* @license http://funhansoft.com/license
* @version 0.3.0
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
            public function main(){
                $resultArray = array();
                return $resultArray;
            }
            public function view(){
                $resultArray = array();
                return $resultArray;
            }
            public function form(){
                $resultArray = array();
                return $resultArray;
            }
            public function lists(){
                $this->getViewer()->setResponseType('404');
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
                    if(Utils::getPostParam($keyId,$noerror) && Utils::getPostParam($keyId,$noerror)!=$this->dto->$keyId){
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