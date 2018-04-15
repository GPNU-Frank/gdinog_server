<?php
    /*
        用于用户提交代码
        @protype 提交的题目类型 1.填空 2选择 3代码 4代码填空
        @language 0 c
    */

    //$user_id = GetParam('user_id',10011,GetCookie('user_id'));  //获取user_id\
    //http://localhost/gdinoj/trunk/server/?action=problem.submit&cookie=9ea2da4548aa587bc128cd2ef315a70c&pid=1525&protype=5&source=d&protype=1&exam_id=80
    //affdf85d0f6e8fe8993c97227c62164c

    $insert_id = 0 ;
    submit() ;

    function submit()
    {
        if(  $_REQUEST['exam_id'] != null )
            examproblem() ;
        else if( $_REQUEST['contest_id'] != null )
            contestproblem() ;
        else if( $_REQUEST['quiz_id'] != null)
            quizproblem();
        else
            usualproblem() ;


    }

    function submit_code_to_remote_server($solution_id)
    {
        $problem_id = GetParam('pid',20001);
        $source = GetParam('source', 20001);
        $language = GetParam('language',20001,null,true) ;
        $sql="select time_limit, memory_limit from problem where problem_id=$problem_id" ;
//        echo $sql ;$sql
        $res=MySqlQuery($sql);
        $time_limit=$res['time_limit'];
        $memory_limit=$res['memory_limit'];
        $curl=curl_init();
        $src = urlencode($source);
        $src = urlencode($src);
        $url="http://182.92.97.143:8086/gdinoj/judge/";
        $body = "time_limit=$time_limit&mem_limit=$memory_limit&problem_id=$problem_id&solution_id=$solution_id&code=$src&lang=$language&debug=0&judged_id=0";
        curl_setopt ( $curl, CURLOPT_URL, $url );
        curl_setopt ( $curl, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt ( $curl, CURLOPT_CONNECTTIMEOUT, 10 );
        curl_setopt ( $curl, CURLOPT_POST, 1 );
        curl_setopt ( $curl, CURLOPT_POSTFIELDS, $body );
        curl_setopt($curl, CURLOPT_HTTPHEADER,  array('Content-Type: text/plain'));
        curl_exec ( $curl );
        curl_close($curl);
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
                    $GLOBALS["insert_id"]=MySqlInsert("INSERT INTO solution(uid,user_id , problem_id  , ip , judgetime , protype , result, in_date, contest_id) VALUES('$uid','$user_id' , '$pid' , '$ip' , '$date' , '$protype' , '4', '$time', '$contest_id' ) ") ;
                    //添加内容到用户代码库，后端叛题需要用到。
                    MySqlInsert("insert into source_code(solution_id, source) values('{$GLOBALS["insert_id"]}', '$source')");
                    $result['accept'] = 1 ;

                    $sql="UPDATE problem SET accepted=accepted+1, submit=submit+1 WHERE problem_id=$pid";
                    MySqlUpdate($sql);
                    setContestFinish($contest_id, $uid, $pid, 4);
                }
                else
                {
                    $GLOBALS["insert_id"]=MySqlInsert("INSERT INTO solution(uid,user_id , problem_id  , ip , judgetime , protype , result, in_date, contest_id  ) VALUES( '$uid','$user_id' , '$pid' , '$ip' , '$date' , '$protype' , '6', '$time', '$contest_id' ) ") ;
                    //添加内容到用户代码库，后端叛题需要用到。
                    MySqlInsert("insert into source_code(solution_id, source) values('{$GLOBALS["insert_id"]}', '$source')");
                    $result['accept'] = 0 ;

                    $sql="UPDATE problem SET submit=submit+1 WHERE problem_id=$pid";
                    MySqlUpdate($sql);
                    setContestFinish($contest_id, $uid, $pid, 6);
                }
                $res['accept'] = $result['accept'] ;
                OutPut(true, "",  $res);

                break;
            case '2': //选择题
                $result = MySqlQuery("select sample_output from problem WHERE  problem_id = ".$pid) ;
                if( $source == $result['sample_output'] ){//与标准答案比对
                    $GLOBALS["insert_id"]=MySqlInsert("INSERT INTO solution(uid,user_id , problem_id  , ip , judgetime , protype , result , in_date, contest_id ) VALUES('$uid','$user_id' , '$pid' , '$ip' , '$date' , '$protype' , '4', '$time', '$contest_id' ) ") ;
                    //添加内容到用户代码库，后端叛题需要用到。
                    MySqlInsert("insert into source_code(solution_id, source) values('{$GLOBALS["insert_id"]}', '$source')");
                    $result['accept'] = 1;

                    $sql="UPDATE problem SET accepted=accepted+1, submit=submit+1 WHERE problem_id=$pid";
                    MySqlUpdate($sql);
                    setContestFinish($contest_id, $uid, $pid, 4);

                }else
                {
                    $GLOBALS["insert_id"]=MySqlInsert("INSERT INTO solution(uid,user_id , problem_id  , ip , judgetime , protype , result , in_date , contest_id) VALUES( '$uid','$user_id' , '$pid' , '$ip' , '$date' , '$protype' , '6', '$time', '$contest_id' ) ") ;
                    //添加内容到用户代码库，后端叛题需要用到。
                    MySqlInsert("insert into source_code(solution_id, source) values(`{$GLOBALS["insert_id"]}`, '$source')");
                    $result['accept'] = 0;

                    $sql="UPDATE problem SET submit=submit+1 WHERE problem_id=$pid";
                    MySqlUpdate($sql);
                    setContestFinish($contest_id, $uid, $pid, 6);
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
                    $sql = "INSERT INTO source_code(`solution_id` , `source` )VALUES($insert_id , '$source')" ;

                    MySqlInsert($sql) ;
                    //更新用户最近提交时间
                    $sql="UPDATE users SET last_submit='$time' WHERE uid=$uid";
                    MySqlUpdate($sql);

                    submit_code_to_remote_server($insert_id);
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
                    $GLOBALS["insert_id"]=MySqlInsert("INSERT INTO solution(uid,user_id , problem_id  , ip , judgetime , protype , result , in_date, contest_id) VALUES('$uid','$user_id' , '$pid' , '$ip' , '$date' , '$protype' , '4', '$time', '$contest_id' ) ") ;
                    //添加内容到用户代码库，后端叛题需要用到。
                    MySqlInsert("insert into source_code(solution_id, source) values(`{$GLOBALS["insert_id"]}`, '$source')");
                    $result['accept'] = 1 ;

                    $sql="UPDATE problem SET accepted=accepted+1, submit=submit+1 WHERE problem_id=$pid";
                    MySqlUpdate($sql);
                    setContestFinish($contest_id, $uid, $pid, 4);

                }else
                {
                    $GLOBALS["insert_id"]=MySqlInsert("INSERT INTO solution(uid,user_id , problem_id  , ip , judgetime , protype , result , in_date, contest_id ) VALUES( '$uid','$user_id' , '$pid' , '$ip' , '$date' , '$protype' , '6' , '$time', '$contest_id') ") ;
                    //添加内容到用户代码库，后端叛题需要用到。
                    MySqlInsert("insert into source_code(solution_id, source) values(`{$GLOBALS["insert_id"]}`, '$source')");
                    $result['accept'] = 0 ;

                    $sql="UPDATE problem SET submit=submit+1 WHERE problem_id=$pid";
                    MySqlUpdate($sql);
                    setContestFinish($contest_id, $uid, $pid, 6);
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
//        echo "examproblem<br>" ;
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
                    $GLOBALS["insert_id"]=MySqlInsert("INSERT INTO solution(uid,user_id , problem_id  , ip , judgetime , protype , result, in_date, exam_id) VALUES('$uid','$user_id' , '$pid' , '$ip' , '$date' , '$protype' , '4', '$time', '$exam_id' ) ") ;
                    //添加内容到用户代码库，后端叛题需要用到。
                    MySqlInsert("insert into source_code(solution_id, source) values(`{$GLOBALS["insert_id"]}`, '$source')");
                    $result['accept'] = 1 ;

                    $sql="UPDATE problem SET accepted=accepted+1, submit=submit+1 WHERE problem_id=$pid";
                    MySqlUpdate($sql);
                }
                else
                {
                    $GLOBALS["insert_id"]=MySqlInsert("INSERT INTO solution(uid,user_id , problem_id  , ip , judgetime , protype , result, in_date, exam_id  ) VALUES( '$uid','$user_id' , '$pid' , '$ip' , '$date' , '$protype' , '6', '$time', '$exam_id' ) ") ;
                    //添加内容到用户代码库，后端叛题需要用到。
                    MySqlInsert("insert into source_code(solution_id, source) values(`{$GLOBALS["insert_id"]}`, '$source')");
                    $result['accept'] = 0 ;

                    $sql="UPDATE problem SET submit=submit+1 WHERE problem_id=$pid";
                    MySqlUpdate($sql);
                }
                $res['accept'] = $result['accept'] ;
                submit_code_to_remote_server($GLOBALS["insert_id"]);
                OutPut(true, "",  $res);

                break;
            case '2': //选择题
                $result = MySqlQuery("select sample_output from problem WHERE  problem_id = ".$pid) ;
                if( $source == $result['sample_output'] ){//与标准答案比对
                    $GLOBALS["insert_id"]=MySqlInsert("INSERT INTO solution(uid,user_id , problem_id  , ip , judgetime , protype , result , in_date, exam_id ) VALUES('$uid','$user_id' , '$pid' , '$ip' , '$date' , '$protype' , '4', '$time', '$exam_id' ) ") ;
                    //添加内容到用户代码库，后端叛题需要用到。
                    MySqlInsert("insert into source_code(solution_id, source) values(`{$GLOBALS["insert_id"]}`, '$source')");
                    $result['accept'] = 1;

                    $sql="UPDATE problem SET accepted=accepted+1, submit=submit+1 WHERE problem_id=$pid";
                    MySqlUpdate($sql);

                }else
                {
                    $GLOBALS["insert_id"]=MySqlInsert("INSERT INTO solution(uid,user_id , problem_id  , ip , judgetime , protype , result , in_date , exam_id) VALUES( '$uid','$user_id' , '$pid' , '$ip' , '$date' , '$protype' , '6', '$time', 'exam_id' ) ") ;
                    //添加内容到用户代码库，后端叛题需要用到。
                    MySqlInsert("insert into source_code(solution_id, source) values(`{$GLOBALS["insert_id"]}`, '$source')");
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
                    echo "pass1<br>";
                    $language = GetParam('language',20001,null,true) ;      //获取语言类型
                    //添加信息到用户代码列表（不包含内容）
                    $insert_id = MySqlInsert("INSERT INTO solution( protype, problem_id,uid,user_id,in_date,language,problem_belong , ip,code_length, exam_id) VALUES( 3, '$pid','$uid','$user_id', '$time'  ,'$language', '0' ,'$ip','".strlen($source)."', '$exam_id')") ;
                    //添加内容到用户代码库，后端叛题需要用到。
                    $sql = "INSERT INTO source_code(`solution_id` , `source` )VALUES($insert_id , '$source')" ;
                    MySqlInsert($sql) ;
                    //更新用户最近提交时间
                    $sql="UPDATE users SET last_submit='$time' WHERE uid=$uid";
                    MySqlUpdate($sql);
//
                    submit_code_to_remote_server($insert_id);
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
                    $GLOBALS["insert_id"]=MySqlInsert("INSERT INTO solution(uid,user_id , problem_id  , ip , judgetime , protype , result , in_date, exam_id) VALUES('$uid','$user_id' , '$pid' , '$ip' , '$date' , '$protype' , '4', '$time', '$exam_id' ) ") ;
                    //添加内容到用户代码库，后端叛题需要用到。
                    MySqlInsert("insert into source_code(solution_id, source) values(`{$GLOBALS["insert_id"]}`, '$source')");
                    $result['accept'] = 1 ;

                    $sql="UPDATE problem SET accepted=accepted+1, submit=submit+1 WHERE problem_id=$pid";
                    MySqlUpdate($sql);

                }else
                {
                    $GLOBALS["insert_id"]=MySqlInsert("INSERT INTO solution(uid,user_id , problem_id  , ip , judgetime , protype , result , in_date, exam_id ) VALUES( '$uid','$user_id' , '$pid' , '$ip' , '$date' , '$protype' , '6' , '$time', '$exam_id') ") ;
                    //添加内容到用户代码库，后端叛题需要用到。
                    MySqlInsert("insert into source_code(solution_id, source) values(`{$GLOBALS["insert_id"]}`, '$source')");
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
                    $GLOBALS["insert_id"]=MySqlInsert("INSERT INTO solution(uid,user_id , problem_id  , ip , judgetime , protype , result, in_date) VALUES('$uid','$user_id' , '$pid' , '$ip' , '$date' , '$protype' , '4', '$time' ) ") ;
                    //添加内容到用户代码库，后端叛题需要用到。
                    MySqlInsert("insert into source_code(solution_id, source) values(`{$GLOBALS["insert_id"]}`, '$source')");
                    $result['accept'] = 1 ;

                    $sql="UPDATE problem SET accepted=accepted+1, submit=submit+1 WHERE problem_id=$pid";
                    MySqlUpdate($sql);
                }
                else
                {
                    $GLOBALS["insert_id"]=MySqlInsert("INSERT INTO solution(uid,user_id , problem_id  , ip , judgetime , protype , result, in_date  ) VALUES( '$uid','$user_id' , '$pid' , '$ip' , '$date' , '$protype' , '6', '$time' ) ") ;
                    //添加内容到用户代码库，后端叛题需要用到。
                    MySqlInsert("insert into source_code(solution_id, source) values(`{$GLOBALS["insert_id"]}`, '$source')");
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
                    $GLOBALS["insert_id"]=MySqlInsert("INSERT INTO solution(uid,user_id , problem_id  , ip , judgetime , protype , result , in_date ) VALUES('$uid','$user_id' , '$pid' , '$ip' , '$date' , '$protype' , '4', '$time' ) ") ;
                    //添加内容到用户代码库，后端叛题需要用到。
                    MySqlInsert("insert into source_code(solution_id, source) values(`{$GLOBALS["insert_id"]}`, '$source')");
                    $result['accept'] = 1;

                    $sql="UPDATE problem SET accepted=accepted+1, submit=submit+1 WHERE problem_id=$pid";
                    MySqlUpdate($sql);

                }else
                {
                    $GLOBALS["insert_id"]=MySqlInsert("INSERT INTO solution(uid,user_id , problem_id  , ip , judgetime , protype , result , in_date ) VALUES( '$uid','$user_id' , '$pid' , '$ip' , '$date' , '$protype' , '6', '$time' ) ") ;
                    //添加内容到用户代码库，后端叛题需要用到。
                    MySqlInsert("insert into source_code(solution_id, source) values(`{$GLOBALS["insert_id"]}`, '$source')");
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
                    $sql = "INSERT INTO source_code(`solution_id` , `source` )VALUES($insert_id , '$source')";
                    MySqlInsert($sql);

                    //更新用户最近提交时间
                    $sql="UPDATE users SET last_submit='$time' WHERE uid=$uid";
                    MySqlUpdate($sql);

                    //提交到远程服务器判题
                    submit_code_to_remote_server($insert_id);
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
                    $GLOBALS["insert_id"]=MySqlInsert("INSERT INTO solution(uid,user_id , problem_id  , ip , judgetime , protype , result , in_date) VALUES('$uid','$user_id' , '$pid' , '$ip' , '$date' , '$protype' , '4', '$time' ) ") ;
                    //添加内容到用户代码库，后端叛题需要用到。
                    MySqlInsert("insert into source_code(solution_id, source) values(`{$GLOBALS["insert_id"]}`, '$source')");
                    $result['accept'] = 1 ;

                    $sql="UPDATE problem SET accepted=accepted+1, submit=submit+1 WHERE problem_id=$pid";
                    MySqlUpdate($sql);

                }else
                {
                    $GLOBALS["insert_id"]=MySqlInsert("INSERT INTO solution(uid,user_id , problem_id  , ip , judgetime , protype , result , in_date ) VALUES( '$uid','$user_id' , '$pid' , '$ip' , '$date' , '$protype' , '6' , '$time') ") ;
                    //添加内容到用户代码库，后端叛题需要用到。
                    MySqlInsert("insert into source_code(solution_id, source) values(`{$GLOBALS["insert_id"]}`, '$source')");
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

    //  考试提交题目
    function quizproblem(){
        $uid = GetCookie('uid');
        $user_id = GetCookie('user_id');
        $submit_type = GetParam('submit_type',20001);
        $quiz_id = GetParam('quiz_id',20001);

        if($submit_type == 1){
            $submit_content = GetParam('submit_content',20001);
            //$submit_content =stripslashes(html_entity_decode($submit_content));
           // echo  $submit_content . '<br/>';

            $content = json_decode($submit_content,true);

            $scores = array();
            foreach ($content as $key => $value){
                $pid = $value['problem_id'];
                $point = $value['point'];
                $source = $value['content'];
                $protype = $value['protype'];

                $source = str_replace("'", "\'" ,$source ) ;
                $ip=$_SERVER['REMOTE_ADDR'];                            //获得IP地址
                $date = strftime("%Y-%m-%d %H:%M",time());
                $time = date('Y-m-d H:i:s',time()) ;
                switch ($protype) {

                    case '1'://填空题

                        break;
                    case '2': //选择题
                        $result = MySqlQuery("select sample_output from problem WHERE  problem_id = ".$pid) ;
                        if( $source == $result['sample_output'] ){//与标准答案比对

                            $GLOBALS["insert_id"]=MySqlInsert("INSERT INTO solution(uid,user_id , problem_id  , ip , judgetime , protype , result , in_date ) VALUES('$uid','$user_id' , '$pid' , '$ip' , '$date' , '$protype' , '4', '$time' ) ") ;
                            //添加内容到用户代码库，后端叛题需要用到。

                            MySqlInsert("insert into source_code(solution_id, source) values({$GLOBALS["insert_id"]}, '$source')");
                            $result['accept'] = 1;

                            $sql="UPDATE problem SET accepted=accepted+1, submit=submit+1 WHERE problem_id=$pid";
                            MySqlUpdate($sql);

                            // 写入 scores
                            $scores[$key] = $point;
                        }else
                        {
                            $GLOBALS["insert_id"]=MySqlInsert("INSERT INTO solution(uid,user_id , problem_id  , ip , judgetime , protype , result , in_date ) VALUES( '$uid','$user_id' , '$pid' , '$ip' , '$date' , '$protype' , '6', '$time' ) ") ;
                            //添加内容到用户代码库，后端叛题需要用到。
                            MySqlInsert("insert into source_code(solution_id, source) values({$GLOBALS["insert_id"]}, '$source')");
                            $result['accept'] = 0;

                            $sql="UPDATE problem SET submit=submit+1 WHERE problem_id=$pid";
                            MySqlUpdate($sql);

                            // 写入 scores
                            $scores[$key] = 0;
                        }
                        $res['accept'] = $result['accept'] ;
                        //   OutPut(true, "",  $res);
                        break;
                    case '3'://代码题

                        break;
                    case '4': //代码类型的填空题(需判题)
                        OutPut(false,50000);
                        break;
                    case '5'://判断题
                        $result = MySqlQuery("select sample_output from problem WHERE  problem_id = ".$pid) ;
                        if( $source == $result['sample_output'] ){//与标准答案比对
                            $GLOBALS["insert_id"]=MySqlInsert("INSERT INTO solution(uid,user_id , problem_id  , ip , judgetime , protype , result , in_date) VALUES('$uid','$user_id' , '$pid' , '$ip' , '$date' , '$protype' , '4', '$time' ) ") ;
                            //添加内容到用户代码库，后端叛题需要用到。
                            MySqlInsert("insert into source_code(solution_id, source) values({$GLOBALS["insert_id"]}, '$source')");
                            $result['accept'] = 1 ;

                            $sql="UPDATE problem SET accepted=accepted+1, submit=submit+1 WHERE problem_id=$pid";
                            MySqlUpdate($sql);

                            // 写入 scores
                            $scores[$key] = $point;
                        }else
                        {
                            $GLOBALS["insert_id"]=MySqlInsert("INSERT INTO solution(uid,user_id , problem_id  , ip , judgetime , protype , result , in_date ) VALUES( '$uid','$user_id' , '$pid' , '$ip' , '$date' , '$protype' , '6' , '$time') ") ;
                            //添加内容到用户代码库，后端叛题需要用到。
                            MySqlInsert("insert into source_code(solution_id, source) values({$GLOBALS["insert_id"]}, '$source')");
                            $result['accept'] = 0 ;

                            $sql="UPDATE problem SET submit=submit+1 WHERE problem_id=$pid";
                            MySqlUpdate($sql);

                            // 写入 scores
                            $scores[$key] = 0;
                        }
                        $res['accept'] = $result['accept'] ;
                        //  OutPut(true, "",  $res);
                        break;
                    default:
                        OutPut(false,50000);
                        break;
                }
            }
            $scores = json_encode($scores);

            // 存在记录则update 不存在则 insert
            $sql = "select * from quiz_submit where uid = $uid and quiz_id = $quiz_id";
            $res = MySqlQuery($sql);
            if($res == null){ // 插入
                $sql = "insert into quiz_submit(quiz_id,uid,submit_content,each_score) values($quiz_id,$uid,'$submit_content','$scores')";
                MySqlInsert($sql);
            }else{
                $sql = "update quiz_submit set  submit_content = '$submit_content' , each_score = '$scores' where uid = $uid and quiz_id = $quiz_id";
                MySqlUpdate($sql);
            }
            OutPut(true,'','');
        }else if($submit_type == 2 ){
            // 代码提交题
            $quiz_num = GetParam('quiz_num',20001);
            $language = GetParam('language',20001,null,true) ;      //获取语言类型
           // $num = GetParam('num',20001);
            $point = GetParam('point',20001);
            $pid = GetParam('pid',20001);                           //题目id
            //$protype = GetParam('protype',20001,null,true);         //提交的题目类型

            $source = str_replace("\\", "\\\\", GetParam('source', 20001));                     //用户提交的答案(选择题也是这个参数)
            $source = str_replace("'", "\'" ,$source ) ;

            $ip=$_SERVER['REMOTE_ADDR'];                            //获得IP地址
            $date = strftime("%Y-%m-%d %H:%M",time());
            $time = date('Y-m-d H:i:s',time()) ;

            $sql="SELECT last_submit FROM users WHERE uid=$uid";
            $res=MySqlQuery($sql);
            if(($res['last_submit']!=null && time()-strtotime($res['last_submit'])>30) || $res['last_submit']==null)
            {
                $language = GetParam('language', 20001, null, true);      //获取语言类型
                //添加信息到用户代码列表（不包含内容）
                $insert_id = MySqlInsert("INSERT INTO solution( protype, problem_id,uid,user_id,in_date,language,problem_belong , ip,code_length) VALUES( 3, '$pid','$uid','$user_id', '$time'  ,'$language', '0' ,'$ip','" . strlen($source) . "')");
                //添加内容到用户代码库，后端叛题需要用到。
                $sql = "INSERT INTO source_code(`solution_id` , `source` )VALUES($insert_id , '$source')";
                MySqlInsert($sql);

                //更新用户最近提交时间
                $sql="UPDATE users SET last_submit='$time' WHERE uid=$uid";
                MySqlUpdate($sql);

                //提交到远程服务器判题
                submit_code_to_remote_server($insert_id);


                // 更新 solution_id 到 quiz_submit 中
                $sql = "select solution_ids from quiz_submit where uid = $uid and quiz_id = $quiz_id ";
                $solutions = MySqlQuery($sql);
                if($solutions != null){  // 更新
                    $solutions = json_decode($solutions['solution_ids'],true);
                    // 保存 题目id  和 solution id
                    $problem = array();
                    $problem['problem_id'] = $pid;
                    $problem['point'] = $point;
                    $problem['solution_id'] = $insert_id;
                    $solutions[$quiz_num] = $problem;
                    $solutions = json_encode($solutions);
                    $sql = "update quiz_submit set solution_ids = '$solutions' where uid = $uid and quiz_id = $quiz_id";
                    MySqlUpdate($sql);
                }else{  // 插入
                    $solutions = array();
                    // 保存 题目id  和 solution id
                    $problem = array();
                    $problem['problem_id'] = $pid;
                    $problem['point'] = $point;
                    $problem['solution_id'] = $insert_id;
                    $solutions[$quiz_num] = $problem;
                    //$solutions[$pid] = $insert_id;
                    $solutions = json_encode($solutions);
                    $sql = "update quiz_submit set solution_ids = '$solutions' where uid = $uid and quiz_id = $quiz_id";
                    MySqlUpdate($sql);

                }
                OutPut(true);
            }
            else
            {
                OutPut(false, 50008);
            }

        }else{
            OutPut(false, 50008);
        }


    }


?>