<?php
/**
 * Created by PhpStorm.
 * User: Joooo
 * Date: 2016/10/16
 * Time: 18:51
 */

commentiList();
//http://localhost/gdinoj/trunk/server/?action=article.commentList&articleid=0&page=1&cookie=805b4312bba7ef47a02865610ac7d90a
function commentiList()
{
    $uid=GetCookie('uid', 20001);
    $articleid=GetParam('articleid', 20001);
    $page=GetParam('page', 20001);

    $min=($page-1)*10;

    $sql="SELECT publisherid, content, commenttime, agreenum FROM comment WHERE textid='$articleid' LIMIT $min, 10";
    $res=MySqlQuerys($sql);

    $num=count($res);

    $sql="SELECT COUNT(*) AS num FROM comment WHERE textid=$articleid";
    $result=MySqlQuery($sql);
    global $maxPage;
    $maxPage=$result['num'];

    for ($i=0; $i<$num; $i++)
    {
        $uid=$res[$i]['publisherid'];
        $sql="SELECT nick , avatarUrl FROM users WHERE uid='$uid'";
        $result=MySqlQuery($sql);

        $res[$i]['name']=$result['nick'];
        $res[$i]['avatarUrl']=$result['avatarUrl'];
    }
    OutPutList(true, "", $res);
}