<?php
/**
 * Created by PhpStorm.
 * User: Joooo
 * Date: 2016/10/23
 * Time: 11:25
 */

Privilege(null , null , setTime , null ) ;

//http://localhost/gdinoj/trunk/server/?action=manager.maintenance.setTime&cookie=d3bbdc336627432b2e0c74d2456c3a3d&start=2016-10-23+13:15:00&end=2016-10-23+14:15:00
function setTime()
{
    $uid=GetCookie('uid', 20001);
    $start=GetParam('start', 20001);
    $end=GetParam('end', 20001);

    $sql="SELECT * FROM maintenance";
    $res=MySqlQuery($sql);
    if($res)
    {
        MySqlUpdate("DELETE FROM maintenance");
        $sql="INSERT maintenance VALUES('$start', '$end')";
        MySqlUpdate($sql);
    }
    else
    {
        $sql="INSERT maintenance VALUES('$start', '$end')";
        MySqlUpdate($sql);
    }
    OutPut(true, null, null);
}