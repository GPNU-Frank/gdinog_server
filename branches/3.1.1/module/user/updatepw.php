<?php
/**
 * Created by PhpStorm.
 * User: shanxuan
 * Date: 16-1-30
 * Time: 上午11:19
 */


$uid = GetCookie('uid') ;
$oldpw = GetParam('oldpw') ;
$newpw = GetParam('newpw') ;

$sql = "SELECT password FROM  users WHERE uid = '$uid'" ;
$row = MySqlQuery($sql) ;
$res = pwCheck($oldpw , $row['password']) ;
if( $res )
{
    $pw = pwGen($newpw) ;
    $sql = "UPDATE users SET password = '$pw' WHERE uid = '$uid'" ;
    MySqlUpdate($sql) ;
    OutPut(true , "" , $row['password']) ;
}
else //密码错误
{
    OutPut(false , 10003 , $row['password']) ;
}