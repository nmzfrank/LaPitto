<?php session_start(); ?>
<?php
	$name = $_GET['name'];
	$hash = md5($_GET['password']);
	$con = mysql_connect('localhost','LaPitto','X2KxFXdTBmHeMwzm');
	
	if (!$con){
		die('Could not connect: ' . mysql_error());
	}
	if(!mysql_select_db('LaPitto', $con))
		die('Could not connect: ' . mysql_error());
		
	$check_query = mysql_query("SELECT * FROM users WHERE name = '$name'");
	
	if($result = mysql_fetch_array($check_query)){
		if($hash == $result['password']){
			if($_SESSION['curUser'] == $name){
				echo('用户已登录');
			}
			else{
				$_SESSION['curUser'] = $name;
				echo('验证成功');
			}
		}
		else{
			echo('用户名密码错误: ');
		}
	}
	else{
		echo('无此账户: '.$hash);
	}
	?>