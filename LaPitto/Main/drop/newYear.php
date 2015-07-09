<?php session_start(); ?>
<?php
	$year = $_POST['year'];
	$index = $_POST['index'];
	
	$con = mysql_connect('localhost','LaPitto','X2KxFXdTBmHeMwzm');
	mysql_query('SET NAMES UTF8');  
	if (!$con){
		die('Could not connect: ' . mysql_error());
	}
	if(!mysql_select_db('LaPitto', $con))
		die('Could not connect: ' . mysql_error());
		
	$check_query = mysql_query("SELECT * FROM event WHERE year = '$year' AND meeting = '$index'");
	if($result = mysql_fetch_array($check_query)){
		echo('会议已存在');
	}
	else{
		echo('添加成功');
	}
?>