<?php
/**
* Description of WebConfigLanguage Controller
* @description FunHanSoft Co.,Ltd  PHP auto templet
* @date 2016-10-24
* @copyright (c)funhansoft.com
* @license 해당 사이트 이외의 사용은 엄격히 금지되어 있습니다.
* @version 4.0.0
*/
        class WebConfigLanguage extends ControllerBase{

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
                $this->dto = new WebConfigLanguageDTO();
            }

            //DB를 연결할 경우만
            private function initDAO(){
                if(!$this->dao){
                    $this->dao = new WebConfigLanguageDAO();
                    $this->dao->setListLimitRow(30);
                    //$this->dao->setListOrderBy(array('cfNo'=>'DESC')); //정렬값
                }
            }

            public function main(){
                $this->setSearchParam();
                $resultArray = array();
                $this->initDAO();
                $resultArray['data'] = $this->dao->getList($this->searchField,$this->searchValue);
                $resultArray['common'] = $this->dao->getListCount($this->searchField,$this->searchValue);
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
                //Input Exception Field ,'cfNo','cfKey1','cfKey2','cfViewType','cfKo','cfEn','cfZh'
                $this->returnarr = $this->returnDTO($this->dto, array());

                if($this->dto->cfNo){
                    if($this->dto->cfNo!=$pri){
                        $resultArray['result'] = ResError::paramUnMatchPri;
                        $resultArray['resultMsg'] = ResString::paramUnMatchPri;
                    }
                }else{
                    $resultArray['result'] = 0;
                    $resultArray['resultMsg'] = ResString::dataNotResult;
                }

                $resultArray['link']['done'] = $resultArray['link']['list'];
                $resultArray['link']['copy'] = str_replace('form', 'copy', $resultArray['link']['write']);
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
                    //Input Exception Field ,'cfNo','cfKey1','cfKey2','cfViewType','cfKo','cfEn','cfZh'
                    $this->returnarr = $this->returnDTO($this->dto, array());
                    if($this->dto->cfNo){
                        if($this->dto->cfNo!=$pri){
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
            
            public function copy(){
                $resultArray = array();
                $resultArray['result'] = ResError::no;
                $resultArray['link'] = parent::getLinkURL();
                $pri = Utils::getUrlParam('id',ResError::no);

                //수정모드
                if($pri) {
                    $this->initDAO();
                    $this->dto = $this->dao->getViewById($pri);
                    //Input Exception Field ,'cfNo','cfKey1','cfKey2','cfViewType','cfKo','cfEn','cfZh'
                    $this->returnarr = $this->returnDTO($this->dto, array());
                    if($this->dto->cfNo){
                        if($this->dto->cfNo!=$pri){
                            $resultArray['result'] = ResError::paramUnMatchPri;
                            $resultArray['resultMsg'] = ResString::paramUnMatchPri;
                        }

                        //수정권한이 있는지 체크

                    }else{
                        $resultArray['result'] = 0;
                        $resultArray['resultMsg'] = ResString::dataNotResult;
                    }

                //쓰기모드
                }
                $resultArray['link']['update'] = str_replace("update", "insert", $resultArray['link']['update']);
                $resultArray['link']['done'] = $resultArray['link']['list']; //완료 후 리스트로 보낼경우
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
                return $this->dao->getList($this->searchField,$this->searchValue);
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
                    $this->dto->cfKey1 = Utils::getPostParam('cfKey1');
                    $this->dto->cfKey2 = Utils::getPostParam('cfKey2',ResError::no);
                    $this->dto->cfViewType = Utils::getPostParam('cfViewType',ResError::no);
                    $this->dto->cfKo = Utils::getPostParam('cfKo');
                    $this->dto->cfEn = Utils::getPostParam('cfEn');
                    $this->dto->cfZh = Utils::getPostParam('cfZh');
                    $this->dto->cfJa = Utils::getPostParam('cfJa');
                    $this->returnarr = $this->returnDTO($this->dto, array('result','cfNo','cfKey2','cfViewType'));
                }catch(Exception $e){
                    $json = json_decode($e);
                    $this->returnarr['result'] = (int)$json->code;
                    return $this->returnarr;
                }

                //param값 유효성 검사
                $this->returnarr['result'] = parent::checkEmptyValue($this->dto,array('result','cfNo','cfKey2','cfViewType'));
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
                    $pri = (int)Utils::getPostParam('cfNo');
                }catch(Exception $e){
                    $json = json_decode($e);
                    $this->returnarr['result'] = (int)$json->code;
                    return $this->returnarr;
                }

                $this->initDAO();
                $this->dto = $this->dao->getViewById($pri);
                $this->returnarr = $this->returnDTO($this->dto, array('result','cfNo','cfViewType'));
                if($this->dto->cfNo){
                    if($this->dto->cfNo!=$pri){
                        $this->returnarr['result'] =  ResError::paramUnMatchPri;
                        return $this->returnarr;
                    }

                    //수정권한이 있는지 체크

                }else{
                    $this->returnarr['result'] = ResError::noResultById;
                    return $this->returnarr;
                }

                try{
                    $this->initModifyPostParam('cfNo');
                    $this->initModifyPostParam('cfKey1');
//                    $this->initModifyPostParam('cfKey2');
                    $this->dto->cfKey2 = Utils::getPostParam('cfKey2',ResError::no);
                    $this->initModifyPostParam('cfViewType',ResError::no);
                    $this->initModifyPostParam('cfKo');
                    $this->initModifyPostParam('cfEn');
                    $this->initModifyPostParam('cfZh');
                    $this->initModifyPostParam('cfJa');
                }catch(Exception $e){
                    $json = json_decode($e);
                    $this->returnarr['result'] = (int)$json->code;
                    return $this->returnarr;
                }

                //param값 유효성 검사
                $this->returnarr['result'] = parent::checkEmptyValue($this->dto,array('result','cfNo','cfKey2','cfViewType'));
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

             /*
             * @brief 데이터 삭제
             * @return int
             */
            public function delete(){
                $this->getViewer()->setResponseType('JSON'); //사용시 JSON으로

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
                //Input Exception Field ,'cfNo','cfKey1','cfKey2','cfViewType','cfKo','cfEn','cfZh'
                $this->returnarr = $this->returnDTO($this->dto, array());
                if($this->dto->cfNo){
                    if($this->dto->cfNo!=$pri){
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

                $title = " 언어관리#".$page;
                $objPHPExcel = new PHPExcel();

                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A1', 'No')
                            ->setCellValue('B1', '타입')
                            ->setCellValue('C1', '메인키')
                            ->setCellValue('D1', '서브키')
                            ->setCellValue('E1', '한국어')
                            ->setCellValue('F1', '영어')
                            ->setCellValue('G1', '중국어')
                            ->setCellValue('G1', '일본어');

                $objPHPExcel->setActiveSheetIndex(0)->getStyle('A1:G1')->getFont()->setBold(true);
                $objPHPExcel->getActiveSheet()->getRowDimension(1)->setRowHeight(15);
                $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
                $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
                $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(30);
                $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(30);
                $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(30);
                $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(30);

                $n = 0;
                $dtoarray = $this->dao->getList($this->searchField,$this->searchValue);
                for($i=0;$i<count($dtoarray);$i++){
                    $n = $i+2;

                    $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A'.$n, $dtoarray[$i]->cfNo)
                            ->setCellValue('B'.$n, $dtoarray[$i]->cfViewType)
                            ->setCellValue('C'.$n, $dtoarray[$i]->cfKey1)
                            ->setCellValue('D'.$n, $dtoarray[$i]->cfKey2)
                            ->setCellValue('E'.$n, $dtoarray[$i]->cfKo)
                            ->setCellValue('F'.$n, $dtoarray[$i]->cfEn)
                            ->setCellValue('G'.$n, $dtoarray[$i]->cfZh)
                            ->setCellValue('G'.$n, $dtoarray[$i]->cfJa);

                    $objPHPExcel->setActiveSheetIndex(0)->getStyle('A'.$n.':G2'.$n)->getFont()->setStrikethrough(false);
                    $objPHPExcel->setActiveSheetIndex(0)->getStyle('A'.$n.':G2'.$n)->getFont()->setColor(new PHPExcel_Style_Color( PHPExcel_Style_Color::COLOR_BLACK ));
                    $objPHPExcel->setActiveSheetIndex(0)->getStyle('A'.$n.':G2'.$n)->getFont()->setSize(9);
                }

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

            function makeLanguageJson(){
                $this->getViewer()->setResponseType('JSON'); //사용시 JSON으로
                $resultArray = array();
                $this->setSearchParam();

                //레퍼러 도메인 체크
                if(parent::checkReferer()<0){
                    return array();
                }
                $this->initDAO();
                $list = $this->dao->getListAll();

                $langKoJson = array();
                $langEnJson = array();
                $langZhJson = array();
                $langKoJs = array();
                $langEnJs = array();
                $langZhJs = array();

                for($i=0; $i<count($list); $i++){
                    if($list[$i]->cfViewType=='PHP'){
                        $langKoJson[$list[$i]->cfKey1][$list[$i]->cfKey2] = $list[$i]->cfKo;
                        $langEnJson[$list[$i]->cfKey1][$list[$i]->cfKey2] = $list[$i]->cfEn;
                        $langZhJson[$list[$i]->cfKey1][$list[$i]->cfKey2] = $list[$i]->cfZh;
                        $langJaJson[$list[$i]->cfKey1][$list[$i]->cfKey2] = $list[$i]->cfJa;
                    }else if($list[$i]->cfViewType=='JS'){
                        if($list[$i]->cfKey1 && $list[$i]->cfKey2 && strlen($list[$i]->cfKey2) > 0 ){
                            $langKoJs[$list[$i]->cfKey1][$list[$i]->cfKey2] = $list[$i]->cfKo;
                            $langEnJs[$list[$i]->cfKey1][$list[$i]->cfKey2] = $list[$i]->cfEn;
                            $langZhJs[$list[$i]->cfKey1][$list[$i]->cfKey2] = $list[$i]->cfZh;
                            $langJaJs[$list[$i]->cfKey1][$list[$i]->cfKey2] = $list[$i]->cfJa;
                        }else if($list[$i]->cfKey1 && !$list[$i]->cfKey2){
                            $langKoJs[$list[$i]->cfKey1] = $list[$i]->cfKo;
                            $langEnJs[$list[$i]->cfKey1] = $list[$i]->cfEn;
                            $langZhJs[$list[$i]->cfKey1] = $list[$i]->cfZh;
                            $langJaJs[$list[$i]->cfKey1] = $list[$i]->cfJa;
                        }
                    }else if($list[$i]->cfViewType=='ALL'){
                        if($list[$i]->cfKey1 && $list[$i]->cfKey2 && strlen($list[$i]->cfKey2) > 0 ){
                            $langKoJson[$list[$i]->cfKey1][$list[$i]->cfKey2] = $list[$i]->cfKo;
                            $langEnJson[$list[$i]->cfKey1][$list[$i]->cfKey2] = $list[$i]->cfEn;
                            $langZhJson[$list[$i]->cfKey1][$list[$i]->cfKey2] = $list[$i]->cfZh;
                            $langJaJson[$list[$i]->cfKey1][$list[$i]->cfKey2] = $list[$i]->cfJa;
                            
                            $langKoJs[$list[$i]->cfKey1][$list[$i]->cfKey2] = $list[$i]->cfKo;
                            $langEnJs[$list[$i]->cfKey1][$list[$i]->cfKey2] = $list[$i]->cfEn;
                            $langZhJs[$list[$i]->cfKey1][$list[$i]->cfKey2] = $list[$i]->cfZh;
                            $langJaJs[$list[$i]->cfKey1][$list[$i]->cfKey2] = $list[$i]->cfJa;
                            
                        }else if($list[$i]->cfKey1 && !$list[$i]->cfKey2){
                            $langKoJson[$list[$i]->cfKey1] = $list[$i]->cfKo;
                            $langEnJson[$list[$i]->cfKey1] = $list[$i]->cfEn;
                            $langZhJson[$list[$i]->cfKey1] = $list[$i]->cfZh;
                            $langJaJson[$list[$i]->cfKey1] = $list[$i]->cfJa;
                            
                            $langKoJs[$list[$i]->cfKey1] = $list[$i]->cfKo;
                            $langEnJs[$list[$i]->cfKey1] = $list[$i]->cfEn;
                            $langZhJs[$list[$i]->cfKey1] = $list[$i]->cfZh;
                            $langJaJs[$list[$i]->cfKey1] = $list[$i]->cfJa;
                        }
                    }
                }
                // json
                $makeKoJsonFile = @fopen('../WebApp/Defined/lang/'."ko.json", "w");
                $resKoJson = @fwrite($makeKoJsonFile, Utils::jsonEncode($langKoJson));
                @fclose($makeKoJsonFile);

                $makeEnJsonFile = @fopen('../WebApp/Defined/lang/'."en.json", "w");
                $resEnJson = @fwrite($makeEnJsonFile, Utils::jsonEncode($langEnJson));
                @fclose($makeEnJsonFile);

                $makeZhJsonFile = @fopen('../WebApp/Defined/lang/'."zh.json", "w");
                $resZhJson = @fwrite($makeZhJsonFile, Utils::jsonEncode($langZhJson));
                @fclose($makeZhJsonFile);
                
                $makeJaJsonFile = @fopen('../WebApp/Defined/lang/'."ja.json", "w");
                $resJaJson = @fwrite($makeJaJsonFile, Utils::jsonEncode($langJaJson));
                @fclose($makeJaJsonFile);
                
                // js
                $makeKoJsFile = @fopen('../WebApp/Defined/lang/js-ko.json', 'w');
                $langKoJs = Utils::jsonEncode($langKoJs);
                $resKoJs = @fwrite($makeKoJsFile, $langKoJs);
                @fclose($makeKoJsFile);

                $makeEnJsFile = @fopen('../WebApp/Defined/lang/js-en.json', 'w');
                $langEnJs = Utils::jsonEncode($langEnJs);
                $resEnJs = @fwrite($makeEnJsFile, $langEnJs);
                @fclose($makeEnJsFile);

                $makeZhJsFile = @fopen('../WebApp/Defined/lang/js-zh.json', 'w');
                $langZhJs = Utils::jsonEncode($langZhJs);
                $resZhJs = @fwrite($makeZhJsFile, $langZhJs);
                @fclose($makeZhJsFile);
                
                
                $makeJaJsFile = @fopen('../WebApp/Defined/lang/js-ja.json', 'w');
                $langJaJs = Utils::jsonEncode($langJaJs);
                $resJaJs = @fwrite($makeJaJsFile, $langJaJs);
                @fclose($makeJaJsFile);

                $this->returnarr['result'] = -1;
                $this->returnarr['msg'] = '[로컬]사이트 적용에 실패하였습니다.';
                if($resKoJson && $resEnJson && $resZhJson && $resKoJs && $resEnJs && $resZhJs) {
                    $this->returnarr['result'] = 1;
                    $this->returnarr['msg'] = '[로컬]사이트 적용에 성공하였습니다.';
                }
                $shareurls = $this->config['configsync']['urls'];
                
                if($shareurls){
                    $arr = explode(',', $shareurls);
                    for($i=0;$i<count($arr);$i++){
                    $response = file_get_contents($arr[$i].__FUNCTION__);
                    $json = json_decode($response,TRUE);
                    $this->returnarr['msg'] = $this->returnarr['msg'] . "\n\n[원격서버". ($i + 1) .']'.$json['msg'];

                    }
                }
                
                
                
                return $this->returnarr;
            }

            /*
             * @brief 검색 파라미터 초기화
             * @return null
             */
            private function setSearchParam(){
                $this->searchField = Utils::getUrlParam('sf',1);
                $this->searchValue = Utils::getUrlParam('sv',1);
                if($this->searchField=='cf_no' || $this->searchField=='cf_key1' || $this->searchField=='cf_key2' || $this->searchField=='cf_view_type' || $this->searchField=='cf_ko' || $this->searchField=='cf_en' || $this->searchField=='cf_zh'){

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