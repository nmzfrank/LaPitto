<?php session_start(); ?>
<?php
	$name = $_POST['name'];
	$hash = md5($_POST['password']);
	$con = mysql_connect('localhost','LaPitto','X2KxFXdTBmHeMwzm');
	
	if (!$con){
		die('Could not connect: ' . mysql_error());
	}
	if(!mysql_select_db('LaPitto', $con))
		die('Could not connect: ' . mysql_error());
		
	$check_query = mysql_query("SELECT * FROM users WHERE name = '$name'");
	
	if($result = mysql_fetch_array($check_query)){
		if($hash == $result['password']){
			if($result['status'] == 1){
				echo(2);											//已有用户登录
			}
			else{
				$_SESSION['curUser'] = $name;
				echo(0);
				mysql_query("UPDATE users SET status = 1 WHERE name = '$name'");												//用户状态设为已登录
			}
		}
		else{
			echo(1);
		}
	}

?>