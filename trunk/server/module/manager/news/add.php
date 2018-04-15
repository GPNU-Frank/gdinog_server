<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/1/27
 * Time: 20:55
 *
 * shanxuan 2016/01/27
 */
Privilege(null,null,addnews);

function addnews()
{
	$uid = GetCookie('uid');
	$title = GetParam('title',20001) ;
	$content = GetParam('content',20001) ;
	$importance = GetParam('importance',20001,null,true) ;
    $now = date('Y-m-d h:i:s',time());
    $sql = "INSERT INTO news(uid ,title , content ,time ,importance ) VALUES({$uid} , '$title' , '$content' , '$now', {$importance}) " ;
    $res = MySqlInsert($sql) ;
    if( $res != false  )  OutPut(true) ;
    else        OutPut(false , 20001) ;
}

