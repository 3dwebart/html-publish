<?php
/**
* Bitcoin Web Trade Solution
* @description FunHanSoft Co.,Ltd (프로그램 수정 및 사용은 자유롭게 가능하나, 재배포를 엄격히 금지합니다.)
* @date 2015-11-19
* @copyright (c)Funhansoft.com
* @license http://funhansoft.com/solutionlicense/bittrade
* @version 0.0.1
*/
class Controller_main extends BaseController{

    private $member =NULL;
    private $jsonObject = '';

    function __construct($conjson=NULL){
        parent::__construct($conjson);
        $this->setDefaultInitJsonObject();
    }
    
    private function setDefaultInitJsonObject($return=null){
        if(!$return){
            $return = array(
                'link'=>base64_encode((json_encode($this->res->config['url']))),
                'client'=>base64_encode((json_encode($this->res->config['client'])))
            );
        }
        $mb = $this->getMemberDataFromRedis();
        $this->member = json_decode($mb,TRUE);
        if ($mb) {
            $return['member'] = base64_encode($mb);
        }
        $this->jsonObject = 'var jsonObject='.json_encode($return );
    }


    // 메인페이지
    function main(){
        
        if($this->member || isset($this->member['mb_no']) || $this->member['mb_no']){
            Utils::redirect('trade/order');
        }
        
        //파라이터를 받아 코인 종류를 알아옴
        $currency_type = isset($this->parameters['krw'])?$this->parameters['krw']:'btc';
        $currency_type_upper = ($currency_type)?strtoupper($currency_type):null;
        
        $master = JsonConfig::get('exchangemarket');
        
		$redis_key = 'ticker-main-master';
		$redis = new Credis_Client($this->res->config['redis_trade']['host'],$this->res->config['redis_trade']['port'],null, '', $this->res->config['redis_trade']['db_ticker']);
		$tickerdata = $redis->hGetAll($redis_key);
		
		$markets = array();
		foreach($master as $keyidx => $value) {
			$key = strtolower($keyidx);
            if(isset($master[$keyidx]['itUse']) && $master[$keyidx]['itUse'] =='Y' ){

                if(isset($tickerdata[$key])){
                    $markets[$keyidx] = json_decode($tickerdata[$key],TRUE);                    
                }else{
                    $markets[$keyidx] = $master[$keyidx];
                }
                unset($markets[$keyidx]['baseVolume']);
                unset($markets[$keyidx]['quoteVolume']);
            }
		}
		
        $market_type_upper = 'KRW_'.$currency_type_upper;
        if(!$currency_type || !isset($master[$market_type_upper])){
            foreach($master as $key => $value) {
                $market_type_upper = $key;
                $currency_type_upper = str_replace("KRW_", "", $market_type_upper);
                $currency_type = strtolower($currency_type_upper);
                break;
            }
        }
        
        $current_master = $master[$market_type_upper];
        
        //소켓 접속 채널
        $exchannel = array(
            'currency_name'=>$current_master['name'],
            'currency_type'=>$currency_type_upper,
            'sock_ch'=>$current_master['ch']
        );
        
        $arrurl = $this->res->config['url'];
        $arrurl['websocket'] = '//'.$master[$market_type_upper]['itServerIp'].':'.$master[$market_type_upper]['itServerPort'];

        $this->setDefaultInitJsonObject(array(
            'link'=>base64_encode((json_encode($arrurl))),
            'client'=>base64_encode((json_encode($this->res->config['client']))),
            'channel'=>base64_encode(json_encode(
                    array('ch'=>'krw_'.$currency_type , 'currency'=>$currency_type)
                    ))
        ));

        $viewdata = array('title'=>$this->res->lang->viewMainMain->title, 'master'=>$master,
            'lang'=>$this->res->lang->viewMainMain, 'langcommon'=>$this->res->lang->common,
            'exchannel'=>$exchannel,
			'markets' => $markets);
        $this->viewer->setHTMLTitle($this->viewer->getHTMLTitle());
        $this->viewer->setJavaScript($this->jsonObject);
        $this->viewer->setViewBeanData($viewdata);

        return $this->viewer;
    }

    // 서버 점검 페이지
    function servercheck(){
        $this->viewer->setJavaScript($this->jsonObject);
        $this->viewer->setViewBeanData(array('test'=> '0'));
        return $this->viewer;
    }

}