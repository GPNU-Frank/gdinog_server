<?php
ignore_user_abort(); // 第一次打开后  后台运行
set_time_limit(0); // run script. forever
$interval=60 * 60 * 24 ; // do every 30 minutes...
require_once "./framework/include/conn.php"; // 加载 连接文件
$pagesize = 20;
$filter = "where defunct = 'N' and is_verify = '1'";
$order_text = " ORDER BY problem_id ASC ";
do{

    sleep($interval);
    //$list = $memcache->get("problem_list:2");
    //var_dump($list);
}while(true);
?>