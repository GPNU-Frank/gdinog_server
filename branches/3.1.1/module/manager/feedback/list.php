<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/1/27
 * Time: 21:00
 *
 * shanxuan 2016/01/27
 */

//分页查询，以时间进行排序
$list = Page("feedback","fid","");
$resultList = array();

//1. uid 用户唯一标识 2.role 3.title 标题 4.type 类型 5. nick 昵称 6.  is_mark是否标志 7 is_solved是否解决

for($i = 0; $i < count($list) ; $i++){
    $uid = $list[$i]['uid'];
    $sqlForUser = "select nick, identity from users where uid = '$uid'";
    $resultForUser = MysqlQuery($sqlForUser);
    $nick = $resultForUser['nick'];
    $role = $resultForUser['identity'];

    $resultList[$i]['fid'] = (int)$list[$i]['fid'];
    $resultList[$i]['uid'] = $uid;
    $resultList[$i]['role'] = (int)$role;
    $resultList[$i]['title'] = $list[$i]['title'];
    $resultList[$i]['type'] = (int)$list[$i]['type'];
    $resultList[$i]['nick'] = $nick;
    $resultList[$i]['is_mark'] = (int)$list[$i]['is_mark'];
    $resultList[$i]['is_solved'] = (int)$list[$i]['is_solved'];
}

OutPutList(true, "", $resultList);