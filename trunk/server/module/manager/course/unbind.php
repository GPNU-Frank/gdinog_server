<?php
	/* 课程取消绑定班级
	 */
	Privilege(null,UnBind,null);
	function UnBind(){
		$course_id = GetParam('course_id',20001);
		$class_id = GetParam('class_id',20001);

		MysqlInsert("delete from courses_class where course_id = '$course_id' and class_id = '$class_id'");
		
		OutPut(true,40003);
	}

?>