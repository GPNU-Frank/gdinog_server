<?php
/**
 * Created by PhpStorm.
 * User: Joooo
 * Date: 2016/10/19
 * Time: 20:33
 */
Privilege(Update_lite,Update_lite,Update_lite,null);

//http://localhost/gdinoj/trunk/server/?action=user.update-lite&cookie=45d71d687311279d2fb242477c9cdc49&avatarUrl=123
function Update_lite()
{
    $uid=GetCookie('uid', 20001);
    $avatarUrl=GetParam('avatarUrl', 20001);

    $sql="UPDATE users SET avatarUrl='$avatarUrl' WHERE uid='$uid'";
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