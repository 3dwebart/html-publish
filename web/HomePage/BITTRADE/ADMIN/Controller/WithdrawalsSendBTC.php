<?php
/**
* Description of WebWalletWithdrawals Controller
* @description FunHanSoft Co.,Ltd  PHP auto templet
* @date 2017-08-19
* @copyright (c)funhansoft.com
* @license 해당 사이트 이외의 사용은 엄격히 금지되어 있습니다.
* @version 4.0.0
*/
        class WithdrawalsSendBTC extends ControllerBase{

            private $dao;
            private $dto;
            private $searchField;
            private $searchValue;
            private $returnarr = array('result'=>0);
            private $poType = 'btc';
            private $bitcoinRPC;
            private $sendaccount = 'system';
            
            /**
            * @brief
            **/
            function __construct(){
                parent::__construct();
                $this->dto = new WebWalletWithdrawalsDTO();
            }
            
            //DB를 연결할 경우만
            private function initServerDAO(){                
                $this->bitcoinRPC = new BitcoinRPCDAO();
                $wsvDAO = new WebConfigWalletServerDAO();
                $dto = $wsvDAO->getViewByPoType($this->poType);
                $this->bitcoinRPC->initServer($dto->waRpcProto,$dto->waRpcIp.':'.$dto->waRpcPort,$dto->waUser,$dto->waPass);
                $this->admin_pwd = $this->config['wallet']['bit_send_pwd'];
            }
            
            //DB를 연결할 경우만
            private function initDAO(){
                if(!$this->dao){
                    $this->dao = new WebWalletWithdrawalsDAO();
                    $this->dao->setIsAdminConfirm(true);
                    $this->dao->setPoType($this->poType);
                    $this->dao->setListLimitRow(30);
                    //$this->dao->setListOrderBy(array('odId'=>'DESC')); //정렬값
                }
            }

            public function main(){
                $this->setSearchParam();
                $resultArray = array();
                $this->initDAO();
                $dtfrom = Utils::getUrlParam('svdf',ResError::no);
                $dtto = Utils::getUrlParam('svdt',ResError::no);
                $resultArray['data'] = $this->dao->getList($this->searchField,$this->searchValue,$dtfrom,$dtto);  
                $resultArray['common'] = $this->dao->getListCount($this->searchField,$this->searchValue,$dtfrom,$dtto);
                $resultArray['link'] = parent::getLinkURL();
                return $resultArray;
            }

            public function view(){
                Utils::redirect('WebWalletWithdrawals/view',array('id'=> Utils::getUrlParam('id')));
                return $resultArray;
            }
            
            
            
            public function updateSendMany(){
                $this->getViewer()->setResponseType('JSON');
                
                //레퍼러 도메인 체크
                if(parent::checkReferer()<0){
                    return array();
                }
                
                $this->returnarr = $this->returnDTO($this->dto, array('result','odId'));

                
                $this->initDAO();

                $address = $_POST['odids'];
                $tmparr = explode(',', $address);
                $success = 0;
                
                $this->initServerDAO();
  
                
                $viewarray = array();
                $tmpcoinsum = 0;
                $sendmanyArrJson = array();
                
                for($i=0; $i< count($tmparr);$i++){
                    $view = $viewarray[$i] = $this->dao->getViewById($tmparr[$i]);
                    
                    //전송할 데이터
                    $tmpcoinsum = $tmpcoinsum + ($viewarray[$i]->odTempAmount - $viewarray[$i]->odFee);
                    $withdrawcoin = $viewarray[$i]->odTempAmount - $viewarray[$i]->odFee;
                    $sendmanyArrJson[$view->odAddr] = $withdrawcoin;
                }
                
                
                //발란스 체크
                $btctotal = $this->bitcoinRPC->getRPCBalance("*");
                if(!$btctotal || $btctotal < $tmpcoinsum){
                    return array('result'=>-5999,'msg'=>'코인서버 잔액이 부족합니다. 출금 시도 금액 : ' .$tmpcoinsum . ' 서버잔액(계정:'.$this->sendaccount.') : ' . $btctotal);
                }
  

//                $rpcresult = $this->bitcoinRPC->getRPCSendMany($this->sendaccount, "'".json_encode($sendmanyArrJson)."'");
//                var_dump($rpcresult);
//                exit;
                
                $pointdao = new WebPointDAO();
                $pointdao->setTableName('web_point_'.$this->poType);
                
                for($i=0; $i< count($tmparr);$i++){

                    $view = $viewarray[$i];
                    if(!$view || !$view->odId) continue;
                    
                    //중복검사
                    $prepoint = $pointdao->getViewByRelId($view->mbNo, $view->odId,null,null,'withdraw');
                    if(count($prepoint)>0){
                        continue;
                    }

                    $withdrawcoin = (float)$view->odTempAmount - (float)$view->odFee;
                    if($withdrawcoin <= 0){
                        continue;
                    }
                    $withdrawcoin = number_format($withdrawcoin,8);
                    $withdrawcoin = str_replace(',', '', $withdrawcoin);
                    
                    // --------------------------
                    // 내부 회원에게 전송 시
                    // -------------------------
                    $to_member_mb_no = 0;
                    if($view->odSendto){
                        $to_member_mb_no = (int)str_replace('MB', '', $view->odSendto);
                    }
                    if($to_member_mb_no>0){
                        $rpcresult = array('txid'=>'member'); //debug
                    }else{
                        $rpcresult = $this->bitcoinRPC->getRPCSendfrom($this->sendaccount,$view->odAddr,$withdrawcoin);
                    }
                    
                    if($rpcresult && isset($rpcresult['txid']) && $rpcresult['txid']){
                        
                        
                        $point = new WebPointDTO();
                        
                        $point->memPoNo = 0;
                        $point->mbNo = $view->mbNo;
                        $point->isTracker = 'N';
                        $point->poContent = strtoupper($this->poType) .' Withdraw';
                        $point->poPoint = $view->odTempAmount * -1;
                        $point->poPointSum = 0;
                        $point->odTotalCost = 0;
                        $point->odFee = $view->odFee;
                        $point->poRelId = $view->odId;
                        $point->poRelAction = 'withdraw';
                        $point->poTradeDt = '0000-00-00';
                        $point->poRegIp = Utils::getClientIP();
                        $pores = $pointdao->setInsert($point);
                        if($pores){
                            
                            // --------------------------
                            // 내부 회원에게 전송 시
                            // -------------------------
                            if($to_member_mb_no>0){
                                $point->poContent = strtoupper($this->poType) .' Deposit From member';
                                $point->mbNo = $to_member_mb_no;
                                $point->poPoint = $view->poAmount;
                                $point->odFee = 0;
                                $point->poRelAction = 'depositfrommb';
                                $pores = $pointdao->setInsert($point);
                                //입금 내역 부분
                                $dto = new WebWalletDepositsDTO();
                                $wdao = new WebWalletDepositsDAO();
                                
                                $dto->odStatus = 'OK';  
                                $dto->poType = $this->poType;
                                $dto->odRegIp = Utils::getClientIP();
                                $dto->mbNo            = $to_member_mb_no;
                                $dto->odTmpMb         = $view->odSendto;
                                $dto->odCategory      = 'receive';
                                $dto->odAmount        = $view->poAmount;
                                $dto->odFromAddr      = '';
                                $dto->odToAddr        = $view->odAddr;
                                $dto->odFee           = 0;
                                $dto->odConfirm       = 100;
                                $dto->poPayYn         = 'Y';
                                $dto->poPayDt         = Utils::getDateNow();
                                $dto->odEtc1          = '';
                                $dto->odEtc2          = '';
                                $dto->odEtc3          = '';
                                $dto->odTxid          = 'member-'.time();
                                $wdao->setInsert($dto);
                                $pointdao->callBalanceSum($to_member_mb_no,'frommb');
                                
                                //입금완료 메일
                                $this->sendDepositEmail($dto);
                            }
                            
                            
                            
                            

                            //출금신청 주문서 수정 OK
                            $view->odTxid = ''.$rpcresult['txid'];
                            $view->odReceiptAmount = $viewarray[$i]->odTempAmount;
                            $view->poAmount = $withdrawcoin;
                            $success = $success + $this->dao->setUpdateSendOK($view);
                            
                            //프로시저콜
                            $pointdao->callBalanceSum($view->mbNo,'sendok');
                        }
                    }
  
                }
                
                $this->returnarr['result'] = $success;
                
                
                
                return $this->returnarr;
            }
            
            
            
            
            

            public function form(){
                $this->getViewer()->setResponseType('404');
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
                $dtfrom = Utils::getUrlParam('svdf',ResError::no);
                $dtto = Utils::getUrlParam('svdt',ResError::no);
                if($page){
                    if($page<1) $page=1;
                    $this->dao->setListLimitStart($this->dao->getListLimitRow() * ($page-1));
                }else{
                    $this->dao->setListLimitStart(0);
                }
                return $this->dao->getList($this->searchField,$this->searchValue,$dtfrom,$dtto);
            }

            /*
             * @brief 데이터 삽입
             * @return object
             */
            public function insert(){
                $this->getViewer()->setResponseType('404');
            }

            /*
             * @brief 데이터 수정
             * @return object
             */
            public function update(){
                $this->getViewer()->setResponseType('404');
            }
            
            
             /*
             * @brief 데이터 삭제
             * @return int
             */
            public function delete(){
                $this->getViewer()->setResponseType('404');
            }


            /*
             * @brief 검색 파라미터 초기화
             * @return null
             */
            private function setSearchParam(){
                $this->searchField = Utils::getUrlParam('sf',1);
                $this->searchValue = Utils::getUrlParam('sv',1);
                if($this->searchField=='od_id' || $this->searchField=='od_status' || $this->searchField=='od_status_msg' || $this->searchField=='mb_no' || $this->searchField=='is_user_confirm_yn' || $this->searchField=='is_user_confirm_ip' || $this->searchField=='is_admin_confirm_yn' || $this->searchField=='od_addr' || $this->searchField=='od_addr_msg' || $this->searchField=='od_sendto' || $this->searchField=='od_txid' || $this->searchField=='po_type' || $this->searchField=='po_pay_yn' || $this->searchField=='od_reg_dt' || $this->searchField=='od_etc1' || $this->searchField=='od_etc2' || $this->searchField=='od_etc3'){

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