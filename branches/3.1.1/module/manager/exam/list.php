<?php

	Privilege(StudentGetCoursesExamList,TeacherGetCoursesExamList,TeacherGetCoursesExamList);

	/*
	 * 获取测验情况
	 */

	function StudentGetCoursesExamList()
	{
		$uid = GetCookie('uid');
		$courseid = GetParam('courseid',20001);
		$arr = Page('courses_exam','exam_id',"where  courses_id = '$courseid'",'exam_id,exam_name,create_time,stop_time','desc');
		//现在查询总题数
		for($i = 0 ; $i < count($arr) ; $i++){
			$examid = $arr[$i]['exam_id'];
			$sql = "select * from courses_exam_problem where exam_id = '$examid'";
			$arr[$i]['problem_count'] = count(MysqlQuerys($sql));

			//现获取该课程对应的学生总数
			$sql = "Select sum(class.studentnum) from class,courses_class where class.class_id = courses_class.class_id and courses_class.courses_id = 73";
			$res = MysqlQuery($sql);
			$arr[$i]['student_count'] = $res['sum(class.studentnum)'];

			//老师需要
			//现在是计算完成的人数 只要提交过就算完成了 （因为要计算分数）problem_belong = 1 exam 2 exam 0 默认

			$sql = "SELECT * FROM solution WHERE exam_id = {$examid} and problem_belong = '1' and result = '4'";
			$arr[$i]['pass_student_count'] = count(MysqlQuerys($sql));


			//学生需要

			$sql = "select count(DISTINCT problem_id) as num from solution where result = '4' AND uid = {$uid} AND
					problem_id in(
				select problem_id from courses_exam_problem where exam_id = {$examid}

			)" ;
			$temp = MySqlQuery($sql) ;
			$arr[$i]['solved_count'] = $temp['num'] ;

			//判断是否结束了 就是判断一下当前时间是否超过测验的结束时间 如果是的话就返回0（已结束），不是就返回1（进行中）
			$stoptime = strtotime($arr[$i]['stop_time']);
			if($stoptime >= time()){
				$arr[$i]['status'] = 1;
			}else{
				$arr[$i]['status'] = 0;
			}

		}
		OutPutList(true,null,$arr);
	}


	function TeacherGetCoursesExamList(){
		//$uid = GetCookie('uid');
        global  $memcache;
        $interval = 60 * 60 * 24;  // 一天
		$courseid = GetParam('courseid',20001);


        $arr = Page('courses_exam','exam_id',"where  courses_id = '$courseid'",'exam_id,exam_name,create_time,stop_time, all_num as student_count','desc');
       // echo  json_encode($arr) . '<br/>';
       // exit(0);
        //现在查询总题数


        for($i = 0 ; $i < count($arr) ; $i++){
            $examid = $arr[$i]['exam_id'];
//            echo  $examid . '<br/>';
            // 判断测验结束时间  如果已经结束 则加入缓存
            $stoptime = strtotime($arr[$i]['stop_time']);
            if($stoptime < time()){
                $arr[$i]['status'] = 0;

                // 添加缓存
                $memkey = 'exam_list:exam_id:'.$examid;
                $memarr = $memcache->get($memkey);
                if($memarr){  // 直接从缓存中取

                    $arr[$i]=$memarr;

                }else {   // 超过缓存时间 重新缓存

                    $sql = "select problem_id from courses_exam_problem where exam_id = '$examid'";
                    $res = MysqlQuerys($sql);

                    $arr[$i]['problem_count'] = count($res);

                    // 获得 班级学号前缀  可能有多个班级
                    $sql = "select class_id from courses_class where courses_id = $courseid";
                    $classes = MysqlQuerys($sql);
                    $class_str = ''; // 拼接sql语句
                    for ($j = 0; $j < count($classes); $j++) {
                        if ($j === 0) {
                            $class_str = "user_id like '" . $classes[$j]['class_id'] . "%' ";
                        } else {
                            $class_str = $class_str . " or user_id like '" . $classes[$j]['class_id'] . "%' ";
                        }
                    }
                    // echo $class_str .' <br/>';
                    //  获取  每道题通过的 uid  进行 相交 可获取 所有题通过的人数
                    $pass_uid = array();
                    for ($j = 0; $j < count($res); $j++) {
                        $problem_id = $res[$j]['problem_id'];

                        $sql = "select DISTINCT(uid) from solution where 1=1 and ($class_str) and result = 4 and problem_id = {$res[$j]['problem_id']} ";

                        $pass = MysqlQuerys($sql);
                        for ($k = 0; $k < count($pass); $k++) {
                            $pass[$k] = $pass[$k]['uid'];
                        }
                        if ($j === 0) {
                            $pass_uid = $pass;
                        } else {
                            // 取交集
                            $pass_uid = array_intersect($pass_uid, $pass);

                        }

                    }
                    $arr[$i]['pass_student_count'] = count($pass_uid);
                    // var_dump($pass_uid);

                    //   json_encode($arr[$i]);
                    $memcache->add($memkey, $arr[$i], $interval);
                }
            } else{
                // 正常查询
                $arr[$i]['status'] = 1;
                $sql = "select problem_id from courses_exam_problem where exam_id = '$examid'";
                $res = MysqlQuerys($sql);

                $arr[$i]['problem_count'] = count($res);

                // 获得 班级学号前缀  可能有多个班级
                $sql = "select class_id from courses_class where courses_id = $courseid";
                $classes = MysqlQuerys($sql);
                $class_str = ''; // 拼接sql语句
                for ($j = 0; $j < count($classes); $j++) {
                    if ($j === 0) {
                        $class_str = "user_id like '" . $classes[$j]['class_id'] . "%' ";
                    } else {
                        $class_str = $class_str . " or user_id like '" . $classes[$j]['class_id'] . "%' ";
                    }
                }
                // echo $class_str .' <br/>';
                //  获取  每道题通过的 uid  进行 相交 可获取 所有题通过的人数
                $pass_uid = array();
                for ($j = 0; $j < count($res); $j++) {
                    $problem_id = $res[$j]['problem_id'];

                    $sql = "select DISTINCT(uid) from solution where 1=1 and ($class_str) and result = 4 and problem_id = {$res[$j]['problem_id']} ";

                    $pass = MysqlQuerys($sql);
                    for ($k = 0; $k < count($pass); $k++) {
                        $pass[$k] = $pass[$k]['uid'];
                    }
                    if ($j === 0) {
                        $pass_uid = $pass;
                    } else {
                        // 取交集
                        $pass_uid = array_intersect($pass_uid, $pass);

                    }

                }
                $arr[$i]['pass_student_count'] = count($pass_uid);
            }
        }
        OutPutList(true,null,$arr);
	}
?>