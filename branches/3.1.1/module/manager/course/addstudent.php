<?php
	/* 课程中添加学生 教师
	 * @CoursesID 课程ID
	 * @StudentID 学生ID
	 */

	Privilege(null,TeacherAddStudentToCourses,null);

	function TeacherAddStudentToCourses(){
		$coursesid = GetParam('coursesid') ;
		$studentid = GetParam('studentid') ;

		$sql = "insert into courses_student(courses_id,student_id) values($coursesid , $studentid)";
        $res = MySqlInsert($sql) ;
		if( $res != false  )
			OutPut(true);
		else
			OutPut(false , 20001);
	}
?>