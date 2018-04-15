<?php
/*
 * 个人信息中心

*/
Privilege(OtherGetInfo,OtherGetInfo,AdminGetInfo,OtherGetInfo);

//http://localhost/gdinoj/trunk/server/?action=user.info&cookie=ccccb51862856962d5ef78e3f58f9a9c

function OtherGetInfo(){
	$uid = GetCookie('uid');
	GetUserInfo($uid);
}

function AdminGetInfo(){
	$uid = GetParam('uid',null,GetCookie('uid'));
	GetUserInfo($uid);
}

function GetUserInfo($uid){

	//获取基本用户信息
	//$uid = GetCookie('uid');
	$sql = "select uid,user_id,nick,email,reg_time,submit,solved,accesstime,identity,ip,school,sex,qq,signature,birthday,address,contact,academy,major,users.class_id,code,users.grade , class_name , getname(major) as major_name , getname(academy) as academy_name , login_time from users , class where uid = {$uid}" ;
	$temp = MySqlQuery($sql) ;
	$arr['userinfo'] = $temp;

	//获取用户排名
	$sql = 'select * from users order by solved desc ';
	$result = MySqlQuerys($sql) ;
	$arr['userinfo']['rank'] = 1;//默认第一然后遍历 每次+1
	foreach($result as $item )
	{
		if($item['uid'] == $uid){
			break;
		}else{
			$arr['userinfo']['rank']++;
		}
	}

	$result_count = MySqlQuery("select count(*) from problem") ;
	$arr['total_amount'] = $result_count['count(*)'];



	//现在开始已经解决问题列表
	$sql = "select distinct problem_id from solution where uid ='".$uid."' and result = 4 and problem_id <> 0";

	$result = MySqlQuerys($sql) ;
	$temp1 = array();
	foreach($result as $row )
	{
		//总数
		$sql1 = "select count(*) as num  from solution where problem_id = '".$row['problem_id']."'AND uid='$uid'";
		$sql2 = "select count(*) as num , protype from solution where problem_id = '".$row['problem_id']."'AND uid='".$uid."' and result = 4";

		$submit = MySqlQuery($sql1) ; $pass = MySqlQuery($sql2) ;
		$temp2 = array(
				'problem_id' => $row['problem_id'],
				'protype' => $pass['protype'] ,
				'pass' => $pass['num'] ,
				'submit' => $submit['num']

		);
		array_push($temp1, $temp2);

	}
	$arr['solved'] =$temp1;



	//获取不正确的列表
	$sql = "select distinct problem_id from solution where uid ='".$uid."'  and problem_id NOT in (
	select distinct problem_id from solution where uid ='".$uid."' and result = 4 and problem_id <> 0
)";
	$result = MySqlQuerys($sql) ;
	$temp3 = array();


	foreach( $result as $row )
	{
		//总数
		$sql1 = "select count(*) as num,problem.problem_type from solution,problem where solution.problem_id = '".$row['problem_id']."'AND solution.uid='".$uid."' and solution.problem_id = problem.problem_id";
		$submit = MySqlQuery($sql1) ;
		$temp4 = array(
				'problem_id' => $row['problem_id'],
				'protype' => $submit['problem_type'],
				'submit' => $submit['num'],

		);
		array_push($temp3, $temp4);

	}

	$arr['dissolved'] =$temp3;

	OutPut(true,"",$arr);

}
?>