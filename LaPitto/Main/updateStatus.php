<?php session_start(); ?>
<?php
	$cid = $_POST['cid'];
	$program = $_POST["program"];
	$comment_a = $_POST["comment_a"];
	$self_status = $_POST["self_status"];

	$con = mysql_connect('localhost','LaPitto','X2KxFXdTBmHeMwzm');
	mysql_query('SET NAMES UTF8');  
	if (!$con){
		die('Could not connect: ' . mysql_error());
	}
	if(!mysql_select_db('LaPitto', $con))
		die('Could not connect: ' . mysql_error());	


	$query = mysql_query("UPDATE content SET program='$program', comment_a='$comment_a', self_status='$self_status' WHERE c_index='$cid'");
	if($query){
		echo(0);
	} else{
		echo(1);
	}

?>