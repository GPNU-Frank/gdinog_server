<?php


	Privilege(GetClassInfo,GetClassInfo,GetClassInfo);

	/*
	 * 查看单一班级
	 */
	
	function GetClassInfo(){

		$class_id = GetParam('class_id',20001);

		$data = array();
		$data['list'] = Page('users','uid',"where class_id = '$class_id'",'uid,code,nick,submit,solved,login_time,sex');
		global $maxPage;
		$data['maxsize'] = $maxPage;

		$sql = "SELECT class_id,grade,academy_id,studentnum,class_name from class where class_id  = '$class_id'";
		$data['info'] = MysqlQuery($sql);
		
		

		OutPut(true,"",$data);
		
	}
?>