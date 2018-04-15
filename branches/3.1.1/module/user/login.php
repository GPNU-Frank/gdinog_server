<?php

/*
 * 登陆模块
 * @Author zhudelin
 * time 2016/01/14
 * @$username 账号
 * @$password 密码
 */

//http://localhost/gdinoj/trunk/server/?action=user.login&username=2014034743045&password=2014034743045
$username = GetParam("account",10000);
$password = GetParam("password",10001);
$row = MySqlQuery("SELECT `uid`,`user_id`,`password`,`identity`,`nick`,`defunct`, `avatarUrl` FROM `users` WHERE `user_id`='$username'");
if (pwCheck ( $password, $row ['password'] )) {

        if($row['defunct'] == 'Y'){
            OutPut(false,10013);
        }
	
	$cookie = md5(time().$username);
	$ip = $_SERVER['REMOTE_ADDR'];
	MySqlInsert("Update `users` set cookie = '$cookie' ,ip = '$ip', login_time = NOW() where user_id = '$username'");

	$data = array(
		"uid" => $row["uid"],
		"nickname" => $row["nick"],
		"role" => $row["identity"],
		'cookie' => $cookie,
        'avatarUrl' => $row["avatarUrl"],
	);
	OutPut ( true, 10002,$data );
} else {
	OutPut ( false, 10003 );
}
?>