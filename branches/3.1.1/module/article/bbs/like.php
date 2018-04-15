<?php
/**
 * Created by PhpStorm.
 * User: chx
 * Date: 16/8/7
 * Time: 上午9:49
 */

Privilege(like , like , like , like ) ;

function like()
{
    $commentType = GetParam('commentType' , 20001 ) ; //1表示对Article点赞,2表示对评论点赞
    $textId = GetParam('textId' , 20001 ) ;             //被评论文章的ID

    //对Article点赞
    if( $commentType == 1 ) {
        $sql = "update article set agreenum = agreenum + 1 where articleid = {$textId}" ;
        MySqlInsert($sql) ;
        OutPut(true , null , null ) ;

    } else {  //对评论点赞
        $sql = "update comment set agreenum = agreenum + 1 where commentid = {$textId}" ;
        MySqlInsert($sql) ;
        OutPut(true , null , null ) ;
    }

}