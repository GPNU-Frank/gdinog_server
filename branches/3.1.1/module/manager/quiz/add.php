<?php
/**
 * Created by PhpStorm.
 * User: Frank
 * Date: 2017/7/2
 * Time: 9:29

*/




    Privilege(null,AddQuiz,null);
    // 添加考试
	function AddQuiz(){
        $quizname = GetParam('quizname',20001) ;  //  试卷名称
        $coursesid = GetParam('coursesid',20001) ;  // 课程id
       // $stoptime = GetParam('stoptime',20001) ;   // 停止时间
        //$creatime = GetParam('createtime' , 20001 ) ; // 开始时间
        $problemarr = GetParam('problemarr',20001); //  题目编号 数组

        $teacher_id = GetCookie('uid');

        $problemarr = json_decode($problemarr);

        $sql="select count(distinct uid) as num from users inner join courses_class using (class_id) where courses_id=$coursesid";
        $res=MySqlQuery($sql);
        $allnum=$res['num'];
        // 插入表中
        $sql = "Insert into courses_quiz(quiz_name,courses_id,teacher_id) values('$quizname',$coursesid,$teacher_id)";
        echo $sql;
        $quiz_id = MySqlInsert($sql) ;

        if( $quiz_id != false ){
            for($i = 0 ; $i < count($problemarr);$i++){
                $sql = "Insert into courses_quiz_problem(exam_id,problem_id) values($quiz_id,$problemarr[$i])";

                MySqlInsert($sql) ;
            }
            OutPut(true);
        }else{
            OutPut(false ,20001);
        }

    }
?>