<?php
//获取所有教师列表
$academy = GetParam('academy');
$sqlAdd = Filter( array(  2  , $academy) , array("=","=")  , array("identity","academy") );
$list = Page("users","uid",$sqlAdd,"uid,nick,code,contact,user_id , getclassnum(uid) as classnum , getname(academy) as  academic , getname(major) as major");
OutPutList(true,"",$list);