<?php
//学生-课程列表
//教师-课程管理-课程列表
Privilege(StudentGetCourseList,TeacherGetCourseList,StudentGetCourseList);

function StudentGetCourseList(){
	$uid = GetCookie("uid");

	$tempsql = "courses_id in ( select courses_id from courses_class where class_id in ( select class_id from users where uid = {$uid}) ) " ;
	$sqladd = Filter(array(GetParam('open') , $tempsql ) , array("=" , "+") , array("open", "") ) ;
	$list = Page("courses","grade", $sqladd ,"courses_id,courses_name,grade,term,open","DESC");
	OutPutList(true,"",$list);
}

function TeacherGetCourseList(){
	$uid = GetCookie("uid");
	$sqladd = Filter(array(GetParam('open') , " courses_id in (select courses_id from `courses_teacher` where teacher_id = '$uid')" ) , array("=" , "+") , array("open" , "") ) ;

	$list = Page("courses","grade",$sqladd,"courses_id,courses_name,grade,term,open","DESC");
	for ($i=0; $i <count($list) ; $i++) { 
		$courses_id = $list[$i]['courses_id'];
		$arr = MySqlQuerys("select class_id,class_name,grade from class where class_id in (select class_id from courses_class where courses_id = '$courses_id')");
		$list[$i]['classlist'] = $arr;
	}
	OutPutList(true,"",$list);
}