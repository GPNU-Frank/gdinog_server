<?php
    /*
        用于用户提交代码
        @protype 提交的题目类型 1.填空 2选择 3代码 4代码填空
        @language 0 c
    */

    //$user_id = GetParam('user_id',10011,GetCookie('user_id'));  //获取user_id\
    //http://localhost/gdinoj/trunk/server/?action=problem.submit&cookie=9ea2da4548aa587bc128cd2ef315a70c&pid=1525&protype=5&source=d&protype=1&exam_id=80
    //affdf85d0f6e8fe8993c97227c62164c
    submit() ;
    function submit()
    {
        if(  $_REQUEST['exam_id'] != null )
            examproblem() ;
        else if( $_REQUEST['contest_id'] != null )
            contestproblem() ;
        else
            usualproblem() ;


    }
	


    //1.0暂时不考虑
    //竞赛提交函数
    function contestproblem()
    {
        $uid = GetCookie('uid');
        $user_id = GetCookie('user_id');
        $contest_id=GetParam('contest_id');


        $pid = GetParam('pid',20001);                           //题目id
        $protype = GetParam('protype',20001,null,true);         //提交的题目类型

        if($protype == '3') {
            $source = str_replace("\\", "\\\\", GetParam('source', 20001));                     //用户提交的答案(选择题也是这个参数)
            $source = str_replace("'", "\'" ,$source ) ;
        }else
        {
            $source=GetParam('source', 20001);
            $source = str_replace("'", "\'" ,$source ) ;
        }


        $ip=$_SERVER['REMOTE_ADDR'];                           //获得IP地址
        $date = strftime("%Y-%m-%d %H:%M",time());
        $time = date('Y-m-d H:i:s',time()) ;

        switch ($protype) {

            case '1'://填空题
                $result = MySqlQuery("select sample_output from problem WHERE  problem_id = ".$pid) ;
                $sample_output=explode(',', $result['sample_output']);
                $source = explode(',',$source);
                $flag=true;
                for($i = 0 ; $i < Count($source) ; $i++)
                {
                    if($source[$i]!=$sample_output[$i])
                    {
                        $flag=false;
                        break;
                    }
                }
                $source=implode(',', $source);
                if($flag)
                {
                    $insert_id=MySqlInsert("INSERT INTO solution(uid,user_id , problem_id  , ip , judgetime , protype , result, in_date, contest_id) VALUES('$uid','$user_id' , '$pid' , '$ip' , '$date' , '$protype' , '4', '$time', '$contest_id' ) ") ;
                    //添加内容到用户代码库，后端叛题需要用到。
                    MySqlInsert("insert into source_code(solution_id, source) values('$insert_id', '$source')");
                    $result['accept'] = 1 ;

                    $sql="UPDATE problem SET accepted=accepted+1, submit=submit+1 WHERE problem_id=$pid";
                    MySqlUpdate($sql);
                }
                else
                {
                    $insert_id=MySqlInsert("INSERT INTO solution(uid,user_id , problem_id  , ip , judgetime , protype , result, in_date, contest_id  ) VALUES( '$uid','$user_id' , '$pid' , '$ip' , '$date' , '$protype' , '6', '$time', '$contest_id' ) ") ;
                    //添加内容到用户代码库，后端叛题需要用到。
                    MySqlInsert("insert into source_code(solution_id, source) values('$insert_id', '$source')");
                    $result['accept'] = 0 ;

                    $sql="UPDATE problem SET submit=submit+1 WHERE problem_id=$pid";
                    MySqlUpdate($sql);
                }
                $res['accept'] = $result['accept'] ;
                OutPut(true, "",  $res);

                break;
            case '2': //选择题
                $result = MySqlQuery("select sample_output from problem WHERE  problem_id = ".$pid) ;
                if( $source == $result['sample_output'] ){//与标准答案比对
                    $insert_id=MySqlInsert("INSERT INTO solution(uid,user_id , problem_id  , ip , judgetime , protype , result , in_date, contest_id ) VALUES('$uid','$user_id' , '$pid' , '$ip' , '$date' , '$protype' , '4', '$time', '$contest_id' ) ") ;
                    //添加内容到用户代码库，后端叛题需要用到。
                    MySqlInsert("insert into source_code(solution_id, source) values('$insert_id', '$source')");
                    $result['accept'] = 1;

                    $sql="UPDATE problem SET accepted=accepted+1, submit=submit+1 WHERE problem_id=$pid";
                    MySqlUpdate($sql);

                }else
                {
                    $insert_id=MySqlInsert("INSERT INTO solution(uid,user_id , problem_id  , ip , judgetime , protype , result , in_date , contest_id) VALUES( '$uid','$user_id' , '$pid' , '$ip' , '$date' , '$protype' , '6', '$time', '$contest_id' ) ") ;
                    //添加内容到用户代码库，后端叛题需要用到。
                    MySqlInsert("insert into source_code(solution_id, source) values('$insert_id', '$source')");
                    $result['accept'] = 0;

                    $sql="UPDATE problem SET submit=submit+1 WHERE problem_id=$pid";
                    MySqlUpdate($sql);
                }
                $res['accept'] = $result['accept'] ;
                OutPut(true, "",  $res);
                break;
            case '3'://代码题
                $sql="SELECT last_submit FROM users WHERE uid=$uid";
                $res=MySqlQuery($sql);
                if(($res['last_submit']!=null && time()-strtotime($res['last_submit'])>30) || $res['last_submit']==null)
                {
                    $language = GetParam('language',20001,null,true) ;      //获取语言类型
                    //添加信息到用户代码列表（不包含内容）
                    $insert_id = MySqlInsert("INSERT INTO solution( protype, problem_id,uid,user_id,in_date,language,problem_belong , ip,code_length, contest_id) VALUES( 3, '$pid','$uid','$user_id', '$time'  ,'$language', '0' ,'$ip','".strlen($source)."', '$contest_id')") ;
                    //添加内容到用户代码库，后端叛题需要用到。
                    $sql = "INSERT INTO source_code(`solution_id` , `source` )VALUES('$insert_id' , '$source')" ;
                    MySqlInsert($sql) ;
                    //更新用户最近提交时间
                    $sql="UPDATE users SET last_submit='$time' WHERE uid=$uid";
                    MySqlUpdate($sql);
					
                    OutPut(true);
					
					
                }
                else
                {
                    OutPut(false, 50008);
                }
                break;
            case '4': //代码类型的填空题(需判题)
                OutPut(false,50000);
                break;
            case '5'://判断题
                $result = MySqlQuery("select sample_output from problem WHERE  problem_id = ".$pid) ;
                if( $source == $result['sample_output'] ){//与标准答案比对
                    $insert_id=MySqlInsert("INSERT INTO solution(uid,user_id , problem_id  , ip , judgetime , protype , result , in_date, contest_id) VALUES('$uid','$user_id' , '$pid' , '$ip' , '$date' , '$protype' , '4', '$time', '$contest_id' ) ") ;
                    //添加内容到用户代码库，后端叛题需要用到。
                    MySqlInsert("insert into source_code(solution_id, source) values('$insert_id', '$source')");
                    $result['accept'] = 1 ;

                    $sql="UPDATE problem SET accepted=accepted+1, submit=submit+1 WHERE problem_id=$pid";
                    MySqlUpdate($sql);

                }else
                {
                    $insert_id=MySqlInsert("INSERT INTO solution(uid,user_id , problem_id  , ip , judgetime , protype , result , in_date, contest_id ) VALUES( '$uid','$user_id' , '$pid' , '$ip' , '$date' , '$protype' , '6' , '$time', '$contest_id') ") ;
                    //添加内容到用户代码库，后端叛题需要用到。
                    MySqlInsert("insert into source_code(solution_id, source) values('$insert_id', '$source')");
                    $result['accept'] = 0 ;

                    $sql="UPDATE problem SET submit=submit+1 WHERE problem_id=$pid";
                    MySqlUpdate($sql);
                }
                $res['accept'] = $result['accept'] ;
                OutPut(true, "",  $res);
                break;
            default:
                OutPut(false,50000);
                break;
        }
    }


