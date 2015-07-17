<?php session_start(); ?>
<?php
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
			$status_dict[$dict_ans['string']] = $dict_ans['status'];
		}
	
	$index = $_POST['index'];
	
	$check = mysql_query("select * from content where content.c_index = '$index'");
	$previous = mysql_fetch_array($check);
	
	$c_ID = $previous['c_ID'];
	$e_ID = $previous['e_ID'];
	
	$self_status = $_POST['self_status'];
	$status = $_POST['status'];
	
	$opinion_a = $_POST['opinion_a'];
	$leader_text = $_POST['leader'];
	$responsibility_text = $_POST['responsibility'];
	$assistant_text = $_POST['assistant'];
	$comment_b = $_POST['comment_b'];
	$comment_a = $_POST['comment_a'];
	$program = $_POST['program'];
	
	$leader = explode(';', $leader_text);
	$responsibility = explode(';', $responsibility_text);
	$assistant = explode(';', $assistant_text);
	
	mysql_query("delete from content where content.c_ID = '$c_ID'");
	mysql_query("delete from content_user where content_user.c_ID = '$c_ID'");
	
	mysql_query("insert into content values(NULL,'$e_ID','$index','$opinion_a','$leader_text','$responsibility_text','$assistant_text','$status','$comment_b','$comment_a','$program','$self_status',0,0,0)");
	$check = mysql_query("select * from content where content.c_index = '$index'");
	$result = mysql_fetch_array($check);
	$c_ID = $result['c_ID'];
		

	linkTheUser($c_ID, $leader, $responsibility, $assistant);
	
	function linkTheUser($c_ID, $leader, $responsibility, $assistant){
		//admin and ob
		$query = mysql_query("INSERT INTO content_user VALUES (NULL, '$c_ID', 1, 7)");
		$query = mysql_query("INSERT INTO content_user VALUES (NULL, '$c_ID', 7, 1)");
		//leader
		foreach($leader as $name){
			$query = mysql_query("SELECT * FROM users WHERE users.cn_name = '$name'");
			if($result = mysql_fetch_array($query)){
				$id = $result['u_ID'];
				$query = mysql_query("INSERT INTO content_user VALUES (NULL, '$c_ID', '$id', 7)");
			}
		}
		//responsibility
		foreach($responsibility as $name){
			$query = mysql_query("SELECT * FROM users WHERE users.cn_name = '$name'");
			if($result = mysql_fetch_array($query)){
				$id = $result['u_ID'];
				$query = mysql_query("INSERT INTO content_user VALUES (NULL, '$c_ID', '$id', 7)");
			}
		}
		//assistant
		foreach($assistant as $name){
			$query = mysql_query("SELECT * FROM users WHERE users.cn_name = '$name'");
			if($result = mysql_fetch_array($query)){
				$id = $result['u_ID'];
				$query = mysql_query("INSERT INTO content_user VALUES (NULL, '$c_ID', '$id', 3)");
			}
		}
	}
?>