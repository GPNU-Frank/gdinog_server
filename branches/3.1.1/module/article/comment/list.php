<?php
/**
 * Created by PhpStorm.
 * User: Joooo
 * Date: 2016/10/16
 * Time: 18:51
 */

commentiList();
//http://localhost/gdinoj/trunk/server/?action=article.comment.list&articleid=0&page=1&cookie=96769e50b7ef3aa79dc63fd63878850d
function commentiList()
{
    $uid=GetCookie('uid', 20001);
    $articleid=GetParam('articleid', 20001);

    global $pagesize;
    global $maxPage;
    $pagesize=10;

    $filter = Filter(array($articleid),array("="), array("textid"));
    $res=Page("comment","" ,$filter, "publisherid, content, commenttime, agreenum", "ASC");

    $result=MySqlQuery("SELECT COUNT(*) AS num FROM comment WHERE textid=$articleid");
    $maxPage=$result['num'];

    $num=count($res);

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