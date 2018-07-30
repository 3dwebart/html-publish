<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Document</title>
</head>
<body>
<form action="file-test-ok.php" method="post" enctype="multipart/form-data">
	<!-- MAX_FILE_SIZE는 file 입력 필드보다 먼저 나와야 합니다 -->
	<input type="hidden" name="MAX_FILE_SIZE" value="300000" />
	<!-- input의 name은 $_FILES 배열의 name을 결정합니다 -->
	이 파일을 전송합니다: <input name="userfile" type="file" />
	<input type="submit" value="파일 전송" />
</form>
</body>
</html>