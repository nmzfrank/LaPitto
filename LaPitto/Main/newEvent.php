<?php session_start(); ?>
<?php
$status_dic = array(
			"暂无" => 0,
			"开始推进" => 10,
			"推进中Ⅰ" => 30,
			"推进中Ⅱ" => 50,
			"推进中Ⅲ" => 70,
			"基本完成" => 90,
			"已完成" => 100
		);
		
	$sel = $_POST['sel'];
	$reason = $_POST['ac_1'];
	$c_index = $_POST['ac_2'];
	$opinion_a = $_POST['ac_3'];
	$leader = $_POST['ac_4'];
	$responsibility = $_POST['ac_5'];
	$assistant_text = $_POST['ac_6'];
	$status = $status_dic[$_POST['ac_7']];
	$comment_b = $_POST['ac_8'];
	$comment_a = $_POST['ac_9'];
	$program = $_POST['ac_10'];
	$self_status = $status_dic[$_POST['ac_11']];
	
	$assistant = explode(' ', $assistant_text);

	
	$con = mysql_connect('localhost','LaPitto','X2KxFXdTBmHeMwzm');
	mysql_query('SET NAMES UTF8');  
	if (!$con){
		die('Could not connect: ' . mysql_error());
	}
	if(!mysql_select_db('LaPitto', $con))
		die('Could not connect: ' . mysql_error());
	//drop
	$check_query = mysql_query("SELECT * FROM content WHERE c_index='$c_index'");
	if($result = mysql_fetch_array($check_query)){
		dropContent($result['c_ID']);
		}
	//insert
	$check_query = mysql_query("SELECT * FROM event WHERE content='$sel'");
	if($result = mysql_fetch_array($check_query)){
		$e_id = $result['e_ID'];
		$query = mysql_query("INSERT INTO content VALUES (NULL, '$e_id', '$reason', '$c_index', '$opinion_a', '$leader', '$responsibility', '$assistant_text', '$status', '$comment_b', '$comment_a', '$program', '$self_status', 0)");
		
		$c_ID = 0;
		$le = 0;
		$re = 0;
		$as = 0;
		$query = mysql_query("SELECT content.c_ID FROM content WHERE content.reason = '$reason'");
		if($result = mysql_fetch_array($query))
			$c_ID = $result['c_ID'];
		linkTheUser($c_ID,$leader,$responsibility,$assistant);
	}
	
	function linkTheUser($c_ID, $leader, $responsibility, $assistant){
		//admin and ob
		$query = mysql_query("INSERT INTO content_user VALUES (NULL, '$c_ID', 1, 7)");
		$query = mysql_query("INSERT INTO content_user VALUES (NULL, '$c_ID', 7, 1)");
		//leader
		$query = mysql_query("SELECT * FROM users WHERE users.cn_name = '$leader'");
		$result = mysql_fetch_array($query);
		$le = $result['u_ID'];
		$query = mysql_query("INSERT INTO content_user VALUES (NULL, '$c_ID', '$le', 7)");
		//responsibility
		$query = mysql_query("SELECT * FROM users WHERE users.cn_name = '$responsibility'");
		$result = mysql_fetch_array($query);
		$re = $result['u_ID'];
		$query = mysql_query("INSERT INTO content_user VALUES (NULL, '$c_ID', '$re', 7)");
		//assistant
		foreach($assistant as $as){
			$query = mysql_query("SELECT * FROM users WHERE users.cn_name = '$as'");
			$result = mysql_fetch_array($query);
			$as = $result['u_ID'];
			$query = mysql_query("INSERT INTO content_user VALUES (NULL, '$c_ID', '$as', 3)");
		}
	}
	
	function dropContent($c_ID){
		$query = mysql_query("DELETE FROM content_user WHERE content_user.c_ID = '$c_ID'");
		$query = mysql_query("DELETE FROM content WHERE c_ID = '$c_ID'");
	}
	
	echo("parsing");	
?>