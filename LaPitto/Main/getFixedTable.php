<?php session_start(); ?>
<?php
	$index = $_POST['index'];
	$con = mysql_connect('localhost','LaPitto','X2KxFXdTBmHeMwzm');
	mysql_query('SET NAMES UTF8');  
	if (!$con){
		die('Could not connect: ' . mysql_error());
	}
	if(!mysql_select_db('LaPitto', $con))
		die('Could not connect: ' . mysql_error());

	$status_dict = array();
	$dict_query = mysql_query("select * from status_tran");
	while($dict_ans = mysql_fetch_array($dict_query)){
		$status_dict[$dict_ans['status']] = $dict_ans['string'];
	}
		
	$check = mysql_query("select * from content where c_index = '$index'");	
	$result = mysql_fetch_array($check);
	$c_ID = $result['c_ID'];
	
	$check = mysql_query("SELECT users.cn_name FROM users JOIN content_user on users.u_ID = content_user.u_ID where users.usage = 1 and content_user.c_ID = '$c_ID' and content_user.usage = 7");
	$larr = array();
	while($leader = mysql_fetch_array($check)){
		$larr[] = $leader['cn_name'];
	}
	
	$check = mysql_query("SELECT users.cn_name FROM users JOIN content_user on users.u_ID = content_user.u_ID where users.usage = 2 and content_user.c_ID = '$c_ID' and content_user.usage = 7");
	$rarr = array();
	while($responsibility = mysql_fetch_array($check)){
		$rarr[] = $responsibility['cn_name'];
	}
	
	$check = mysql_query("SELECT users.cn_name FROM users JOIN content_user on users.u_ID = content_user.u_ID where users.usage = 2 and content_user.c_ID = '$c_ID' and content_user.usage = 3");
	$aarr = array();
	while($assistant = mysql_fetch_array($check)){
		$aarr[] = $assistant['cn_name'];
	}
	
	$ret = array($result['opinion_a'],$larr,$rarr,$aarr,$status_dict[$result['status']],$result['comment_b'],$result['comment_a'],$result['program'],$status_dict[$result['self_status']]);
	echo(json_encode($ret));
?>