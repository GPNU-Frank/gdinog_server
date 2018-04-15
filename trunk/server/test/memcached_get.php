<?php
/**
 * Created by PhpStorm.
 * User: csx
 * Date: 16/12/3
 * Time: 上午12:13
 */

//$memcache_obj = new Memcache();
//$memcache_obj->connect( "127.0.0.1" , 11211 ) ;
//
////set
//
//$value = $memcache_obj->get('a1') ;
//echo "value : " + $value ;

$mem = new Memcache; $mem = new Memcache;
$mem->addServer("localhost", 11211);

$val = $mem->get('key1');
echo "Get key1 value: " . $val ."<br>";

//$mem->close();

