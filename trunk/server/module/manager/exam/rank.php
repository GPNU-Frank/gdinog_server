<?php

	/*
	 * 获取排行榜
	 */

	$exam_id = GetParam('exam_id',20001);
	$class_ids = GetParam('class_id');

	$page = GetParam("page",null,1,true);
	$pagesize = GetParam("pagesize",null,4,true);

	$sql_add = null;
	if(isset($class_ids)){
		$class_ids = json_decode($class_ids);

		foreach ($class_ids as $class_id) {
			if($sql_add == null){
				$sql_add = $sql_add . " and ";
			}else{
				$sql_add = $sql_add ." or ";
			}
			$sql_add = $sql_add. " class_id = '$class_id' ";
		}
		
	}

	$sql = "select uid,nick,class_id from users where class_id in (select class_id from courses_class where courses_id in(select courses_id from courses_exam where exam_id = '$exam_id')) and identity = '1' ".$sql_add;

	$student_arr = MysqlQuerys($sql);
	$sql = "select problem_id from courses_exam_problem where exam_id = '$exam_id'";
	$problem_arr = MysqlQuerys($sql);
	$problem_count = count($problem_arr);
	$student_count = count($student_arr);

	$page = $page - 1;
	$maxSize = $student_count;
	$maxPage = ceil ( $maxSize / $pagesize );
	$startNum = $pagesize * $page;

	//检测每个学生的做题情况
	for($i = 0 ; $i < $student_count ; $i++){
		$class_id = $student_arr[$i]['class_id'];
		$res = MysqlQuery("select class_name from class where class_id = '$class_id'");
		$student_arr[$i]['class_name'] = $res['class_name'];

		$student_arr[$i]['solve'] = 0;
		$student_arr[$i]['submit_count'] = 0;
		$uid = $student_arr[$i]['uid'];
		for($j = 0 ; $j < $problem_count ; $j++){
			//////////////////////////////////////////////////////题目情况
			if(!isset($problem_arr[$j]['solve_count'])){
				$problem_arr[$j]['solve_count'] = 0;
			}
			if(!isset($problem_arr[$j]['submit_count'])){
				$problem_arr[$j]['submit_count'] = 0;
			}

			//////////////////////////////////////////////////////学生排名
			$problem_id = $problem_arr[$j]['problem_id'];
			$arr = MysqlQuerys("select DISTINCT  problem_id from solution where problem_id = '$problem_id' and uid = '$uid' and result = '4' ");
			if(count($arr) > 0){
				$problem_arr[$j]['solve_count'] = $problem_arr[$j]['solve_count'] + count($arr);
				$student_arr[$i]['solve']++;
			}
			$arr = MysqlQuerys("select result from solution where problem_id = '$problem_id' and uid = '$uid'");

			$student_arr[$i]['submit_count'] = $student_arr[$i]['submit_count'] + count($arr) ;
			$problem_arr[$j]['submit_count'] = $problem_arr[$j]['submit_count'] + count($arr);
		}
	}

	usort($student_arr, "student_compare");//排序
	InsertRankNumber($student_arr);
	$student_arr = array_slice($student_arr, $startNum,$pagesize);

	usort($problem_arr, "problem_compare");//排序
	InsertRankNumber($problem_arr);

	///////////班级列表
	$sql = "select class_id,class_name from class where class_id in (select class_id from courses_class where courses_id in(select courses_id from courses_exam where exam_id = '$exam_id'))";
	$class_arr = MysqlQuerys($sql);

	$data = array(
		'maxsize' => $maxPage,
		'problem_count' => $problem_count,
		'ran_list' => $student_arr,
		'problem_list' => $problem_arr,
		'class_list' => $class_arr,
		);

	OutPut(true,'',$data);

	function student_compare($a,$b){
		if($a['solve'] == $b['solve']){
			return $a['submit_count'] > $b['submit_count'];
		}
		return $a['solve'] < $b['solve'];
	}

	function problem_compare($a,$b){
		if($a['solve_count'] == $b['solve_count']){
			return $a['submit_count'] > $b['submit_count'];
		}
		return $a['solve_count'] < $b['solve_count'];
	}

	function InsertRankNumber(&$arr){
		$rank = 1;
		for($i = 0 ; $i < count($arr); $i++){
			$arr[$i]['rank'] = $rank;
			$rank++;
		}
	}
?>