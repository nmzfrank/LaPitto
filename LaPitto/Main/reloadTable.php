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
	
	
	function getMeeting($u_ID, $year, $level){
		$query = mysql_query("select distinct meeting.meeting from content, event, content_user, meeting where content_user.u_ID = '$u_ID' and content_user.c_ID = content.c_ID and content.e_ID = event.e_ID and event.m_ID = meeting.m_ID and meeting.year = '$year' order by meeting.meeting asc");
		while($ans = mysql_fetch_array($query)){
			echo("<tr>");
			echo("<td><div class='container-fluid'><div class='row'>");
			echo("<div class='col-lg-12 lv-meeting'><h2 style='margin:10px'>".$year."-第".$ans['meeting']."次校长办公会"."</h2></div>");
			getEvent($ans['meeting'],$u_ID,$level);
			echo("</div></div></td>");
			echo("</tr>");
		}
	}
	
	function getEvent($meeting, $u_ID,$level){
		$query = mysql_query("select distinct content.e_ID, event.content from content, event, content_user, meeting where content_user.u_ID = '$u_ID' and content_user.c_ID = content.c_ID and content.e_ID = event.e_ID and event.m_ID = meeting.m_ID and meeting.meeting = '$meeting' order by content.e_ID asc");
		while($ans = mysql_fetch_array($query)){
			echo("<div class='col-lg-12 lv-event'>");
			echo("<h3>议题： ".$ans['content']."</h3>");
			echo("</div>");
			getContent($ans['e_ID'], $u_ID);
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
			echo("<div class='cid col-lg-6 lv-content'><div style='font-size:1.3em'>"."意见编号：".$ans['c_index']."</div></div>");
			echo("<div class='col-lg-6 lv-content'><div style='font-size:1.3em'>"."牵头领导：".$ans['leader']."</div></div>");
			echo("<div class='col-lg-6 lv-content'><div style='font-size:1.3em'>"."牵头单位：".$ans['responsibility']."</div></div>");
			echo("<div class='col-lg-6 lv-content'><div style='font-size:1.3em'>"."协助单位：".$ans['assistant']."</div></div>");
			echo("<div class='col-lg-8 lv-content'>");
			echo("<div><div style='font-size:1.3em'>"."拟办意见：</div><div class='contentFloat vc_3'>".$ans['opinion_a']."</div></div>");
			echo("<div class='clearfix'></div>");
			echo("<div><div style='font-size:1.3em'>"."落实状态：</div><div class='contentFloat vc_7'>".$status_dict[$ans['status']]."</div></div>");
			echo("<div class='clearfix'></div>");
			echo("<div><div style='font-size:1.3em'>"."备&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;注：</div><div class='contentFloat vc_8'>".$ans['comment_b']."</div></div>");
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
				echo("<div><div style='font-size:1.3em'>"."节点计划：</div><div class='contentFloat vc_10'>".$ans['program']."</div></div>");
				echo("<div class='clearfix'></div>");
				if($ans['loc_program']==0){
					echo('<button class="btn btn-info lockProgram" > 已解锁</button>');
					echo("<div class='clearfix'></div>");
				} else{
					echo('<button class="btn btn-danger lockProgram"> 已锁定</button>');
					echo("<div class='clearfix'></div>");
				}
				echo("<div><div style='font-size:1.3em'>"."进度自评：</div><div class='contentFloat'>".$status_dict[$ans['self_status']]."</div></div>");
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
					echo("<div><div style='font-size:1.3em'>"."落实备注：<span style='font-size:0.8em;color:red'>（已锁定）</span></div><div class='contentFloat'>".$ans['comment_a']."</div></div>");
				}
				echo("<div class='clearfix'></div>");
				
				
				if($ans['loc_program']==0){
					echo("<div><div style='font-size:1.3em'>"."节点计划：<span style='font-size:0.8em;color:#60DD7C'>（已解锁）</span></div>");
					echo("<textarea class='program' rows='3' cols='60' style='color:black;margin-left:150px'>".$ans['program']."</textarea></div>");
				} else{
					echo("<div><div style='font-size:1.3em'>"."节点计划：<span style='font-size:0.8em;color:red'>（已锁定）</span></div><div class='contentFloat'>".$ans['program']."</div></div>");
				}
				echo("<div class='clearfix'></div>");
				
				
				if($ans['loc_self']==0){
					echo("<div><div style='font-size:1.3em'>"."进度自评：<span style='font-size:0.8em;color:#60DD7C'>（已解锁）</span></div>");
					echo("<select class='cc_11' rows='3' cols='60' style='color:black;margin-left:150px'>");
				    if($status_dict[$ans['self_status']] == "暂无"){
				    	echo("<option selected='selected'>暂无</option>");
				   	}else{
				   		echo("<option>暂无</option>");
				   	}
				   	if($status_dict[$ans['self_status']] == "开始推进"){
				    	echo("<option selected='selected'>开始推进</option>");
				   	}else{
				   		echo("<option>开始推进</option>");
				   	}
				   	if($status_dict[$ans['self_status']] == "推进中Ⅰ"){
				    	echo("<option selected='selected'>推进中Ⅰ</option>");
				   	}else{
				   		echo("<option>推进中Ⅰ</option>");
				   	}
				   	if($status_dict[$ans['self_status']] == "推进中Ⅱ"){
				    	echo("<option selected='selected'>推进中Ⅱ</option>");
				   	}else{
				   		echo("<option>推进中Ⅱ</option>");
				   	}
				   	if($status_dict[$ans['self_status']] == "推进中Ⅲ"){
				    	echo("<option selected='selected'>推进中Ⅲ</option>");
				   	}else{
				   		echo("<option>推进中Ⅲ</option>");
				   	}
				   	if($status_dict[$ans['self_status']] == "推进中Ⅳ"){
				    	echo("<option selected='selected'>推进中Ⅳ</option>");
				   	}else{
				   		echo("<option>推进中Ⅳ</option>");
				   	}
				   	if($status_dict[$ans['self_status']] == "推进中Ⅴ"){
				    	echo("<option selected='selected'>推进中Ⅴ</option>");
				   	}else{
				   		echo("<option>推进中Ⅴ</option>");
				   	}
				   	if($status_dict[$ans['self_status']] == "推进中Ⅵ"){
				    	echo("<option selected='selected'>推进中Ⅵ</option>");
				   	}else{
				   		echo("<option>推进中Ⅵ</option>");
				   	}
				   	if($status_dict[$ans['self_status']] == "推进中Ⅶ"){
				    	echo("<option selected='selected'>推进中Ⅶ</option>");
				   	}else{
				   		echo("<option>推进中Ⅶ</option>");
				   	}
				   	if($status_dict[$ans['self_status']] == "推进中Ⅶ"){
				    	echo("<option selected='selected'>推进中Ⅶ</option>");
				   	}else{
				   		echo("<option>推进中Ⅶ</option>");
				   	}
				   	if($status_dict[$ans['self_status']] == "基本完成"){
				    	echo("<option selected='selected'>基本完成</option>");
				   	}else{
				   		echo("<option>基本完成</option>");
				   	}
				   	if($status_dict[$ans['self_status']] == "已完成"){
				    	echo("<option selected='selected'>已完成</option>");
				   	}else{
				   		echo("<option>已完成</option>");
				   	}
				         
				    echo("</select></div>");
				} else{
					echo("<div><div style='font-size:1.3em'>"."进度自评：<span style='font-size:0.8em;color:red'>（已锁定）</span></div><div class='contentFloat'>".$status_dict[$ans['self_status']]."</div></div>");
				}
				echo("<div class='clearfix'></div>");
				echo("<button class='btn btn-primary pull-right modify' data-cid='$cid' >修改</button>");
				echo("<div class='clearfix'></div>");
				echo("</div>");
			}
			echo("</div>");
		}
	}
?>