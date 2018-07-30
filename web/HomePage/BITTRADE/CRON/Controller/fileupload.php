<?php
/**
* Description of WebBbsMain Controller
* @description Funhansoft PHP auto templet
* @date 2013-09-20
* @copyright (c)funhansoft.com
* @license http://funhansoft.com/license
* @version 0.2.2
*/


class fileupload extends ControllerBase{
    private $client;
    private $mbNo,$mbId,$mbNick;
    /**
    * @brief
    **/
    function __construct(){
        parent::__construct();
    }

    public function editor() {
        
        $sFileInfo = '';
        
	$headers = array();
	 
	foreach($_SERVER as $k => $v) {
		if(substr($k, 0, 9) == "HTTP_FILE") {
			$k = substr(strtolower($k), 5);
			$headers[$k] = $v;
		} 
	}
	
	$file = new stdClass;
	$file->name = rawurldecode($headers['file_name']);
	$file->size = $headers['file_size'];
	$file->content = file_get_contents("php://input");
	
        
        $arr = explode(".", $file->name);
        $filename_ext = strtolower(array_pop($arr));
	$allow_file = array("jpg", "png", "bmp", "gif"); 
	
//        if(!$this->mbNo) {
//            echo "NOTALLOW_".$file->name; //회원이 아니면
//            return;
//        }
        
	if(!in_array($filename_ext, $allow_file)) {
		echo "NOTALLOW_".$file->name;
	} else {
            $ftpClient = new FTPClientDAO();
     
            $folder = 'editor/bbs/'.date("Ymd");
            $ftpClient->makedir($folder);
            $newPath = $ftpClient->getAccessPoint().'/'.$folder.'/'.iconv("utf-8", "cp949", $file->name);
            
            $context = stream_context_create(array('ftp' => array('overwrite' => true)));
            if(file_put_contents($newPath, $file->content,null, $context )) {
                $sFileInfo .= "&bNewLine=true";
                $sFileInfo .= "&sFileName=".$file->name;
                $sFileInfo .= "&sFileURL=".$this->config['url']['image'].'/'.$folder.'/'.$file->name;
            }

            echo $sFileInfo;
	}

    }
    
    public function castImage(){
        $this->getViewer()->setResponseType('JSON');
        
        //레퍼러 도메인 체크
//        if(parent::checkReferer()<0){
//             return array();
//        }
        $ftpClitnt = new FTPClientDAO();
        $file = Utils::getFileParam('img',ResError::no);
        $mb_id = Utils::getPostParam('ch');
        
        //파일업로드
        $resarr = $ftpClitnt->putImage('cast',md5($mb_id),$file['tmp_name']);
        return $resarr;
    }

    public function delete(){}
    public function insert() {}
    public function lists() {}
    public function main() {}
    public function update() {}
			
}