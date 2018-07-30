<?php
$link = mysqli_connect("localhost", "root", "root", "test");
mysqli_set_charset($link, 'utf8');
if (!$link) {
    echo "Error: Unable to connect to MySQL." . PHP_EOL;
    echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
    echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
    exit;
}
for ($i=1; $i < 104; $i++) {
	$no = sprintf('%03d', $i);
	echo "<h1>subj no = ".$no."</h1>";
	$subj = '제목_'.$no;
	$cont = '내용_'.$no;
	$sql = "INSERT INTO test_board (subject,content) VALUES('$subj','$cont')";
	if(mysqli_query($link, $sql)){
    	echo "Records inserted successfully.";
	} else {
	    echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
	}
}


mysqli_close($link);
?>