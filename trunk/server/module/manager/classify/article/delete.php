<?php
/**
 * Created by PhpStorm.
 * User: Joooo
 * Date: 2016/9/22
 * Time: 20:44
 */
//http://localhost/gdinoj/trunk/server/?action=manager.classify.article.delete&labelid=2&cookie=48a09e531c3a7e6a4724266057203bb3
Privilege(null , null ,deletearticlelabel , null ) ;

function deletearticlelabel()
{
    $labelid=GetParam('labelid', 20001);

    $sql="delete from label where labelid = '$labelid' ";
    $result = MySqlUpdate($sql) ;
    if($result)
    {
        OutPut(true);
    }else
    {
        OutPut(false , 20001);
    }
}