<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/5/10
 * Time: 19:05
 */

Privilege(null , null  , updateproblemclassify , null ) ;

function updateproblemclassify()
{
    $tagid = GetParam('tagid' , 20001 ) ;
    $tagname = GetParam('tagname' , 20001 ) ;
    $sql = "update tags set `tagname` = '$tagname' where `tagid` = $tagid " ;
    MySqlUpdate($sql) ;
    OutPut(true , null , null ) ;
}



