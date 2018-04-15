<?php
/*获取所有学生列表*/
$academy_id = GetParam('academy_id');
$major = GetParam('major');
$sqlAdd = Filter( array(  1  , $academy_id,$major) , array("=","=","=")  , array("identity","academy",'major') );
$list = Page("users","uid",$sqlAdd,"uid,nick,code,contact,user_id ,academy as academy_id, getclassnum(uid) as classnum , getname(academy) as  academy , getname(major) as major,login_time,users.class_id");

$num = count($list) ;
for($i = 0 ; $i<$num ; $i++ )
{
    $class_id = $list[$i]['class_id'] ;
    $sql = "SELECT class_name FROM class where class_id = '$class_id'" ;
    $res = MySqlQuery($sql) ;
    $list[$i]['class_name'] = $res['class_name'] ;
}

OutPutList(true,"",$list);