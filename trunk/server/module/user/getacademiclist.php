<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/2/15
 * Time: 11:04
 */

getacademiclist() ;

function getacademiclist()
{
    $school_id = GetParam('school_id' , 20001) ;
    $sql = "select name , id from school WHERE school_id = {$school_id} AND academy_id = -1" ;
    $res = MySqlQuerys($sql) ;
    if( $res != false )
        OutPut(true ,"" , $res ) ;
    else
        OutPut(false , 20000 ) ;
}