<?php
/**
 * Created by PhpStorm.
 * User: Joooo
 * Date: 2017/5/5
 * Time: 23:04
 */
$solution_id=GetParam('solution_id');
$result=GetParam('result');
$time=GetParam('time');
$memory=GetParam('memory');
$runtimeinfo=GetParam('runtimeinfo');
$compileinfo=GetParam('compileinfo');
$date = strftime("%Y-%m-%d %H:%M",time());
$time = date('Y-m-d H:i:s',time()) ;

//更新数据库result,代码判断结果.....
$sql="update solution set result='$result', time=Now(), memory=$memory, judgetime=Now(), judger='LOCAL' where solution_id=$solution_id";
MySqlUpdate($sql);

if($result==2 || $result==6 || $result==10){
    $sql="insert into runtimeinfo(solution_id, error) values($solution_id, '$runtimeinfo')";
    MySqlUpdate($sql);
}
if($result==11){
    $sql="insert into compileinfo(solution_id, error) values($solution_id, '$compileinfo')";
    MySqlUpdate($sql);
}

//若是竞赛的提交。则更新contest_finsih
$sql="select contest_id, uid, problem_id, in_date from solution where solution_id=$solution_id";
$res=MySqlQuery($sql);
$contest_id=$res['contest_id'];
$uid=$res['uid'];
$problem_id=$res['problem_id'];
$in_date=$res['in_date'];

if($contest_id!=null){
    setContestFinish($contest_id, $uid, $problem_id, $result, $in_date);
}

OutPut(true);