/**
 *
 *
 *
 */
    //测验提交函数
    function examproblem()
    {
        $uid = GetCookie('uid');
        $user_id = GetCookie('user_id');
        $exam_id=GetParam('exam_id');


        $pid = GetParam('pid',20001);                           //题目id
        $protype = GetParam('protype',20001,null,true);         //提交的题目类型

        if($protype == '3') {
            $source = str_replace("\\", "\\\\", GetParam('source', 20001));                     //用户提交的答案(选择题也是这个参数)
            $source = str_replace("'", "\'" ,$source ) ;
        }else
        {
            $source=GetParam('source', 20001);
            $source = str_replace("'", "\'" ,$source ) ;
        }


        $ip=$_SERVER['REMOTE_ADDR'];                           //获得IP地址
        $date = strftime("%Y-%m-%d %H:%M",time());
        $time = date('Y-m-d H:i:s',time()) ;

        switch ($protype) {

            case '1'://填空题
                $result = MySqlQuery("select sample_output from problem WHERE  problem_id = ".$pid) ;
                $sample_output=explode(',', $result['sample_output']);
                $source = explode(',',$source);
                $flag=true;
                for($i = 0 ; $i < Count($source) ; $i++)
                {
                    if($source[$i]!=$sample_output[$i])
                    {
                        $flag=false;
                        break;
                    }
                }
                $source=implode(',', $source);
                if($flag)
                {
                    $insert_id=MySqlInsert("INSERT INTO solution(uid,user_id , problem_id  , ip , judgetime , protype , result, in_date, exam_id) VALUES('$uid','$user_id' , '$pid' , '$ip' , '$date' , '$protype' , '4', '$time', '$exam_id' ) ") ;
                    //添加内容到用户代码库，后端叛题需要用到。
                    MySqlInsert("insert into source_code(solution_id, source) values('$insert_id', '$source')");
                    $result['accept'] = 1 ;

                    $sql="UPDATE problem SET accepted=accepted+1, submit=submit+1 WHERE problem_id=$pid";
                    MySqlUpdate($sql);
                }
                else
                {
                    $insert_id=MySqlInsert("INSERT INTO solution(uid,user_id , problem_id  , ip , judgetime , protype , result, in_date, exam_id  ) VALUES( '$uid','$user_id' , '$pid' , '$ip' , '$date' , '$protype' , '6', '$time', '$exam_id' ) ") ;
                    //添加内容到用户代码库，后端叛题需要用到。
                    MySqlInsert("insert into source_code(solution_id, source) values('$insert_id', '$source')");
                    $result['accept'] = 0 ;

                    $sql="UPDATE problem SET submit=submit+1 WHERE problem_id=$pid";
                    MySqlUpdate($sql);
                }
                $res['accept'] = $result['accept'] ;
                OutPut(true, "",  $res);

                break;
            case '2': //选择题
                $result = MySqlQuery("select sample_output from problem WHERE  problem_id = ".$pid) ;
                if( $source == $result['sample_output'] ){//与标准答案比对
                    $insert_id=MySqlInsert("INSERT INTO solution(uid,user_id , problem_id  , ip , judgetime , protype , result , in_date, exam_id ) VALUES('$uid','$user_id' , '$pid' , '$ip' , '$date' , '$protype' , '4', '$time', '$exam_id' ) ") ;
                    //添加内容到用户代码库，后端叛题需要用到。
                    MySqlInsert("insert into source_code(solution_id, source) values('$insert_id', '$source')");
                    $result['accept'] = 1;

                    $sql="UPDATE problem SET accepted=accepted+1, submit=submit+1 WHERE problem_id=$pid";
                    MySqlUpdate($sql);

                }else
                {
                    $insert_id=MySqlInsert("INSERT INTO solution(uid,user_id , problem_id  , ip , judgetime , protype , result , in_date , exam_id) VALUES( '$uid','$user_id' , '$pid' , '$ip' , '$date' , '$protype' , '6', '$time', 'exam_id' ) ") ;
                    //添加内容到用户代码库，后端叛题需要用到。
                    MySqlInsert("insert into source_code(solution_id, source) values('$insert_id', '$source')");
                    $result['accept'] = 0;

                    $sql="UPDATE problem SET submit=submit+1 WHERE problem_id=$pid";
                    MySqlUpdate($sql);
                }
                $res['accept'] = $result['accept'] ;
                OutPut(true, "",  $res);
                break;
            case '3'://代码题
                $sql="SELECT last_submit FROM users WHERE uid=$uid";
                $res=MySqlQuery($sql);
                if(($res['last_submit']!=null && time()-strtotime($res['last_submit'])>30) || $res['last_submit']==null)
                {
                    $language = GetParam('language',20001,null,true) ;      //获取语言类型
                    //添加信息到用户代码列表（不包含内容）
                    $insert_id = MySqlInsert("INSERT INTO solution( protype, problem_id,uid,user_id,in_date,language,problem_belong , ip,code_length, exam_id) VALUES( 3, '$pid','$uid','$user_id', '$time'  ,'$language', '0' ,'$ip','".strlen($source)."', '$exam_id')") ;
                    //添加内容到用户代码库，后端叛题需要用到。
                    $sql = "INSERT INTO source_code(`solution_id` , `source` )VALUES('$insert_id' , '$source')" ;
                    MySqlInsert($sql) ;
                    //更新用户最近提交时间
                    $sql="UPDATE users SET last_submit='$time' WHERE uid=$uid";
                    MySqlUpdate($sql);
					

                    OutPut(true);
                }
                else
                {
                    OutPut(false, 50008);
                }
                break;
            case '4': //代码类型的填空题(需判题)
                OutPut(false,50000);
                break;
            case '5'://判断题
                $result = MySqlQuery("select sample_output from problem WHERE  problem_id = ".$pid) ;
                if( $source == $result['sample_output'] ){//与标准答案比对
                    $insert_id=MySqlInsert("INSERT INTO solution(uid,user_id , problem_id  , ip , judgetime , protype , result , in_date, exam_id) VALUES('$uid','$user_id' , '$pid' , '$ip' , '$date' , '$protype' , '4', '$time', '$exam_id' ) ") ;
                    //添加内容到用户代码库，后端叛题需要用到。
                    MySqlInsert("insert into source_code(solution_id, source) values('$insert_id', '$source')");
                    $result['accept'] = 1 ;

                    $sql="UPDATE problem SET accepted=accepted+1, submit=submit+1 WHERE problem_id=$pid";
                    MySqlUpdate($sql);

                }else
                {
                    $insert_id=MySqlInsert("INSERT INTO solution(uid,user_id , problem_id  , ip , judgetime , protype , result , in_date, exam_id ) VALUES( '$uid','$user_id' , '$pid' , '$ip' , '$date' , '$protype' , '6' , '$time', '$exam_id') ") ;
                    //添加内容到用户代码库，后端叛题需要用到。
                    MySqlInsert("insert into source_code(solution_id, source) values('$insert_id', '$source')");
                    $result['accept'] = 0 ;

                    $sql="UPDATE problem SET submit=submit+1 WHERE problem_id=$pid";
                    MySqlUpdate($sql);
                }
                $res['accept'] = $result['accept'] ;
                OutPut(true, "",  $res);
                break;
            default:
                OutPut(false,50000);
                break;
        }
    }

    //普通提交函数
    function usualproblem()
    {
        $uid = GetCookie('uid');
        $user_id = GetCookie('user_id');

        $pid = GetParam('pid',20001);                           //题目id
        $protype = GetParam('protype',20001,null,true);         //提交的题目类型

        if($protype == '3')
        {
            $source = str_replace("\\", "\\\\", GetParam('source', 20001));                     //用户提交的答案(选择题也是这个参数)
            $source = str_replace("'", "\'" ,$source ) ;
        }else
        {
            $source=GetParam('source', 20001);
            $source = str_replace("'", "\'" ,$source ) ;
        }


        $ip=$_SERVER['REMOTE_ADDR'];                            //获得IP地址
        $date = strftime("%Y-%m-%d %H:%M",time());
        $time = date('Y-m-d H:i:s',time()) ;

        switch ($protype) {

            case '1'://填空题
                $result = MySqlQuery("select sample_output from problem WHERE  problem_id = ".$pid) ;
                $sample_output=explode(',', $result['sample_output']);
                $source = explode(',',$source);
                $flag=true;
                for($i = 0 ; $i < Count($source) ; $i++)
                {
                    if($source[$i]!=$sample_output[$i])
                    {
                        $flag=false;
                        break;
                    }
                }
                $source=implode(',', $source);
                if($flag)
                {
                    $insert_id=MySqlInsert("INSERT INTO solution(uid,user_id , problem_id  , ip , judgetime , protype , result, in_date) VALUES('$uid','$user_id' , '$pid' , '$ip' , '$date' , '$protype' , '4', '$time' ) ") ;
                    //添加内容到用户代码库，后端叛题需要用到。
                    MySqlInsert("insert into source_code(solution_id, source) values('$insert_id', '$source')");
                    $result['accept'] = 1 ;

                    $sql="UPDATE problem SET accepted=accepted+1, submit=submit+1 WHERE problem_id=$pid";
                    MySqlUpdate($sql);
                }
                else
                {
                    $insert_id=MySqlInsert("INSERT INTO solution(uid,user_id , problem_id  , ip , judgetime , protype , result, in_date  ) VALUES( '$uid','$user_id' , '$pid' , '$ip' , '$date' , '$protype' , '6', '$time' ) ") ;
                    //添加内容到用户代码库，后端叛题需要用到。
                    MySqlInsert("insert into source_code(solution_id, source) values('$insert_id', '$source')");
                    $result['accept'] = 0 ;

                    $sql="UPDATE problem SET submit=submit+1 WHERE problem_id=$pid";
                    MySqlUpdate($sql);
                }
                $res['accept'] = $result['accept'] ;
                OutPut(true, "",  $res);

                break;
            case '2': //选择题
                $result = MySqlQuery("select sample_output from problem WHERE  problem_id = ".$pid) ;
                if( $source == $result['sample_output'] ){//与标准答案比对
                    $insert_id=MySqlInsert("INSERT INTO solution(uid,user_id , problem_id  , ip , judgetime , protype , result , in_date ) VALUES('$uid','$user_id' , '$pid' , '$ip' , '$date' , '$protype' , '4', '$time' ) ") ;
                    //添加内容到用户代码库，后端叛题需要用到。
                    MySqlInsert("insert into source_code(solution_id, source) values('$insert_id', '$source')");
                    $result['accept'] = 1;

                    $sql="UPDATE problem SET accepted=accepted+1, submit=submit+1 WHERE problem_id=$pid";
                    MySqlUpdate($sql);

                }else
                {
                    $insert_id=MySqlInsert("INSERT INTO solution(uid,user_id , problem_id  , ip , judgetime , protype , result , in_date ) VALUES( '$uid','$user_id' , '$pid' , '$ip' , '$date' , '$protype' , '6', '$time' ) ") ;
                    //添加内容到用户代码库，后端叛题需要用到。
                    MySqlInsert("insert into source_code(solution_id, source) values('$insert_id', '$source')");
                    $result['accept'] = 0;

                    $sql="UPDATE problem SET submit=submit+1 WHERE problem_id=$pid";
                    MySqlUpdate($sql);
                }
                $res['accept'] = $result['accept'] ;
                OutPut(true, "",  $res);
                break;
            case '3'://代码题
                $language = GetParam('language',20001,null,true) ;      //获取语言类型

                $sql="SELECT last_submit FROM users WHERE uid=$uid";
                $res=MySqlQuery($sql);
                if(($res['last_submit']!=null && time()-strtotime($res['last_submit'])>30) || $res['last_submit']==null)
                {
                    $language = GetParam('language', 20001, null, true);      //获取语言类型
                    //添加信息到用户代码列表（不包含内容）
                    $insert_id = MySqlInsert("INSERT INTO solution( protype, problem_id,uid,user_id,in_date,language,problem_belong , ip,code_length) VALUES( 3, '$pid','$uid','$user_id', '$time'  ,'$language', '0' ,'$ip','" . strlen($source) . "')");
                    //添加内容到用户代码库，后端叛题需要用到。
                    $sql = "INSERT INTO source_code(`solution_id` , `source` )VALUES('$insert_id' , '$source')";
                    MySqlInsert($sql);

                    //更新用户最近提交时间
                    $sql="UPDATE users SET last_submit='$time' WHERE uid=$uid";
                    MySqlUpdate($sql);
					
                    OutPut(true);
                }
                else
                {
                    OutPut(false, 50008);
                }
                break;
            case '4': //代码类型的填空题(需判题)
                OutPut(false,50000);
                break;
            case '5'://判断题
                $result = MySqlQuery("select sample_output from problem WHERE  problem_id = ".$pid) ;
                if( $source == $result['sample_output'] ){//与标准答案比对
                    $insert_id=MySqlInsert("INSERT INTO solution(uid,user_id , problem_id  , ip , judgetime , protype , result , in_date) VALUES('$uid','$user_id' , '$pid' , '$ip' , '$date' , '$protype' , '4', '$time' ) ") ;
                    //添加内容到用户代码库，后端叛题需要用到。
                    MySqlInsert("insert into source_code(solution_id, source) values('$insert_id', '$source')");
                    $result['accept'] = 1 ;

                    $sql="UPDATE problem SET accepted=accepted+1, submit=submit+1 WHERE problem_id=$pid";
                    MySqlUpdate($sql);

                }else
                {
                    $insert_id=MySqlInsert("INSERT INTO solution(uid,user_id , problem_id  , ip , judgetime , protype , result , in_date ) VALUES( '$uid','$user_id' , '$pid' , '$ip' , '$date' , '$protype' , '6' , '$time') ") ;
                    //添加内容到用户代码库，后端叛题需要用到。
                    MySqlInsert("insert into source_code(solution_id, source) values('$insert_id', '$source')");
                    $result['accept'] = 0 ;

                    $sql="UPDATE problem SET submit=submit+1 WHERE problem_id=$pid";
                    MySqlUpdate($sql);
                }
                $res['accept'] = $result['accept'] ;
                OutPut(true, "",  $res);
                break;
            default:
                OutPut(false,50000);
                break;
        }
    }




?>