<?php
	/* 课程中添加教师 教师 管理员
	 * @CoursesID 课程ID
	 * @$teacherid 教师ID
	 */

	Privilege(null,AddTeacherToCourses,AddTeacherToCourses);

	function AddTeacherToCourses(){
		$coursesid = GetParam('coursesid') ;
		$teacherid = GetParam('teacherid') ;

		$sql = "INSERT INTO courses_teacher(courses_id , teacher_id) VALUES ('$coursesid','$teacherid')" ;
		$res = MySqlInsert($sql) ;
		if( $res != false  )	OutPut(true);
		else
				OutPut(false , 20001);
	}
?>