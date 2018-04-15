<?php
/**
 * Created by PhpStorm.
 * User: Joooo
 * Date: 2017/1/20
 * Time: 23:08
 */

Privilege(null,AddStudent,AddStudent);

function AddStudent()
{
    $class_code=GetParam('class_code', 20001);
    $student_id=GetParam('student_id', 20001);

    $sql="update oj_data.users set class='$class_code' where user_id=$student_id";
    MySqlUpdate($sql);
    OutPut(true, "添加学生成功");
}