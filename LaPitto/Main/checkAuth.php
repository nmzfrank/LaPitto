<?php session_start(); 
	$name = $_SESSION['curUser'];
	$con = mysql_connect('localhost','LaPitto','X2KxFXdTBmHeMwzm');
	mysql_query('SET NAMES UTF8');  
	if (!$con){
		die('Could not connect: ' . mysql_error());
	}
	if(!mysql_select_db('LaPitto', $con))
		die('Could not connect: ' . mysql_error());
	$check_query = mysql_query("SELECT * FROM users WHERE name = '$name'");
	if($result = mysql_fetch_array($check_query)){
		echo(0);
	} else{
		echo(1);
	}
?>