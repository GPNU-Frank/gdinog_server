<?php

	/*
	 * 主页中最近（一天 一周 一月）排行榜 
	 */
	$Mem = $GLOBALS['memcache'] ;
	$Temp = $Mem->get('recentrank');
	if( !$Temp )
    {

        $count = GetParam('count',null,"5",true);

        $today_arr = GetRankData($count,TodayFirstAndLast());
        $week_arr = GetRankData($count,WeekFirstAndLast());
        $month_arr = GetRankData($count,MonthFirstAndLast());

        $data = array(
            'today_list' => $today_arr,
            "week_list" => $week_arr,
            'month_list' => $month_arr,
        );
        //设置两分钟的超时时间
        $Mem->set( 'recentrank' , $data , 120 ) ;

        OutPut( true, '', $data );
    }
    else
    {
        OutPut( true, '', $Temp );
    }



	function GetRankData($count,$time_arr){

		$time_start = strftime("%Y-%m-%d %H:%M:%S",$time_arr[0]);
		$time_end = strftime("%Y-%m-%d %H:%M:%S",$time_arr[1]);
		return MysqlQuerys("select solution.user_id,users.nick,count(DISTINCT  problem_id) as count from solution  LEFT JOIN users ON users.user_id = solution.user_id where result = '4' and in_date > '$time_start' and in_date < '$time_end' group by user_id order by count desc limit 0,".$count);
	}

?>