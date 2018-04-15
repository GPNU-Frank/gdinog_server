<?php
	require './framework/main.php';
	/* 
	 * 广东技术师范学院在线评判系统 Api
	 * @Author 朱德林
	 * @Eamil 76676854@qq.com
	 * @Version 1.0
	 * @Date 2015-5-20
	 */
	$path = sprintf("./module/%s.php",str_replace(".","/",GetParam("action",20001)));

	if(!file_exists($path)){
		echo OutPut(false,20001);
	}else{
		require($path);
	}

?>


