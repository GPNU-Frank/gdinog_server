<?php

	/*
	 * 获取一页题库信息
	 * @page 页码
	 * @problem_type 问题类型
	 * @pagesize 一页显示数量
	 * @tagid 标签 id 
	 * 返回值 
	 * @maxsize 最大页数
	 * @problem 题目列表
	 */
	$title = GetParam('title') ;
	$pid = GetParam('pid') ;
	$problem_type = GetParam('problem_type');
	$tagid = GetParam('tagid',null,-1,true); //默认-1则全选
	$defunct = GetParam('defunct','','N');
	$sqlAdd = $tagid != -1 ?  " problem_id in ( SELECT problem_id FROM `problem_tag` where tagid = '$tagid' )" : "" ;

	if( $defunct == 'A' )
		$filter = Filter(array($sqlAdd,$problem_type,$title,$pid,'1'),array("+","=","%" ,"=","=","="),array(null,"problem_type","title","problem_id","is_verify"));
	else
		$filter = Filter(array($sqlAdd,$problem_type,$title,$pid,$defunct,'1'),array("+","=","%" ,"=","=","="),array(null,"problem_type","title","problem_id","defunct","is_verify"));

	$uid = GetCookie( 'uid' , false ) ;
    // 添加memcache缓存功能
    $memkey = "problem_list:".$page;
    $mempagekey = 'problem:maxPage';
    $memarr = $memcache->get($memkey);
    $maxPage = $memcache->get($mempagekey);
    if($memarr){
        // 获取 用户是否完成题目信息

        $len = count($memarr);
        for($i = 0 ; $i < $len ; $i++){
            if( $uid == null )
                $memarr[$i]['finished'] = 0 ;
            else
            {
                $pid = $memarr[$i]['problem_id'] ;
         //       echo  $pid;
                $sql = "select hasfinishpro({$uid} , {$pid}) as status" ;
                $res = MySqlQuery($sql) ;
                $memarr[$i]['finished'] = $res['status'] ;
            }
        }
        OutPutList ( true, '', $memarr );
    }else{
        //用户没有登录
        //获取题目信息
        $arr = Page("problem","problem_id",$filter,"`problem_id`,`title`,`submit`,`problem_type`,`accepted`,`in_date`,hastestdata","ASC");


        $len =count($arr) ;
        for($i = 0 ; $i<$len ; $i++ )
        {
            // 获取题目分类信息
            $pid = $arr[$i]['problem_id'] ;
            $sql = "select tagname from tags where tagid in (select tagid from problem_tag where problem_id = '$pid' )" ;
            $res = MySqlQuerys($sql) ;

            $arr1 = array() ;
            foreach($res as $item )
            {
                array_push($arr1 , $item['tagname'] ) ;
            }

            $arr[$i]['tagnames'] = $arr1 ;

            // 获取 用户是否完成题目信息
            if( $uid == null )
                $arr[$i]['finished'] = 0 ;
            else
            {
                $sql = "select hasfinishpro({$uid} , {$pid}) as status" ;
                $res = MySqlQuery($sql) ;
                $arr[$i]['finished'] = $res['status'] ;
            }


        }

        OutPutList ( true, '', $arr );
    }

?>