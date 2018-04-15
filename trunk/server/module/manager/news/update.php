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
	$nid = GetParam('nid') ;
	$title = GetParam('title') ;
	$content = GetParam('content') ;
	$importance = GetParam('importance') ;
    $now = date('Y-m-d h:i:s',time());
    $sql = "UPDATE news  SET title = '$title' , content = '$content' , importance={$importance} WHERE news_id = {$nid}" ;
    $res = MySqlUpdate($sql) ;
    if( $res != false  )  OutPut(true) ;
    else        OutPut(false , 20001) ;
}

