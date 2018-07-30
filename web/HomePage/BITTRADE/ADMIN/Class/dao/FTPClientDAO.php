<?php

class FTPClientDAO {

    private $cfg;
    private $client;
    private $clientLogin;
    private $imgExt = '.img';

    function __construct() {
        $this->cfg = Config::getConfig();
    }

    private function initClient(){
        if(!$this->client) {
            $conntxt = array("result"=>ResError::ok,"msg"=>"연결실패","url"=>"");
            $this->client = ftp_connect($this->cfg['ftp']['server'],$this->cfg['ftp']['port']) or die(Utils::jsonEncode($conntxt));
            if($this->client){
                $this->clientLogin = ftp_login($this->client, $this->cfg['ftp']['user'], $this->cfg['ftp']['passwd']);
                ftp_pasv($this->client, true);
            }
        }
    }

    private function closeClient(){
        if($this->client) ftp_close($this->client);
    }

    public function getAccessPoint(){
        $point = $this->cfg['ftp']['user'].':'.$this->cfg['ftp']['passwd'].'@'.$this->cfg['ftp']['server'].':'.$this->cfg['ftp']['port'];
        return "ftp://".$point;
    }
    /*
     * @param 루트 부터 시작해서
     */
    public function makedir($fulldir){
        $this->initClient();

        if (@ftp_chdir($this->client, $fulldir)) {
            @ftp_chdir($this->client, '/');

            return true;
        } else {
            if(ftp_mkdir($this->client, $fulldir))
                return true;
        }
        return false;

    }

    public function putImage($prefix,$filename,$filepath){
        $this->initClient();

        $arrayResult = array("result"=>ResError::ok,"msg"=>"등록실패","url"=>"");
        if(!$this->clientLogin) $arrayResult;
        if(!file_exists($filepath)){
            $arrayResult["result"] = ResError::paramEmptyFile;
            $arrayResult["msg"] = '전송된 파일이 존재하지 않습니다. 용량이 큰지 확인';
            return $arrayResult;
        }
        try {
            if($prefix){
                $this->makedir($prefix);
            }
            $remote_file = $prefix.'/'.$filename .$this->imgExt;
            if (ftp_put($this->client, $remote_file , $filepath, FTP_BINARY)) {
                $arrayResult["result"] = ResError::no;
                $arrayResult["url"] = $this->cfg['url']['image'].(($prefix)?'/':'').''.$remote_file;
                $arrayResult["msg"] = '성공적으로 업로드 하였습니다.';
                return $arrayResult;
            } else {
                $arrayResult["result"] = ResError::ok;
                $arrayResult["url"] = $this->cfg['url']['image'].(($prefix)?'/':'').'/'.$remote_file;
                $arrayResult["msg"] = '업로드에 실패하였습니다.';
                return $arrayResult;
            }
        } catch (Exception $e) {
            $arrayResult["result"] = ResError::exception;
            $arrayResult["msg"] = '예외가 발생되었습니다.';
            return $arrayResult;
        }
        $this->closeClient();
        return $arrayResult;
    }

    public function putMemberImg($mb_id,$filepath){
         $arrayResult = $this->putImage($this->getMemberFolder($mb_id),md5($mb_id),$filepath);
         return $arrayResult;
    }

    public function getMemberImgUrl($mb_id){
         $imgdir = '/member/'.substr($mb_id, 0,2).'/'.md5($mb_id);
         return $this->cfg['url']['image'].$imgdir.$this->imgExt;
    }

    private function getMemberFolder($mb_id){
        $res = 'member/'.substr($mb_id, 0,2);
        return $res;
    }

    public function getLevelRequestFileUrl($filename){
        $filedir = '';
    }

    function delImage($prefix,$filename){
        $this->initClient();

         $arrayResult = array("result"=>ResError::no,"msg"=>"삭제에 실패하였습니다.","url"=>"");
         if(!$this->clientLogin) $arrayResult;
         $remote_file = $prefix.'/'.$filename .$this->imgExt;
         try{
             // get the size of $file
             $res = ftp_size($this->client, $remote_file);
             if($res != -1){ //파일이 있다면

                 if (ftp_delete($this->client, $remote_file)) {
                     $arrayResult["result"] = ResError::no;
                     $arrayResult["url"] = "";
                     $arrayResult["msg"] = '삭제하였습니다.';
                 } else {
                     $arrayResult["result"] = ResError::ok;
                     $arrayResult["url"] = "";
                     $arrayResult["msg"] = '삭제에 실패하였습니다.';
                 }
             }else{
                 $arrayResult["result"] = ResError::ok;
                 $arrayResult["msg"] = '이미삭제되었거나 삭제할 파일이 없습니다.';
                 $arrayResult["url"] = "";
             }

        } catch (Exception $e) {
            $arrayResult["result"] = ResError::exception;
            $arrayResult["msg"] = '예외가 발생되었습니다.';
            return $arrayResult;
        }
        $this->closeClient();
        return $arrayResult;
    }



    public function delMemberImg($mb_id){
        return $this->delImage($this->getMemberFolder($mb_id),md5($mb_id));
    }



}

?>
