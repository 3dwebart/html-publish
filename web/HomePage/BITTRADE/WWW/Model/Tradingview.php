<?php
class Tradingview extends BaseModelBase{

    function __construct($dbconfname=null) {
        parent::__construct($dbconfname);
    }

    /*
     * 차트
     * @param url param
     */
    public function getExecute($param){
        $explode_request_uri = explode("/", $_SERVER['REQUEST_URI']);
        $request_type = $explode_request_uri[2];
        
        switch($request_type){
            case 'config': 
                $resolutions_data = array('1','5','15','30','60','240','1D');
                $data = array(
                    "supported_resolutions" => $resolutions_data,
                    "supports_group_request" => false,
                    "supports_marks" => false,
                    "supports_search" => true,
                    "supports_time" => true
                );
            break;
        
            case 'time':
                $data = time();
            break;
        
            case 'symbols':
                $resolutions_data = array('1','5','15','30','60','240','1D');
        
                $data = array(
                    "currency_code" => "", 
                    "data_status" => "", 
                    "description" => $param['symbol']."/KRW", 
                    "exchange" => "", 
                    "expiration_date" => "", 
                    "expired" => "", 
                    "force_session_rebuild" => "", 
                    "fractional" => false, 
                    "has_daily" => true, 
                    "has_empty_bars" => true, 
                    "has_intraday" => true, 
                    "has_no_volume" => false, 
                    "has_seconds" => false, 
                    "has_weekly_and_monthly" => false, 
                    "industry" => "", 
                    "intraday_multipliers" => $resolutions_data, 
                    "listed_exchange" => "", 
                    "minmov" => 1, 
                    "minmove2" => 4, 
                    "name" => $param['symbol'], 
                    "pricescale" => 1, 
                    "seconds_multipliers" => "", 
                    "sector" => "", 
                    "session" => "24x7", 
                    "supported_resolutions" => $resolutions_data, 
                    "ticker" => $param['symbol'], 
                    "timezone" => "Asia/Seoul", 
                    "type" => "", 
                    "volume_precision" => ""
                );
            break;
        
            case 'history':
                $currency = strtolower($param['symbol']);
                $resolution = $param['resolution'];
                $candlesticks = $resolution;
                $from_dt = $param['from'];
                $to_dt = (int)$param['to'] - 120;
                
                //사용할 remotedb 생성
                $remotedb = $this->res->ctrl->database->$currency;
                $sql = str_replace("{currency}", $currency, $this->res->ctrl->min_sql);
                $mapkeys = $this->res->ctrl->min_mapkeys;
                
                if($resolution != 'D'){
                    $sql = str_replace("{currency}", $currency, $this->res->ctrl->min_sql);
                    $mapkeys = $this->res->ctrl->min_mapkeys;
                }else if($resolution == '60' || $resolution == '240'){
                    $sql = str_replace("{currency}", $currency, $this->res->ctrl->hour_sql);
                    $mapkeys = $this->res->ctrl->hour_mapkeys;
                    $candlesticks = (int)$resolution / 60;
                }else{
                    $sql = str_replace("{currency}", $currency, $this->res->ctrl->day_sql);
                    $mapkeys = $this->res->ctrl->day_mapkeys;
                    $candlesticks = 1;
                }
        
                $result = $this->execRemoteLists(
                    $remotedb,
                    array(
                        'sql'=>$sql,
                        'mapkeys'=>$mapkeys,
                    ),array(
                        "candlesticks"=>$candlesticks,
                        "from_dt"=>$from_dt,
                        "to_dt"=>$to_dt
                    )
                );
                
                if(isset($result[0]) && (int)$result[0]['result'] > 0){
                    $chart_t_data = array();
                    $chart_c_data = array();
                    $chart_o_data = array();
                    $chart_h_data = array();
                    $chart_l_data = array();
                    $chart_v_data = array();

                    for($i=0;$i<count($result);$i++){
                        array_push($chart_t_data, $result[$i]['t']);
                        array_push($chart_c_data, (int)$result[$i]['c']);
                        array_push($chart_o_data, (int)$result[$i]['o']);
                        array_push($chart_h_data, (int)$result[$i]['h']);
                        array_push($chart_l_data, (int)$result[$i]['l']);
                        array_push($chart_v_data, $result[$i]['v']);
                    }

                    $data = array(
                        "s" => "ok",
                        "t" => $chart_t_data,
                        "c" => $chart_c_data,
                        "o" => $chart_o_data,
                        "h" => $chart_h_data,
                        "l" => $chart_l_data,
                        "v" => $chart_v_data
                    );
                }else{
                    $data = array(
                        "s" => "no_data"
                    );
                }

            break;
        }

        return $data;
    }

    function __destruct(){

    }


}
