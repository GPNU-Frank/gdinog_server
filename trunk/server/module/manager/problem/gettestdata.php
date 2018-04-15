<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/2/19
 * Time: 9:38
 */

//var_dump($_FILES) ;

require './plugins/upload/UploadUtil.php';

Privilege(null , gettestdata ,  gettestdata  ) ;

function gettestdata()
{
    $pid = GetParam("pid" , 20001) ;


    $upload = new Upload();
    $basedir = $GLOBALS['OJ_DATA']."/$pid/";
    $result =  $upload -> GetTestFiles(array("in" , "out" , "txt") , $basedir );
    if( $result['success'] != false )
    {
        $sql = "update problem set hastestdata = 1" ;
        $res = MySqlUpdate($sql) ;
        OutPut(true) ;
    }
    else
        OutPut(false , 50001 ) ;
}





