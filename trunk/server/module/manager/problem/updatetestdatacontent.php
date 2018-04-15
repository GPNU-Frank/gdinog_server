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
    $content = GetParam("content",20001);

    $path = $GLOBALS['OJ_DATA']."/$pid/".$filename;

    $fp = fopen ( $path, "w" );
    if($fp){
        $input = str_replace(  "\\n" , "\r\n" , $content ) ;
        fwrite($fp, $input);
        //fputs ( $fp, preg_replace ( "(\r\n)", "\n", $input ) );
        fclose ( $fp );
        OutPut(true,50005);
    }else{
        OutPut(false,50004);
        // echo "Error while opening".$basedir . "/$filename ,try [chgrp -R www-data $OJ_DATA] and [chmod -R 771 $OJ_DATA ] ";
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





