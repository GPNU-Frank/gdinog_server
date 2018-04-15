<?php
/**
 * Created by PhpStorm.
 * User: Joooo
 * Date: 2016/9/23
 * Time: 13:44
 */
ariticalDetail() ;

function ariticalDetail()
{
    $articleid = GetParam('articleid' , 20001 ) ;
    $sql = "select title , publisherid, nick as publishername  , avatarUrl , content , publishtime , pvnum , agreenum , summary, isMarkdown, mcontent, labelid , isTop, isQuality from article , users where article.articleid = '$articleid' AND  users.uid = article.publisherid" ;
    $res = MySqlQuery($sql) ;

    $sql="select tagnames from article where article.articleid = '$articleid'" ;
    $result=MySqlQuery($sql);
    $tagnames=explode(",", $result['tagnames']);
    $res['tagnames']=$tagnames;

    $sql="UPDATE article SET pvnum = pvnum + 1 WHERE articleid='$articleid'";
    MySqlUpdate($sql);

    OutPut(true , "" , $res ) ;
}

//http://localhost/gdinoj/trunk/server/?action=manager.article.detail&articleid=77