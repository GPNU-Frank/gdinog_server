<?php
	/*
	 * 查看单一测验详情
	 */
	GetTestInfo();
	
	function GetTestInfo(){
		
		$uid = GetCookie('uid');
		$testid = GetParam('testid',20001);

		$sql = "SELECT courses_id,test_id,test_name,create_time,stop_time from courses_test where test_id  = '$testid'";
		$arr = MysqlQuery($sql);


		$sql = "select problem_id,title,in_date,submit,accepted from problem where problem_id in(select problem_id from courses_test_problem where test_id = '$testid')";
		$arr['problem_list'] = MysqlQuerys($sql);
		/*for ($i=0; $i <count($arr['problem_list']) ; $i++) { 
		 	$arr['problem_list'][$i]['pass'] = 0.5;//通过率 未算
		} */
		OutPut(true,"",$arr);
				
	}
?>