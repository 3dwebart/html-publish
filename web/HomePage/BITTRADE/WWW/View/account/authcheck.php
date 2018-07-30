<?php
	session_start();

    $this->cfg = $cfg = Config::getConfig();
    $sitecode   = $this->cfg['niceauth']['sitecode'];       // NICE로부터 부여받은 사이트 코드
    $sitepasswd = $this->cfg['niceauth']['sitepasswd'];     // NICE로부터 부여받은 사이트 패스워드

    $cb_encode_path = $this->cfg['module']['niceauthclient'];

    $enc_data = $_POST["EncodeData"];		// 암호화된 결과 데이타
    $sReserved1 = $_POST['param_r1'];
    $sReserved2 = $_POST['param_r2'];
    $sReserved3 = $_POST['param_r3'];

		//////////////////////////////////////////////// 문자열 점검///////////////////////////////////////////////
    if(preg_match('~[^0-9a-zA-Z+/=]~', $enc_data, $match)) {echo "입력 값 확인이 필요합니다 : ".$match[0]; exit;} // 문자열 점검 추가.
    if(base64_encode(base64_decode($enc_data))!=$enc_data) {echo "입력 값 확인이 필요합니다"; exit;}
    if(preg_match("/[#\&\\+\-%@=\/\\\:;,\.\'\"\^`~\_|\!\/\?\*$#<>()\[\]\{\}]/i", $sReserved1, $match)) {echo "문자열 점검 : ".$match[0]; exit;}
    if(preg_match("/[#\&\\+\-%@=\/\\\:;,\.\'\"\^`~\_|\!\/\?\*$#<>()\[\]\{\}]/i", $sReserved2, $match)) {echo "문자열 점검 : ".$match[0]; exit;}
    if(preg_match("/[#\&\\+\-%@=\/\\\:;,\.\'\"\^`~\_|\!\/\?\*$#<>()\[\]\{\}]/i", $sReserved3, $match)) {echo "문자열 점검 : ".$match[0]; exit;}
		///////////////////////////////////////////////////////////////////////////////////////////////////////////
    // 상태값 변수 선언
    $ciphertime = '';
    $requestnumber = '';
    $responsenumber = '';
    $authtype = '';
    $name = '';
    $birthdate = '';
    $gender = '';
    $nationalinfo = '';
    $dupinfo = '';
    $conninfo = '';

    if ($enc_data != "") {

        $plaindata = `$cb_encode_path DEC $sitecode $sitepasswd $enc_data`;		// 암호화된 결과 데이터의 복호화
        // echo "[plaindata]  " . $plaindata . "<br>";

        if ($plaindata == -1){
            $returnMsg  = "암/복호화 시스템 오류";
        }else if ($plaindata == -4){
            $returnMsg  = "복호화 처리 오류";
        }else if ($plaindata == -5){
            $returnMsg  = "HASH값 불일치 - 복호화 데이터는 리턴됨";
        }else if ($plaindata == -6){
            $returnMsg  = "복호화 데이터 오류";
        }else if ($plaindata == -9){
            $returnMsg  = "입력값 오류";
        }else if ($plaindata == -12){
            $returnMsg  = "사이트 비밀번호 오류";
        }else{
            // 복호화가 정상적일 경우 데이터를 파싱합니다.
            $ciphertime = `$cb_encode_path CTS $sitecode $sitepasswd $enc_data`;	// 암호화된 결과 데이터 검증 (복호화한 시간획득)

            $requestnumber = GetValue($plaindata , "REQ_SEQ");
            $responsenumber = GetValue($plaindata , "RES_SEQ");
            $authtype = GetValue($plaindata , "AUTH_TYPE");
            $name = GetValue($plaindata , "NAME");
            $name = iconv('EUC-KR','UTF-8',$name);
            $birthdate = GetValue($plaindata , "BIRTHDATE");
            $gender = GetValue($plaindata , "GENDER");
            if($gender==="0"){  // 0 여성, 1 남성
                $gender = 'F';
            }else if($gender==="1"){
                $gender = 'M';
            }
            $nationalinfo = GetValue($plaindata , "NATIONALINFO");	//내/외국인정보(사용자 매뉴얼 참조)
            $dupinfo = '';
            $conninfo = '';
            $dupinfo = GetValue($plaindata , "DI");
            $conninfo = GetValue($plaindata , "CI");
            $mobileno = GetValue($plaindata , "MOBILE_NO");

            if(strcmp($_SESSION["REQ_SEQ"], $requestnumber) != 0)
            {
            	echo "세션값이 다릅니다. 올바른 경로로 접근하시기 바랍니다.<br>";
                $requestnumber = "";
                $responsenumber = "";
                $authtype = "";
                $name = "";
                $birthdate = "";
                $gender = "";
                $nationalinfo = "";
                $dupinfo = "";
                $conninfo = "";
                $mobileno = "";
            }
        }
    }

    function GetValue($str , $name)
    {
        $pos1 = 0;  //length의 시작 위치
        $pos2 = 0;  //:의 위치

        while( $pos1 <= strlen($str) )
        {
            $pos2 = strpos( $str , ":" , $pos1);
            $len = substr($str , $pos1 , $pos2 - $pos1);
            $key = substr($str , $pos2 + 1 , $len);
            $pos1 = $pos2 + $len + 1;
            if( $key == $name )
            {
                $pos2 = strpos( $str , ":" , $pos1);
                $len = substr($str , $pos1 , $pos2 - $pos1);
                $value = substr($str , $pos2 + 1 , $len);
                return $value;
            }
            else
            {
                // 다르면 스킵한다.
                $pos2 = strpos( $str , ":" , $pos1);
                $len = substr($str , $pos1 , $pos2 - $pos1);
                $pos1 = $pos2 + $len + 1;
            }
        }
    }
?>

<html>
<head>
    <title>NICE평가정보 - CheckPlus 본인인증 테스트</title>
    <script src="<?=$view['url']['static']?>/assets/js/utils.min.js"></script>
</head>
<body>
    <center>
    <p><p><p><p>
    본인인증이 완료 되었습니다.<br>
    </center>
<script>
    var result = true;

    var authdata = {
        name:$.base64.encode(encodeURI('<?= $name?>'))
        ,birthdate:$.base64.encode('<?= $birthdate?>')
        ,gender:$.base64.encode('<?= $gender?>')
        ,mobileno:$.base64.encode('<?= $mobileno?>')
    };

    $(document).ready(function(){
        window.opener.nameChecked(result, authdata);
        window.close();
    });
</script>
</body>
</html>
