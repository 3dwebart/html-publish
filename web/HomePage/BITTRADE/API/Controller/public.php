<?php
/**
* Bitcoin Web Trade Solution
* @description FunHanSoft Co.,Ltd (프로그램 수정 및 사용은 자유롭게 가능하나, 재배포를 엄격히 금지합니다.)
* @date 2015-11-19
* @copyright (c)Funhansoft.com
* @license http://funhansoft.com/solutionlicense/bittrade
* @version 0.0.1
*/
class Controller_public extends BaseController{

    private $member =NULL;
    private $jsonObject = '';

    function __construct($conjson=NULL){
        parent::__construct($conjson);
    }

    function ticker(){
        
        $returnjson = array();
        $tickerdata = array();
        $master = JsonConfig::get('exchangemarket');
        
        $redis_key = 'ticker-main-master';
        $redis = new Credis_Client($this->res->config['redis_trade']['host'],$this->res->config['redis_trade']['port'],null, '', $this->res->config['redis_trade']['db_ticker']);

        $tickerdata = $redis->hGetAll($redis_key);

        $returnjson['market'] = array();
        foreach($master as $keyidx => $value) {
            
            $key = strtolower($keyidx);
            if(isset($master[$keyidx]['itUse']) && $master[$keyidx]['itUse'] =='Y' ){

                if(isset($tickerdata[$key])){
                    $returnjson['market'][$keyidx] = json_decode($tickerdata[$key],TRUE);                    
                    
                }else{
                    $returnjson['market'][$keyidx] = $master[$keyidx];
                    unset($returnjson['market'][$keyidx]['name']);
                    unset($returnjson['market'][$keyidx]['itUse']);
                    unset($returnjson['market'][$keyidx]['itServerIp']);
                    unset($returnjson['market'][$keyidx]['itServerPort']);
                    unset($returnjson['market'][$keyidx]['itServerSignIp']);
                    unset($returnjson['market'][$keyidx]['itServerSignPort']);
                }
                unset($returnjson['market'][$keyidx]['baseVolume']);
                unset($returnjson['market'][$keyidx]['quoteVolume']);
                
            }
        }
        
        return json_encode($returnjson);
    }
    
    
    function orderbook(){
        
        $returnjson = array();
        $tickerdata = array();
        $master = JsonConfig::get('exchangemarket');
        
        $redis_key_buy = 'market-orderbook-tradebuy';
        $redis_key_sell = 'market-orderbook-tradesell';
        $redis = new Credis_Client($this->res->config['redis_trade']['host'],$this->res->config['redis_trade']['port'],null, '', $this->res->config['redis_trade']['db_ticker']);

        $buydata = $redis->hGetAll($redis_key_buy);
        $selldata = $redis->hGetAll($redis_key_sell);
        

        $returnjson = array();
        foreach($master as $keyidx => $value) {
            
            $key = strtolower($keyidx);
            if(isset($master[$keyidx]['itUse']) && $master[$keyidx]['itUse'] =='Y' ){

                if(isset($buydata[$key])){
                    $returnjson[$keyidx]['buy'] = json_decode($buydata[$key],TRUE);
                }
                if(isset($selldata[$key])){
                    $returnjson[$keyidx]['sell'] = json_decode($selldata[$key],TRUE);
                }
                
            }
        }
        
        return json_encode($returnjson);
    }
    
    function tradehistory(){
        
        $returnjson = array();
        $tickerdata = array();
        $master = JsonConfig::get('exchangemarket');
        
        
        
        $redis_trade_his = 'market-orderbook-tradecomplete';
        $redis = new Credis_Client($this->res->config['redis_trade']['host'],$this->res->config['redis_trade']['port'],null, '', $this->res->config['redis_trade']['db_ticker']);

        $tradedata = $redis->hGetAll($redis_trade_his);

        
        $returnjson = array();
        foreach($master as $keyidx => $value) {
            
            $key = strtolower($keyidx);
            
            
            if(isset($master[$keyidx]['itUse']) && $master[$keyidx]['itUse'] =='Y' ){

                if(isset($tradedata[$key])){
                    $returnjson[$keyidx] = json_decode($tradedata[$key],TRUE);
                }
                                
            }
        }
        
        return json_encode($returnjson);
    }

}