<?php
$conn = mysqli_connect("localhost", "root", "root", "test");
mysqli_set_charset($conn, 'utf8');

/*
if(!$db_server) die ("Unable to connect to MySQL :" . mysql_error());

mysql_select_db($db_database) or die("Unable to select Database : " . mysql_error());

mysql_query("set session character_set_connection=utf8;");

mysql_query("set session character_set_results=utf8;");

mysql_query("set session character_set_client=utf8;");
*/

if (!$conn) {
    echo "Error: Unable to connect to MySQL." . PHP_EOL;
    echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
    echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
    exit;
}

//echo "Success: A proper connection to MySQL was made! The my_db database is great." . PHP_EOL;
//echo "Host information: " . mysqli_get_host_info($link) . PHP_EOL;
?>
<style>
	.container {
		max-width: 1200px;
		margin: 0 auto;
	}
	.row {
		display: flex;
		flex-direction: row;
		border-bottom: 1px solid #999;
	}
	.row:first-child {
		border-top: 1px solid #999;
	}
	.subj {
		width: 25%;
		max-width: 25%;
		background-color: #dedede;
		padding: 10px 5px;
	}
	.cont {
		width: 75%;
		max-width: 75%;
		padding: 10px 5px;
	}
	.text-center {
		text-align: center;
	}
	.btn {
		text-decoration: none;
		padding: 10px 15px;
		border: 1px solid #dedede;
		display: inline-block;
	}
</style>
<script src="js/jquery.min.js"></script>
<?php
$sql = "SELECT COUNT(id) AS cnt FROM test_board";
$res = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($res);
$cnt = $row['cnt'];
$page_list_cnt = 13; // 리스트 갯수 - 관리자에서 페이지당 리스트수 필드가 존제하면 해당 필드값 받아서 대체 처리 가능

if($cnt == 0) { // DB에 아무 data가 없을 때
	echo('<h1>값이 없어요~</h1>');
} else { // DB에 data가 하나라도 존재 할 때 - BIGIN
	$total_calc1 = $cnt / $page_list_cnt; // 전체 갯수(cnt) / 페이지당 라스트 갯수(page_list_cnt) : 페이지 수를 계산하기 위한 수식
	$total_calc2 = $cnt % $page_list_cnt; // 전체 갯수(cnt) % 페이지당 라스트 갯수(page_list_cnt) : 나머지가 0 인지 체크하기 위한 수식
	if($total_calc2 == 0) {
		$total_page = $total_calc1; // 나머지가 0 이면 페이지 수에 증감 없음
	} else {
		$total_page = floor($total_calc1) + 1; // 나머지가 0이 아니면 페이지수 계한한것의 소수점 자리를 버리고 1을 더함
	}
	$sql = "SELECT id, subject, content FROM test_board LIMIT 0, $page_list_cnt";
	$res = mysqli_query($conn, $sql);
	echo "<div class='container'>";
	echo "<h3>Total list count : ".$cnt."</h3>"; // 
	echo "<h3>Total page count : ".$total_page."</h3>";
	echo "<h3>page list count : ".$page_list_cnt."</h3>";
	echo "<h3>Total page count calculation : ".round($total_calc1,4)."</h3>";
	echo "</div>";
?>
<div class="container list-box">
<?php
	while ($row = mysqli_fetch_assoc($res)) {
?>

	<div class="row">
		<div class="subj"><?php echo($row['subject']); ?></div>
		<div class="cont"><?php echo($row['content']); ?></div>
	</div>

<?php
	} // END :: while
?>
</div>

<div class="text-center">
	<!-- // 전체 갯수가 페이지당 리스트 수 보다 클 때만 버튼이 활성화됨 -->
	<button class="btn btn-info more" <?php if($cnt <= $page_list_cnt) { echo('disabled=""'); } ?>>More</button>
</div>

<script>
(function($) {
	var limit = Number('<?php echo($page_list_cnt); ?>');
	var page_cnt = 1;
	var total_page = Number('<?php echo($total_page); ?>');
	var page_list_cnt = Number('<?php echo($page_list_cnt); ?>');
	var append_start = '<div class="row">';
	var append_end = '</div>';
	var append_data = '';
	$('.more').on('click', function(e) {
		if(page_cnt < total_page) {
			$.ajax({
				url: "ajax.php",
				type: "post",
				data: 
					{
						page_cnt : page_cnt,
						page_list_cnt : page_list_cnt
					},
				dataType: "json",
				cache: false,
				timeout: 30000,
				success: function(data) {
					for(var i = 0; i < data.length; i++) {
						append_data = '<div class="subj">' + data[i].Subject + '</div><div class="cont">' + data[i].Content + '</div>';
						$('.list-box').append(append_start + append_data + append_end);
					}
				},
				error: function(xhr, textStatus, errorThrown) {
					$("div").html("<div>" + textStatus + " (HTTP-" + xhr.status + " / " + errorThrown + ")</div>" );
				}
			});
			limit = limit + page_list_cnt;
			page_cnt++;
			console.log('전체 페이지 : ' + total_page);
			console.log('페이지 분할 : ' + limit);
			console.log('페이지 수 : ' + page_cnt);
		} else {
			alert('마지막 페이지야~');
			return false;
		}
		e.preventDefault();
	});
})(jQuery);
</script>
<?php
} // DB에 data가 하나라도 존재 할 때 - END (else END)
?>
<?php
mysqli_close($conn);
?>