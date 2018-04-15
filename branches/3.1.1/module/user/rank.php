<?php

	/*
	 * 获取排行榜 按通过的数量排序
	 * @Author zhudelin
	 * @Time 2016/01/14
	 * @page 页码
	 * @pagesize 一页显示数量

	 * 返回值 
	 * @maxsize 最大页数
	 * @ranklist 排行列表
	 * @submit 总提交数
	 * @pass 通过数
	 * @percent 通过率（小数)
	 * @uid 用户id
	 * @nick 昵称
	 */

	$nick = GetParam( "nick" ) ;
	$page = GetParam("page",null,1,true);
	$pagesize = GetParam("pagesize",null,4,true);

	$sqladd = Filter( array($nick ) , array("%") , array("nick") ) ;
	$res = page( "users" , "solved+solved/submit" ,$sqladd , "submit , solved as pass , uid , nick , signature " , "desc"  );

	OutPutList(true , "" , $res ) ;

//0 Limit 0,20SELECT submit , solved as pass , uid , nick , signature FROM `users` where nick like '%aa%' ORDER BY `getdegree( users.solved , users.submit )` desc Limit 0,20
?>