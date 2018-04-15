<?php
//教师 管理员 获取某课程中所有学生

Privilege(null,GetCoursesStudent,GetCoursesStudent);

function GetCoursesStudent(){
	$courseid = GetParam('courseid');

	$sqladd = "uid in( select student_id from ( Select student_id from courses_student where courses_id = '$courseid')  )";
	$filter = Filter(array($sqladd) , array("+") , array(null) ) ;
	$arr = Page("users" , "" , $filter , " 'uid','nick' " );

	OutPut(true,"",$arr);
}
?>