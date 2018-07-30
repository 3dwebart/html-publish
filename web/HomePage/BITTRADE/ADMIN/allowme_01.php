<?php
class Index{
    private $tmp_path = '../WebApp/Debug/admin_allowips.txt';

    // config cron 허용 아이피 체크
    private function checkConfigIpAddress(){
        $oauthResult = false;

        $cfgIpValue = @file_get_contents($this->tmp_path);
        if(!$cfgIpValue || !isset($cfgIpValue)){
            return $oauthResult;
        }
        $cfgIpValueArr = explode(',', $cfgIpValue);

        $checkIpStr = '';

        for($i=0; $i<count($cfgIpValueArr); $i++){
            if($_SERVER['REMOTE_ADDR'] == $cfgIpValueArr[$i]){
                $oauthResult = true;
                return $oauthResult;
            }
        }
        return $oauthResult;
    }

    public function run(){
        if(!$this->checkConfigIpAddress()){
            $myfile = file_put_contents($this->tmp_path, $_SERVER['REMOTE_ADDR'].',', FILE_APPEND | LOCK_EX);
            if($myfile){
                echo "hello ". $_SERVER['REMOTE_ADDR'];
            }
        }
    }
}
$index = new Index();
$index->run();

