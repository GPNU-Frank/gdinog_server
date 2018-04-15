<?php

/*
 * 获取一个题目详细的信息
 * @pid 题目id
 * @Example http://localhost/GdinOJ/function/problem/?pid=1000
 * 返回值 
{"code":"1","text":"","res":[{"problem_id":"1000","title":"test","description":"
test<\/p>","input":"

a<\/p>","output":"

a<\/p>","sample_input":"a","sample_output":"a"}]}


 */

/**
 *
 * ProType 题目等于1是填空题，2是选择题。
 * 注意，当ProType是1的是否，pid就是表示Fill_problemId ;
 *
 */



$ProType = GetProType();


if( $ProType == 1 )
{
	//填空题
	$pid = $_REQUEST['pid'] ;
    $uid = GetCookie('uid', false);
    $identity = GetCookie('identity', false);
    //http://localhost/gdinoj/trunk/server/?action=problem.detail&pid=1495&cookie=affdf85d0f6e8fe8993c97227c62164c
    //http://localhost/gdinoj/trunk/server/?action=problem.detail&pid=1495&cookie=affdf85d0f6e8fe8993c97227c62164c
    //管理员
	if($identity == 3)
    {
        $sql = "SELECT title , description,  sample_output as ans, accepted, submit FROM problem WHERE problem_id = ".$pid ;
        $result = MySqlQuery($sql) ;

        //tagname
        $sql = "select  tagname from tags where tagid in (select tagid from problem_tag where problem_id = {$pid})";
        $tags = MySqlQuerys($sql);
        $tagnamearray = array();
        foreach ($tags as $item) {
            array_push($tagnamearray, $item['tagname']);
        }
        $result['tagnames'] = $tagnamearray;


        //tagid
        $sql = "select  tagid from tags where tagid in (select tagid from problem_tag where problem_id = {$pid})";
        $tagids = MySqlQuerys($sql);
        $tagidarray = array();
        foreach ($tagids as $item)
        {
            array_push($tagidarray, $item['tagid']);
        }
        $result['tagids'] = $tagidarray;


        $sql="select * from solution where problem_id=$pid and uid=$uid order by solution_id desc";
        $res=MySqlQuery($sql);
        if($res)
        {
            $solution_id=$res['solution_id'];
            $res=MySqlQuery("select source from source_code where solution_id=$solution_id");
            $result['source']=$res['source'];
        }


        OutPut(true , null , $result ) ;

    }
    //普通用户
    else
    {
        $sql = "SELECT title , description, accepted, submit  FROM problem WHERE problem_id = ".$pid ;
        $result = MySqlQuery($sql) ;

        //tagname
        $sql = "select  tagname from tags where tagid in (select tagid from problem_tag where problem_id = $pid)";
        $tags = MySqlQuerys($sql);
        $tagnamearray = array();
        foreach ($tags as $item) {
            array_push($tagnamearray, $item['tagname']);
        }
        $result['tagnames'] = $tagnamearray;

        //tagid
        $sql = "select  tagid from tags where tagid in (select tagid from problem_tag where problem_id = {$pid})";
        $tagids = MySqlQuerys($sql);
        $tagidarray = array();
        foreach ($tagids as $item)
        {
            array_push($tagidarray, $item['tagid']);
        }
        $result['tagids'] = $tagidarray;

        //如果用户没有登录，不需要查询solution
        if( $identity == null ) {
            $result['source'] = null ;
            $result['ans'] = null ;
        } else {
            $sql="select * from solution where problem_id=$pid and uid=$uid order by solution_id desc";
            $res=MySqlQuery($sql);
            if($res)
            {
                $solution_id=$res['solution_id'];
                $res=MySqlQuery("select source from source_code where solution_id=$solution_id");
                $result['source']=$res['source'];

                $res=MySqlQuery("select sample_output from problem where problem_id=$pid");
                $result['ans']=$res['sample_output'];
            }else {
                $result['source'] = null;
                $result['ans'] = null ;
            }
        }


        OutPut(true , null , $result ) ;
    }

}
else if( $ProType == 2 )
{

	//选择题
	$pid = GetParam('pid') ;
    $uid=GetCookie('uid', false);
    $identity = GetCookie('identity', false);
    //http://localhost/gdinoj/trunk/server/?action=user.login&username=aaqqdd&password=654321
    //http://localhost/gdinoj/trunk/server/?action=problem.detail&ProType=2&pid=1957&cookie=abde9048da81ec9a5724daa3e9315110
    // http://114.215.99.34/gdinoj/server/?action=problem.detail&ProType=2&pid=1525&cookie=852a64c8952447b209cca82623ae49f7
    //管理员
    //echo $uid."<br>" ;
    //echo $identity."<br>" ;
    if ($identity == 3)
    {
        $sql = "SELECT title , description , sample_input as selectjson , sample_output as ans, accepted, submit, analysis FROM problem WHERE problem_id = ".$pid ;
        $result = MySqlQuery($sql) ;

        //echo "pass1"."<br>";

        //tagname
        $sql = "select  tagname from tags where tagid in (select tagid from problem_tag where problem_id = {$pid})";
        $tags = MySqlQuerys($sql);
        $tagnamearray = array();
        foreach ($tags as $item) {
            array_push($tagnamearray, $item['tagname']);
        }
        $result['tagnames'] = $tagnamearray;

        //tagid
        $sql = "select  tagid from tags where tagid in (select tagid from problem_tag where problem_id = {$pid})";
        $tagids = MySqlQuerys($sql);
        $tagidarray = array();
        foreach ($tagids as $item) {
            array_push($tagidarray, $item['tagid']);
        }
        $result['tagids'] = $tagidarray;

        $selectArray = SolveSelectJson($result['selectjson']) ;
        $result['selectjson'] = $selectArray ;
        $result['optionA'] = $selectArray['0'] ;
        $result['optionB'] = $selectArray['1'] ;
        $result['optionC'] = $selectArray['2'] ;
        $result['optionD'] = $selectArray['3'] ;
        unset($result['selectjson']) ;

        //echo "pass"."<br>";

        $sql="select * from solution where problem_id=$pid and uid=$uid order by solution_id desc";
        $res=MySqlQuery($sql);
        if($res) {
            $solution_id = $res['solution_id'];
            $res = MySqlQuery("select source from source_code where solution_id=$solution_id");
            $result['source'] = $res['source'];

        } else{
            $result['source'] = null;
        }

        OutPut(true , null , $result ) ;

    }
    else
    {
        $sql = "SELECT title , description , sample_input as selectjson, accepted, submit, analysis  FROM problem WHERE problem_id = ".$pid ;
        //echo  $sql ;
        $result = MySqlQuery($sql) ;

        //tagname
        $sql = "select  tagname from tags where tagid in (select tagid from problem_tag where problem_id = {$pid})";
        $tags = MySqlQuerys($sql);
        $tagnamearray = array();
        foreach ($tags as $item) {
            array_push($tagnamearray, $item['tagname']);
        }
        $result['tagnames'] = $tagnamearray;

        //tagid
        $sql = "select  tagid from tags where tagid in (select tagid from problem_tag where problem_id = {$pid})";
        $tagids = MySqlQuerys($sql);
        $tagidarray = array();
        foreach ($tagids as $item) {
            array_push($tagidarray, $item['tagid']);
        }
        $result['tagids'] = $tagidarray;

        $selectArray = SolveSelectJson($result['selectjson']) ;
        $result['selectjson'] = $selectArray ;
        $result['optionA'] = $selectArray['0'] ;
        $result['optionB'] = $selectArray['1'] ;
        $result['optionC'] = $selectArray['2'] ;
        $result['optionD'] = $selectArray['3'] ;
        unset($result['selectjson']) ;

        if( $identity == null ) {
            $result['source'] = null;
            $result['ans'] = null ;
        } else {
            $sql="select * from solution where problem_id=$pid and uid=$uid order by solution_id desc";
            $res=MySqlQuery($sql);
            if($res)
            {
                $solution_id=$res['solution_id'];
                $res=MySqlQuery("select source from source_code where solution_id=$solution_id");
                $result['source']=$res['source'];

                $res=MySqlQuery("select sample_output from problem where problem_id=$pid");
                $result['ans']=$res['sample_output'];
            } else {
                $result['source'] = null;
                $result['ans'] = null ;
            }
        }


        OutPut(true , null , $result ) ;
    }
}
else if( $ProType == 3 ) {
    $pid = GetParam('pid', 20001, null, true);
    $row = MysqlQuery("SELECT  `problem_id`,`problem_type`,`title`,`description`,`memory_limit`,`time_limit`,`input`,`output`,`sample_input`,`sample_output` , `defunct` FROM problem WHERE problem_id=" . $pid);

    //tagname
    $sql = "select  tagname from tags where tagid in (select tagid from problem_tag where problem_id = {$row['problem_id']})";
    $tags = MySqlQuerys($sql);
    $tagnamearray = array();
    foreach ($tags as $item) {
        array_push($tagnamearray, $item['tagname']);
    }
    $row['tagnames'] = $tagnamearray;

    //tagid
    $sql = "select  tagid from tags where tagid in (select tagid from problem_tag where problem_id = {$row['problem_id']})";
    $tagids = MySqlQuerys($sql);
    $tagidarray = array();
    foreach ($tagids as $item) {
        array_push($tagidarray, $item['tagid']);
    }
    $row['tagids'] = $tagidarray;

    //echo "pass0" ;

    //state and code
    $uid = GetCookie('uid', false);
    if ($uid == null) {
        //未登录
        //echo "pass1" ;
        $row['result'] = null;
        $row['code'] = null;
    } else {
        //登录
        //获得res
        $sql = "SELECT result , solution_id , language from solution where solution_id = ( SELECT max(solution_id) from solution WHERE uid = {$uid} AND problem_id = {$pid} )";
        $res = MySqlQuery($sql);
        $row['result'] = (int)$res['result'];
        $row['language'] = (int)$res['language'];
        //获得code
        $solution_id = $res['solution_id'];
        $sql = "select source from source_code where solution_id = '$solution_id'";
        $res = MySqlQuery($sql);
        $row['code'] = $res['source'];

        $sql = "select error from runtimeinfo where solution_id = '$solution_id'";
        $res = MySqlQuery($sql);
        if (count($res) >= 1) {
            $row['error_msg'] = $res['error'];
        } else {
            $row['error_msg'] = "";
        }
    }
    OutPut(true, '', $row);

}
else if($ProType == 5 )
{
    $pid = $_REQUEST['pid'] ;
    $uid = GetCookie('uid', false);
    $identity = GetCookie('identity', false);
    //http://localhost/gdinoj/trunk/server/?action=problem.detail&pid=1499&cookie=affdf85d0f6e8fe8993c97227c62164c
    //管理员
    if($identity == 3)
    {
        $sql = "SELECT title , description, sample_output as ans, accepted, submit, analysis FROM problem WHERE problem_id = ".$pid ;
        //echo  $sql ;
        $result = MySqlQuery($sql) ;

        //tagname
        $sql = "select  tagname from tags where tagid in (select tagid from problem_tag where problem_id = {$pid})";
        $tags = MySqlQuerys($sql);
        $tagnamearray = array();
        foreach ($tags as $item) {
            array_push($tagnamearray, $item['tagname']);
        }
        $result['tagnames'] = $tagnamearray;


        //tagid
        $sql = "select  tagid from tags where tagid in (select tagid from problem_tag where problem_id = {$pid})";
        $tagids = MySqlQuerys($sql);
        $tagidarray = array();
        foreach ($tagids as $item)
        {
            array_push($tagidarray, $item['tagid']);
        }
        $result['tagids'] = $tagidarray;


        $sql="select * from solution where problem_id=$pid and uid=$uid order by solution_id desc";
        $res=MySqlQuery($sql);
        if($res)
        {
            $solution_id=$res['solution_id'];
            $res=MySqlQuery("select source from source_code where solution_id=$solution_id");
            $result['source']=$res['source'];
        }

        OutPut(true , null , $result ) ;

    }
    //普通用户
    else
    {
        $sql = "SELECT title , description, accepted, submit, analysis  FROM problem WHERE problem_id = ".$pid ;
        //echo  $sql ;
        $result = MySqlQuery($sql) ;

        //tagname
        $sql = "select  tagname from tags where tagid in (select tagid from problem_tag where problem_id = $pid)";
        $tags = MySqlQuerys($sql);
        $tagnamearray = array();
        foreach ($tags as $item) {
            array_push($tagnamearray, $item['tagname']);
        }
        $result['tagnames'] = $tagnamearray;

        //tagid
        $sql = "select  tagid from tags where tagid in (select tagid from problem_tag where problem_id = {$pid})";
        $tagids = MySqlQuerys($sql);
        $tagidarray = array();
        foreach ($tagids as $item)
        {
            array_push($tagidarray, $item['tagid']);
        }
        $result['tagids'] = $tagidarray;

        if( $identity == null ) {
            $result['source'] = null ;
            $result['ans'] = null ;
        } else {
            $sql="select * from solution where problem_id=$pid and uid=$uid order by solution_id desc";
            $res=MySqlQuery($sql);
            if($res)
            {
                $solution_id=$res['solution_id'];
                $res=MySqlQuery("select source from source_code where solution_id=$solution_id");
                $result['source']=$res['source'];

                $res=MySqlQuery("select sample_output from problem where problem_id=$pid");
                $result['ans']=$res['sample_output'];
            } else {
                $result['source'] = null ;
                $result['ans'] = null ;
            }
        }


        OutPut(true , null , $result ) ;
    }

}







function SolveSelectJson($selectjson) {

    $selectArray = explode("||",$selectjson) ;

    return $selectArray ;
}


?>