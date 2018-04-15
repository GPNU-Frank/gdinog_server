<?php
/**
 * Created by PhpStorm.
 * User: Joooo
 * Date: 2016/11/6
 * Time: 17:13
 */

Privilege(null,commit_report,commit_report);
//commit_report();
function commit_report()
{
    $class_id=GetParam('class_id', 20001);
    $time_type=GetParam('time_type');
    $sort_type=GetParam('sort_type');

    $sql="SELECT uid,user_id, nick FROM users WHERE class_id='$class_id'";
    $res=MySqlQuerys($sql);
    $cnt=COUNT($res);

    if($time_type==0)               //获取全部历史提交
    {
        for ($i=0; $i<$cnt; $i++)
        {
            $res[$i]['submit']=0;
            $res[$i]['solve']=0;
            $res[$i]['pass']=0;

            $uid=$res[$i]['uid'];
            $sql="SELECT COUNT(user_id) AS num FROM solution WHERE uid={$uid}";
            $result=MySqlQuery($sql);
            $res[$i]['submit']=$result['num'];

            $sql="SELECT COUNT(user_id) AS num FROM solution WHERE uid={$uid}  AND result=4";
            $result=MySqlQuery($sql);
            $res[$i]['solve']=$result['num'];

            $res[$i][pass]=round($res[$i]['solve']/$res[$i]['submit']*100, 2);
        }
    }
    else if($time_type==1)          //获取本周历史提交
    {
        $end_day=date("Y-m-d H:i:s");
        $day=date("w");
        $start_day=date("Y-m-d 0:0:0", strtotime("-$day days"));

        for ($i=0; $i<$cnt; $i++)
        {
            $res[$i]['submit']=0;
            $res[$i]['solve']=0;
            $res[$i]['pass']=0;

            $uid=$res[$i]['uid'];
            $sql="SELECT COUNT(user_id) AS num FROM solution WHERE  uid={$uid} AND in_date>'$start_day' AND in_date<'$end_day'";
            $result=MySqlQuery($sql);
            $res[$i]['submit']=$result['num'];

            $sql="SELECT COUNT(user_id) AS num FROM solution WHERE  uid={$uid} AND in_date>'$start_day' AND in_date<'$end_day' AND result=4";
            $result=MySqlQuery($sql);
            $res[$i]['solve']=$result['num'];

            $res[$i][pass]=round($res[$i]['solve']/$res[$i]['submit']*100, 2);
        }
    }
    else if($time_type==2)          //获取上周历史提交
    {
        $day=date("w")+1;
        $end_day=date("Y-m-d 0:0:0", strtotime("-$day days"));
        $day=$day+6;
        $start_day=date("Y-m-d 0:0:0", strtotime("-$day days"));

        for ($i=0; $i<$cnt; $i++)
        {
            $res[$i]['submit']=0;
            $res[$i]['solve']=0;
            $res[$i]['pass']=0;

            $uid=$res[$i]['uid'];
            $sql="SELECT COUNT(user_id) AS num FROM solution WHERE uid={$uid} AND in_date>'$start_day' AND in_date<'$end_day'";
            $result=MySqlQuery($sql);
            $res[$i]['submit']=$result['num'];

            $sql="SELECT COUNT(user_id) AS num FROM solution WHERE uid={$uid} AND in_date>'$start_day' AND in_date<'$end_day' AND result=4";
            $result=MySqlQuery($sql);
            $res[$i]['solve']=$result['num'];

            $res[$i][pass]=round($res[$i]['solve']/$res[$i]['submit']*100, 2);
        }

    }
    else if($time_type==3)              //获取本月历史提交
    {
        $end_day=date("Y-m-d H:i:s");
        $day=date("d")-1;
        $start_day=date("Y-m-d 0:0:0", strtotime("-$day days"));

        for ($i=0; $i<$cnt; $i++)
        {
            $res[$i]['submit']=0;
            $res[$i]['solve']=0;
            $res[$i]['pass']=0;

            $uid=$res[$i]['uid'];
            $sql="SELECT COUNT(user_id) AS num FROM solution WHERE uid={$uid} AND in_date>'$start_day' AND in_date<'$end_day'";
            $result=MySqlQuery($sql);
            $res[$i]['submit']=$result['num'];

            $sql="SELECT COUNT(user_id) AS num FROM solution WHERE uid={$uid} AND in_date>'$start_day' AND in_date<'$end_day' AND result=4";
            $result=MySqlQuery($sql);
            $res[$i]['solve']=$result['num'];

            $res[$i][pass]=round($res[$i]['solve']/$res[$i]['submit']*100, 2);
        }
    }
    else if($time_type==4)              //获取上月历史提交
    {
        $mon=date("m")-1;
        $start_day=date("Y-m-1 0:0:0", strtotime("-1 month"));
        $firstday = date("Y-m-01");
        $end_day = date("Y-m-d 0:0:0",strtotime("$firstday -1 day"));

        for ($i=0; $i<$cnt; $i++)
        {
            $res[$i]['submit']=0;
            $res[$i]['solve']=0;
            $res[$i]['pass']=0;

            $uid=$res[$i]['uid'];
            $sql="SELECT COUNT(user_id) AS num FROM solution WHERE uid={$uid} AND in_date>'$start_day' AND in_date<'$end_day'";
            $result=MySqlQuery($sql);
            $res[$i]['submit']=$result['num'];

            $sql="SELECT COUNT(user_id) AS num FROM solution WHERE uid={$uid} AND in_date>'$start_day' AND in_date<'$end_day' AND result=4";
            $result=MySqlQuery($sql);
            $res[$i]['solve']=$result['num'];

            $res[$i][pass]=round($res[$i]['solve']/$res[$i]['submit']*100, 2);
        }
    }
    else if($time_type==5)      //任意时间段历史提交
    {
        $start_day=GetParam('start');
        $end_day=GetParam('end');

        if($start_day=="" || $end_day=="")      //没有接收到前端传的参数，返回历史提交
        {
            for ($i=0; $i<$cnt; $i++)
            {
                $res[$i]['submit']=0;
                $res[$i]['solve']=0;
                $res[$i]['pass']=0;

                $uid=$res[$i]['uid'];
                $sql="SELECT COUNT(user_id) AS num FROM solution WHERE uid={$uid}";
                $result=MySqlQuery($sql);
                $res[$i]['submit']=$result['num'];

                $sql="SELECT COUNT(user_id) AS num FROM solution WHERE uid={$uid} AND result=4";
                $result=MySqlQuery($sql);
                $res[$i]['solve']=$result['num'];

                $res[$i][pass]=round($res[$i]['solve']/$res[$i]['submit']*100, 2);
            }
        }
        else        //返回任意时间段的历史提交
        {
            for ($i=0; $i<$cnt; $i++)
            {
                $res[$i]['submit']=0;
                $res[$i]['solve']=0;
                $res[$i]['pass']=0;

                $uid=$res[$i]['uid'];
                $sql="SELECT COUNT(user_id) AS num FROM solution WHERE uid={$uid} AND in_date>'$start_day' AND in_date<'$end_day'";
                $result=MySqlQuery($sql);
                $res[$i]['submit']=$result['num'];

                $sql="SELECT COUNT(user_id) AS num FROM solution WHERE uid={$uid} AND in_date>'$start_day' AND in_date<'$end_day' AND result=4";
                $result=MySqlQuery($sql);
                $res[$i]['solve']=$result['num'];

                $res[$i][pass]=round($res[$i]['solve']/$res[$i]['submit']*100, 2);
            }
        }
    }
    if($sort_type==0)   //  按提交数排序
    {
        $res=commit_sort($res, 'submit', SORT_DESC, 'solve', SORT_DESC);
    }
    else if($sort_type==1)      //按通过数排序
    {
        $res=commit_sort($res, 'solve', SORT_DESC, 'submit', SORT_ASC);
    }
    else if($sort_type==2)      //按通过率排序
    {
        $res=commit_sort($res, 'pass', SORT_DESC, 'solve', SORT_DESC);
    }
    OutPutList(true, "", $res);
}

function commit_sort($arrays,$sort_key1, $order1, $sort_key2, $order2)
{
    $type=SORT_REGULAR;
    if(is_array($arrays))
    {
        foreach ($arrays as $array)
        {
            if(is_array($array))
            {
                $key_arrays1[] = $array["$sort_key1"];
                $key_arrays2[] = $array["$sort_key2"];
            }
            else
            {
                return false;
            }
        }
    }else
    {
        return false;
    }
    array_multisort($key_arrays1,$order1, $key_arrays2, $order2,  $arrays);
    return $arrays;
}

?>