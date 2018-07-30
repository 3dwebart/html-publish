<?php
class CreateChartFile extends BaseModelBase{

    private $chart_write_time = 60;
    private $chart_basic_path = '../WebApp/Data/chart';
    
    function __construct($dbconfname=null) {
        parent::__construct($dbconfname);
    }

    private function getChartFilePath($currency, $timetype){
        return $this->chart_basic_path."/".$currency.'-'.$timetype.'-'.date('Ymd').'.json';
    }

    /*
     * 차트
     * @param url param
     */
    public function getExecute($param){
        
        $timetype = $param['timetype'];
        $candlesticks = $param['timevalue'];
        $currency   = $param['currency'];
        
        //사용할 remotedb 생성
        $remotedb = $this->res->ctrl->database->$currency;
        //currency에 맞춰서 테이블 변경
        if($timetype == 'day'){
            $sql = str_replace("{currency}", $currency, $this->res->ctrl->day_sql);
            $mapkeys = $this->res->ctrl->day_mapkeys;
        }else if($timetype == 'hour'){
            $sql = str_replace("{currency}", $currency, $this->res->ctrl->hour_sql);
            $mapkeys = $this->res->ctrl->hour_mapkeys;
        }else{
            $timetype = $candlesticks.$timetype;
            $sql = str_replace("{currency}", $currency, $this->res->ctrl->min_sql);
            $mapkeys = $this->res->ctrl->min_mapkeys;
        }

        @mkdir($this->chart_basic_path, 0707);
        $chart_file_path = $this->getChartFilePath($currency, $timetype);
        $chart_file_data = @fopen($chart_file_path, 'r');

        if($chart_file_data){
            $file_time = filemtime($chart_file_path);
            
            if( time() - $this->chart_write_time > $file_time ){
                $data = $this->execRemoteLists(
                    $remotedb,
                    array(
                        'sql'=>$sql,
                        'mapkeys'=>$mapkeys,
                    ),array(
                        "candlesticks"=>$candlesticks
                    )
                );
                $make_file = @fopen($chart_file_path, 'w');
                $json_data = Utils::jsonEncode($data);
                @fwrite($make_file, $json_data);
                @fclose($make_file);
            }else{
                while (!feof($chart_file_data)) {
                    $line_of_text = fgets($chart_file_data);
                }
                fclose($chart_file_data);
                $data = $line_of_text;

                echo $data;
                exit;
            }
        }else{
            $data = $this->execRemoteLists(
                $remotedb,
                array(
                    'sql'=>$sql,
                    'mapkeys'=>$mapkeys,
                ),array(
                    "candlesticks"=>$candlesticks
                )
            );
            $make_file = @fopen($chart_file_path, 'w');
            $json_data = Utils::jsonEncode($data);
            @fwrite($make_file, $json_data);
            @fclose($make_file);
        }
        
        return $data;
    }



    function __destruct(){

    }


}
