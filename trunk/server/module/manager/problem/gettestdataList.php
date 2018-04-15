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
    //$content = file_get_contents($GLOBALS['OJ_DATA']."/$pid/".)
    $path = $GLOBALS['OJ_DATA']."/$pid/";
    if(!is_dir($path)){
        OutPut(false,50004);
    }
    $fileList = scandir($path);
    $fileArr =  array();
    for($i = 0 ; $i < count($fileList) ; $i++){
        if(strpos($fileList[$i],'test') !== false){
            array_push($fileArr,$fileList[$i]);
        }
        if(strpos($fileList[$i],'sample') !== false){
            array_push($fileArr,$fileList[$i]);
        }
    }
    OutPut(true,null,array('fileList'=> $fileArr));

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





