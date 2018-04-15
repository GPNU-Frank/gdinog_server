<?php

	Privilege(null,TeacherAddCourse,null);
	
	/* 添加课程 教师
	 * @courseName 课程名
	 * @term 学期
	 * @Teacher_id(可选,默认自动添加当前登陆账号id) 
	 */

	function TeacherAddCourse(){
		$courseName = GetParam('coursename',20001);
		$grade = GetParam('grade',20001);
		$term = GetParam('term',20001);
		$teacher_id = GetParam('teacherid',null,GetCookie('uid'));
		
		$sql = "Insert into courses(courses_name,grade,term) values('$courseName' , $grade , $term)";
		$res = MySqlInsert($sql) ;
		if($res != false ){
			$CoursesID = $res ;
			$sql = "Insert into courses_teacher(courses_id,teacher_id) values( $CoursesID , $teacher_id)";
	        $res = MySqlInsert($sql) ;
			if( $res != false  )    OutPut(true,40000);
			else 	                OutPut(false,10011);

		}else{
			OutPut(false,40001);
		}
	}
?>