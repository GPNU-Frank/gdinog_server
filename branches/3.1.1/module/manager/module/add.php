<?php
/**
 * Created by PhpStorm.
 * User: Joooo
 * Date: 2016/10/19
 * Time: 21:22
 */

Privilege(null , null , add , null ) ;
//http://localhost/gdinoj/trunk/server/?action=manager.module.add&cookie=45d71d687311279d2fb242477c9cdc49&name=1&pid=1&remark=123
function add()
{
    $uid=GetCookie('uid', 20001);
    $name=GetParam('name', 20001);
    $pid=GetParam('pid', 20001);
    $remark=GetParam('remark');

    $sql="INSERT m_modules (name, pid, remark) VALUES('$name', '$pid', '$remark')";
    $res=MySqlUpdate($sql);

    if( $res )
    {
        OutPut(true) ;
    }
    else
    {
        OutPut(false,20001) ;
    }
}