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

	$data_text = $_POST['data'];
	if($data_text=="")
		exit();
	$data = explode('	',$data_text);

	$reason = $data[0];
	$c_index = $data[1];
	$opinion_a = $data[2];
	$leader_text = $data[3];
	$responsibility_text = $data[4];
	$assistant_text = $data[5];
	$status = $status_dict[$data[6]];
	$comment_b = $data[7];
	$comment_a = $data[8];
	$program = $data[9];
	$self_status = $status_dict[$data[10]];
	
	$info = explode('-',$c_index);
	$year = $info[1];
	$meeting = $info[2];
	
	
	$leader = explode(';', $leader_text);
	$responsibility = explode(';', $responsibility_text);
	$assistant = explode(';', $assistant_text);

	$check_query = mysql_query("select distinct max(event.e_index) as largest from event, meeting where meeting.year = '$year' and meeting.meeting = '$meeting' and meeting.m_ID = event.m_ID");
	if($result = mysql_fetch_array($check_query))
		$e_index = $result['largest'] + 1;
	else
		$e_index = 1;
		
	$check_query = mysql_query("select distinct event.e_ID, meeting.m_ID from event, meeting where event.content = '$reason' and meeting.year = '$year' and meeting.meeting = '$meeting' and event.m_ID = meeting.m_ID");
	if($result = mysql_fetch_array($check_query)){
		$e_ID = $result['e_ID'];
		$m_ID = $result['m_ID'];	
	}
	else{
		$check_query = mysql_query("select distinct * from meeting where meeting.meeting = '$meeting' and meeting.year = '$year'");
		if($result = mysql_fetch_array($check_query)){
			$m_ID = $result['m_ID'];
			$query = mysql_query("insert into event values(null,'$m_ID','$e_index','$reason')");
			$check_query = mysql_query("select distinct event.e_ID, meeting.m_ID from event, meeting where event.content = '$reason' and meeting.year = '$year' and meeting.meeting = '$meeting'");
			$result = mysql_fetch_array($check_query);
			$e_ID = $result['e_ID'];
		}
		else{
			$query = mysql_query("insert into meeting values(null,'$year','$meeting')");
			$check_query = mysql_query("select distinct * from meeting where meeting.meeting = '$meeting' and meeting.year = '$year'");
			$result = mysql_fetch_array($check_query);
			$m_ID = $result['m_ID'];	
			$query = mysql_query("insert into event values(null,'$m_ID','$e_index','$reason')");
			$check_query = mysql_query("select distinct event.e_ID, meeting.m_ID from event, meeting where event.content = '$reason' and meeting.year = '$year' and meeting.meeting = '$meeting'");
			$result = mysql_fetch_array($check_query);
			$e_ID = $result['e_ID'];
		}
	}
	
	$check_query = mysql_query("select * from event where event.e_ID = '$e_ID'");
	$result = mysql_fetch_array($check_query);
	$e_index = $result['e_index'];
	$check_query = mysql_query("select count(c_index) as c_num from content where content.e_ID = '$e_ID'");
	$result = mysql_fetch_array($check_query);
	$c_num = $result['c_num']+1;
	$c_index = 'A-'.$year.'-'.$meeting.'-'.$e_index.'-'.$c_num;
	$query = mysql_query("insert into content values(null,'$e_ID','$c_index','$opinion_a','$leader_text','$responsibility_text','$assistant_text','$status','$comment_b','$comment_a','$program','$self_status',0,0,0)");
	$check_query = mysql_query("select c_ID from content where content.c_index = '$c_index'");
	$result = mysql_fetch_array($check_query);
	$c_ID = $result['c_ID'];
	linkTheUser($c_ID, $leader, $responsibility, $assistant);
	
	function linkTheUser($c_ID, $leader, $responsibility, $assistant){
		//admin and ob
		echo($c_ID);
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