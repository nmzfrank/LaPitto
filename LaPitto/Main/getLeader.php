<?php session_start(); ?>
<?php
	$con = mysql_connect('localhost','LaPitto','X2KxFXdTBmHeMwzm');
	mysql_query('SET NAMES UTF8');  
	if (!$con){
		die('Could not connect: ' . mysql_error());
	}
	if(!mysql_select_db('LaPitto', $con))
		die('Could not connect: ' . mysql_error());
		
	$check_query = mysql_query("SELECT * FROM users WHERE users.usage = 1");
	$count = 0;
	echo("<div class='btn-group' role='group'>");
	while($result = mysql_fetch_array($check_query)){
		$count = $count + 1;
		if($count == 5){
			echo("</div></br>");
			echo("<div class='btn-group' role='group'>");
			$count = 0;
		}
		$name = $result['cn_name'];
		echo("<button type='button' class='btn btn-danger multiselect' style='margin:5px' data-name='$name'>".$name."<span class='hidden'>;</span></button>");
	}
	echo('</div>');
?>