<?php session_start(); ?>
<?php
	$usage_dict = array('校领导'=>1,'校单位'=>2);
	$name = $_POST['name'];
	$pw = md5($_POST['pw']);
	$cn = $_POST['cn'];
	$usage = $usage_dict[$_POST['usage']];
	$weight = $_POST['weight'];
	$con = mysql_connect('localhost','LaPitto','X2KxFXdTBmHeMwzm');
	mysql_query('SET NAMES UTF8');  
	if (!$con){
		die('Could not connect: ' . mysql_error());
	}
	if(!mysql_select_db('LaPitto', $con))
		die('Could not connect: ' . mysql_error());
		
	$check_query = mysql_query("SELECT * FROM users WHERE name = '$name'");
	if($result = mysql_fetch_array($check_query)){
		if($name=='admin'){
			$query = mysql_query("UPDATE users SET password = '$pw' WHERE name = '$name'");
			echo("admin账户仅能修改密码，正在修改");
		}
		else{
			$query = mysql_query("UPDATE users SET password = '$pw', cn_name = '$cn', weight = '$weight' WHERE name = '$name'");
			echo("正在修改用户");
		}
	}
	else{
		$query = mysql_query("INSERT INTO users VALUES(NULL, '$name', '$pw', '$cn', '$usage', '$weight')");
		echo("正在新建用户");
	}
?>