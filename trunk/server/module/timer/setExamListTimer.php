<?php
ignore_user_abort(); // 第一次打开后  后台运行
set_time_limit(0); // run script. forever
$interval=60 * 60 * 2 ; // do every 10 minutes...
require_once "./framework/include/conn.php"; // 加载 连接文件
//require_once "./module/manager/exam/list.php";

$pagesize=10;  // 根据前端给的数据   根据需要修改
print '计时器开始';
do{
    // 查询所有的课程
    $sql = 'select courses_id from courses  where open = 0';
    $courses = MysqlQuerys($sql);

    // 对于 每一个课程
    for($i = 0 ; $i < count($courses) ; ++$i){
     //   print $courses[$i]['courses_id'];
        $courseid = $courses[$i]['courses_id'];
        $key = 'exam_list:course_id:'.$courseid ;
        //  查出 课程相应的结果 待修改

        $arr = Page('courses_exam','exam_id',"where  courses_id = '$courseid'",'exam_id,exam_name,create_time,stop_time','desc');
        //现在查询总题数
        for($i = 0 ; $i < count($arr) ; $i++){
            $examid = $arr[$i]['exam_id'];
            $sql = "select * from courses_exam_problem where exam_id = '$examid'";
            $arr[$i]['problem_count'] = count(MysqlQuerys($sql));

            //现获取该课程对应的学生总数
            $sql = "Select sum(class.studentnum) from class,courses_class where class.class_id = courses_class.class_id and courses_class.courses_id = $courseid";
            //echo $sql;
            $res = MysqlQuery($sql);
            // echo $courseid;
            $arr[$i]['student_count'] = $res['sum(class.studentnum)'];


            //现在是计算完成的人数 只有全部完成才算完成了 problem_belong = 1 exam 2 exam 0 默认
            //首先获得测验的题目数
            $sql = "select count(*) as num from courses_exam_problem where exam_id = '$examid'" ;

            $res = MySqlQuery($sql) ;
            $problemcnt = $res['num'] ;

            //获得学生的列表
            $sql="	SELECT uid from users where class_id in (
						SELECT class_id from courses_class where courses_id in (
							SELECT courses_id from courses_exam where exam_id = {$examid}
						)
					)  ORDER BY uid" ;

            $res = MySqlQuerys($sql) ;
            $cnt = 0 ;
            foreach($res as $item )
            {
                $studentid = $item['uid'] ;
                $sql = "select count(DISTINCT problem_id) as num  from solution where result = 4 AND uid = '$studentid' AND
							problem_id in(
						select problem_id from courses_exam_problem where exam_id = {$examid}

					) " ;
                $res = MySqlQuery($sql) ;
                $num = $res['num'] ;
                if( $num == $problemcnt )
                {
                    //echo $studentid."<br>" ;
                    $cnt++ ;
                }

            }
            $arr[$i]['pass_student_count'] = $cnt ;

            //判断是否结束了 就是判断一下当前时间是否超过测验的结束时间 如果是的话就返回0（已结束），不是就返回1（进行中）
            $stoptime = strtotime($arr[$i]['stop_time']);
            if($stoptime >= time()){
                $arr[$i]['status'] = 1;
            }else{
                $arr[$i]['status'] = 0;
            }

        }

        // 添加到缓存中
        $memcache->add($key,$arr,$interval); //缓存10分钟
        //OutPutList(true,null,$arr);
        ///////////////////////////////
    }

    sleep($interval);

}while(true);
print '计时器结束';
?>