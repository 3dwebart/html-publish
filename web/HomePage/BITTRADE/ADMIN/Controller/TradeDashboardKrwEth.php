<?php
/**
* Description of ViewTradeBitcoinComplete Controller
* @description Funhansoft PHP auto templet
* @date 2015-08-21
* @copyright (c)funhansoft.com
* @license http://funhansoft.com/license
* @version 4.0.0
*/
        class TradeDashboardKrwEth extends ControllerBase{

            private $dao;
            private $dto;
            private $searchField;
            private $searchValue;
            private $returnarr = array('result'=>0);
            
            private $dbName = 'fns_trade_order_eth';
            private $dbTableName = 'mem_open_order_krw_eth';
            /**
            * @brief
            **/
            function __construct(){
                parent::__construct();
                $this->dao = new TradeDashboardKrwEthDAO();
                $this->dto = new TradeDashboardKrwEthDTO();
            }
            
            public function setDbName($nm){
                $this->dbName = $nm;
            }
            
            public function setTableName($nm){
                $this->dbTableName = $nm;
            }
            
            //DB를 연결할 경우만
            private function initDAO(){
                if(!$this->dao){
                    $this->dao = new TradeDashboardKrwEthDAO();
                    $this->dao->setListLimitRow(30);
                    //$this->dao->setListOrderBy(array(''=>'DESC')); //정렬값
                }
            }

            public function main(){
                $this->setSearchParam();
                $resultArray = array();
                
                $this->dao = new TradeDashboardKrwEthDAO();
                $this->dao->setListLimitRow(100);
                $this->dao->setDbName('fns_trade_order_eth');
                
                //상황판
                $dtfrom = Utils::getUrlParam('svdf',ResError::no);
                $dtto = Utils::getUrlParam('svdt',ResError::no);
                // $resultArray['data'] = $this->dao->getList($this->searchField,$this->searchValue,$dtfrom,$dtto);  
                // $resultArray['data_untrade'] = $this->dao->getUnTradeListGroup(); 
                $resultArray['selldata'] = $this->dao->getSellList($this->searchField,$this->searchValue,$dtfrom,$dtto);  
                $resultArray['buydata'] = $this->dao->getBuyList($this->searchField,$this->searchValue,$dtfrom,$dtto); 
                $resultArray['common'] = $this->dao->getListCount($this->searchField,$this->searchValue,$dtfrom,$dtto);
                $resultArray['link'] = parent::getLinkURL();
                return $resultArray;
            }
            
            // 미체결된 주문리스트
            public function marketList(){
                $this->getViewer()->setResponseType('JSON');
                $cost = Utils::getUrlParam('cost');
                $objdao = new TradeDashboardKrwEthDAO();
                $objdao->setDbName('fns_trade_order_eth');
                $objdao->setListLimitRow(1000);
                $objdao->setListOrderBy(array('od_id'=>'ASC'));
                $list = $objdao->getMarketList('od_market_price',(isset($cost)?$cost:0));

                return $list;
            }
            
            public function untradeorderjoin(){
                $this->getViewer()->setResponseType('JSON');
                $objdao = new WebTradeBitcoinDAO();
                $objdao->setListOrderBy(array('od_id'=>'ASC'));
                $objdao->setListLimitRow(1000);
                $list = $objdao->getUnTradeList('od_market_price',(isset($_GET['cost'])?$_GET['cost']:0));
                
                $first_remain_qty = 0; //첫번째 주문의 남은 비트코인 수량
                $first_action = ''; //첫번째 주문이 BUY냐 SELL이냐
                $trade_target_remain_qty = 0; //첫번째 주문의 남은 비트코인 수량
                $trade_target_action = ''; //첫번째 주문이 BUY냐 SELL이냐
                $trade_target_index = 0; //체결시키고 있는 기준점 index
                $trade_target_index_last = 0; //기준점 index의 마지막 값
                /*
                 * 0. buy -- $trade_target_index
                 * 1. buy
                 * 2. buy --> $trade_target_index_last
                 * 3. sell ---> $trade_target_index++ 하면서 체결
                 */
                
                for($i=0;$i<count($list);$i++){
                    
                    //체결시킬 목표값 보다 클 수 었다.
                    if($trade_target_index>$i){
                        break;
                    }
                    
                    if($i==0){
                        $first_action = $list[$i]->odAction;
                        $first_remain_qty = $list[$i]->odTempCoin - $list[$i]->odReceiptCoin;
                        
                        $trade_target_action = $first_action;
                        $trade_target_remain_qty = $first_remain_qty;
                    }else{
                        //2번째부터
                        if($trade_target_action==$list[$i]->odAction){
                            $trade_target_index_last++; //체결시킬 수 있는 상황판 마지막 값
                            continue;
                        /*
                         * 액션값이 틀리다면 체결을 시작한다.
                         */
                        }else{
                            
                            $trade_target_remain_qty = $list[$trade_target_index]->odTempCoin - $list[$trade_target_index]->odReceiptCoin;
                            $list_remain_qty = $list[$i]->odTempCoin - $list[$i]->odReceiptCoin;
                            
                            
                            if($trade_target_remain_qty<=$list_remain_qty){
                                $pay_qty = $trade_target_remain_qty;
                            }else if($trade_target_remain_qty>$list_remain_qty){
                                $pay_qty = $list_remain_qty;
                            }

                            //target값 없뎃
                            $tmpdto = new WebTradeBitcoinDTO(); 
                            $tmpdto = $list[$trade_target_index];

                            //TARGET에 모자라는 비트코인을 채운다.

                            $tmpdto->odReceiptCoin = $tmpdto->odReceiptCoin + $pay_qty;
                            $tmpdto->odReceiptDt = time();
                            $status = ($tmpdto->odTempCoin<=$tmpdto->odReceiptCoin)?'OK':'REQ';
                            $tmpdto->odPayStatus = $status;
                            $objdao->setUpdate($tmpdto);

                            //list값 업뎃, 받음
                            $tmpdto = new WebTradeBitcoinDTO(); 
                            $tmpdto = $list[$i];
                            $tmpdto->odReceiptCoin = $list[$i]->odReceiptCoin + $pay_qty;
                            $tmpdto->odReceiptDt = time();
                            $status = ($tmpdto->odTempCoin<=$tmpdto->odReceiptCoin)?'OK':'REQ';
                            $tmpdto->odPayStatus = $status;
                            $tmpdto->partner = 'NOCOMPLETE'; //구매 / 판매의 주체는 TRADE완료 값에 값을 처리하지 않음
                            $objdao->setUpdate($tmpdto);


                            break;
                        }
                    }
                    
                    
                    
                    
                }

                return array('result'=>0,'i'=>$i);
            }
            

            public function forcetrade(){
                $this->getViewer()->setResponseType('JSON');
                //seller를 찾는다.
                $sellerdao = new WebTradeBitcoinDAO();
                $sellerlist = $sellerdao->getSellerList((int)$_GET['cost']);  
                var_dump($sellerlist);
            }


            public function view(){
                $this->getViewer()->setResponseType('404');
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
                if($this->searchField=='od_id' || $this->searchField=='tr_total_cost' || $this->searchField=='od_market_price'){

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