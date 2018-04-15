<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/2/25
 * Time: 18:20
 */

Privilege(null , update , null ) ;

function update()
{
    $exam_id = GetParam("exam_id" , 20001 ) ;
    $exam_name = GetParam('exam_name',20001) ;
    $courses_id = GetParam('courses_id',20001) ;
    $stop_time = GetParam('stop_time',20001) ;
    $create_time = GetParam('create_time' , 20001 ) ;
    $problemarr = GetParam('problemarr',20001);

    $sql = "update courses_exam  set exam_name = '$exam_name' , courses_id = {$courses_id} , stop_time = '$stop_time'
        , create_time = '$create_time' where exam_id = {$exam_id}" ;
    $res1 = MySqlUpdate($sql) ;

    if( $res1 == false  )
    {
        OutPut(false ,20001);
    }
    else
    {
        $sql = "delete  from courses_exam_problem where exam_id = {$exam_id}" ;
        $res2 = MySqlUpdate($sql) ;
        if( $res2 == false )
        {
            OutPut(false ,20001);
        }
        else
        {
            $problemarr = json_decode($problemarr);
            for($i = 0 ; $i < count($problemarr);$i++){
                $sql = "Insert into courses_exam_problem(exam_id,problem_id) values($exam_id,$problemarr[$i])";

                MySqlInsert($sql) ;
            }
            OutPut(true);

        }
    }




}