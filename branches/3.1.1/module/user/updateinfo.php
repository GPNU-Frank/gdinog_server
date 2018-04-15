<?php
	//更新用户信息
	Privilege(Update,Update,Update,Update);

	function Update(){
		$uid = GetParam('uid',null,GetCookie('uid'));//uid

		if($uid == null){
			OutPut(false,20001);
		}

		$nick = GetParam('nick',null,null,null,32) ;//昵称

		if($nick !== null){
			//昵称应该不能重复
			
		}

		$email = GetParam('email',null,null,null,140) ;//邮箱


		$school = GetParam('school',null,null,true) ;//学校id

		$sex = GetParam('sex') ;//性别0 1

		$qq = GetParam('qq') ;//qq

		$signature = GetParam('signature',null,null,null,140) ;//签名


		$class_id = GetParam("class_id");
		$address = GetParam('address') ;//地址
		$contact = GetParam('contact') ;//联系方式

		$academy = GetParam('academy',null,null,true) ;//学院id
		$major = GetParam('major') ;//专业id

		$idcard = GetParam('idcard',null,null,true,18);//身份证
		$code = GetParam('code'); //学生号 教工号 管理员
		$grade = GetParam('grade',null,null,true); //年级


		$sql_arr = array(
			$nick === null ? "" : "nick = '$nick'",
			$email === null ? "" : "email = '$email'",
			$school === null ? "" : "school = '$school'",
			$sex === null ? "" : "sex = '$sex'",
			$qq === null ? "" : "qq = '$qq'",
			$signature === null ? "" : "signature = '$signature'",
			$class_id === null ? "" : "class_id = '$class_id'",
			$address === null ? "" : "address = '$address'",
			$contact === null ? "" : "contact = '$contact'",
			$academy === null ? "" : "academy = '$academy'",
			$major === null ? "" : "major = '$major'",
			$idcard === null ? "" : "idcard = '$idcard'",
			$code === null ? "" : "code = '$code'",
			$grade === null ? "" : "grade = '$grade'",
		);


		$sql_all = "";
		for ($i=0; $i < count($sql_arr); $i++) { 
			if($sql_arr[$i]){
				if($sql_all == ""){
					$sql_all = $sql_all . " Set ";
				}else{
					$sql_all = $sql_all . " , ";
				}	
				$sql_all = $sql_all . $sql_arr[$i];
			}
		}


		
		if($sql_all == ""){
			OutPut(false,20001);
		}

		$sql = "UPDATE users $sql_all WHERE uid = '$uid'";

		$res = MySqlUpdate($sql) ;

		if( $res )
		{
		    OutPut(true) ;
		}
		else
		{
		    OutPut(false,20001) ;
		}
	}
