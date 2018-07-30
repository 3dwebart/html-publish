<?php
exit;
require_once './../../../../Class/Class/Config.class.php';
$config = Config::getConfig();
require_once $config['path']['class'].'dao/FTPClientDAO.php';


//$config['path']['tmpfile']

// default redirection
$url = $_REQUEST["callback"].'?callback_func='.$_REQUEST["callback_func"];
$bSuccessUpload = is_uploaded_file($_FILES['Filedata']['tmp_name']);

$filename = time()."_".$_FILES['Filedata']['name'];
//파일업로드
$ftpClitnt = new FTPClientDAO();
$resarr = $ftpClitnt->putImg('editor/bbs',$filename,$bSuccessUpload);
if($resarr['result']>0){
    $url .= "&bNewLine=true";
    $url .= "&sFileName=".urlencode(urlencode($filename));
    $url .= "&sFileURL=".$resarr['url'];
}else{
    $url .= '&errstr=error';
}
//
//
//// SUCCESSFUL
//if(bSuccessUpload) {
//	$tmp_name = $_FILES['Filedata']['tmp_name'];
//	$name = $_FILES['Filedata']['name'];
//	
//	$filename_ext = strtolower(array_pop(explode('.',$name)));
//	$allow_file = array("jpg", "png", "bmp", "gif");
//	
//	if(!in_array($filename_ext, $allow_file)) {
//		$url .= '&errstr='.$name;
//	} else {
//		$uploadDir = '../../upload/';
//		if(!is_dir($uploadDir)){
//			mkdir($uploadDir, 0777);
//		}
//		
//		$newPath = $uploadDir.urlencode($_FILES['Filedata']['name']);
//		
//		@move_uploaded_file($tmp_name, $newPath);
//		
//		$url .= "&bNewLine=true";
//		$url .= "&sFileName=".urlencode(urlencode($name));
//		$url .= "&sFileURL=/editorimg/upload/".urlencode(urlencode($name));
//	}
//}
//// FAILED
//else {
//	$url .= '&errstr=error';
//}
	
header('Location: '. $url);
?>