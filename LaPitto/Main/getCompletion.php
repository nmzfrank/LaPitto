<?php session_start(); ?>
<?php
	
	$u_ID = $_SESSION['u_ID'];
	$year = $_POST['year'];

	$con = mysql_connect('localhost','LaPitto','X2KxFXdTBmHeMwzm');
	mysql_query('SET NAMES UTF8');  
	if (!$con){
		die('Could not connect: ' . mysql_error());
	}
	if(!mysql_select_db('LaPitto', $con))
		die('Could not connect: ' . mysql_error());


	$query = mysql_query("select distinct meeting.meeting from content, event, content_user, meeting where content_user.u_ID = '$u_ID' and content_user.c_ID = content.c_ID and content.e_ID = event.e_ID and event.m_ID = meeting.m_ID and meeting.year = '$year' order by meeting.meeting asc");

	while($ans = mysql_fetch_array($query)){




?>