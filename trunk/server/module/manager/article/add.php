<?php
/*
 *
 *  增加
 *
 */

Privilege(add , add , add , add ) ;

//http://localhost/gdinoj/trunk/server/?action=manager.article.add&type=2&title=aadf&labelid=['1']&isMarkdown=1&content=<p><strong>Hello<span style="color:#ff000">wor</span>ld</strong></p>&mcontent=adsfasdfsdf&cookie=3cbd699621e01cd74ac6f30bad277422&isTop=0&isQuality=0
//http://localhost/gdinoj/trunk/server/?action=manager.article.add&type=2&title=This is not GOOD&labelid=['1']&isMarkdown=0&content=<p><strong>Hello<span style="color:#ff000">wor</span>ld</strong></p>&suummary=HelloWorld&cookie=3cbd699621e01cd74ac6f30bad277422&isTop=0&isQuality=0
//9a5696ceb7935d3bf00b0ae0aee4318a
function add()
{
    $type = GetParam('type' , 20001 ) ;
    $title = GetParam('title' , 20001 ) ;
    $publisherid = GetCookie('uid') ;
    $content = GetParam('content') ;
    $labelid=GetParam('labelid', 20001);
    $tagnames = GetParam('tagnames') ;
    $isMarkdown = GetParam('isMarkdown',20001) ;
    $mcontent = GetParam('mcontent' ) ;
    $summary=GetParam('summary');
    $isTop=GetParam('isTop');
    $isQuality=GetParam('isQuality');

    $identity=GetCookie('identity');
    if($identity==1) $isAuthorized=0;
    else $isAuthorized=1;

    $pvnum = 0 ;
    $publishtime = date('Y-m-d h:i:s',time()) ;


    //格式处理
    $title=addslashes($title);
    $content=addslashes($content);
    $tagnames=addslashes($tagnames);
   $mcontent=addslashes($mcontent);
    $summary=addslashes($summary);

    $sql = "insert into article( `title` , `publisherid` , `content`  , `publishtime` , `pvnum` , `type` , `summary`, `isMarkdown` , `mcontent`, `tagnames` ,`labelid`, `isTop`, `isQuality`, `isAuthorized`)
      VALUES( '$title' , '$publisherid' , '$content'  , '$publishtime' , '$pvnum' , '$type' ,'$summary', '$isMarkdown' , '$mcontent', '$tagnames' ,'$labelid' , '$isTop', '$isQuality', '$isAuthorized')" ;

    $articleid = MySqlInsert($sql) ;
    //InsertTags($articleid , $tagnames ) ;
    $sql="insert into article_label(`articleid`, `labelid`) values('$articleid', '$labelid')";
    $res=MySqlInsert($sql);
    OutPut(true , null , null ) ;
}

function InsertTags($articleid,$tagnames){
    $tags = json_decode($tagnames);
    $id = $articleid;//必须 $pid 为引用会改变
//    foreach ($tags as $tag) {
//        MySqlInsert("INSERT INTO article(articleid,labelid) VALUES('$articleid','$tag')");
//    }
}
























