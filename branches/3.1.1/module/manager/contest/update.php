<?php
/**
 * Created by PhpStorm.
 * User: ouyangjia
 * Date: 16/3/21
 * Time: 15:55
 * 修改竞赛, 参数列表
 * begin=2016-03-19 18:35:19
 * contest_id=1026
 * cookie=3435467fc8f7885e0bd62e33e46f7d57
 * end=2016-03-24 18:30:00
 * password=123456789
 * problem_list=["1003","1006","1010"]
 * title=LITTLE TEST2
 * type=1
 */

Privilege(null, null, UpdateContest);

function UpdateContest()
{
    $contest_id = GetParam('contest_id', 20001);
    $begin = GetParam('begin', 20001);
    $end = GetParam('end', 20001);
    $password = GetParam('password', 20001);
    $problem_list = GetParam('problem_list', 20001);
    $title = GetParam('title', 20001);
    $type = GetParam('type', 20001);

    $updateContestSql = "update contest set title = '$title', begin = '$begin', end = '$end', password = '$password', type = {$type} where contest_id = {$contest_id}";
    $updateResult = MySqlUpdate($updateContestSql);
    if($updateResult == false)
    {
        OutPut(false ,20001);
    }
    else
    {
        $deleteContestProblemSql = "delete from contest_problem where contest_id = {$contest_id}";
        $deleteResult = MySqlUpdate($deleteContestProblemSql);
        if( $deleteResult == false )
        {
            OutPut(false ,20001);
        }
        else
        {
            $problemList = json_decode($problem_list);
            for($i = 0 ; $i < count($problemList); $i++)
            {
                $sql = "Insert into contest_problem(contest_id, problem_id) values($contest_id, $problemList[$i])";
                MySqlInsert($sql) ;
            }
            OutPut(true);
        }
    }
}