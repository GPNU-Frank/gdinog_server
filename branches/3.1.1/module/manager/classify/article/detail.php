<?php
/**
 * Created by PhpStorm.
 * User: Joooo
 * Date: 2016/12/6
 * Time: 23:40
 */


detail();
function detail()
{
    $labelid=GetParam('labelid', 20001);
    $sql="select * from label where labelid='$labelid'";
    $res=MySqlQuery($sql);
    OutPut(true, '', $res);
}