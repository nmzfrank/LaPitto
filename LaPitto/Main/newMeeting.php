<?php session_start(); ?>
<?php
	$year = $_POST['year'];
	$index = $_POST['index'];
	$content = $_POST['content'];
	$con = mysql_connect('localhost','LaPitto','X2KxFXdTBmHeMwzm');
	mysql_query('SET NAMES UTF8');  
	if (!$con){
		die('Could not connect: ' . mysql_error());
	}
	if(!mysql_select_db('LaPitto', $con))
		die('Could not connect: ' . mysql_error());
		
	$check_query = mysql_query("SELECT * FROM meeting WHERE year = '$year' AND meeting = '$index'");
	if($result = mysql_fetch_array($check_query)){
		echo('会议已存在, 事件在追加');
		$m_id = $result['m_ID'];
		$query = mysql_query("INSERT INTO event VALUES (NULL, '$m_id', '$content')");
	}
	else{
		$query = mysql_query("INSERT INTO meeting VALUES (NULL, '$year', '$index')");
		$check_query = mysql_query("SELECT * FROM meeting WHERE year = '$year' AND meeting = '$index'");
		if($result = mysql_fetch_array($check_query)){
			$m_id = $result['m_ID'];
			$query = mysql_query("INSERT INTO event VALUES (NULL, '$m_id', '$content')");
		}
		echo('正在添加');
	}
?>