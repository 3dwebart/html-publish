<?php
$conn = mysqli_connect("localhost", "root", "root", "test");
mysqli_set_charset($conn, 'utf8');
if (!$conn) {
    echo "Error: Unable to connect to MySQL." . PHP_EOL;
    echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
    echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
    exit;
}
$page_no        = $_POST['page_cnt'];
$page_list_cnt  = $_POST['page_list_cnt'];
//$page_no = "1";
$page_cnt       = $page_no * $page_list_cnt;
/*
echo "<h1>";
echo($page_no);
echo "</h1>";
echo "<h1>";
echo($page_cnt);
echo "</h1>";
*/
//exit();
$data = array();
$sql = "SELECT id, subject, content FROM test_board LIMIT $page_cnt, $page_list_cnt";
$res = mysqli_query($conn, $sql);

for($i = 0;$row = mysqli_fetch_assoc($res);$i++) {
	$data[$i] = array(
		'Subject' => $row['subject'],
		'Content' => $row['content']
	);
}
echo json_encode($data,JSON_UNESCAPED_UNICODE);

mysqli_close($conn);
?>