<?php
/**
 * Created by PhpStorm.
 * User: chx
 * Date: 16/8/7
 * Time: 上午9:18
 */


Privilege(comment , comment , comment , comment ) ;

//http://localhost/gdinoj/trunk/server/?action=article.bbs.comment&textid=0&commentType=0&content=123234213423123&articleid=0&cookie=45e3bca91fbce084c32a53b2f4fd095c
function comment()
{
    $uid = GetCookie('uid') ;
    $content = GetParam('content' , 20001 ) ;
    $textid = GetParam('textid' , 20001 ) ;
    $commentType = GetParam('commentType' , 20001 ) ;
    $articleid = GetParam('articleid' , 20001 ) ;


    $sql = "insert into  comment( textid , commentType , publisherid , content , commenttime  ) values( {$textid} , {$commentType} , {$uid} , '$content' , NOW() ) " ;
    MySqlInsert($sql) ;

    $sql = "update article set commentnum = commentnum + 1 where articleid = {$articleid}" ;
    MySqlUpdate($sql) ;


    OutPut(true , null , null) ;
}

