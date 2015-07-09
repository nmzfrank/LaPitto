<?php session_start(); ?>
<?php
	$con = mysql_connect('localhost','LaPitto','X2KxFXdTBmHeMwzm');
	mysql_query('SET NAMES UTF8');  
	if (!$con){
		die('Could not connect: ' . mysql_error());
	}
	if(!mysql_select_db('LaPitto', $con))
		die('Could not connect: ' . mysql_error());
	
	$check = mysql_query("select cn_name from users where u_ID != 1 and u_ID != 7");	
	$result = mysql_fetch_array($check);
	
	while($result = mysql_fetch_array($check)){
		$arr[] = $result['cn_name'];
	}
	
	
	$ret = array($arr);
	echo(json_encode($ret));
?>