<?php

	Privilege(null,TeacherBindClass,TeacherBindClass);

	/* 课程绑定班级 -教师 管理员 */
	
	function TeacherBindClass(){
		$course_id = GetParam('course_id',20001);
		$classlist = json_decode(GetParam('classlist',20001));
		if(count($classlist) == 0){
			OutPut(true,20001);
		}
		foreach ($classlist as $class_id) {
			MysqlInsert("Insert Into courses_class(`courses_id`,`class_id`) values('$course_id','$class_id')");
		}
		OutPut(true,40002);
	}

?>