<?php
	/* 添加题库(管理员权限)
	 * @title 标题
	 * @time_limit 时间限制
	 * @memory_limit 内存限制
	 * @description 问题描述
	 * @input 输入
	 * @output 输出
	 * @sample_input 
	 * @sample_output
	 * @hint 暗示
	 * @source 来源
	 * @spj 是否特殊叛题 0 1
	 * http://localhost/GdinOJ/?function=admin&action=addproblem
	 * Example http://127.0.0.1/GdinOJ/?fun=AddProblem&p0=title&p1=1&p2=128&p3=description&p4=input&p5=output&p6=sample_input&p7=sample_output&p8=hint&p9=source&p10=0
	 */

	/*
	 * update by shanxuan 1/16
	 *
	 * addproblem
	 * There are four problem's type
	 * 1.fill problem
	 * 2.selected problem
	 * 3.code problem
	 * 4.fill && code problem
	 *
	 * actualy is
	 *
	 */



	$isDonate = GetParam('isDonate');//是否普通用户捐献的题目

	
	$owner = -1;//若是系统上传的默认-1
	$isVerify = 1;//默认已认证

	if($isDonate){
		$owner = GetCookie('uid');
		$isVerify = 0;
		InsertProblem($owner,$isVerify);

	}else{
		//判断是否拥有管理权限才行
		Privilege(null , null ,  checkAdmin) ;
	}



	function checkAdmin(){
		//只是检查一下管理员权限 没做什么东西
		//echo "pass2"."<br>" ;
		InsertProblem(-1,1);
	}



	function InsertTags($pid,$tagids){


		$tags = json_decode($tagids);
		$id = $pid;//必须 $pid 为引用会改变
		foreach ($tags as $tag) {

			MySqlInsert("INSERT INTO problem_tag(problem_id,tagid) VALUES('$id','$tag')");
		}
	}

	function InsertProblem($owner,$isVerify)
	{
		$proType = GetParam('protype' , 20001 ) ;
		//echo $proType ;
		if( $proType == 1 )
		{
			//http://localhost/gdinoj/trunk/server/?cookie=3690eb828f2302c7e122ebd65e485124&action=manager.problem.add&protype=1&title=test&description=test&ans=test&content=test


			//description是解析后的HTML字符串 content是带有占位符的文本
			$title = GetParam('title') ;
			$description = GetParam('description') ;
			$content = GetParam('content') ;
			$ans = GetParam('ans') ;
			$tagids = GetParam('tagids');


			$now =  date('Y-m-d h:i:s',time()) ;

			//格式处理
			//$description = htmlspecialchars($description) ;
			$ans= str_replace("\\n", "", $ans);

			//用hint来存储content
			$sql = "INSERT INTO problem( hint , title , description , sample_output , in_date , problem_type,owner,is_verify) VALUES ( '$content' , '$title' , '$description' , '$ans' , '$now' , '$proType','$owner','$isVerify')" ;
			$res = MySqlInsert($sql) ;

			if( $res )
			{
				InsertTags($res,$tagids);
				OutPut(true  ) ;

			}
			else
				Output(false , 20000 , $sql ) ;

		}
		else if( $proType == 2 ) //selected problem
		{
			//http://localhost/gdinoj/trunk/server/?cookie=f5adada97d8f9e819cac2423bca0b564&action=manager.problem.add&protype=2&title=aa...&description=%3Cp%3Eaa%3C%2Fp%3E%0A&ans=a&tagids=%5B8%2C7%5D&tagnames=%E5%AD%97%E7%AC%A6%E4%B8%B2%2C%E6%95%B0%E7%BB%84&optionA=A&optionB=B&optionC=C&optionD=D&analysis=124124124
			$title = GetParam('title');
			$description = GetParam('description');
			$ans = GetParam('ans');
			//$selectjson = GetParam('selectjson');
			$now =  date('Y-m-d h:i:s',time()) ;
			$tagids = GetParam('tagids');
			$analysis=GetParam('analysis');

			$optionA  = GetParam('optionA');
			$optionB = GetParam('optionB');
			$optionC = GetParam('optionC');
			$optionD = GetParam('optionD');



			//格式处理
			$selectjson = $optionA."||".$optionB."||".$optionC."||".$optionD ;

			$sql = "INSERT INTO problem(title , description , sample_input , sample_output ,in_date , problem_type,owner,is_verify, analysis ) VALUES ('$title' ,'$description' , '$selectjson' , '$ans' , '$now' , '$proType',$owner,$isVerify, '$analysis')";
			$res = MySqlInsert($sql);
			if ($res)
			{

				InsertTags($res,$tagids);
				OutPut(true) ;
			}
			else
			{
				Output(false , 20000 , $sql ) ;
			}


		}
		else if( $proType == 3 ) // code problem
		{

			$title = GetParam('title') ;
			$timelimit = GetParam('time_limit') ;
			$memorylimit = GetParam('memory_limit') ;
			$description = GetParam('description') ;
			$input = GetParam('input') ;
			$output = GetParam('output') ;
			$sample_output = GetParam('sample_output') ;
			$sample_input = GetParam('sample_input') ;
			$hint = GetParam('hint') ;
			$source = GetParam('source') ;
			$spj = GetParam('spj') ;
			$now =  date('Y-m-d h:i:s',time()) ;
			$pid = null ;
			$tagids = GetParam('tagids');
			$defunct = GetParam('defunct','','N');

			//$contestid = $_REQUEST['contestid'] ;
			if( isset($_REQUEST['contestid']) )
			{
				$contestid = GetParam('contestid') ;
				$sql = "INSERT INTO problem (title,time_limit,memory_limit,description,input,output,sample_input,sample_output,hint,source,spj,in_date,defunct,problem_type,owner,is_verify)
 VALUES('$title','$timelimit','$memorylimit','$description','$input','$output', '$sample_input','$sample_output','$hint','$source','$spj','$now','$defunct','$proType',$owner,$isVerify)";
				$pid = MySqlInsert($sql) ;

			}
			else
			{
				$sql = "INSERT into problem (title,time_limit,memory_limit,
				  description,input,output,sample_input,sample_output,hint,source,spj,in_date,defunct,problem_type,owner,is_verify)
					VALUES('$title','$timelimit','$memorylimit','$description','$input','$output',
					'$sample_input','$sample_output','$hint','$source','$spj','$now','$defunct',$proType,$owner,$isVerify)";
				$pid = MySqlInsert($sql) ;

			}
			InsertTags($pid,$tagids);
			savesample($pid,$sample_input,$sample_output ) ;
			OutPut(true , null , null ) ;
		}
		else if( $proType == 5 ) {
			//判断题
			//http://localhost/gdinoj/trunk/server/?cookie=abde9048da81ec9a5724daa3e9315110&action=manager.problem.add&protype=5&title=aa...&description=%3Cp%3Eaa%3C%2Fp%3E%0A&ans=a&tagids=[8%2C7]&tagnames=%E5%AD%97%E7%AC%A6%E4%B8%B2%2C%E6%95%B0%E7%BB%84&analysis=124124124

			$title = GetParam('title');
			$description = GetParam('description');
			$ans = GetParam('ans');
			$now =  date('Y-m-d h:i:s',time()) ;
			$tagids = GetParam('tagids');
			$analysis=GetParam('analysis');


			$sql = "INSERT INTO problem(title , description  , sample_output ,in_date , problem_type,owner,is_verify, analysis ) VALUES ('$title' ,'$description' , '$ans' , '$now' , '$proType',$owner,$isVerify, '$analysis')";
			$res = MySqlInsert($sql);
			if ($res)
			{

				InsertTags($res,$tagids);
				OutPut(true) ;
			}
			else
			{
				Output(false , 20000 , $sql ) ;
			}
		}
	}



?>


