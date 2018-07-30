<?php
/**
* Description of WebConfigLanguage Controller
* @description FunHanSoft Co.,Ltd  PHP auto templet
* @date 2016-10-24
* @copyright (c)funhansoft.com
* @license 해당 사이트 이외의 사용은 엄격히 금지되어 있습니다.
* @version 0.3.0
*/
        class systemConfigFile extends ControllerBase{

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
                    $this->dao->setListLimitRow(50);
                    //$this->dao->setListOrderBy(array('cfNo'=>'DESC')); //정렬값
                }
            }

            public function main(){
                return null;
            }

            public function view(){

                return null;
            }

            public function form(){

                return null;
            }
            public function lists(){
                $this->getViewer()->setResponseType('404');
            }

            public function insert(){
                $this->getViewer()->setResponseType('404');
            }

            public function update(){
                $this->getViewer()->setResponseType('404');
                return $this->returnarr;
            }
            public function delete(){
                $this->getViewer()->setResponseType('404'); //사용시 JSON으로
            }

            function makeLanguageJson(){
                
                $this->getViewer()->setResponseType('JSON'); //사용시 JSON으로
                $resultArray = array();
                $this->setSearchParam();
                //레퍼러 도메인 체크
//                if(parent::checkReferer()<0){
//                    return array();
//                }
                $this->dto = new WebConfigLanguageDTO();
                $this->dao = new WebConfigLanguageDAO();
                $this->dao->setListLimitRow(30);
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
                $resJaJson = @fwrite($makeZhJsonFile, Utils::jsonEncode($langJaJson));
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
                $resJaJs = @fwrite($makeZhJsFile, $langJaJs);
                @fclose($makeJaJsFile);
                
                
                $this->returnarr['result'] = -1;
                $this->returnarr['msg'] = '사이트 적용에 실패하였습니다.';
                if($resKoJson && $resEnJson && $resZhJson && $resKoJs && $resEnJs && $resZhJs) {
                    $this->returnarr['result'] = 1;
                    $this->returnarr['msg'] = '사이트 적용에 성공하였습니다.';
                }
//                echo json_encode($this->returnarr);
                return $this->returnarr;
            }
            

            
            function makeSiteJson(){
                
                $this->getViewer()->setResponseType('JSON'); //사용시 JSON으로
                $resultArray = array();
                
                //레퍼러 도메인 체크
//                if(parent::checkReferer()<0){
//                    return array();
//                }
                $this->dto = new WebConfigExchangeMarketDTO();
                $this->dao = new WebConfigExchangeMarketDAO();

                $this->dao->setListLimitStart(0);
                $this->dao->setListLimitRow(1000);
                
                
                $this->dao->setListLimitStart(0);
                $this->dao->setListOrderBy(array('it_sort'=>'DESC')); //정렬값
                $list = $this->dao->getList($this->searchField,$this->searchValue);
                

                
                $json = array();
                for($i=0;$i<count($list);$i++){
                    
                    $dto = $list[$i];
                    if($dto->itUse != 'Y' && $dto->itUse != 'P' && $dto->itUse != 'R'){
                        continue;
                    }else if($dto->itUse == 'P' || $dto->itUse == 'R'){
                        $json[$dto->itMarketId]['name'] = 'Stoped';
                    }
                    
                    $json[$dto->itMarketId]['name'] = $dto->itName;
                    $json[$dto->itMarketId]['ch'] = strtolower($dto->itMarketId);
                    $json[$dto->itMarketId]['last'] = '0.0000000';
                    $json[$dto->itMarketId]['ask'] = '0.0000000';
                    $json[$dto->itMarketId]['bid'] = '0.0000000';
                    $json[$dto->itMarketId]['percentChange'] ='0.00';
                    $json[$dto->itMarketId]['volume24h'] = '0.0000000';
                    $json[$dto->itMarketId]['baseVolume'] = '0.0000000';
                    $json[$dto->itMarketId]['quoteVolume'] = '0.0000000';
                    $json[$dto->itMarketId]['itUse'] = $dto->itUse;
                    $json[$dto->itMarketId]['itServerIp'] =  $dto->itServerIp;
                    $json[$dto->itMarketId]['itServerPort'] =  $dto->itServerPort;
                    $json[$dto->itMarketId]['itServerSignIp'] =  $dto->itServerSignIp;
                    $json[$dto->itMarketId]['itServerSignPort'] =  $dto->itServerSignPort;
                    $json[$dto->itMarketId]['itWalletUse'] =  $dto->itWalletUse;
                }

                $makeFeeJsonFile = @fopen("../WebApp/Defined/config/exchangemarket.json", "w");
                $resFeeJson = @fwrite($makeFeeJsonFile, Utils::jsonEncode($json));
                @fclose($makeFeeJsonFile);

                $this->returnarr['result'] = -1;
                $this->returnarr['msg'] = '사이트 적용에 실패하였습니다. 폴더 퍼미션을 확인해 보세요.';
                if($resFeeJson) {
                    $this->returnarr['result'] = 1;
                    $this->returnarr['msg'] = '사이트 적용에 성공하였습니다.';
                }
                return $this->returnarr;
            }
            
            function makeFeeSiteJson(){
                $this->getViewer()->setResponseType('JSON'); //사용시 JSON으로
                $resultArray = array(); 

                //레퍼러 도메인 체크
//                if(parent::checkReferer()<0){
//                    return array();
//                }
                $this->dao = new WebConfigTradeFeeDAO();
                $this->dto = new WebConfigTradeFeeDTO();
                $this->dao->setListLimitStart(0);
                $this->dao->setListLimitRow(1000);
                $list = $this->dao->getList($this->searchField,$this->searchValue);
                
                $json = array();
                for($i=0;$i<count($list);$i++){
                    $dto = $list[$i];
                    $json[$dto->cfMarketType]['cfTrTrackerFee'] = $dto->cfTrTrackerFee;
                    $json[$dto->cfMarketType]['cfTrTrackerFeeDisplay'] = (string)((float)$dto->cfTrTrackerFee * 100);
                    $json[$dto->cfMarketType]['cfTrMarketmakerFee'] = $dto->cfTrMarketmakerFee;
                    $json[$dto->cfMarketType]['cfTrMarketmakerFeeDisplay'] = (string)((float)$dto->cfTrMarketmakerFee * 100);
                    $json[$dto->cfMarketType]['cfOrderMinKrw'] = (string)($dto->cfOrderMinKrw);
                    $json[$dto->cfMarketType]['cfOrderMinCoin'] = (string)($dto->cfOrderMinCoin);
                    $json[$dto->cfMarketType]['cfCallUnitKrw'] = (string)($dto->cfCallUnitKrw);
                    $json[$dto->cfMarketType]['cfCallUnitCoin'] = (string)($dto->cfCallUnitCoin);
                }

                $makeFeeJsonFile = @fopen("../WebApp/Defined/config/configtradefee.json", "w");
                $resFeeJson = @fwrite($makeFeeJsonFile, Utils::jsonEncode($json));
                @fclose($makeFeeJsonFile);

                $this->returnarr['result'] = -1;
                $this->returnarr['msg'] = '사이트 적용에 실패하였습니다. 폴더 퍼미션을 확인해 보세요.';
                if($resFeeJson) {
                    $this->returnarr['result'] = 1;
                    $this->returnarr['msg'] = '사이트 적용에 성공하였습니다.';
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