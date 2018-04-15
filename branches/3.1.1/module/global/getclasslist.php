<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/2/19
 * Time: 10:23
 */

Privilege(getclasslist , getclasslist , getclasslist );

function getclasslist()
{
    $academy_id = GetParam("academy_id") ;
    $grade = GetParam("grade") ;

    $sqladd = Filter( array($academy_id , $grade) , array("=" , "=") , array("academy_id" ,"grade") ) ;
    $sql = "select class_id ,class_name from class ".$sqladd ;

    $res = MySqlQuerys($sql) ;
    OutPutList(true , "" , $res) ;
}

//http://114.215.99.34/gdinoj/server/test/upload.html

