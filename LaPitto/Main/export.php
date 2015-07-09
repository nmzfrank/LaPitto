<?php session_start(); ?>
<?php
	$year = $_POST['year'];


	$con = mysql_connect('localhost','LaPitto','X2KxFXdTBmHeMwzm');
	mysql_query('SET NAMES UTF8');  
	if (!$con){
		die('Could not connect: ' . mysql_error());
	}
	if(!mysql_select_db('LaPitto', $con))
		die('Could not connect: ' . mysql_error());	
	getMeeting(1,$year,3);
	
	
	function getMeeting($u_ID, $year, $level){
		$query = mysql_query("select distinct meeting.meeting from content, event, content_user, meeting where content_user.u_ID = '$u_ID' and content_user.c_ID = content.c_ID and content.e_ID = event.e_ID and event.m_ID = meeting.m_ID and meeting.year = '$year' order by meeting.meeting asc");
		while($ans = mysql_fetch_array($query)){
			if($level>1){
				getEvent($ans['meeting'],$u_ID,$level);
			}
		}
	}
	
	function getEvent($meeting, $u_ID,$level){
		$query = mysql_query("select distinct content.e_ID, event.content from content, event, content_user, meeting where content_user.u_ID = '$u_ID' and content_user.c_ID = content.c_ID and content.e_ID = event.e_ID and event.m_ID = meeting.m_ID and meeting.meeting = '$meeting' order by content.e_ID asc");
		while($ans = mysql_fetch_array($query)){
			if($level>2){
				getContent($ans['e_ID'], $u_ID);
			}
		}
	}
	
	function getContent($e_ID, $u_ID){
		$status_dict = array();
		$dict_query = mysql_query("select * from status_tran");
		while($dict_ans = mysql_fetch_array($dict_query)){
			$status_dict[$dict_ans['status']] = $dict_ans['string'];
		}
	
		$query = mysql_query("select distinct content.* from content, content_user where content_user.u_ID = '$u_ID' and content_user.c_ID = content.c_ID and content.e_ID = '$e_ID' order by content.c_index asc");

		while($ans = mysql_fetch_array($query)){
			$eq =  mysql_query("select distinct event.content from event where event.e_ID = '$e_ID'");
			$e_content = mysql_fetch_array($eq);
			echo($e_content['content']."\t");
			echo($ans['c_index']."\t");
			echo($ans['opinion_a']."\t");
			echo($ans['leader']."\t");
			echo($ans['responsibility']."\t");
			echo($ans['assistant']."\t");
			echo($status_dict[$ans['status']]."\t");
			echo($ans['comment_b']."\t");
			echo($ans['comment_a']."\t");
			echo($ans['program']."\t");
			echo($status_dict[$ans['self_status']]."\n");
		}
	}
?>