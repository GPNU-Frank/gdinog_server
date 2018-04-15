<?php
/**
 * Created by PhpStorm.
 * User: Frank
 * Date: 2017/7/2
 * Time: 21:38
 */

Privilege(null,changeQuizstatus,null);

//   只有两种改变状态  未开始(0) -> 开始 (1)   进行中(1)-> 结束(2)
 function changeQuizstatus(){
     $quiz_id = GetParam('quiz_id',20001);
     $status  = GetParam('status',20001); // 表示 要 转换到哪一个状态

     $sql = "select time_length from courses_quiz where quiz_id = {$quiz_id}";
     $res = MySqlQuery($sql);
     $time_length = $res['time_length'];
     if($status == 1){
            // 开始考试
         $create_time = date("Y-m-d H:i:s");
         $stop_time = date("Y-m-d H:i:s",strtotime($time_length));
         //echo $start_time . '<br/>' . $end_time;
         // 存入 表
         $sql = "update courses_quiz set create_time = '$create_time' , stop_time = '$stop_time' , status = {$status}   where quiz_id ={$quiz_id}";
         MySqlUpdate($sql);
         OutPut(true,'','');
     }else if($status == 2){   // 提前结束
        $stop_time = date("Y-m-d H:i:s");
         $sql = "update courses_quiz set  stop_time = '$stop_time' , status = {$status}   where quiz_id ={$quiz_id}";
         MySqlUpdate($sql);
         OutPut(true,'','');
     }else{
         OutPut(false,'参数错误','');
     }
 }