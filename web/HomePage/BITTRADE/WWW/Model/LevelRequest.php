<?php

class LevelRequest extends BaseModelBase{

    private $member;

    function __construct($dbconfname=null) {
        parent::__construct($dbconfname);

    }
    /*
     * 실행
     * @param url param
     */
    // 회원 레벨 승급 요청
    public function getExecute($param){

        require_once $this->res->config['path']['plugin'].'FTPClientDAO.php';

        // 중요 파라미터 타입
        if( !isset($param['mb_cur_level']) || !isset($param['mb_req_level']) || !isset($param['mb_prove_method']) ){
            return array(
                "result" => -5401,
                "success" => false,
                "error" => $this->res->lang->validator->required );
        }
        $request = $this->execLists(
            array(
                'sql'=>$this->res->ctrl->sql_select,
                'mapkeys'=>$this->res->ctrl->mapkeys_select,
                'rowlimit'=>1
            ),
            array('mb_req_level'=>$param['mb_req_level'])
        );


        if(isset($request) && $request[0]['cnt']>0){
            return array(
                "result" => -5402,
                "success" => false,
                "error" => $this->res->lang->account->levelRequestValidHistory );
        }

        // 인증 방법 파일일경우 파일 유효성 체크
        $oFtp = new FTPClientDAO();

        $allow_file = array("jpg", "jpeg", "png", "bmp", "gif");

        $file = $_FILES;
        if(!isset($file)){
            return array(
                "result" => -5403,
                "success" => false,
                "error" => $this->res->lang->validator->required );
        }

        $arr1 = explode(".", $file['mb_prove_file1']['name']);
        $arr2 = explode(".", $file['mb_prove_file2']['name']);

        $filename_ext1 = strtolower(array_pop($arr1));
        $filename_ext2 = strtolower(array_pop($arr2));

        if(!in_array($filename_ext1, $allow_file) && !in_array($filename_ext2, $allow_file)) {
            $this->returnarr['result'] = -5411;
            $this->returnarr['msg'] = $this->res->lang->account->levelRequestFileTypeError;
            return $this->returnarr;
        }

        $oFtp->setFileExt($filename_ext1);
        $oFtp->setFileExt($filename_ext2);

        $filename1 = MD5($param['mb_id'].$arr1[0]).'_1_'.strtotime("now").'.'.$filename_ext1;
        $filename2 = MD5($param['mb_id'].$arr2[0]).'_2_'.strtotime("now").'.'.$filename_ext2;
        // 업로드 위치
        $outputpah1      = $this->res->config['path']['tmpfile'].$filename1;
        $outputpah2      = $this->res->config['path']['tmpfile'].$filename2;

        @unlink($outputpah1);
        @unlink($outputpah2);

        if(move_uploaded_file($file['mb_prove_file1']['tmp_name'], $outputpah1)){
//                echo 'success';
        }else{
            return array(
                "result" => -5412,
                "success"=>false,
                "error"=>$this->res->lang->validator->fileUploadFailed);
        }
        if(move_uploaded_file($file['mb_prove_file2']['tmp_name'], $outputpah2)){
//                echo 'success';
        }else{
            return array(
                "result" => -5412,
                "success"=>false,
                "error"=>$this->res->lang->validator->fileUploadFailed);
        }

        // 파일 업로드
        $dirprefix = 'member/certificate';
        $resarr1 = $oFtp->putImage($dirprefix, $filename1, $outputpah1);
        $resarr2 = $oFtp->putImage($dirprefix, $filename2, $outputpah2);

        // 파일 크기 체크
        $filesize1 = filesize($outputpah1);
        $filesize2 = filesize($outputpah2);
        // 파일 업로드 제한 - 2개 제한크기/2 - 내려주는 파일 크기 각 폼의 개수에 맞게
        // config 는 mb 내려주는건 byte
        $upload_file_size_limit = $this->res->config['ftp']['upload_file_size_limit'];
        if(!isset($upload_file_size_limit)){
            $upload_file_size_limit = (int)((10/2)*1024)*1024;
        }else{
            $upload_file_size_limit = (int)(($upload_file_size_limit/2)*1024)*1024;
        }

        if($upload_file_size_limit < $filesize1 || $upload_file_size_limit < $filesize2){
            return array(
                "result" => -5413,
                "success"=>false,
                "error"=>$this->res->lang->validator->fileUploadFileSizeFailed);
        }

        if($resarr1['result']<0 || $resarr2['result']<0){
            return array(
                "result" => -5414,
                "success"=>false,
                "error"=>$this->res->lang->validator->fileUploadFailed);
        }
        if(($this->returnarr1['result'] = $resarr1['result'] )>0){
            $this->returnarr1['mbProveFile1'] = ($resarr1['url'])?$resarr1['url'].'?'.time():'';
        }
        if(($this->returnarr2['result'] = $resarr2['result'] )>0){
            $this->returnarr2['mbProveFile2'] = ($resarr2['url'])?$resarr2['url'].'?'.time():'';
        }
        $param['mb_prove_file1'] = $filename1;
        $param['mb_prove_file2'] = $filename2;

        // 등업 요청 삽입
        $result = $this->execUpdate(array(),$param);

        // 등업 요청 실패
        if( isset($result['success']) && (string)$result['success']=="false"){
            return array(
                "result" => -5421,
                "success"=>false,
                "error"=>$this->res->lang->account->levelRequestFail);
        }else if( isset($result['result']) && $result['result']<1 ) {
            return array(
                "result" => -5422,
                "success"=>false,
                "error"=>$this->res->lang->account->levelRequestFail);
        }
        return $result;
    }



    function __destruct(){

    }



}
