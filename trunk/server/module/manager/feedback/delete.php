<?php
/**
 * Created by PhpStorm.
 * User: ouyangjia
 * Date: 16/3/22
 * Time: 17:21
 */

Privilege(null, null, DeleteFeedback);

function DeleteFeedback(){
    $fid = GetParam('fid', 20001);
    $sql ="delete from feedback where fid = '$fid' ";
    $result = MySqlUpdate($sql);
    if($result ){
        OutPut(true);
    }else {
        OutPut(false , 20001);
    }
}