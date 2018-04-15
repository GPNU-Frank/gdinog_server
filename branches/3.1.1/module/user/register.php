<?php

/*
 * 注册
 * @ $username 用户名
 * @ $nickname 昵称
 * @ $password 密码
 * @ $repassword 重复密码
 * @ $captchacode 验证码
 */
$username = GetParam("username",10000);
$nickname = GetParam("nickname",10006);
$password = GetParam("password",10001);
$captchacode = GetParam("captchacode",10005);

$ip=$_SERVER['REMOTE_ADDR'];         //获得IP地址

$row = MySqlQuery("select count(*) from loginlog where ip = '$ip' and captcha = '$captchacode'");
if($row['count(*)'] == 0){
	OutPut(false,10005);
}

MySqlUpdate("DELETE FROM loginlog where ip = '$ip' and captcha = '$captchacode'");

//限制用命名为邮箱格式
if (!filter_var($username,FILTER_VALIDATE_EMAIL)){
	OutPut(false,10000);
	exit(1);
}

// 检测用户是否存在
$row = MySqlQuery("SELECT `user_id` FROM `users` WHERE `users`.`user_id` = '" . $username . "'");
if (Count($row) != 0) {
	OutPut(false,10008);
	exit(1);
}

$password = pwGen ($password); // 加密密码

$isSuccess = MySqlInsert("INSERT INTO `users`(`user_id`,`email`,`ip`,`accesstime`,`password`,`reg_time`,`nick`)" . "VALUES('" . $username . "','" . $username . "','" . $_SERVER ['REMOTE_ADDR'] . "',NOW(),'" . $password . "',NOW(),'" . $nickname . "')");

if (isSuccess) {
	OutPut (true, 10007,$data);
}else{
	OutPut (false, 10009,$data);
}

?>