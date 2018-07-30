<?php
/**
* Description of WebMember Controller
* @description Funhansoft PHP auto templet
* @date 2013-09-05
* @copyright (c)funhansoft.com
* @license http://funhansoft.com/license
* @version 0.2.1
*/
        class WebMemberBalance extends ControllerBase{

            private $dao;
            private $dto;
            private $searchField;
            private $searchValue;

            /**
            * @brief
            **/
            function __construct(){
                parent::__construct();
                $this->dto = new WebMemberDTO();
                $this->dao = new WebMemberDAO();
            }

            //DB를 연결할 경우만
            private function initDAO(){
                if(!$this->dao){
                    $this->dao = new WebMemberDAO();
                    
                    //$this->dao->setListOrderBy(array('poNo'=>'DESC')); //정렬값
                }
                $this->dao->setListLimitRow(5);
            }
 

           public function balanceSum(){
               $this->getViewer()->setResponseType('JSON');
               $mb_no = Utils::getUrlParam('param');
               $this->dao = new WebPointDAO();
               $result = $this->dao->callBalanceSum($mb_no,'admin');
               // 밸런스 메모리화로 인한 redis 값변경
               $this->setBalanceUpdate($mb_no);
           }
           
           public function balanceSumRecovery(){
               $this->getViewer()->setResponseType('JSON');
               $mb_no = Utils::getUrlParam('param');
               $this->dao = new WebPointDAO();
               $result = $this->dao->callBalanceSumRecovery($mb_no,'recovery');
               // 밸런스 메모리화로 인한 redis 값변경
               $this->setBalanceUpdate($mb_no);
           }
           
           public function setBalanceUpdate($mb_no)
            {
                $loginstatus = 0;

                $mbsearchkey = '';
                $mbsearchkey = 'MB'.$mb_no;
                $session_expire = ((int)$this->config['session']['cache_expire'] * 60);

                //balance
                $pointdao = new WebPointDAO();
                $balancedata = $pointdao->setRedisBalance($mb_no);

                //balance update
                if($mbsearchkey){
                    $this->initRedisServer($this->config['redis']['host'],$this->config['redis']['port'], null,  $this->config['redis']['db_member'], null);
                    $balancekey = $mbsearchkey . '-balance';
                    $tmpbalancedata = $this->getRedisData($balancekey);

                    if(isset($tmpbalancedata)){
                        $jsonbalancedata = json_encode($balancedata);
                        if($tmpbalancedata != $jsonbalancedata){
                            $this->delRedisData($balancekey);
                            $this->setRedisData($balancekey,$jsonbalancedata,$session_expire);
                        }
                    }
                }

                return $balancedata;
            }

           public function main(){
                $this->setSearchParam();
                $this->initDAO();
                $resultArray = array();
                $resultArray['data'] = $this->dao->getListExtend($this->searchField,$this->searchValue);
                $resultArray['common'] = $this->dao->getListCount($this->searchField,$this->searchValue);
                $resultArray['link'] = parent::getLinkURL();
                return $resultArray;
            }

            public function view(){
                $this->getViewer()->setResponseType('404');
                

                return ;
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
                return $this->dao->getListExtend($this->searchField,$this->searchValue);
            }

            /*
             * @brief 데이터 삽입
             * @return object
             */
            public function insert(){
                $this->getViewer()->setResponseType('404');
                return $this->dto;

            }

            /*
             * @brief 데이터 수정
             * @return object
             */
            public function update(){
                $this->getViewer()->setResponseType('404');
                return $this->dto;
            }

            public function downloadExcel(){

                if(parent::checkReferer()<0){
                    return array();
                }

                $page = (int)Utils::getUrlParam('page',ResError::no);
                $limit = (int)Utils::getUrlParam('limit',ResError::no);

                $this->setSearchParam();
                $this->initDAO();
                if($limit){
                    if($limit>1000) $limit = 1000;
                }else{
                    $limit = 1000;
                }
                $this->dao->setListLimitRow($limit);

                if($page){
                    if($page<1) $page=1;
                    $this->dao->setListLimitStart($this->dao->getListLimitRow() * ($page-1));
                }else{
                    $this->dao->setListLimitStart(0);
                    $page = 1;
                }

                $title = " 회원계좌정보#".$page;
                $objPHPExcel = new PHPExcel();

                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A1', '회원번호')
                            ->setCellValue('B1', '회원아이디')
                            ->setCellValue('C1', '비트코인계좌')
                            ->setCellValue('D1', '보유 BTC')
                            ->setCellValue('E1', '보유 KRW')
                            ->setCellValue('F1', '사용중 BTC')
                            ->setCellValue('G1', '사용중 KRW');

                $objPHPExcel->setActiveSheetIndex(0)->getStyle('A1:R1')->getFont()->setBold(true);
                $objPHPExcel->getActiveSheet()->getRowDimension(1)->setRowHeight(15);
                $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(24.5);    //아이디
                $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(35);    //비트코인 계좌
                $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(18);      // 보유 BTC
                $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(18);      // 보유 KRW
                $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(18);      // 사용중 BTC
                $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(18);      // 사용중 KRW

                $n = 0;
                $dtoarray = $this->dao->getList($this->searchField,$this->searchValue);
                for($i=0;$i<count($dtoarray);$i++){
                    $n = $i+2;

                    $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A'.$n, $dtoarray[$i]->mbNo)
                            ->setCellValue('B'.$n, $dtoarray[$i]->mbId)
                            ->setCellValue('C'.$n, $dtoarray[$i]->mbBitWallet)
                            ->setCellValue('D'.$n, $dtoarray[$i]->mbBtc)
                            ->setCellValue('E'.$n, $dtoarray[$i]->mbKrw)
                            ->setCellValue('F'.$n, $dtoarray[$i]->mbUsedBtc)
                            ->setCellValue('G'.$n, $dtoarray[$i]->mbUsedKrw);

                    $objPHPExcel->setActiveSheetIndex(0)->getStyle('A'.$n.':K2'.$n)->getFont()->setStrikethrough(false);
                    $objPHPExcel->setActiveSheetIndex(0)->getStyle('A'.$n.':K2'.$n)->getFont()->setColor(new PHPExcel_Style_Color( PHPExcel_Style_Color::COLOR_BLACK ));
                    $objPHPExcel->setActiveSheetIndex(0)->getStyle('A'.$n.':K2'.$n)->getFont()->setSize(9);
                }
                $n++;
                //sum
                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('C'.$n, '합산==>')
                            ->setCellValue('D'.$n, '=SUM(D2:D'.($n-1).')')
                            ->setCellValue('E'.$n, '=SUM(E2:E'.($n-1).')')
                            ->setCellValue('F'.$n, '=SUM(F2:F'.($n-1).')')
                            ->setCellValue('G'.$n, '=SUM(G2:G'.($n-1).')');

                $objPHPExcel->setActiveSheetIndex(0)->getStyle('A'.$n.':K2'.$n)->getFill()->setStartColor(new PHPExcel_Style_Color( PHPExcel_Style_Color::COLOR_GRAY ));
                $objPHPExcel->setActiveSheetIndex(0)->getStyle('A'.$n.':K2'.$n)->getFont()->setStrikethrough(false);
                $objPHPExcel->setActiveSheetIndex(0)->getStyle('A'.$n.':K2'.$n)->getFont()->setColor(new PHPExcel_Style_Color( PHPExcel_Style_Color::COLOR_BLACK ));
                $objPHPExcel->getProperties()->setCreator("Funhansoft Bitcoin Solution")
                                             ->setTitle($title);

                // Rename worksheet
                $objPHPExcel->getActiveSheet()->setTitle($title.' page-'.$page);
                // Set active sheet index to the first sheet, so Excel opens this as the first sheet
                $objPHPExcel->setActiveSheetIndex(0);
                // Redirect output to a client’s web browser (Excel5)
                header('Content-Type: application/vnd.ms-excel');
                header('Content-Disposition: attachment;filename="'.$title.'.xls"');
                header('Cache-Control: max-age=0');
                // If you're serving to IE 9, then the following may be needed
                header('Cache-Control: max-age=1');
                // If you're serving to IE over SSL, then the following may be needed
                header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
                header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
                header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
                header ('Pragma: public'); // HTTP/1.0

                $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
                $objWriter->save('php://output');
                exit;
            }



             /*
             * @brief 데이터 삭제
             * @return int
             */
            public function delete(){

            }


            /*
             * @brief 검색 파라미터 초기화
             * @return null
             */
            private function setSearchParam(){
                $this->searchField = Utils::getUrlParam('sf',1);
                $this->searchValue = Utils::getUrlParam('sv',1);
                if($this->searchField=='mb_no' || $this->searchField=='mb_id' || $this->searchField=='mb_nick' || $this->searchField=='mb_pwd' || $this->searchField=='mb_key' || $this->searchField=='mb_level' || $this->searchField=='mb_bit_wallet' || $this->searchField=='mb_point' || $this->searchField=='mb_birth' || $this->searchField=='mb_email' || $this->searchField=='mb_hp' || $this->searchField=='mb_password_q' || $this->searchField=='mb_password_a' || $this->searchField=='mb_certificate' || $this->searchField=='mb_agent' || $this->searchField=='contry_code' || $this->searchField=='mb_reg_ip' || $this->searchField=='mb_reg_dt' || $this->searchField=='mb_del_yn' || $this->searchField=='mb_name' || $this->searchField=='mb_gender'){

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