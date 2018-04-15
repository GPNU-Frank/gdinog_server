<?php
require './plugins/captcha/ValidateCode.class.php';  //先把类包含进来，实际路径根据实际情况进行修改。
$_vc = new ValidateCode();		//实例化一个对象
$_vc->doimg();
$value = $_vc->getCode();//验证码保存到对应SESSION中
$ip=$_SERVER['REMOTE_ADDR'];         //获得IP地址
MySqlInsert("INSERT INTO loginlog VALUES('','$value','$ip',Now())");
?>