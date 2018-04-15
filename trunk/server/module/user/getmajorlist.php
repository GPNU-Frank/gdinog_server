<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/2/15
 * Time: 12:55
 */

getmajorlist() ;

function getmajorlist()
{
    $academic_id = GetParam('academic_id' , 20001) ;
    $remark = GetParam('remark' , 20001) ;
    $sql = "select name , id from school WHERE academy_id = {$academic_id} AND remark = {$remark}" ;
    $res = MySqlQuerys($sql) ;
    if( $res != false )
        OutPut(true , "" , $res ) ;
    else
        OutPut(false) ;

}