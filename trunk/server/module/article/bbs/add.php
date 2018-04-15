<?php
/*
 *
 *  增加
 *
 */



Privilege(add , add , add , add) ;


function add()
{
    $type = GetParam('type' , 20001 ) ;
    $title = GetParam('title' , 20001 ) ;
    $publisherid = GetCookie('uid') ;
    $content = GetParam('content' , 20001 ) ;
    $tagids = GetParam('tagids',20001) ;
    $summary = GetParam('summary') ;

    $pvnum = 0 ;
    $publishtime = date('Y-m-d h:i:s',time()) ;

    $sql = "insert into article( `title` , `publisherid` , `content`  , `publishtime` , `pvnum` , `type` , `summary` )
      VALUES( '$title' , '$publisherid' , '$content'  , '$publishtime' , '$pvnum' , '$type' , '$summary' )" ;
    $articleid = MySqlInsert($sql) ;
    InsertTags($articleid , $tagids ) ;
    OutPut(true , null , null ) ;
}

function InsertTags($articleid,$tagids){
    $tags = json_decode($tagids);
    $id = $articleid;//必须 $pid 为引用会改变
    foreach ($tags as $tag) {
        MySqlInsert("INSERT INTO article_label(articleid,labelid) VALUES('$articleid','$tag')");
    }
}

























