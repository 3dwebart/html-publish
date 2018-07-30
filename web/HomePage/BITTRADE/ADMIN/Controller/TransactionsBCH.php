<?php
/**
* Description of TransactionsMobileDAO Controller
* @description Funhansoft PHP auto templet
* @date 2015-06-29
* @copyright (c)funhansoft.com
* @license http://funhansoft.com/license
* @version 4.0.0
*/
        class TransactionsBCH extends ControllerBase{

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
                $this->dto = new TransactionsDTO();
            }
            
            //DB를 연결할 경우만
            private function initDAO(){
//                if(!$this->dao){
//                    $this->dao = new PluginBitcoin($this->config['bitcoin']);
//                    $this->dao->setListLimitRow(30);
//                    //$this->dao->setListOrderBy(array('trNo'=>'DESC')); //정렬값
//                }
                
                if(!$this->dao){
                    $this->dao = new BitcoinRPCDAO();
                    $wsvDAO = new WebConfigWalletServerDAO();
                    $dto = $wsvDAO->getViewByPoType('bch');
                    $this->dao->initServer($dto->waRpcProto,$dto->waRpcIp.':'.$dto->waRpcPort,$dto->waUser,$dto->waPass);
                }
            }

            public function main(){
                $this->setSearchParam();
                $resultArray = array();
                $this->initDAO();
                $resultArray['common'] = array(
                    'result'=>0,
                    'totalCount'=>0,
                    'totalPage'=>0,
                    'limitRow'=>0
                );
                $resultArray['link'] = parent::getLinkURL();
                
                $this->initDAO();
                //bitcoin
                $mb_id = '';
                if($this->searchValue) $mb_id = $this->searchValue;
                

                $resultArray['result'] = ResError::no;


                $this->dao->setListLimitStart(0);
                if(!$mb_id) $resultArray['data'] = $this->dao->getRPCTransactionListAll();
                else $resultArray['data'] = $this->dao->getRPCTransactionList($mb_id);

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
                
                $mb_id = '';
                if($this->searchValue) $mb_id = $this->searchValue;

                $page = (int)Utils::getUrlParam('page',ResError::no);
                if($page){
                    if($page<1) $page=1;
                    $this->dao->setListLimitStart($this->dao->getListLimitRow() * ($page-1));
                }else{
                    $this->dao->setListLimitStart(0);
                }
                if(!$mb_id) $listdata = $this->dao->getRPCTransactionListAll();
                else $listdata = $this->dao->getRPCTransactionList($mb_id);
                
                return $listdata;
            }
            
            
            
            
            public function view(){
                $this->getViewer()->setResponseType('404');
            }

            public function form(){
                $this->getViewer()->setResponseType('404');
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
                $this->getViewer()->setResponseType('404'); //사용시 JSON으로
            }


            /*
             * @brief 검색 파라미터 초기화
             * @return null
             */
            private function setSearchParam(){
                $this->searchField = Utils::getUrlParam('sf',1);
                $this->searchValue = Utils::getUrlParam('sv',1);
                if($this->searchField=='de_de_id' || $this->searchField=='txid' || $this->searchField=='server' || $this->searchField=='account' || $this->searchField=='category' || $this->searchField=='address' || $this->searchField=='amount' || $this->searchField=='fee' || $this->searchField=='confirmations' || $this->searchField=='blockhash' || $this->searchField=='blockindex' || $this->searchField=='walletconflicts' || $this->searchField=='reg_dt'){

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