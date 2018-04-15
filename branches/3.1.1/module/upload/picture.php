<?php
/**
 * Created by PhpStorm.
 * User: chx
 * Date: 16/8/7
 * Time: 上午1:20
 */

require './plugins/upload/UploadUtil.php';

$upload = new Upload();
$filepath = "upload/pic/users/" ;
$result =  $upload -> GetUploadFile( array("jpg" , "png" , "jpeg" , "gif" , "bmp" )  ,  $filepath  ) ;

OutPut(true , "" , "http://114.215.99.34/gdinoj/server/" . $result['path'] ) ;