<?php session_start(); ?>
<?php
	$u_ID = $_SESSION['u_ID'];
	$year = $_POST['year'];
	$level = 3;
	$con = mysql_connect('localhost','LaPitto','X2KxFXdTBmHeMwzm');
	mysql_query('SET NAMES UTF8');  
	if (!$con){
		die('Could not connect: ' . mysql_error());
	}
	if(!mysql_select_db('LaPitto', $con))
		die('Could not connect: ' . mysql_error());	
	getMeeting($u_ID,$year,$level);
	
	
	function getMeeting($u_ID, $year, $level){
		$query = mysql_query("select distinct meeting.meeting from content, event, content_user, meeting where content_user.u_ID = '$u_ID' and content_user.c_ID = content.c_ID and content.e_ID = event.e_ID and event.m_ID = meeting.m_ID and meeting.year = '$year'");
		while($ans = mysql_fetch_array($query)){
			echo("<div class='col-lg-8'>".$year."-第".$ans['meeting']."次校长办公会"."</div>");
			echo("<div class='col-lg-2'>实际完成率</div>");
			echo("<div class='col-lg-2'>参考完成率</div>");
			if($level>1){
				getEvent($ans['meeting'],$u_ID,$level);
			}
		}
	}
	
	function getEvent($meeting, $u_ID,$level){
		$query = mysql_query("select distinct content.e_ID, event.content from content, event, content_user, meeting where content_user.u_ID = '$u_ID' and content_user.c_ID = content.c_ID and content.e_ID = event.e_ID and event.m_ID = meeting.m_ID and meeting.meeting = '$meeting' order by content.e_ID asc");
		while($ans = mysql_fetch_array($query)){
			$content = $ans['content'];
			echo("<div class='col-lg-11 col-lg-offset-1'>议题：$content </div> ");
			if($level>2){
				getContent($ans['e_ID'], $u_ID);
			}
		}
	}
	
	function getContent($e_ID, $u_ID){
		$cid = $_POST['cid'];
		$content = $_POST['content'];
		$leader = $_POST['leader'];
		$responsibility = $_POST['responsibility'];
		$assistant = $_POST['assistant'];
		$status = $_POST['status'];
		$comment_b = $_POST['comment_b'];
		$comment_a = $_POST['comment_a'];
		$program = $_POST['program'];
		$self_status = $_POST['self_status'];
	
		$status_dict = array();
		$dict_query = mysql_query("select * from status_tran");
		while($dict_ans = mysql_fetch_array($dict_query)){
			$status_dict[$dict_ans['status']] = $dict_ans['string'];
		}
		
		$query = mysql_query("select distinct content.* from content, content_user where content_user.u_ID = '$u_ID' and content_user.c_ID = content.c_ID and content.e_ID = '$e_ID'");
		while($ans = mysql_fetch_array($query)){
			echo("<div class='col-lg-2'><b>意见编号：</b>".$ans['c_index']."</div>");
			echo("<div class='col-lg-2'><b>牵头领导：</b>".$ans['leader']."</div>");
			echo("<div class='col-lg-3'><b>牵头单位：</b>".$ans['responsibility']."</div>");
			echo("<div class='col-lg-3'><b>协助单位：</b>".$ans['assistant']."</div>");
			if($ans['status'] >= 90){
				echo("<div class='col-lg-2' style='background-color:green'><b>落实状态：</b>".$status_dict[$ans['status']]."</div>");
			}
			else if($ans['status'] > 10){
				echo("<div class='col-lg-2' style='background-color:yellow'><b>落实状态：</b>".$status_dict[$ans['status']]."</div>");
			}
			else{
				echo("<div class='col-lg-2' style='background-color:red'><b>落实状态：</b>".$status_dict[$ans['status']]."</div>");
			}
			echo("<div class='col-lg-12'><b>拟办意见：</b>".$ans['opinion_a']."</div>");
			echo("<div class='col-lg-3'><b>备&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;注：</b><div class='contentFloat'>".$ans['comment_b']."</div></div>");
			echo("<div class='col-lg-3'><b>节点计划：</b>".$ans['program']."</div>");
			echo("<div class='col-lg-3'><b>落实备注：</b>".$ans['comment_a']."</div>");
			echo("<div class='col-lg-3'><b>进度自评：</b>".$status_dict[$ans['self_status']]."</div>");
		}
	}
?>