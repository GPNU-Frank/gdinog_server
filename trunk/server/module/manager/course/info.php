<?php

	/*
	 * 获取课程详细信息 学生 教师 管理员
	 */

	Privilege(GetInfo,GetInfo,GetInfo);

	function GetInfo(){
		$course_id = GetParam('course_id');

		$courses_row = MySqlQuery("select courses_id,courses_name,grade,term,open from courses where courses_id = '$course_id'");

		$courses_row['classlist'] = MySqlQuerys("select class_id,class_name,grade from class where class_id in (select class_id from courses_class where courses_id = '$course_id')");

		OutPut(true,null,$courses_row);
	}
		
?>


