<?php
/**
* Description of WebChartStat Controller
* @description Funhansoft PHP auto templet
* @date 2015-06-29
* @copyright (c)funhansoft.com
* @license http://funhansoft.com/license
* @version 4.0.0
*/
        class WebChartStat extends ControllerBase{

            private $dao;
            private $searchField;
            private $searchValue;
            private $returnarr = array('result'=>0);

            /**
            * @brief
            **/
            function __construct(){
                parent::__construct();
            }

            //DB를 연결할 경우만
            private function initDAO(){
                if(!$this->dao){
                    $this->dao = new WebChartStatDAO();
                    $this->dao->setListLimitRow(30);
                    //$this->dao->setListOrderBy(array('snNo'=>'DESC')); //정렬값
                }
            }

            public function main(){
                $resultArray['token'] = parent::createTocken();
                return $resultArray;
            }

            public function runChatStatCalculation(){
				$this->getViewer()->setResponseType('JSON');
                if(parent::checkReferer()<0){
                    return array();
                }
                $year   = Utils::getUrlParam('year',ResError::no);
                $month  = Utils::getUrlParam('month',ResError::no);
//                $day    = Utils::getUrlParam('day',ResError::no);
                $today_year = date("Y");
                $today_month = date("m");
                $today_day = date("d");
                $today_hour = date("H");
                $success_cnt = 0;

                try{
                    $this->initDAO();
                    if($month<10){
                        $month = '0'.$month;
                    }
                    // 일
                    $date = $year.'-'.$month;
                    for($d=1; $d<=31; $d++){
                        if($d<10){
                            $day = '0'.$d;
                        }else{
                            $day = $d;
                        }
                            $date = $year.'-'.$month.'-'.$d;
                            sleep(2);
                        // 시간
                        for($h=0; $h<24; $h++){
                            // 오늘 이후면 break
                            if($today_year<=$year){
                                if($today_month<=$month){
                                        if($today_day<=$d){
                                        if($today_hour<=$h){
                                            echo $date;
                                            break 2;
                                        }
                                    }
                                }
                            }
                            if($h<10){
                                $hour = '0'.$h;
                            }else{
                                $hour = $h;
                            }
                            // 분
                            for($m=0; $m<6; $m++){
                                $min = $m.'0';
                                $date = $year.'-'.$month.'-'.$day.' '.$hour.':'.$min.':00';
                                echo $date;
                                $this->dao->setInsertChartStat($date);
                                $success_cnt++;
                            }
                        }
                    }
                }catch(Exception $e){
                    $json = json_decode($e);
                    $this->dto->result = (int)$json->code;
                    return $this->dto;
                }
                $returnarr['result'] = $success_cnt;
                echo $this->returnarr;
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