<?php session_start(); ?>
<?php
	$u_ID = $_SESSION['u_ID'];
	$year = $_POST['year'];
	$level = $_POST['level'];
	


	$con = mysql_connect('localhost','LaPitto','X2KxFXdTBmHeMwzm');
	mysql_query('SET NAMES UTF8');  
	if (!$con){
		die('Could not connect: ' . mysql_error());
	}
	if(!mysql_select_db('LaPitto', $con))
		die('Could not connect: ' . mysql_error());	
	getMeeting($u_ID,$year,$level);
	
	function getMeetingCompletion($mid){
		$completion = 0.0;
		$completion_t = 0.0;
		$count = 0;
		$query = mysql_query("select distinct content.status as status from content inner join event on content.e_ID = event.e_ID where event.m_ID = '$mid'");
		while($ans = mysql_fetch_array($query)){
			$status = intval($ans['status']);
			$count = $count + 1;
			$completion = $completion + $status;
			if($status == 100){
				$completion_t = $completion_t + 1.0;
			}
		}
		$completion = round( $completion / $count , 3);
		$completion_t = round($completion_t * 100 / $count , 3);
		$result[0] = $completion;
		$result[1] = $completion_t;
		return $result;
	}

	function getEventCompletion($eid){
		$completion = 0.0;
		$completion_t = 0.0;
		$count = 0;
		$query = mysql_query("select distinct content.status as status from content where content.e_ID = '$eid'");
		while($ans = mysql_fetch_array($query)){
			$status = intval($ans['status']);
			$count = $count + 1;
			$completion = $completion + $status;
			if($status == 100){
				$completion_t = $completion_t + 1.0;
			}
		}
		$completion = round( $completion / $count , 3);
		$completion_t = round($completion_t * 100 / $count , 3);
		$result[0] = $completion;
		$result[1] = $completion_t;
		return $result;
	}
	
	function getMeeting($u_ID, $year, $level){
		$query = mysql_query("select distinct meeting.meeting,meeting.m_ID from content, event, content_user, meeting where content_user.u_ID = '$u_ID' and content_user.c_ID = content.c_ID and content.e_ID = event.e_ID and event.m_ID = meeting.m_ID and meeting.year = '$year' order by meeting.meeting asc");
		while($ans = mysql_fetch_array($query)){
			$result = getMeetingCompletion($ans['m_ID']);
			$mid = $ans['m_ID'];
			echo("<tr>");
			echo("<td><div class='container-fluid'><div class='row'>");
			echo("<div class='col-lg-12 lv-meeting' style='background-color:rgb(128,185,188); border-radius:0.5em; padding:5px;'><span style='font-size:1.5em;'>".$year."-第".$ans['meeting']."次校长办公会"."</span><span class='pull-right' style='padding:5px;'>实际完成率：$result[0]%;&nbsp;&nbsp;&nbsp;参考完成率: $result[1]%;</span></div>");
			getEvent($ans['meeting'],$u_ID,$level);
			echo("</div></div></td>");
			echo("</tr>");
		}
	}
	
	function getEvent($meeting, $u_ID,$level){
		$query = mysql_query("select distinct content.e_ID, event.content from content, event, content_user, meeting where content_user.u_ID = '$u_ID' and content_user.c_ID = content.c_ID and content.e_ID = event.e_ID and event.m_ID = meeting.m_ID and meeting.meeting = '$meeting' order by content.e_ID asc");
		while($ans = mysql_fetch_array($query)){
			$result = getEventCompletion($ans['e_ID']);
			echo("<div class='col-lg-12 lv-event'>");
			echo("<div class='panel panel-success' style='margin-top:10px;'>");
			// echo("<div class='panel-heading' style='font-size:2em'>议题： ".$ans['content']."<span class='pull-right' style='font-size:0.5em; padding-top:10px;'>实际完成率：$result[0]%;&nbsp;&nbsp;&nbsp;参考完成率: $result[1]%;</span></div>");
			echo("<div class='panel-heading' style='font-size:2em'>议题： ".$ans['content']."</div>");
			echo("<table class='table table-striped content-table'>");
			getContent($ans['e_ID'], $u_ID);
			echo("</table></div></div>");
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
			$cid = $ans['c_index'];
			$status = $ans['status'];
			$self_status = $ans['self_status'];
			echo("<tr><td>");
			echo("<div class='cid col-lg-6 lv-content' data-cid='$cid'><div style='font-size:1.3em'>"."意见编号：".$ans['c_index']."</div></div>");
			echo("<div class='col-lg-6 lv-content'><div style='font-size:1.3em'>"."牵头领导：".$ans['leader']."</div></div>");
			echo("<div class='col-lg-6 lv-content'><div style='font-size:1.3em'>"."牵头单位：".$ans['responsibility']."</div></div>");
			echo("<div class='col-lg-6 lv-content'><div style='font-size:1.3em'>"."协助单位：".$ans['assistant']."</div></div>");
			echo("<div class='col-lg-12 lv-content'>");
			echo("<div><div style='font-size:1.3em'>"."拟办意见：</div><div class='contentFloat vc_3'>".$ans['opinion_a']."</div></div>");
			echo("<div class='clearfix'></div>");
			echo("<div><div style='font-size:1.3em'>"."备&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;注：</div><div class='contentFloat vc_8'>".$ans['comment_b']."</div></div>");
			echo("<div class='clearfix'></div>");
			
			if($u_ID==1){
				echo("<div><div style='font-size:1.3em'>"."节点计划：</div><div class='contentFloat vc_10'>".$ans['program']."</div></div>");
				echo("<div class='clearfix'></div>");
				if($ans['loc_program']==0){
					echo('<button class="btn btn-info lockProgram" > 已解锁</button>');
					echo("<div class='clearfix'></div>");
				} else{
					echo('<button class="btn btn-danger lockProgram"> 已锁定</button>');
					echo("<div class='clearfix'></div>");
				}
				
			}
			else{
				if($ans['loc_program']==0){
					echo("<div><div style='font-size:1.3em'>"."节点计划：<span style='font-size:0.8em;color:#60DD7C'>（已解锁）</span></div>");
					echo("<textarea class='program' rows='3' cols='60' style='color:black;margin-left:150px'>".$ans['program']."</textarea></div>");
				} else{
					echo("<div><div style='font-size:1.3em'>"."节点计划：<span style='font-size:0.8em;color:red'>（已锁定）</span></div><div class='contentFloat program'>".$ans['program']."</div></div>");
				}
				echo("<div class='clearfix'></div>");
			}
			echo("<div><div style='font-size:1.3em'>"."落实状态：</div><div class='contentFloat vc_7' data-content='$status'>".$status_dict[$ans['status']]."</div></div>");
			echo("<div class='clearfix'></div>");
			if($u_ID==1){
				echo("<div><div style='font-size:1.3em'>"."落实备注：</div><div class='contentFloat vc_9'>".$ans['comment_a']."</div></div>");
				echo("<div class='clearfix'></div>");
				if($ans['loc_comment']==0){
					echo('<button class="btn btn-info lockComment"> 已解锁</button>');
					echo("<div class='clearfix'></div>");
				} else{
					echo('<button class="btn btn-danger lockComment" > 已锁定</button>');
					echo("<div class='clearfix'></div>");
				}
				echo("<div><div style='font-size:1.3em'>"."进度自评：</div><div class='contentFloat self_status' data-content='$self_status'>".$status_dict[$ans['self_status']]."</div></div>");
				echo("<div class='clearfix'></div>");
				if($ans['loc_self']==0){
					echo('<button class="btn btn-info lockSelf" > 已解锁</button>');
					echo("<div class='clearfix'></div>");
				} else{
					echo('<button class="btn btn-danger lockSelf"> 已锁定</button>');
					echo("<div class='clearfix'></div>");
				}
				echo("<button class='btn btn-primary pull-right'  data-toggle='modal' data-target='#modifyModal' data-cid='$cid'>修改</button>");
				echo("<div class='clearfix'></div>");
			}
			else{				
				if($ans['loc_comment']==0){
					echo("<div><div style='font-size:1.3em'>"."落实备注：<span style='font-size:0.8em;color:#60DD7C'>（已解锁）</span></div>");
					echo("<textarea class='comment_a' rows='3' cols='60' style='color:black;margin-left:150px'>".$ans['comment_a']."</textarea></div>");
				} else{
					echo("<div><div style='font-size:1.3em'>"."落实备注：<span style='font-size:0.8em;color:red'>（已锁定）</span></div><div class='contentFloat comment_a'>".$ans['comment_a']."</div></div>");
				}
				echo("<div class='clearfix'></div>");
				if($ans['loc_self']==0){
					echo("<div><div style='font-size:1.3em'>"."进度自评：<span style='font-size:0.8em;color:#60DD7C'>（已解锁）</span></div>");
					echo("<select class='cc_11 self_status' rows='3' cols='60' style='color:black;margin-left:150px' data-content='$self_status'>");
				    if($status_dict[$ans['self_status']] == "暂无"){
				    	echo("<option selected='selected' value='0'>暂无</option>");
				   	}else{
				   		echo("<option value='0'>暂无</option>");
				   	}
				   	if($status_dict[$ans['self_status']] == "开始推进"){
				    	echo("<option selected='selected' value='10'>开始推进</option>");
				   	}else{
				   		echo("<option value='10'>开始推进</option>");
				   	}
				   	if($status_dict[$ans['self_status']] == "推进中I"){
				    	echo("<option selected='selected' value='30'>推进中I</option>");
				   	}else{
				   		echo("<option value='30'>推进中Ⅰ</option>");
				   	}
				   	if($status_dict[$ans['self_status']] == "推进中II"){
				    	echo("<option selected='selected' value='50'>推进中II</option>");
				   	}else{
				   		echo("<option value='50'>推进中Ⅱ</option>");
				   	}
				   	if($status_dict[$ans['self_status']] == "推进中III"){
				    	echo("<option selected='selected' value='70'>推进中III</option>");
				   	}else{
				   		echo("<option value='70'>推进中Ⅲ</option>");
				   	}
				   	if($status_dict[$ans['self_status']] == "基本完成"){
				    	echo("<option selected='selected' value='90'>基本完成</option>");
				   	}else{
				   		echo("<option value='90'>基本完成</option>");
				   	}
				   	if($status_dict[$ans['self_status']] == "已完成"){
				    	echo("<option selected='selected' value='100'>已完成</option>");
				   	}else{
				   		echo("<option value='100'>已完成</option>");
				   	}
				         
				    echo("</select></div>");
				} else{
					echo("<div><div style='font-size:1.3em'>"."进度自评：<span style='font-size:0.8em;color:red'>（已锁定）</span></div><div class='contentFloat self_status' data-content='$self_status'>".$status_dict[$ans['self_status']]."</div></div>");
				}
				echo("<div class='clearfix'></div>");
				echo("<button class='btn btn-primary pull-right modify' data-cid='$cid' >保存</button>");
				echo("<div class='clearfix'></div>");
				echo("</div>");				
			}
			echo("</td></tr>");
			echo("</div>");
		}
	}
?>