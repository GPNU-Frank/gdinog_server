<?php
/**
 * Created by PhpStorm.
 * User: Joooo
 * Date: 2017/5/2
 * Time: 23:17
 */

$solution_id=GetParam('solution_id');
$result=GetParam('result');

$sql="update solution set result='$result' where solution_id=$solution_id";
MySqlUpdate($sql);

$sql="select in_data, contest_id, uid, problem_id from solution where solution_id=$solution_id";
$res=MySqlQuery($sql);
$in_data=$res['in_data'];
$contest_id=$res['contest_id'];
$uid=$res['uid'];
$problem_id=$res['problem_id'];

setContestFinish($contest_id, $uid, $problem_id, $result, $in_data);