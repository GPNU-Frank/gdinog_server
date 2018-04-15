<?php
/**
 * Created by PhpStorm.
 * User: Joooo
 * Date: 2016/10/19
 * Time: 21:22
 */

Privilege(null , null , moduleList , null );
//http://localhost/gdinoj/trunk/server/?action=manager.module.list&cookie=45d71d687311279d2fb242477c9cdc49&pid=0

function moduleList()
{
    $uid=GetCookie('uid', 20001);
    $pid=GetParam('pid', 20001);

    $sql="SELECT * FROM m_modules WHERE pid='$pid'";
    $res=MySqlQuery($sql);

    OutPutList(true, "", $res);
}