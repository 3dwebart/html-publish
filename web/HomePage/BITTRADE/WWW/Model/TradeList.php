<?php
class TradeList extends BaseModelBase{

    function __construct($dbconfname=null) {
        parent::__construct($dbconfname);
    }


    /*
     * 거래소 거래내역 리스트 상세 리스트(체결 내역)
     * @param url param
     */
    public function getExecute($param){
        $parampono = base64_decode($param['pono']);

        $pono = explode(',', $parampono);

        $sql_value = '';
        $map_value = '';
        $total_count = count($pono);

        $map = '{';
        $map .= '"mb_no":{"default":"SS_MB_NO"},';
        for($i=0; $i<$total_count;$i++){
            $param['p'.$i] = $pono[$i];
            $map .= '"p'.$i.'":{"default":"'.$pono[$i].'"}';
            $sql_value .='?';
            if($i!=($total_count-1)){
                $map .=',';
                $sql_value .=',';
            }
        }

        $map = $map.' }';
        $maparray = json_decode($map);

        $sql = str_replace("{SQLPARSE}", $sql_value, $this->res->ctrl->sql);
//        var_dump($sql, $map, $pono, $sql_value);

        return $this->execLists(
            array(
                'sql'=>$sql,
                'mapkeys'=>$maparray,
                'rowlimit'=>$this->res->ctrl->rowlimit
            )
            , $param,1
        );
    }



    function __destruct(){

    }


}
