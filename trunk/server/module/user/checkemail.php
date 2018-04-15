<?php

	$email = GetParam('email');

	$res = MysqlQuery("select user_id from users where user_id = '$email'");

	if($res){
		Output(true,10012);
	}else
	{
		Output(false);
	}

?>