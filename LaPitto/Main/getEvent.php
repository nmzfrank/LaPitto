<?php session_start(); ?>
<?php
	$con = mysql_connect('localhost','LaPitto','X2KxFXdTBmHeMwzm');
	mysql_query('SET NAMES UTF8');  
	if (!$con){
		die('Could not connect: ' . mysql_error());
	}
	if(!mysql_select_db('LaPitto', $con))
		die('Could not connect: ' . mysql_error());
		
	$check_query = mysql_query("SELECT * FROM event");
	while($result = mysql_fetch_array($check_query)){
		echo("<option>".$result['content']."</option>");
	}
?>