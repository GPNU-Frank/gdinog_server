<?php
/*
 *
 * @author 陈善轩
 * PHP版本为5.5
 * 由于，在PHP5.5以上版本时，用旧版的数据库连接方式会引起
 * Deprecated: mysql_connect(): The mysql extension is deprecated and will be removed in the future: use mysqli or PDO instead in
 * 异常，所以改用一下版本的数据库连接方式
 *
 */
    $link = mysqli_connect( $ip  , $mysqlUser ,$mysqlPass, $database);
    mysqli_query ( $link , 'set names utf8' ) ;


    //MEMCACHED配置
    $memcache  = new Memcache();
    $memcache->addServer("127.0.0.1", 11211) or die ("Could not connect") ;
?>