<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/3/31
 * Time: 16:50
 */

classrank();

function classrank()
{
    $class_id = GetParam('class_id',20001) ;
    $course_id = GetParam('course_id' , 20001) ;

    $sql = "select count(DISTINCT problem_id) as problemcnt from courses_exam_problem where exam_id in(
            SELECT exam_id from courses_exam where courses_id = {$course_id}
        )";
    $t = MySqlQuery($sql);
    $problemcnt = $t['problemcnt'] ;

    $sql = "select count(DISTINCT exam_id) as examcnt from courses_exam where courses_id = {$course_id}" ;
    $t = MySqlQuery($sql) ;
    $examcnt = $t['examcnt'] ;

//    $sql = "SELECT DISTINCT uid , code , nick , class_name  ,
//              exam_student_all_solved({$course_id},uid) as pass1 , exam_student_all_submit({$course_id},uid) as submit1
//
//              from users , class
//                 where users.class_id = {$class_id} AND  class.class_id = {$class_id}
//	              ORDER BY pass1 + pass1/submit1 desc" ;

    // 查出  该课程的所有学生
    $sql = "select distinct uid , nick , user_id  from users where  class_id = '$class_id'";

    $users = MySqlQuerys($sql) ;
    // 查出 class_name
    $sql = "select class_name from class where class_id = {$class_id}";
    $class_name = MySqlQuery($sql) ;
    $res = array();
    $count = count($users);
//    uid , code , nick , class_name  ,
//              exam_student_all_solved({$course_id},uid) as pass1 , exam_student_all_submit({$course_id},uid) as submit1
    for($i = 0  ; $i < $count ; $i++){  // 查询每个学生课程的完成情况
        $uid = $users[$i]['uid'];
        $nickname=$users[$i]['nick'];
        $code = $users[$i]['user_id'];
        $sql = "select count(*) as submit1 from solution where uid = '$uid' and exam_id in(
            SELECT exam_id from courses_exam where courses_id = {$course_id} )";
        $submit_num = MySqlQuery($sql);
        $res[$i]['uid'] = $uid;
        $res[$i]['nick'] = $nickname;
        $res[$i]['submit1']=$submit_num['submit1'];
        $res[$i]['code'] = $code;
        $res[$i]['class_name'] = $class_name['class_name'];
        $sql = "select count(*) as pass1 from solution where uid = '$uid' and exam_id in(
            SELECT exam_id from courses_exam where courses_id = {$course_id} ) and result = 4";
        $accept_num = MySqlQuery($sql);
        $res[$i]['pass1']=$accept_num['pass1'];
    }
    // 对 结果排序  通过数优先 相同比较提交数  较小者排前
    $res = commit_sort($res,'pass1',SORT_DESC,'submit1',SORT_ASC);
    global $maxPage;
    $list = array(
        'maxsize' => $maxPage,
        'list' => $res,
        'problemnum' => $problemcnt ,
        'examcnt' => $examcnt ,
    );
    OutPut(true ,"" ,$list);

}

function commit_sort($arrays,$sort_key1, $order1, $sort_key2, $order2)
{
    $type=SORT_REGULAR;
    if(is_array($arrays))
    {
        foreach ($arrays as $array)
        {
            if(is_array($array))
            {
                $key_arrays1[] = $array["$sort_key1"];
                $key_arrays2[] = $array["$sort_key2"];
            }
            else
            {
                return false;
            }
        }
    }else
    {
        return false;
    }
    array_multisort($key_arrays1,$order1, $key_arrays2, $order2,  $arrays);
    return $arrays;
}