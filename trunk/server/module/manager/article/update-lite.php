<?php
/**
 * Created by PhpStorm.
 * User: Joooo
 * Date: 2016/10/10
 * Time: 11:59
 */

//http://localhost/gdinoj/trunk/server/?action=manager.article.update-lite&articleid=77&isTop=0&isQuality=0
Privilege(null , modify_lite , modify_lite , null ) ;

function modify_lite()
{
    $articleid = GetParam('articleid' , 20001 ) ;
    $isTop=GetParam('isTop');
    $isQuality=GetParam('isQuality');

    $sql="UPDATE article SET isTop='$isTop', isQuality='$isQuality' WHERE articleid='$articleid'";
    MySqlUpdate($sql);

    OutPut(true , null , null ) ;
}