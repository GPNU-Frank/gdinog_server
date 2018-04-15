<?php

	/*
	 * 获取一页题库二级菜单
	 */

	$sql = "Select * from `tags` where pid = '-1'";
	$arr = MysqlQuerys($sql);
	//print_r( $arr);
	$list = array();
	foreach ($arr as $row) {
		$sql_item = "Select * from `tags` where pid = '".$row["tagid"]."'";
		$arr_item = MysqlQuerys($sql_item);
		//print_r($arr_item);
		$item = array();
		foreach ($arr_item as $row_item) {
			array_push($item,$row_item);
		}
		$list_item = array(
			"data" => $row,
			"list" => $item,
		);
		array_push($list,$list_item);
	}
	//print_r($list);
	OutPut(true,"",$list);
?>