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




	function updatetagids($pid,$tagids){
		$tags = json_decode($tagids);
		$id = $pid;//必须 $pid 为引用会改变
		//先删掉之前的标记
		$sql = "delete from problem_tag where problem_id = '$id'" ;
		MySqlUpdate($sql) ;
		foreach ($tags as $tag) {
			$sql = "INSERT INTO problem_tag(problem_id,tagid) VALUES('$id','$tag')" ;
			MySqlInsert($sql);

		}
	}

	$pid = GetParam('pid' , 20001 ) ;
	$proType = GetProType();



	//fill problem
	if( $proType == 1 )
	{


		$title = GetParam('title') ;
		$content = GetParam('content') ;
		$description = GetParam('description') ;
		$ans = GetParam('ans') ;
		$tagids = GetParam('tagids');

		$now =  date('Y-m-d h:i:s',time()) ;
		$sql = "UPDATE problem SET hint = '$content' , title = '$title', description = '$description'  ,sample_output = '$ans'  , in_date = '$now'  where  problem_id = {$pid}	" ;
		$res = MySqlUpdate($sql) ;

		if( $res )
		{
			updatetagids($pid ,$tagids ) ;
			OutPut(true  ) ;

		}
		else
			Output(false , 20000 , $sql ) ;

	}
	else if( $proType == 2 ) //selected problem
	{
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

		//http://localhost/gdinoj/trunk/server/?action=problem.detail&pid=1959&title=aaqqdd&description=aaqqdd&cookie=abde9048da81ec9a5724daa3e9315110&analysis=123123
        //http://localhost/gdinoj/trunk/server/?action=manager.problem.update&pid=1959&title=aaqqdd&description=aaqqdd&cookie=abde9048da81ec9a5724daa3e9315110&analysis=123123
		$selectjson = $optionA."||".$optionB."||".$optionC."||".$optionD ;

		$sql = "UPDATE problem SET  title = '$title', description = '$description' ,sample_input = '$selectjson' ,sample_output = '$ans'  , in_date = '$now', analysis='$analysis' where  problem_id = {$pid}	" ;
		$res = MySqlUpdate($sql) ;
		if ($res)
		{
			updatetagids($pid ,$tagids ) ;
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
		$tagids = GetParam('tagids');
		$defunct = GetParam('defunct','','N');

		//fiter
		$description = str_replace("\\", "\\\\", $description);
		$description = str_replace("'", "\'" ,$description ) ;

		//$contestid = $_REQUEST['contestid'] ;
		if( isset($_REQUEST['contestid']) )
		{
			/*
			$contestid = GetParam('contestid') ;
			$sql = "INSERT INTO problem (title,time_limit,memory_limit,description,input,output,sample_input,sample_output,hint,source,spj,in_date,defunct,problem_type)
 VALUES('$title','$timelimit','$memorylimit','$description','$input','$output', '$sample_input','$sample_output','$hint','$source','$spj','$now','Y','$proType')";
			MySqlInsert($sql) ;
			*/

		}
		else
		{

			$sql = "UPDATE problem SET  title = '$title' , time_limit = '$timelimit' , memory_limit = '$memorylimit'
				, description = '$description' , input = '$input' , output = '$output' , sample_input = '$sample_input' ,
				sample_output = '$sample_output' ,hint = '$hint' , source = '$source' , spj = '$spj' , in_date = '$now' ,
				 defunct = '$defunct' , problem_type = '$proType' where  problem_id = {$pid}	" ;
			$res = MySqlUpdate($sql) ;

			if( $res == false )	OutPut(false , 20001 ) ;

		}
		updatetagids($pid ,$tagids ) ;
		OutPut(true , null , null ) ;
	}
	else if( $proType == 5 ) {
		$title = GetParam('title');
		$description = GetParam('description');
		$ans = GetParam('ans');
		$now =  date('Y-m-d h:i:s',time()) ;
		$tagids = GetParam('tagids');
        $analysis=GetParam('analysis');


		$sql = "UPDATE problem SET  title = '$title', description = '$description'  ,sample_output = '$ans'  , in_date = '$now', analysis='$analysis'  where  problem_id = {$pid}	" ;
		$res = MySqlInsert($sql);
		if ($res)
		{

			updatetagids($pid ,$tagids ) ;
			OutPut(true) ;
		}
		else
		{
			Output(false , 20000 , $sql ) ;
		}
	}

?>