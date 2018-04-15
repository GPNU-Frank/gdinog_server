<?php
/**
 * Created by PhpStorm.
 * User: Frank
 * Date: 2017/7/18
 * Time: 22:02
 */


Privilege(null,ListQuiz(),null);
// 添加考试
function ListQuiz(){
    $coursesid = GetParam('coursesid',20001) ;  // 课程id
    $sql = "select * from courses_quiz  where courses_id=$coursesid";
    $res = MySqlQuerys($sql);
    OutPutList(true,"",$res);
}