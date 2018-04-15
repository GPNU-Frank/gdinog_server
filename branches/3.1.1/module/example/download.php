<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/2/20
 * Time: 0:48
 */

$filename = GetParam("filename" ,20001) ;
downloads("/var/www/html/gdinoj/server/example/".$filename) ;