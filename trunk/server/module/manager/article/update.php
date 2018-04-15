<?php
/*
 *
 *
 *   修改文章
 *
 */


Privilege(modify , modify , modify , null ) ;
//http://localhost/gdinoj/trunk/server/?action=manager.article.update&articleid=16&type=1&title=aasssssdf&labelid=['1']&isMarkdown=1&mcontent=adsfasdfsdf&cookie=3cbd699621e01cd74ac6f30bad277422

function modify()
{
    $articleid = GetParam('articleid' , 20001 ) ;
    $type = GetParam('type' , 20001 ) ;
    $title = GetParam('title' , 20001 ) ;
    $publisherid = GetCookie('uid') ;
    $content = GetParam('content' ) ;
    $labelid=GetParam('labelid', 20001);
    $tagnames = GetParam('tagnames') ;
    $isMarkdown = GetParam('isMarkdown',20001) ;
    $summary=GetParam('summary');
    $mcontent = GetParam('mcontent' ) ;

    $identity=GetCookie('identity');
    if($identity==1) $isAuthorized=0;
    else $isAuthorized=1;

    //格式处理
    $title=addslashes($title);
    $content=addslashes($content);
    $tagnames=addslashes($tagnames);
    $mcontent=addslashes($mcontent);
    $summary=addslashes($summary);

    $publishtime = date('Y-m-d h:i:s',time()) ;

    $sql = "update article set title = '$title' , publisherid = '$publisherid' , content = '$content' ,
      publishtime = '$publishtime' , type = '$type', labelid='$labelid', isMarkdown='$isMarkdown'
       , isAuthorized='$isAuthorized' where articleid = '$articleid'" ;

    MySqlUpdate($sql) ;
    if($isMarkdown==1)
    {
        $sql="update article set mcontent='$mcontent' where articleid='$articleid'";
        MySqlUpdate($sql);
    }
    if($summary!=null)
    {
        $sql="update article set summary='$summary' where articleid='$articleid'";
        MySqlUpdate($sql);
    }
    if($tagnames!=null)
    {
        $sql="update article set tagnames='$tagnames' where articleid='$articleid'";
        MySqlUpdate($sql);
    }

    MySqlUpdate("update article_label set labelid='$labelid' where articleid='$articleid'");


    OutPut(true , null , null ) ;
}