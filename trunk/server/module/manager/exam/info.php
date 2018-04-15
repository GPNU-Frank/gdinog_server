<?php

	Privilege(GetExamInfo,GetExamInfo,GetExamInfo);

	/*
	 * 查看单一测验详情
	 */
	//localhost/gdinoj/trunk/server/?ation=manager.exam.info&examid=78&cookie=2e50b02dc01ab1fd28f4da0aeea63dde
	function GetExamInfo(){
		$uid = GetCookie('uid');
		$examid = GetParam('examid',20001);

		$sql = "SELECT courses_id,exam_id,exam_name,create_time,stop_time from courses_exam where exam_id  = '$examid'";
		$arr = MysqlQuery($sql);

		//现在是计算完成的人数 只要提交过就算完成了 （因为要计算分数）problem_belong = 1 exam 2 exam 0 默认

		$arr['solved_count'] = 0;

		//判断是否结束了 就是判断一下当前时间是否超过测验的结束时间 如果是的话就返回0（已结束），不是就返回1（进行中）
		$stoptime = strtotime($arr['stop_time']);
		if($stoptime >= time()){
			$arr['status'] = 1;
		}else{
			$arr['status'] = 0;
		}


		$sql = "select problem_id,title,in_date, problem_type from problem where problem_id in(select problem_id from courses_exam_problem where exam_id = '$examid')";
		$problem_list =  MysqlQuerys($sql) ;
		$num = count($problem_list) ;
		for( $i = 0 ; $i < $num ; $i++ )
		{
			$pid = $problem_list[$i]['problem_id'] ;
			$sql = "select totolscore from courses_exam_problem WHERE  problem_id = {$pid} AND exam_id = {$examid}" ;
			$res = MySqlQuery($sql) ;
			$problem_list[$i]['totolscore'] = $res['totolscore'] ;

			$sql = "select result from solution where uid = '$uid' and result = '4' and problem_id = '$pid' ";
			$res = MySqlQuery($sql) ;
			if($res){
				$arr['solved_count']++;
			}

			//submit
			$sql = "select count(*) as num from solution where  uid = '$uid' and problem_id = '$pid'" ;
			$res = MySqlQuery($sql) ;
			$problem_list[$i]['submit'] = $res['num'] ;

			//accept
			$sql = "select count(*) as num from solution where  uid = '$uid'  and problem_id = '$pid' and result = 4" ;
			$res = MySqlQuery($sql) ;
			$problem_list[$i]['accept'] = $res['num'] ;


		}
		$arr['problem_list'] =$problem_list;



		OutPut(true,"",$arr);
		
	}
?>