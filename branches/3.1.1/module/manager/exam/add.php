<?php


	Privilege(null,AddExam,null);


	/* 添加测验 http://localhost/GdinOJ/?function=admin&action=AddExam&examname=myexam&coursesid=3&stoptime=2016-6-6 00:00:00&problemarr=["1000","1001"]
	 * @examname 测验名称
	 * @coursesid 课程id
	 * @stoptime 截止时间 格式 2015-06-15 12:12:12
	 * @problemarr 题目数组序号 （可选）输入json数组格式 ["1001","1000"] 必须是数字
	 * return 
	 * {"code":"1","text":"\u6dfb\u52a0\u6d4b\u9a8c\u6210\u529f"}
	 *
	 * 2016/1/26 update by shanxuan
	 */


	function AddExam(){
		$examname = GetParam('examname',20001) ;
		$coursesid = GetParam('coursesid',20001) ;
		$stoptime = GetParam('stoptime',20001) ;
		$creatime = GetParam('createtime' , 20001 ) ;
		$problemarr = GetParam('problemarr',20001);
		
		$uid = GetCookie('uid');

		$problemarr = json_decode($problemarr);

        $sql="select count(distinct uid) as num from users inner join courses_class using (class_id) where courses_id=$coursesid";
        $res=MySqlQuery($sql);
        $allnum=$res['num'];

        $sql = "Insert into courses_exam(exam_name,create_time,stop_time,courses_id,uid, all_num) values('$examname','$creatime','$stoptime',$coursesid,$uid, $allnum)";

		$exam_id = MySqlInsert($sql) ;

		if( $exam_id != false ){
			for($i = 0 ; $i < count($problemarr);$i++){
				$sql = "Insert into courses_exam_problem(exam_id,problem_id) values($exam_id,$problemarr[$i])";

				MySqlInsert($sql) ;
			}
			OutPut(true);
		}else{
			OutPut(false ,20001);
		}
		
	}
?>