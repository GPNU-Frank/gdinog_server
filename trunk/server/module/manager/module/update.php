<?php
/**
 * Created by PhpStorm.
 * User: Joooo
 * Date: 2016/10/19
 * Time: 21:22
 */

Privilege(null , null , modify , null );

//http://localhost/gdinoj/trunk/server/?action=manager.module.update&cookie=45d71d687311279d2fb242477c9cdc49&name=123&pid=0&remark=12345
function modify()
{
    $uid=GetCookie('uid', 20001);
    $name=GetParam('name', 20001);
    $pid=GetParam('pid', 20001);
    $remark=GetParam('remark');

    $sql="UPDATE m_modules SET name='$name', pid='$pid', remark='$remark'";
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