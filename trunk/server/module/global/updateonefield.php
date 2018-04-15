<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/2/25
 * Time: 22:10
 */

Privilege( updateonefield , updateonefield , updateonefield ) ;

function updateonefield()
{
    $tablename = GetParam('tablename' , 20001 ) ;
    $fieldname = GetParam('fieldname' ,  20001 ) ;
    $value = GetParam('value' , 20001 ) ;
    $id_name = GetParam('id_name' , 20001 ) ;
    $id = GetParam('id' ,20001 ) ;

    $sql = "update ".$tablename." set ".$fieldname." = '$value' where ".$id_name." = ".$id ;
    $res = MySqlUpdate($sql) ;
    if( $res  ) OutPut(true) ;
    else        OutPut(false , 20001 ) ;
}