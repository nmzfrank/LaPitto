<?php session_start(); ?>
<?php
	$index = $_POST['index'];
	$type = $_POST['type'];
	$con = mysql_connect('localhost','LaPitto','X2KxFXdTBmHeMwzm');
	mysql_query('SET NAMES UTF8');  
	if (!$con){
		die('Could not connect: ' . mysql_error());
	}
	if(!mysql_select_db('LaPitto', $con))
		die('Could not connect: ' . mysql_error());
		
	echo($type);
	
	if($type==0){
		$check_query = mysql_query("SELECT loc_comment from content where c_index = '$index'");
		$result = mysql_fetch_array($check_query);
		$loc_comment = $result['loc_comment'];
		if($loc_comment==0)
			$loc_comment=1;
		else
			$loc_comment=0;
		$query = mysql_query("UPDATE content SET loc_comment='$loc_comment' WHERE c_index='$index'");	
	}
	else if($type==1){
		$check_query = mysql_query("SELECT loc_program from content where c_index = '$index'");
		$result = mysql_fetch_array($check_query);
		$loc_program = $result['loc_program'];
		if($loc_program==0)
			$loc_program=1;
		else
			$loc_program=0;
		$query = mysql_query("UPDATE content SET loc_program='$loc_program' WHERE c_index='$index'");	
	}
	else if($type==2){
		$check_query = mysql_query("SELECT loc_self from content where c_index = '$index'");
		$result = mysql_fetch_array($check_query);
		$loc_self = $result['loc_self'];
		if($loc_self==0)
			$loc_self=1;
		else
			$loc_self=0;
		$query = mysql_query("UPDATE content SET loc_self='$loc_self' WHERE c_index='$index'");	
	}
?>