<?php
/**
 * Created by PhpStorm.
 * User: Frank
 * Date: 2017/7/4
 * Time: 22:30
 */
Privilege(null,get_student_submit,null);
// 获得  学生提交的答案
//get_student_submit();
function get_student_submit(){
    // student_id
    //  quiz_id
    $uid = GetParam('uid',20001);
    $quiz_id = GetParam('quiz_id',20001);
    $sql = "select * from  quiz_submit where uid = $uid and quiz_id = $quiz_id";
    $attr = MySqlQuery($sql);
    //var_dump($attr);
    $res = json_decode($attr['submit_content'],true);
    //var_dump($res);
    $oi_problem  = json_decode($attr['solution_ids'],true);
    foreach($oi_problem as $key => $value){
        $value['protype'] = 3;
        $res[$key] = $value;
    }

    OutPutList(true,"",$res);

    //  获得题目的正确答案   TODO



}