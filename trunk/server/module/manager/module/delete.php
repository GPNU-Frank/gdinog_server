<?php
/**
 * Created by PhpStorm.
 * User: Joooo
 * Date: 2016/10/19
 * Time: 21:22
 */

Privilege(null , null , delete , null );

//http://localhost/gdinoj/trunk/server/?action=manager.module.delete&cookie=45d71d687311279d2fb242477c9cdc49&id=2
function delete()
{
    $uid=GetCookie('uid', 20001);
    $id=GetParam('id', 20001);

    $sql="DELETE FROM m_modules WHERE id='$id'";
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