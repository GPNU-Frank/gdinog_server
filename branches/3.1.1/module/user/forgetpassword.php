<?php
require_once '../../plugins/email/email.php';
$step = $_REQUEST ['step'];
if ($step == '') {
	$step = "1";
}
switch ($step) {
	case "1" :
		step_1 ();
		break;
	case "2" :
		step_2();
		break;
	case "3":
		step_3();
		break;
}
function step_1() {
	$email = $_REQUEST ['email'];
	if (! filter_var ( $email, FILTER_VALIDATE_EMAIL ) || strlen ( $email ) > 100 || strlen ( $email ) < 3) {
		OutPut ( 2, "该邮箱不存在" );
		exit ( 1 );
	}
	
	$sql = "SELECT `email` FROM `users` WHERE `email`='" . $email . "'";
	$result = mysqli_query ( $sql );
	if (mysql_num_rows ( $result ) == 0) {
		OutPut ( 2, "该邮箱不存在" );
		exit ( 1 );
	}
	$code = md5($email.time().rand(0,1000));
	$sql = "INSERT INTO `forgetpassword`(`email`,`code`,`time`,`isclick`) VALUES('$email','$code',".'NOW()'.",'0')";
	@mysqli_query ( $sql ) or die ( mysqli_error () );
	
	$mailsubject = "Gdin在线评判系统:您正在找回您的账号密码,请确认此邮件";
	$mailbody = "
		尊敬的用户:</br>
		您好，我们收到了您找回密码的申请，现在请您确认。</br>
		===========================================================</br>
		您申请的邮箱为" . $email . "</br>
		===========================================================</br>
		如果是您申请找回密码, 请点击以下链接进行确认，否则请不要点击:</br>
		".$GLOBALS['HOME_URL'] . $GLOBALS['API_DIR'] . "/function/forgetpassword/?step=2&code=".$code."</br>
		本邮件由系统自动发出，请勿回复。</br>
		感谢您的使用。</br>
		Gdin</br>	
		";
	
	 // $smtp = new smtp ( $smtpserver, $smtpserverport, true, $smtpuser, $smtppass ); // 这里面的一个true是表示使用身份验证,否则不使用身份验证.
	 // $smtp->debug = true; // 是否显示发送的调试信息
	  $GLOBALS['smtp']->sendmail ( $email, $GLOBALS["smtpusermail"], $mailsubject, $mailbody, $GLOBALS["mailtype"] );
	 
	  OutPut("1","发送邮件成功");
}

function step_2(){
	$code = $_REQUEST['code'];
	$password = $_REQUEST['password'];
	$repassword = $_REQUEST['repassword'];
	
	$sql = "SELECT `code`,`isclick` FROM forgetpassword where code = '".$code."'";
	
	$result = mysqli_query($sql);
	if(mysql_num_rows($result) == 0){
		OutPut ( 6, "非法访问" );
		exit ( 1 );
	}
	
	if($row = mysqli_fetch_assoc($result)){
		if($row['isclick'] == "1"){
			OutPut ( 5, "该链接已失效" );
			exit ( 1 );
		}
	}
	
	if (! isset ( $password ) || ! isset ( $repassword ) || $repassword == '' || $password == '') {
		OutPut ( 2, "密码不能为空" );
		exit ( 1 );
	}
	
	if ($password != $repassword) {
		OutPut ( 4, "两次输入密码不相同" );
		exit ( 1 );
	}
	
	$password = pwGen ( $password ); // 加密密码
	
	$sql = "UPDATE  `users` SET password = '".$password."'";
	
	if (mysqli_query ( $sql )) {
		OutPut ( 1, "修改密码成功" );
		$sql = "UPDATE  forgetpassword SET isclick = '1' WHERE code = '".$code."'";
		if(mysqli_query($sql)){
			
		}else{
			echo mysql_error();
		}
	}else{
		OutPut ( 5, "修改密码失败" );
	}
}

function step_3(){
	$code = $_REQUEST['code'];

	$sql = "SELECT `code`,`isclick` FROM forgetpassword where code = '".$code."'";
	$result = mysqli_query($sql);
	if(mysql_num_rows($result) == 0){
		OutPut ( 6, "非法访问" );
		exit ( 1 );
	}else{
		if($row = mysqli_fetch_assoc($result)){
			if($row['isclick'] == "1"){
				OutPut ( 5, "该链接已失效" );
				exit ( 1 );
			}
		}else{
			OutPut(1,"该链接未失效");
		}
	}
}
?>