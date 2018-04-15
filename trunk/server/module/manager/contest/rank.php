<?php

/*
 *
 *  思路，首先把所有的比赛用户找出来
 *  然后再进行排序，最后进行分页处理。
 *
 */

$page = GetParam("page",null,1,true);
$pagesize = GetParam("pagesize",null,10,true);
$user_id = GetCookie('uid');
$contest_id = GetParam('contest_id', 20001);
global $maxPage;
$num=($page-1)*$pagesize;

//获取排名最大页数
$sql="select count(*) as num from contest_finish where contest_id=$contest_id";
$result=MySqlQuery($sql);
$maxPage=ceil($result['num']/$pagesize);

//获取题目列表和将结构转为数组
$sql="select problem_id from contest_problem where contest_id=$contest_id order by problem_id";
$problemlist=MySqlQuerys($sql);
$problem_cnt=count($problemlist);

$plist=array();
for($i = 0 ; $i<$problem_cnt ; $i++ )
{
    array_push($plist , (int)$problemlist[$i]['problem_id'] ) ;
}


//获取这一页的排名
$sql="select uid, finish as passlist, all_time as Time, accept_num as pass, submit_num as submit from contest_finish where contest_id=$contest_id order by accept_num desc, all_time asc limit $num, $pagesize";
$res=MySqlQuerys($sql);
$cnt=count($res);

$myrank=1;
$flag=1;
for($i=0; $i<$cnt; $i++)
{
    //获取我的排名
    $id=$res[$i]['uid'];
    if($flag && $id!=$user_id)
    {
        $myrank++;
    }
    else $flag=0;
    if($flag==1) $myrank=0;
    //获取用户名称
    $sql="select nick from users where uid=$id";
    $result=MySqlQuery($sql);
    $res[$i]['nick']=$result['nick'];

}

$list = array(
    'maxsize' => $maxPage,
    'list' => $res ,
    'myrank' => $myrank ,
    'problemlist' => $plist ,
);
OutPut(true,"",$list);

function gmstrftimeA($seconds)
{
    if ($seconds > 3600)
        return intval($seconds/3600).':'.gmstrftime('%M:%S', $seconds/600);
    return gmstrftime('%H:%M:%S', $seconds);
}

?>