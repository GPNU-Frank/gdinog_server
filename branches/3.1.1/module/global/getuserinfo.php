<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/3/4
 * Time: 10:24
 */

light_getuserinfolist() ;

function light_getuserinfolist()
{
    $uid = GetParam("uid" , 20001) ;
    $sql = "select uid , nick , qq , email , signature , school ,login_time from users where uid = {$uid}" ;
    $res = MySqlQuery($sql) ;
    $school_id = $res['school'];
    $temp = MySqlQuery("select name from school where id = '$school_id'");
    $res['school'] = $temp['name'];

    $sql = "select count(*) as num from solution where uid = {$uid}" ;
    $num = MySqlQuery($sql) ;
    $res['submit'] = $num['num'] ;

    $sql = "select count(DISTINCT  problem_id) as num  from solution where uid = {$uid} AND result = 4 " ;
    $num = MySqlQuery($sql) ;
    $res['pass'] = $num['num'] ;

    //获取用户排名
    $sql = 'select * from users order by solved desc ';
    $result = MySqlQuerys($sql) ;
    $res['rank'] = 1;//默认第一然后遍历 每次+1
    foreach($result as $item )
    {
        if($item['uid'] == $uid){
            break;
        }else{
            $res['rank']++;
        }
    }

    OutPut(true , "" , $res ) ;

}