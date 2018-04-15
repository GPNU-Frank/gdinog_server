<?php
	header("Content-type: text/html; charset=utf-8");
	header("Access-Control-Allow-Origin: *");
	header("Access-Control-Allow-Methods", "POST,OPTIONS,GET");
	date_default_timezone_set('Etc/GMT-8'); //设置时区
	//error_reporting(E_ALL & ~E_WARNING & ~E_NOTICE);
	
	require_once './framework/include/config.php';
	require_once './framework/include/conn.php';
	require_once './framework/include/msg.php';
	require_once './framework/include/util.php';
	
	$page = GetParam('page',null,1,true);
	$pagesize = GetParam('pagesize',null,4,true);

?>