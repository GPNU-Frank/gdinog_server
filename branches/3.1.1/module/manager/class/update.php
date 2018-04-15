<?php
/**
 * Created by PhpStorm.
 * User: shanxuan
 * Date: 16-2-20
 * Time: 下午3:42
 */

Privilege( null , updateinfo , updateinfo) ;

function updateinfo()
{
    $class_id = GetParam("class_id") ;
    $class_name = GetParam("class_name") ;
    $grade = GetParam("grade") ;
    $academy_id = GetParam("academy_id") ;

    $sql = "update class set class_name = '$class_name' , grade = {$grade} , academy_id = '$academy_id'
        where class_id = '$class_id' " ;
    MySqlUpdate($sql) ;
    OutPut(true ) ;
}