<?php
/**
 * Created by PhpStorm.
 * User: ouyangjia
 * Date: 16/3/22
 * Time: 17:31
 */

//fid: 指定要修改的反馈信息的唯一标识
//is_mark:表示是否标注 0表示未标注 1表示已标注
//is_solved:表示是否解决 0表示未解决 1表示已解决

Privilege(null, null, UpdateFeedback);

function UpdateFeedback(){
    $fid = GetParam('fid', 20001);
    $is_mark = GetParam('is_mark', 20001);
    $is_solved = GetParam('is_solved', 20001);
    $sql ="update feedback set is_mark = {$is_mark}, is_solved = {$is_solved} where fid = '$fid' ";
    $result = MySqlUpdate($sql);
    if($result ){
        OutPut(true);
    }else {
        OutPut(false , 20001);
    }
}