<?php session_start(); 
	$name = $_SESSION['curUser'];
	$con = mysql_connect('localhost','LaPitto','X2KxFXdTBmHeMwzm');
	mysql_query('SET NAMES UTF8');  
	if (!$con){
		die('Could not connect: ' . mysql_error());
	}
	if(!mysql_select_db('LaPitto', $con))
		die('Could not connect: ' . mysql_error());
	mysql_query("UPDATE users SET status = 0 WHERE name = '$name'");
	session_unset();
	session_destroy();
?>