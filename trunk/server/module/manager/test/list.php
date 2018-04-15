<?php
	/*
	 * 获取练习情况
	 */
	Privilege(GetCoursesTestList,GetCoursesTestList,GetCoursesTestList);
	
	function GetCoursesTestList(){
		$uid = GetCookie('uid');
		$courseid = GetParam('courseid',20001);
		$arr = Page('courses_test','test_id',"where uid = '$uid' and courses_id = '$courseid'",'test_id,test_name,create_time,stop_time','desc');
		//现在查询总题数
		for($i = 0 ; $i < count($arr) ; $i++){
			$testid = $arr[$i]['test_id'];
			$sql = "select * from courses_test_problem where test_id = '$testid'";
			$arr[$i]['problem_count'] = count(MysqlQuerys($sql));

			//现获取该课程对应的学生总数
			$sql = 'Select `uid`,`nick` from users where uid in( select student_id from ( Select student_id from courses_student where courses_id = '.$courseid.') as `tp` )';
			$arr[$i]['student_count'] = count(MysqlQuerys($sql)); 

			//现在是计算完成的人数 只要提交过就算完成了 （因为要计算分数）problem_belong = 1 exam 2 test 0 默认
			for($i = 0 ; $i < count($arr) ; $i++){
				$testid = $arr[$i]['testid'];
				$sql = "SELECT * FROM solution WHERE test_id = '$testid' and problem_belong = '2' and result = '4'";
				$arr[$i]['pass_student_count'] = count(MysqlQuerys($sql));
			}
		}
		

		

	
		OutPutList(true,null,$arr);
				
	}
?>