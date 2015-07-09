<?php session_start(); ?>
<?php

	$lock = $_POST['lock'];
	$content = $_POST['content'];
	$con = mysql_connect('localhost','LaPitto','X2KxFXdTBmHeMwzm');
	mysql_query('SET NAMES UTF8');  
	if (!$con){
		die('Could not connect: ' . mysql_error());
	}
	if(!mysql_select_db('LaPitto', $con))
		die('Could not connect: ' . mysql_error());
		
	$check_query = mysql_query("SELECT * FROM event WHERE content = '$content'");
	$result = mysql_fetch_array($check_query);
	$e_ID = $result['e_ID'];
	$query = mysql_query("UPDATE content SET content.lock = '$lock' WHERE e_ID='$e_ID'");
	echo("parsing");
?>