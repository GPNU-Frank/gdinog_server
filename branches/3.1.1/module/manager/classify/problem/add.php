<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/5/10
 * Time: 19:00
 */


Privilege(null , null , addproblemclassify , null ) ;


function addproblemclassify()
{
    $pid = GetParam('pid',20001) ;
    $tagname = GetParam('tagname' ,20001 ) ;

    $sql = "insert into tags(`tagname` , `pid`) values('$tagname' , '$pid')" ;
    MySqlInsert($sql) ;

    OutPut(true , null , null ) ;
}







