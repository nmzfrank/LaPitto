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
			echo("<tr>");
			echo("<td colspan='11'>");
			echo("<h3 style='margin:10px'>".$year."-第".$ans['meeting']."次校长办公会"."</h3>");
			if($level>1){
				getEvent($ans['meeting'],$u_ID,$level);
			}
			echo("</td>");
			echo("</tr>");
		}
	}
	
	function getEvent($meeting, $u_ID,$level){
		$query = mysql_query("select distinct content.e_ID, event.content from content, event, content_user, meeting where content_user.u_ID = '$u_ID' and content_user.c_ID = content.c_ID and content.e_ID = event.e_ID and event.m_ID = meeting.m_ID and meeting.meeting = '$meeting' order by content.e_ID asc");
		while($ans = mysql_fetch_array($query)){
			echo("<div style='border:1px solid #000;padding:5px 15px;margin-top:5px'>");
			echo("<h4>议题：</h4> ");
			echo($ans['content']);
			
			if($level>2){
				getContent($ans['e_ID'], $u_ID);
			}

			echo("</div>");
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
			echo("<div style='border:1px solid #000;padding:5px 15px;margin-top:5px'>");
			if($cid=='true'){	echo("<div>"."<b>意见编号：</b><div class='contentFloat'>".$ans['c_index']."</div></div>");}
			if($content=='true')	echo("<div>"."<b>拟办意见：</b><div class='contentFloat'>".$ans['opinion_a']."</div></div>");
			if($leader=='true')	echo("<div>"."<b>牵头领导：</b><div class='contentFloat'>".$ans['leader']."</div></div>");
			if($responsibility=='true')	echo("<div>"."<b>牵头单位：</b><div class='contentFloat'>".$ans['responsibility']."</div></div>");
			if($assistant=='true')	echo("<div>"."<b>协助单位：</b><div class='contentFloat'>".$ans['assistant']."</div></div>");
			if($program=='true')	echo("<div>"."<b>节点计划：</b><div class='contentFloat'>".$ans['program']."</div></div>");
			if($comment_b=='true')	echo("<div>"."<b>备&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;注：</b><div class='contentFloat'>".$ans['comment_b']."</div></div>");
			echo("<hr style='height:3px;border:none;border-top:3px double red;'/>");
			if($status=='true')	{
				if($ans['status'] >= 90){
					echo("<div style='background-color:#60DD7C'>"."<b>落实状态：</b><div class='contentFloat'>".$status_dict[$ans['status']]."</div></div>");
				}
				else if($ans['status'] > 10){
					echo("<div style='background-color:yellow'>"."<b>落实状态：</b><div class='contentFloat'>".$status_dict[$ans['status']]."</div></div>");
				}
				else{
					echo("<div style='background-color:red'>"."<b>落实状态：</b><div class='contentFloat'>".$status_dict[$ans['status']]."</div></div>");
				}
				
			}
			if($comment_a=='true')	echo("<div>"."<b>落实备注：</b><div class='contentFloat'>".$ans['comment_a']."</div></div>");
			if($self_status=='true')	echo("<div>"."<b>进度自评：</b><div class='contentFloat'>".$status_dict[$ans['self_status']]."</div></div>");
			echo("<div class='clearfix'></div>");
			echo("</div>");
		}
	}
?>