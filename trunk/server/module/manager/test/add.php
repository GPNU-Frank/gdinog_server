	<?php
	/* 添加练习 */

	Privilege(null,AddTest,null);

	function AddTest(){
		$testname = GetParam('testname',20001) ;
		$coursesid = GetParam('coursesid',20001) ;
		$stoptime = GetParam('stoptime',20001) ;
		$problemarr = GetParam('problemarr',20001) ;

		$uid = GetCookie('uid');

		$problemarr = json_decode($problemarr); 
		
		$creatime = date('Y-m-d h:i:s',time());
		$sql = "Insert into courses_test(`test_name`,`create_time`,`stop_time`,`courses_id`,`uid`) values('$testname','$creatime','$stoptime','$coursesid','$uid')";
		$res = MySqlInsert($sql) ;

		if( $res != false  ){
			$TestID = $res ;
			for($i = 0 ; $i < count($problemarr);$i++){
				$sql = "Insert into courses_test_problem(test_id,problem_id) values( '$TestID'  , '$problemarr[$i]' )";
				MySqlInsert($sql) ;
			}
			OutPut(true);
		}else{
			OutPut(false , 20001);
		}
		
	}
?>