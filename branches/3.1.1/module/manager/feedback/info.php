<?php

$fid = GetParam('fid');
$res = MySqlQuery("select title,content,type,uid,is_mark,is_solved,remark from feedback where fid = '$fid'");
$res['type'] = (int)$res['type'];
$res['is_mark'] = (int)$res['is_mark'];
$res['is_solved'] = (int)$res['is_solved'];

OutPut(true,'',$res);