<?php
/*
 *
 *  删除文章
 *
 *
 */


Privilege(null , null , delete , null ) ;

//http://localhost/gdinoj/trunk/server/?action=manager.article.delete&articleid=70&cookie=9a5696ceb7935d3bf00b0ae0aee4318a
function delete()
{
    $articleid = GetParam('articleid') ;

    $sql = "delete from article where articleid = $articleid " ;
    MySqlUpdate($sql) ;
    $sql="delete from article_label where articleid=$articleid";
    MySqlUpdate($sql);

    OutPut(true , null , null ) ;

}