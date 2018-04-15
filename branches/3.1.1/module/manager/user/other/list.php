<?php
//获取所有教师列表
$nick = GetParam('nick');
$sex = GetParam('sex');

$sqlAdd = Filter( array(0, $nick ,$sex) , array("=","%","=")  , array("identity","nick","sex") );
$list = Page("users","uid",$sqlAdd,"uid,email,sex,nick,reg_time,login_time");
OutPutList(true,"",$list);