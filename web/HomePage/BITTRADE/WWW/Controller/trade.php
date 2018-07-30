<?php
/**
* Description of IndexMain
* @author bugnote.net
* @description Bugnote PHP auto templet
* @date 2013-08-31
*/
class Controller_trade extends BaseController{

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

    // 금액 관련 config
    private function getTradeConfig($type){

        $fee = JsonConfig::get('configtradefee');
        $order_min_krw  = 10000;
        $order_min_coin = 0.01;
        $call_unit_krw  = 10000;
        $call_unit_coin = 0.01;
        $trade_maker_fee = 0.0;
        $trade_tracker_fee = 0.0;
        if( $fee && isset($fee[$type])  ){
            $order_min_krw = $fee[$type]['cfOrderMinKrw'];
            $order_min_coin = $fee[$type]['cfOrderMinCoin'];
            $call_unit_krw = $fee[$type]['cfCallUnitKrw'];
            $call_unit_coin = $fee[$type]['cfCallUnitCoin'];
            $trade_maker_fee = $fee[$type]['cfTrMarketmakerFee'];
            $trade_tracker_fee = $fee[$type]['cfTrTrackerFee'];
        }
        $returnArray = array(
            'order_min_krw'=>$order_min_krw
            ,'order_min_coin'=>$order_min_coin
            ,'call_unit_krw'=>$call_unit_krw
            ,'call_unit_coin'=>$call_unit_coin
            ,'trade_maker_fee'=>$trade_maker_fee
            ,'trade_tracker_fee'=>$trade_tracker_fee);

        return $returnArray;
    }

    // 거래소 > 비트코인 구매
    function order()
	{
		return $this->api_order(0);
    }
	
	// 테스트용
	function order_2()
	{
		return $this->api_order(0);
	}
	
	function order_3()
	{
        //파라이터를 받아 코인 종류를 알아옴
        $currency_type = isset($this->parameters['krw'])?$this->parameters['krw']:'btc';
        $currency_type_upper = ($currency_type)?strtoupper($currency_type):null;
        
        $master = JsonConfig::get('exchangemarket');
        
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
        
		$tradeConfig = $this->getTradeConfig($market_type_upper);
        $viewdata = array('title'=>$this->res->lang->viewTradeBuybtc->title, 'member'=>$this->member,
			'trade_coin_min_limit'=>$tradeConfig['order_min_coin'], 'trade_krw_min_limit'=>$tradeConfig['order_min_krw'],
            'call_unit_coin'=>$tradeConfig['call_unit_coin'], 'call_unit_krw'=>$tradeConfig['call_unit_krw'],
            'maker_fee'=>$tradeConfig['trade_maker_fee'], 'tracker_fee'=>$tradeConfig['trade_tracker_fee'],
            'maker_fee_per'=> round($tradeConfig['trade_maker_fee']*100,2), 'tracker_fee_per'=> round($tradeConfig['trade_tracker_fee']*100,2),
			'master'=>$master, 'exchannel'=>$exchannel,
			'currency'=>$currency_type_upper,
			'is_mobile'=> 0,
			'currency_key'=> strtoupper($currency_type_upper));
        $this->viewer->setHTMLTitle($viewdata['title'].'|'.$this->viewer->getHTMLTitle());
        $this->viewer->setJavaScript($this->jsonObject);
        $this->viewer->setViewBeanData($viewdata);
        return $this->viewer;
	}
	
	function order_m()
	{
		return $this->api_order(1);
	}
	
	function current_price_m()
	{
		return $this->api_order(1);
	}
	
	private function api_order($is_mobile){
        if(!$this->member || !isset($this->member['mb_no']) || !$this->member['mb_no']){
            Utils::redirect('account/signin/return-trade/order');
        }
        
        //파라이터를 받아 코인 종류를 알아옴
		$currency_type = isset($this->parameters['krw']) ? $this->parameters['krw'] : 'btc';

		//echo $currency_type;
		//return;
		
        $currency_type_upper = ($currency_type)?strtoupper($currency_type):null;
        
        $master = JsonConfig::get('exchangemarket');

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

        $this->mblevelchecked();
        $token = Utils::createToken();
        $tradeConfig = $this->getTradeConfig($market_type_upper);

        $viewdata = array('title'=>$this->res->lang->viewTradeBuybtc->title, 'member'=>$this->member,
            'token'=>$token,
            'trade_coin_min_limit'=>$tradeConfig['order_min_coin'], 'trade_krw_min_limit'=>$tradeConfig['order_min_krw'],
            'call_unit_coin'=>$tradeConfig['call_unit_coin'], 'call_unit_krw'=>$tradeConfig['call_unit_krw'],
            'maker_fee'=>$tradeConfig['trade_maker_fee'], 'tracker_fee'=>$tradeConfig['trade_tracker_fee'],
            'maker_fee_per'=> round($tradeConfig['trade_maker_fee']*100,2), 'tracker_fee_per'=> round($tradeConfig['trade_tracker_fee']*100,2),
            'lang'=>$this->res->lang->viewTradeBuybtc, 'langcommon'=>$this->res->lang->common,
            'master'=>$master,'exchannel'=>$exchannel,
			'is_mobile'=> $is_mobile,
			'currency'=> $currency_type_upper);
        $this->viewer->setHTMLTitle($viewdata['title'].'|'.$this->viewer->getHTMLTitle());
        $this->viewer->setJavaScript($this->jsonObject);
        $this->viewer->setViewBeanData($viewdata);

        return $this->viewer;
    }

    // 회원 레벨 체크용 인증 전 즉 레벨 1일경우 본인인증페이지로 이동처리
    public function mblevelchecked(){
        if((int)$this->member['mb_level'] < 2){
//            Utils::redirect('account/verificationcenter/return');
        }
    }

}