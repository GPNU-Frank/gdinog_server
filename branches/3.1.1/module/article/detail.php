<?php
/**
 * Created by PhpStorm.
 * User: chx
 * Date: 16/8/6
 * Time: 下午9:43
 */

//Privilege( ariticalDetail , ariticalDetail , ariticalDetail , ariticalDetail ) ;

ariticalDetail() ;

function ariticalDetail()
{
    $articleid = GetParam('articleid' , 20001 ) ;
    $sql = "select title , nick as publishername , content , publishtime , pvnum , agreenum from article , users where articleid = '$articleid' AND  users.uid = article.publisherid" ;
    $res = MySqlQuery($sql) ;
    OutPutList(true , "" , $res ) ;
}