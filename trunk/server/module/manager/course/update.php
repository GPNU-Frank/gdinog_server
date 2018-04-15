<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/2/24
 * Time: 21:27
 */

Privilege(null , updatecourse , null ) ;

function updatecourse()
{
    $course_id = GetParam('course_id',20001);
    $course_name = GetParam('course_name' , 20001) ;
    $grade = GetParam('grade' , 20001 ) ;
    $term = GetParam('term' , 20001 ) ;
    $open = GetParam('open' , 20001 ) ;
    $classlist = json_decode(GetParam('classlist',20001));

    $sql = "update courses set courses_name = '$course_name' , grade = '$grade' , term = {$term} , open = {$open} WHERE courses_id = {$course_id} " ;
    MySqlUpdate($sql) ;

    $sql = "delete from courses_class where courses_id = {$course_id}" ;
    MySqlUpdate($sql) ;

    if(count($classlist) == 0){
        OutPut(true,20001);
    }
    foreach ($classlist as $class_id) {
        MysqlInsert("Insert Into courses_class(`courses_id`,`class_id`) values('$course_id','$class_id')");
    }

    OutPut(true ) ;

}