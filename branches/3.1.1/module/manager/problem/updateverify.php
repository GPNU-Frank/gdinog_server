<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/2/19
 * Time: 9:38
 */

//var_dump($_FILES) ;

Privilege(null , null ,  updateVerify  ) ;

function updateVerify()
{
    $pid = GetParam("pid" , 20001);
    $verify = GetParam("verify",20001);

    $res = MySqlUpdate("UPDATE problem set is_verify = $verify");
    if($res){
         output(true);
    }else{
        output(false);
    }
   
    // $pid = GetParam("pid" , 20001) ;


    // $upload = new Upload();
    // $basedir = $GLOBALS['OJ_DATA']."/$pid/";
    // $result =  $upload -> GetTestFiles(array("in" , "out" , "txt") , $basedir );
    // if( $result['success'] != false )
    // {
    //     $sql = "update problem set hastestdata = 1" ;
    //     $res = MySqlUpdate($sql) ;
    //     OutPut(true) ;
    // }
    // else
    //     OutPut(false , 50001 ) ;
}





