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
			$meeting = $ans['meeting'];
			echo("<div class='col-lg-8'>".$year."-第".$ans['meeting']."次校长办公会"."</div>");
			echo("<div class='col-lg-2 meeting-completion-real' data-year='$year' data-meeting='$meeting'>实际完成率</div>");
			echo("<div class='col-lg-2 meeting-completion-ref' data-year='$year' data-meeting='$meeting'>参考完成率</div>");
			$result = getEvent($ans['meeting'],$u_ID,$level);
			$real = round($result[1] / $result[0], 3);
			$ref = round($result[2] * 100 / $result[0], 3);
			echo("<div class='col-lg-6 real' data-year='$year' data-meeting='$meeting' data-real='$real' style='display:none'></div>");
			echo("<div class='col-lg-6 ref' data-year='$year' data-meeting='$meeting' data-ref='$ref' style='display:none'></div>");
		}
	}
	
	function getEvent($meeting, $u_ID,$level){
		$count = 0;
		$real = 0.0;
		$ref = 0.0;
		$query = mysql_query("select distinct content.e_ID, event.content from content, event, content_user, meeting where content_user.u_ID = '$u_ID' and content_user.c_ID = content.c_ID and content.e_ID = event.e_ID and event.m_ID = meeting.m_ID and meeting.meeting = '$meeting' order by content.e_ID asc");
		while($ans = mysql_fetch_array($query)){
			$content = $ans['content'];
			echo("<div class='col-lg-12'>议题：$content </div> ");
			$result = getContent($ans['e_ID'], $u_ID);
			$count = $count + $result[0];
			$real = $real + $result[1];
			$ref = $ref + $result[2];
		}
		$result[0] = $count;
		$result[1] = $real;
		$result[2] = $ref;
		return $result;
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

		$count = 0.0;
		$real = 0.0;
		$ref = 0.0;
		

		$query = mysql_query("select distinct content.* from content, content_user where content_user.u_ID = '$u_ID' and content_user.c_ID = content.c_ID and content.e_ID = '$e_ID'");
		while($ans = mysql_fetch_array($query)){

			$cid = $ans['c_index'];
			$status = $ans['status'];
			$self_status = $ans['self_status'];
			$count = $count + 1;
			$real = $real + $status;
			if($status == 100){
				$ref = $ref + 1;
			}
			echo("<div class='col-lg-2 cid' data-cid='$cid' style='border:1px solid rgba(86,61,124,.2)'><b>意见编号：</b>".$ans['c_index']."</div>");
			echo("<div class='col-lg-2' data-cid='$cid' style='border:1px solid rgba(86,61,124,.2)'><b>牵头领导：</b>".$ans['leader']."</div>");
			echo("<div class='col-lg-3' data-cid='$cid' style='border:1px solid rgba(86,61,124,.2)'><b>牵头单位：</b>".$ans['responsibility']."</div>");
			echo("<div class='col-lg-3' data-cid='$cid' style='border:1px solid rgba(86,61,124,.2)'><b>协助单位：</b>".$ans['assistant']."</div>");
			if($ans['status'] >= 90){
				echo("<div class='col-lg-2 status' data-cid='$cid' style='font:green;border:1px solid rgba(86,61,124,.2)' value='$status'><b>落实状态：</b>".$status_dict[$ans['status']]."</div>");
			}
			else if($ans['status'] > 10){
				echo("<div class='col-lg-2 status' data-cid='$cid' style='font:yellow;border:1px solid rgba(86,61,124,.2)' value='$status'><b>落实状态：</b>".$status_dict[$ans['status']]."</div>");
			}
			else{
				echo("<div class='col-lg-2 status' data-cid='$cid' style='font:red;border:1px solid rgba(86,61,124,.2)' value='$status'><b>落实状态：</b>".$status_dict[$ans['status']]."</div>");
			}
			echo("<div class='col-lg-12' data-cid='$cid' style='border:1px solid rgba(86,61,124,.2)'><b>拟办意见：</b>".$ans['opinion_a']."</div>");
			echo("<div class='col-lg-3' data-cid='$cid' style='border:1px solid rgba(86,61,124,.2)'><b>备&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;注：</b>".$ans['comment_b']."</div>");
			echo("<div class='col-lg-3' data-cid='$cid' style='border:1px solid rgba(86,61,124,.2)'><b>节点计划：</b>".$ans['program']."</div>");
			echo("<div class='col-lg-3' data-cid='$cid' style='border:1px solid rgba(86,61,124,.2)'><b>落实备注：</b>".$ans['comment_a']."</div>");
			echo("<div class='col-lg-3 self_status' data-cid='$cid' style='border:1px solid rgba(86,61,124,.2)' value='$self_status'><b>进度自评：</b>".$status_dict[$ans['self_status']]."</div>");
		}
		$result[0] = $count;
		$result[1] = $real;
		$result[2] = $ref;
		return $result;
	}
?>