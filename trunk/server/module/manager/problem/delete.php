<?php
	/* 删除题目
	 * @pid 题目ID
	 * update by shanxuan
	 */

	Privilege(null,null,DeleteProblem);

	function DeleteProblem(){
		$pid = GetParam('pid',20001);
		$sql ="delete from problem where problem_id = '$pid' ";
		$result = MySqlUpdate($sql) ;
		if($result ){

			//删掉所有提交过得东西
			$sql = "delete from solution where problem_id = '$pid'" ;
			MySqlUpdate($sql) ;

			$basedir = $GLOBALS['OJ_DATA']."/$pid/";
			$filesnames = scandir($basedir);
			
			foreach ($filesnames as $name) {
				unlink($basedir.$name);
			}
			rmdir($basedir);
			OutPut(true);
		}else {
			OutPut(false , 20001);
		}
	}
?>