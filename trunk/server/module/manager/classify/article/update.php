<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/9/19
 * Time: 19:57
 */

//http://localhost/gdinoj/trunk/server/?action=manager.classify.update&name=aatest&pid=6&description=aa&type=2&iconUrl=adfasd&bannerUrl=adsf&cookie=48a09e531c3a7e6a4724266057203bb3&labelid=9
//
Privilege(null , null , updateClassify , null ) ;

function updateClassify() {
    $labelid = GetParam('labelid',20001) ;
    $name=GetParam('name',20001) ;
    $pid=GetParam('pid',20001) ;
    $discription=GetParam('description',20001) ;
    $type=GetParam('type',20001) ;
    $iconUrl=GetParam('iconUrl',20001) ;
    $bannerUrl = GetParam('bannerUrl', 20001) ;


    $sql = "update label set name = '$name' , pid = '$pid' , discription = '$discription' , type = '$type' , iconUrl = '$iconUrl' , bannerUrl = '$bannerUrl' WHERE labelid = '$labelid'" ;
    $res = MySqlUpdate($sql) ;

    if( $res )
        OutPut(true , null , null ) ;
    else
        OutPut(false ,null , null ) ;

}