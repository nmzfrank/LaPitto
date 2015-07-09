<?php session_start(); ?>
<?php
	$cur = $_SESSION['u_ID'];
	$con = mysql_connect('localhost','LaPitto','X2KxFXdTBmHeMwzm');
	mysql_query('SET NAMES UTF8');  
	if (!$con){
		die('Could not connect: ' . mysql_error());
	}
	if(!mysql_select_db('LaPitto', $con))
		die('Could not connect: ' . mysql_error());
		
	$check_query = mysql_query("SELECT DISTINCT content.c_index FROM content, content_user WHERE content_user.u_ID = '$cur' AND content_user.c_ID = content.c_ID AND content_user.usage >= 4 AND content.lock = 0");
	while($result = mysql_fetch_array($check_query)){
		echo("<option>".$result['c_index']."</option>");
	}
?>