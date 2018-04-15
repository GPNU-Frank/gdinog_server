<?php

	/* 获取单一题目的提交状态 可以筛选
	 * 不提交参数时 显示所有
	 * @author zhudelin
	 * @time 2016/01/14
	 *
	 * @pid 题目ID
	 * @user_id 提交人ID
	 * @jresult 评判结果
	 * @language 程序语言
	 * @page 页码
	 * @pagesize 一页显示数量 默认2
	 * http://localhost/GdinOJ/function/problemstate/
	 */

	$pid = GetParam('pid');
	$user_id = GetParam('user_id');
	$jresult = GetParam('jresult');
	$language = GetParam('language');
	$nick = GetParam('nick') ;

	if($nick != null) {
		$sql1 = "uid in (
			SELECT uid from users where nick like '%$nick%'
			)" ;
	}
	else
		$sql1 = "" ;

	$SqlAdd = Filter(array($jresult,$user_id,$language,$pid,$sql1),array("=","%","=","=","+"),array("result","user_id","language","problem_id" ,null  )) ;
	//echo $SqlAdd."<br>" ;
	$res = Page("solution","solution_id",$SqlAdd,"`solution_id`,`problem_id`,`user_id`,`time`,`memory`,`in_date`,`result`,`language`,`code_length`,protype");

	for($i = 0 ; $i < Count($res) ; $i++){
		$row1 = MySqlQuery("select `uid`,`nick` from users where user_id = '".$res[$i]['user_id']."'");
		$res[$i]['nick'] = $row1['nick'];
		$res[$i]['uid'] = $row1['uid'];
		//unset($res[$i]['user_id']);
	}
	
	OutPutList(true,"",$res);
?>