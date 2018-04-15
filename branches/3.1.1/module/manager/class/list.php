<?php

	Privilege(StudentGetClass,TeacherGetClass,AdminGetClass);

	function StudentGetClass(){

		echo 'StudentGetClass'; 
	}

	function TeacherGetClass(){
		//教师获取所属的所有班级
		$uid = GetCookie("uid");
        $grade=GetParam('grade');

        if($grade=="")
        {
            $list = Page("class","grade","where academy_id = (select academy from users where uid = '$uid')","class_id,class_name,grade,academy_id","DESC");
        }
        else
        {
            $list = Page("class","grade","where academy_id = (select academy from users where uid = '$uid') and grade=$grade","class_id,class_name,grade,academy_id","DESC");
        }

		for ($i=0; $i <count($list) ; $i++) {
			$class_id = $list[$i]['class_id'];
			//查询班级所有学生人数
			$sql = "Select studentnum from class where class_id = '$class_id'";
			$res = MysqlQuery($sql);
			$list[$i]['student_count'] = $res['studentnum'];

			//查询班级所有课程
			$courses_arr = MysqlQuerys("select courses_id,courses_name from courses where courses_id in (select courses_id from courses_class where class_id = {$class_id} )");
			$list[$i]['courses_list'] = $courses_arr;

			//获取academy
			$academic = MySqlQuery("select name from school where id = {$list[$i]['academy_id']}") ;
			$list[$i]['academy'] = $academic['name'] ;

		}
		OutPutList(true,"",$list);
	}

	function AdminGetClass(){

		/*管理员获取所有班级列表*/
		$sqlAdd = Filter(array(GetParam("grade"),GetParam("classname"),GetParam("academy_id")),array("=","%","="),array("grade","class_name","academy_id"));
		$list = Page("class","class_id",$sqlAdd,"class_id,class_name,grade,academy_id");

		for ($i=0; $i <count($list) ; $i++) {
			$class_id = $list[$i]['class_id'];
			//查询班级所有学生人数
			$sql = "Select studentnum from class where class_id = '$class_id'";
			$res = MysqlQuery($sql);
			$list[$i]['student_count'] = $res['studentnum'];

			//查询班级所有课程
			$courses_arr = MysqlQuerys("select courses_id,courses_name from courses where courses_id in (select courses_id from courses_class where class_id = {$class_id} )");
			$list[$i]['courses_list'] = $courses_arr;

			//获取academy
			$academic = MySqlQuery("select name from school where id = {$list[$i]['academy_id']}") ;
			$list[$i]['academy'] = $academic['name'] ;

		}
		OutPutList(true,"",$list);
	}
?>