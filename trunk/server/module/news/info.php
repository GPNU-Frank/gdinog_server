<?php
/**
 * Created by PhpStorm.
 * User: shanxuan
 * Date: 16-1-30
 * Time: 下午12:32
 */

$nid = GetParam('nid') ;

$sql = "SELECT nick,title,content,time , importance  FROM users , news WHERE users.uid = news.uid AND news.news_id = {$nid}" ;
$res = MySqlQuery($sql);
if( $res )  OutPut(true , "" , $res ) ;
else        OutPut(false , 20001 ) ;