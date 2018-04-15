<?php

	$nick = GetParam('nick');

	$res = MysqlQuery("select nick from users where nick = '$nick'");

	if($res){
		Output(true,10012);
	}else
	{
		Output(false);
	}

?>