<?php
/**
 * Created by PhpStorm.
 * User: Joooo
 * Date: 2016/10/17
 * Time: 0:07
 */

Privilege(comment , comment , comment , comment ) ;

//http://localhost/gdinoj/trunk/server/?action=article.bbs.comment&textid=0&commentType=0&content=123234213423123&articleid=0&cookie=45e3bca91fbce084c32a53b2f4fd095c
function comment()
{
    $uid = GetCookie('uid') ;
    $content = GetParam('content' , 20001 ) ;
    $textid = GetParam('textid' , 20001 ) ;
    $type = GetParam('type' , 20001 ) ;
    $id = GetParam('id' , 20001 ) ;


    $sql = "insert into  comment( textid , commentType , publisherid , content , commenttime  ) values( {$textid} , {$type} , {$uid} , '$content' , NOW() ) " ;
    MySqlInsert($sql) ;

    if($commentType==1)
    {
        $sql = "update article set commentnum = commentnum + 1 where articleid = {$id}" ;
        MySqlUpdate($sql) ;
    }
    else
    {
        $sql="UPDATE comment SET commentnum=commentnum+1 WHERE textid='$textid'";
        MySqlUpdate($sql);
    }

    OutPut(true , null , null) ;
}
