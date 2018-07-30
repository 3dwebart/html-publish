<?php
/**
* Description of TickerServer Controller
* @description Funhansoft PHP auto templet
* @date 2015-06-29
* @copyright (c)funhansoft.com
* @license http://funhansoft.com/license
* @version 4.0.0
*/
        class TickerServer extends ControllerBase{

            private $dao;
            private $returnarr = array('result'=>0);
            private $rpcticker = null;
            private $type = null;
            
            /**
            * @brief
            **/
            function __construct(){
                parent::__construct();
                
                $marketdao = new WebConfigExchangeMarketDAO();

                $this->type = Utils::getUrlParam('type',1);
                $tmp = $marketdao->getList('it_market_id', $this->type);
                $dto = $tmp[0];
                if(!$dto || !isset($dto->itServerSignIp) || !$dto->itServerSignIp){
                    exit;
                }
                $this->rpcticker = $dto->itServerSignIp .':1'. $dto->itServerSignPort;
            }
            
            //DB를 연결할 경우만
            private function initDAO(){
                try {
                    $this->dao = new jsonRPCClient('http://'.$this->rpcticker.'/',true);
                }
                catch (Exception $e) {
                    new RPCException("<p>Ticker Server error! ".$e);
                }
            }

            public function main(){
                $resultArray['token'] = parent::createTocken();
                $resultArray['type'] = $this->type;
                return $resultArray;
            }
			
            public function getStatus(){
                    $this->getViewer()->setResponseType('JSON');
                    $this->initDAO();
                    $function = 'command';
                    return $this->dao->$function('status');
            }
            public function getStartServer(){
                    $this->getViewer()->setResponseType('JSON');
                    $this->initDAO();
                    $function = 'command';
                    return $this->dao->$function('start');
            }
            public function getReStartServer(){
                    $this->getViewer()->setResponseType('JSON');
                    $this->initDAO();
                    $function = 'command';
                    return $this->dao->$function('restart');
            }
            public function getStopServer(){
                    $this->getViewer()->setResponseType('JSON');
                    $this->initDAO();
                    $function = 'command';
                    return $this->dao->$function('stop');
            } 

            /*
             * @brief 데이터 리스트
             * @return array object
             */
            public function lists(){
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
            public function delete(){
                $this->getViewer()->setResponseType('404'); //사용시 JSON으로
            }			
            function __destruct(){
                unset($this->dao);
                unset($this->dto);
            }
			
        }