<?php
/**
 * Created by PhpStorm.
 * User: chx
 * Date: 16/8/6
 * Time: 下午9:43
 */

Privilege( ariticalDetail , ariticalDetail , ariticalDetail , ariticalDetail ) ;

ariticalDetail() ;

//http://localhost/gdinoj/trunk/server/?action=article.knowledge.detail&articleid=2
function ariticalDetail()
{
    $articleid = GetParam('articleid' , 20001 ) ;
    $sql = "select title , nick as publishername  , avatarUrl , content , publishtime , pvnum , agreenum , summary, tagnames, isMarkdown, mcontent, labelid from article , users where article.articleid = '$articleid' AND  users.uid = article.publisherid" ;
    $res = MySqlQuery($sql) ;
    OutPut(true , "" , $res ) ;
}