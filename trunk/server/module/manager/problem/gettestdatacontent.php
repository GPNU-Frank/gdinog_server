<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/2/19
 * Time: 9:38
 */

//var_dump($_FILES) ;

require './plugins/upload/UploadUtil.php';

Privilege(null , gettestdataList ,  gettestdataList  ) ;

function gettestdataList()
{
    $pid = GetParam("pid" , 20001);
    $filename = GetParam("filename",20001);

    $path = $GLOBALS['OJ_DATA']."/$pid/".$filename;
    $content = file_get_contents($path);
    if($content === false){
        OutPut(false,50004);
    }else{
     OutPut(true,null,array('fileContent'=> $content));
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





