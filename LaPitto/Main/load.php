<?php session_start(); ?>
<?php
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
		$_SESSION['cn_name'] = $result['cn_name'];
		$_SESSION['u_ID'] = $result['u_ID'];
		echo($result['cn_name']);
	}
?>