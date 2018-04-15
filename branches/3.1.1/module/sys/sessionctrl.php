<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/5/13
 * Time: 21:22
 */

//用于session内容的控制
session_start() ;

//或许标记
$tag = GetParam('tag',20001) ;

$baseurl = "" ;

//保存于题目
if( $tag == 'problems' )
    $baseurl = "http://114.215.99.34/gdinoj/server/upload/pic/problems/" ;
else if( $tag == 'bbs' )    //讨论区
    $baseurl = "http://114.215.99.34/gdinoj/server/upload/pic/bbs/" ;
else if( $tag == 'knowledge' )    //题解区
    $baseurl = "http://114.215.99.34/gdinoj/server/upload/pic/knowledge/" ;
else if( $tag == 'users' )      //用户图片
    $baseurl = "http://114.215.99.34/gdinoj/server/upload/pic/users/" ;
else
    $baseurl = "http://114.215.99.34/gdinoj/server/upload/pic/else/" ;

$_SESSION['baseurl'] = $baseurl ;






















