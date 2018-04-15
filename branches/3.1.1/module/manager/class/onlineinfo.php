<?php


	Privilege(GetOnlineInfo,GetOnlineInfo,GetOnlineInfo);

	/*
	 * 查看班级同学们上课干了些什么
	 */
	
	function GetOnlineInfo(){

		$class_id = GetParam('class_id',20001);
		$start_time = GetParam('start_time',20001);
		$end_time = GetParam('end_time',20001);

		$data = array();
		$data['list'] = Page('users','uid',"where class_id = '$class_id'",'uid,code,nick,login_time,sex');
		global $maxPage;
		$data['maxsize'] = $maxPage;

		$sql = "SELECT class_id,grade,academy_id,studentnum,class_name from class where class_id  = '$class_id'";
		$data['info'] = MysqlQuery($sql);

		//查询有木有上线
		for($i = 0 ; $i < count($data['list']) ;$i++){
			$result = MysqlQuery("select * from users where login_time <= '$end_time' and login_time >= '$start_time' and uid = ".$data['list'][$i]['uid']);
			
			if($result){
				//这段时间上线过
				$data['list'][$i]['isLogin'] = 1;
				////查询上线过的同学们的这段时间内做题情况
				$problem_result_list = MysqlQuerys("select * from solution where uid = ".$data['list'][$i]['uid']." and in_date <= '$end_time' and in_date >= '$start_time' ");
				$data['list'][$i]['submit_times'] = count($problem_result_list);//总提交数

				$problem_result_list = MysqlQuerys("select * from solution where uid = ".$data['list'][$i]['uid']." and in_date <= '$end_time' and in_date >= '$start_time' group by problem_id");
				$data['list'][$i]['submit_problem'] = count($problem_result_list);//涉及的题目数

				$problem_result_list = MysqlQuerys("select * from solution where uid = ".$data['list'][$i]['uid']." and in_date <= '$end_time' and in_date >= '$start_time'  and result = 4 group by problem_id");
				$data['list'][$i]['solved_problem'] = count($problem_result_list);//总解决题目数

			}else{
				//未上线的同学们
				$data['list'][$i]['isLogin'] = 0;
				$data['list'][$i]['submit_times'] = 0;
				$data['list'][$i]['submit_problem'] = 0;
				$data['list'][$i]['solved_problem'] = 0;
			}
		}
		
		

		OutPut(true,"",$data);
		
	}
?>