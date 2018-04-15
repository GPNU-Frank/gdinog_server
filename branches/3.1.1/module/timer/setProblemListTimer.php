<?php
ignore_user_abort(); // 第一次打开后  后台运行
set_time_limit(0); // run script. forever
$interval=60 * 60 * 20 ; // do every 30 minutes...
require_once "./framework/include/conn.php"; // 加载 连接文件
$pagesize = 20;
$filter = "where defunct = 'N' and is_verify = '1'";
$order_text = " ORDER BY problem_id ASC ";
do{

    $problem_list = array();
    // 获取数据库页数
    $row = MySqlQuery("SELECT count(*) as num from problem" );
    $maxPage = ceil($row["num"] / $pagesize); //最大值
    $pagekey = 'problem:maxPage';
  //  echo  "pages" .$maxPage;
    for($i = 1 ; $i <= $maxPage ; $i++){
        // 取每一页
        $liMitText = ' Limit '.($pagesize * ($i - 1)).",".$pagesize; //限制

        $arr = MySqlQuerys('SELECT `problem_id`,`title`,`submit`,`problem_type`,`accepted`,`in_date`,hastestdata  FROM problem '.$filter.$order_text.$liMitText );
        $len = count($arr);
        // 每页的每条记录 获取题目分类

        for($j = 0 ; $j<$len ; $j++ )
        {
            // 获取题目分类信息
            $pid = $arr[$j]['problem_id'] ;
            $sql = "select tagname from tags where tagid in (select tagid from problem_tag where problem_id = '$pid' )" ;
            $res = MySqlQuerys($sql) ;

            $arr1 = array() ;
            foreach($res as $item )
            {
                array_push($arr1 , $item['tagname'] ) ;
            }

            $arr[$j]['tagnames'] = $arr1 ;
        }
        // 每一页存入memeryache
        $key = 'problem_list:'.$i;
       // echo $key.  " \n";
        $memcache->add($key,$arr,60 * 60 * 24);
        $memcache->add($pagekey,$maxPage,60 * 60 * 24);  // 页数
        sleep($interval);

    }
    //$list = $memcache->get("problem_list:2");
    //var_dump($list);
}while(true);
?>