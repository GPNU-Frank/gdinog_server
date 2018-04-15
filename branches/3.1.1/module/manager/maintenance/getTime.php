<?php
/**
 * Created by PhpStorm.
 * User: Joooo
 * Date: 2016/10/23
 * Time: 11:25
 */
//http://localhost/gdinoj/trunk/server/?action=manager.maintenance.getTime&cookie=d3bbdc336627432b2e0c74d2456c3a3d
getTime();
function getTime()
{
    $uid=GetCookie('uid', 20001);
    $sql="SELECT * FROM maintenance";
    $res=MySqlQuery($sql);
    if($res)
    {
        OutPut(true, "", $res);
    }
    else
    {
        OutPut(false, null, null);
    }
}