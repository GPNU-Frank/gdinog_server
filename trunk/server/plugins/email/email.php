<?php  
require_once ('email.class.php'); 
//########################################## 
$smtpserver = "smtp.helloworld.net.cn";//SMTP服务器 
$smtpserverport =25;//SMTP服务器端口 
$smtpusermail = "admin@helloworld.net.cn";//SMTP服务器的用户邮箱 
$smtpemailto = "407731919@qq.com";//发送给谁 
$smtpuser = "admin@helloworld.net.cn";//SMTP服务器的用户帐号 
$smtppass = "3398652852aA";//SMTP服务器的用户密码 
$mailsubject = "PHP100测试邮件系统";//邮件主题 
$mailbody = "<h1> 这是一个测试程序 PHP100.com </h1>";//邮件内容 
$mailtype = "HTML";//邮件格式（HTML/TXT）,TXT为文本邮件 

$smtp = new smtp ( $smtpserver, $smtpserverport, true, $smtpuser, $smtppass ); // 这里面的一个true是表示使用身份验证,否则不使用身份验证.
$smtp->debug = false; // 是否显示发送的调试信息

/*function Send($smtpemailto,$mailsubject,$mailbody){
	$smtp = new smtp($smtpserver,$smtpserverport,true,$smtpuser,$smtppass);//这里面的一个true是表示使用身份验证,否则不使用身份验证.
	$smtp->debug = true;//是否显示发送的调试信息
	$smtp->sendmail($smtpemailto, $smtpusermail, $mailsubject, $mailbody, $mailtype);
}*/

########################################## 

?> 