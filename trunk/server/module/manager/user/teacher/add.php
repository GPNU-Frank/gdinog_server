<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/2/1
 * Time: 17:28
 */

$code = GetParam('code' ,20001) ;
$nick = GetParam('nick',20001) ;
$sex = GetParam('sex',20001) ;
$academic = GetParam('academic',20001) ;
$department = GetParam('department',20001) ;
$contact = GetParam('contact',20001) ;
$email = GetParam('email',20001) ;
$qq = GetParam('qq',20001) ;
$pwd = pwGen("123456") ;
$ip = $_SERVER ['REMOTE_ADDR'];


$sql = "INSERT INTO users (user_id , sex , ip , password , nick , identity , qq , email , academy , major , code , contact) VALUES (  '$code' ,{$sex} , '$ip' , '$pwd' , '$nick' , 2 , '$qq' , '$email' , {$academic} , {$department} , '$code' , '$contact') " ;
$uid = MySqlInsert($sql) ;

if( $uid != false  )
{
    OutPut(true) ;
}
else
       OutPut(false ,20001 ) ;