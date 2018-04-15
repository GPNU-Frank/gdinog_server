<?php
    $user_id = GetParam("user_id",20001);   //获取user_id
    $source = GetParam('source',20001); //获取源代码
    $language = GetParam('language',20001,null,true) ; //获取语言类型
    //$now=strftime("%Y-%m-%d %H:%M",time()); //现在的时间
    //$cid = GetParam("cid"); //竞赛id
    $pid = GetParam('pid',20001); //题目id

    $len = strlen($source);               //获取代码长度
    $ip=$_SERVER['REMOTE_ADDR'];         //获得IP地址

/*
    if($cid){ //竞赛
        $sql = "SELECT `problem_id` FROM `contest_problem` WHERE `num` = '$pid' AND contest_id = $cid" ;
    }else{//平时题目
        $sql = "SELECT `problem_id` FROM `problem` WHERE `problem_id` NOT IN (
                SELECT DISTINCT problem_id FROM contest_problem WHERE `contest_id` IN (
                SELECT `contest_id` FROM `contest` WHERE ( `end_time` > '$now' OR private = 1 ) AND 
                `defunct` = `N`))" ;


    }
*/
    /*
     * 几种情况不能再提交代码
     * 1.竞赛题目被修改或者被撤销
     * 2.题目列表中的题目已经被修改
     * 3.权限变为私有
     *
     */
    /*
    echo $sql;
    $res = MySqlQuerys($sql);
    if( count($res) == 0 )
    {
        echo '题目不存在或者已经被修改' ;
        exit(0) ;
    }
    */
    /*
     *
     * 如果竞赛权限为私有，检查该用户是否可以进行提交
     *
     */

   /* if($pid && $cid){
        //检查用户是否私有
        $sql = "SELECT `private` FROM `contest` WHERE `contest_id` = '$cid' AND `start_time` <= '$now'
          AND `end_time` > '$now'";

        $result = mysqli_query($link,$sql) ;
        $rows_cnt = mysqli_num_rows($result) ;
        if( $rows_cnt != 1 )
        {
            echo "You Can't Submit Now Because Your are not invited by the contest or the contest is not running!!";
            mysqli_free_result($result) ;

            exit(0) ;
        }
        else
        {
            $row = mysqli_fetch_array($result) ;
            $isprivate = intval($row[0]) ;
            mysqli_free_result($result) ;
            if( $isprivate == 1 )
            {
                $sql = "SELECT count(*) FROM `privilege` WHERE
                  `user_id` = '$user_id' AND `rightstr` = 'c$cid' ";
                $result = mysqli_query($link,$sql) ;
                $row = mysqli_fetch_array($result) ;
                $ccnt = intval($row[0]) ;
                mysqli_free_result($result) ;
                if( ccnt == 0 )
                {
                    $view_errors = "You are not invited!\n" ;
                    exit(0) ;
                }
            }
        }
    }*/
    
    //插入到数据库
    /*if($cid){
        echo "插入到有比赛中" ;
        $sql = "INSERT INTO solution(`problem_id` , `user_id` , `in_date` , `language` , `ip` , `code_length` , `contest_id` , `num` )
          VALUES('$pid' , '$user_id' , NOW() , '$language' , '$ip' , '$len' , '$cid' , '$pid' ) "  ;
    }else{*/
        $time = date('Y-m-d H:i:s',time()) ;
        $sql="INSERT INTO solution(problem_id,user_id,in_date,language,ip,code_length )
            VALUES($pid,'$user_id', '$time'  ,$language,'$ip',$len)";
    //}

    $insert_id = MySqlInsert($sql) ;
    //添加到用户代码储存库 后端叛题需要用到
    MySqlInsert("INSERT INTO source_code(`solution_id` , `source` )VALUES('$insert_id' , '$source')") ;
    OutPut(true);
?>