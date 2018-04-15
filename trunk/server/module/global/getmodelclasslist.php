<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/2/19
 * Time: 10:23
 */

getmodelclasslist();

function getmodelclasslist()
{
    $sql = "select * from label where type = -1" ;
    $res = MySqlQuerys($sql) ;
    OutPutList(true , "" , $res) ;
}



//http://localhost/gdinoj/trunk/server/?action=global.getmodelclasslist