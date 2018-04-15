<?php
/**
 * Created by PhpStorm.
 * User: Frank
 * Date: 2017/7/5
 * Time: 20:39
 */

// 统计得分
Privilege(null,calculate_score,null);

//calculate_score();
function calculate_score(){
    $quiz_id = GetParam('quiz_id',20001);
    $uid = GetParam('uid',20001);
    // 获得 教师评改的题目的得分
    $scores = GetParam('scores',20001);
    $scores = json_decode($scores,true);
    // 获得 获取编程题的得分
    $sql = "select * from quiz_submit where quiz_id = $quiz_id and uid = $uid";
    $arr = MySqlQuery($sql);
    var_dump($arr);
    $solutions = json_decode($arr['solution_ids'],true);
    //  通过 solution_id 查询得分情况
    foreach($solutions as $key => $val){
        $solution_id = $val['solution_id'];
        $sql = "select pass_rate from solution where solution_id = $solution_id ";
        $rate = MySqlQuery($sql);
        $score = ceil($val['point'] * $rate['pass_rate']);

        $scores[$key] = $score;
    }
    // 获取  客观题的得分
    $obj_scores = json_decode($arr['each_score'],true);
    var_dump($obj_scores);
    
    foreach($obj_scores as $key => $val){
        $scores[$key] = $val;
    }
    // 计算总得分
    $grade = 0;
    foreach ($scores as $val){
        $grade+=$val;
    }
    $scores = json_encode($scores);
    $sql = "update quiz_submit set each_score = '$scores' , total_score = $grade where quiz_id = $quiz_id and uid = $uid";
    MySqlUpdate($sql);
    OutPut(true,'');
}