<?php

	/*
	 *  课程中添加学生（班级) 教师
	 * @CoursesID 课程ID
	 * @$classid 班级ID
	 */

	Privilege(null,TeacherAddClassToCourses,null);


	function TeacherAddClassToCourses(){
		$coursesid = GetParam('coursesid') ;
		$classid = GetParam('classid') ;

		$sql = "insert into courses_class(`courses_id`,`class_id`) values('".$coursesid."','".$classid."')";
		$res = MySqlInsert($sql) ;
		if( $res != false  ){
			OutPut(ture);
		}else{
			OutPut(false , 20001);
		}
	}
?>