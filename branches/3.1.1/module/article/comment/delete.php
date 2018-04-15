<?php
/**
 * Created by PhpStorm.
 * User: Joooo
 * Date: 2016/10/16
 * Time: 23:26
 */

delComment();

//http://localhost/gdinoj/trunk/server/?action=article.bbs.delComment&cookie=45e3bca91fbce084c32a53b2f4fd095c&commentid=4
function delComment()
{
    $uid=GetCookie('uid', 20001);
    $identity=GetCookie('identity', 20001);
    $commentid=GetParam('commentid', 20001);

    $sql="SELECT publisherid FROM comment WHERE commentid=$commentid";
    $res=MySqlQuery($sql);

    //只有管理员和评论者可以删除评论
    if($identity==3 || $uid==$res['publisherid'])
    {
        $sql="DELETE FROM comment WHERE commentid='$commentid'";
        MySqlUpdate($sql);
        OutPut(true , null , null ) ;
    }
    else
    {
        OutPut(false , null , null ) ;
    }
}