<?php
/**
 * Created by PhpStorm.
 * User: Joooo
 * Date: 2017/5/10
 * Time: 22:41
 */
error_reporting(E_ALL || ~E_NOTICE || ~E_WARNING);
include '/var/www/html/gdinoj/server/framework/include/config.php';
include '/var/www/html/gdinoj/server/framework/include/conn.php';
include '/var/www/html/gdinoj/server/framework/include/util.php';
include '/var/www/html/gdinoj/server/framework/include/msg.php';
//ignore_user_abort(true);
//set_time_limit(0);
//$interval=60*2;

//$sql="select solution_id from solution ORDER BY solution_id desc LIMIT 0, 1";
//$res=MySqlQuery($sql);
//$solution_id=$res['solution_id'];
//69074   70186
$solution_id=69074;
$array=array();
$j=0;

//do{
    $run = include 'config.php';
    if(!$run) die('process abort');

    //ToDo
    $sql="select solution_id, contest_id, uid, problem_id, result, in_date from solution where solution_id>=$solution_id   and contest_id= 1092";
    $res=MySqlQuerys($sql);
    $count=count($res);
    echo $count , "<br/>" ;
    for ($i=0; $i<$count; $i++){
        $contest_id=$res[$i]['contest_id'];
        $uid=$res[$i]['uid'];
        $problem_id=$res[$i]['problem_id'];
        $result=$res[$i]['result'];
        $in_date=$res[$i]['in_date'];
        if($result==2){//题目尚未判断，，加入数组，稍后继续操作
            $array[$j]['solution']=$solution_id;
            $j++;
            continue;
        }
        echo $contest_id,"  ", $uid,"  ", $problem_id,"  ", $result,"  ", $in_date, '<br/>' ;
        setContestFinish($contest_id, $uid, $problem_id, $result, $in_date);
        $solution_id=$res[$i]['solution_id'];
    }
    $num=array();
    //处理之前的未判断的题目
    for($i=0; $i<$j; $i++){
        $sid=$array[$i]['solution_id'];
        $sql="select solution_id, contest_id, uid, problem_id, result, in_date from solution where solution_id=$sid";
        $res=MySqlQuery($sql);
        $contest_id=$res['contest_id'];
        $uid=$res['uid'];
        $problem_id=$res['problem_id'];
        $result=$res['result'];
        $in_date=$res['in_date'];
        if($result==2){
            continue;
        }
        setContestFinish($contest_id, $uid, $problem_id, $result, $in_date);
        $num.add($i);
    }
    for($i=0; $i<count($num); $i++){
        array_remove($array, $num[$i]);
    }
    $j=count($array);
//    sleep($interval);// 等待2分钟
//}
//while(true);

function array_remove(&$arr, $offset)
{
    array_splice($arr, $offset, 1);
}