<?php

	Privilege(null,null,AddClass);

	/* 管理员添加班级
	 * @className 班级名(专业名)
	 * @课程ID
	 */

	function AddClass(){
		$classid = GetParam('classid',20001) ;
		$classname = GetParam('classname',20001) ;
		$grade = GetParam('grade',20001) ;
		$academy_id = GetParam('academy_id',20001);

		$sql = "Insert into class(class_id,class_name,grade,academy_id) VALUES('$classid','$classname' , '$grade','$academy_id')" ;
		$sql1 = "select count(*) from `class` where class_id = '$classid' ;";
		$res = MySqlInsert($sql,$sql1,30000) ;

		if($res !== false){
			OutPut(true,30002);
		}else{
			OutPut(false,30001);
		}
	}

?>