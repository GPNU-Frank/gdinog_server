<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/2/3
 * Time: 11:11
 */
Privilege(null,null,Delete);

function Delete(){
	$nid = GetParam('nid') ;

	$sql = "DELETE  FROM news WHERE  news_id = {$nid}" ;
	$res = MySqlUpdate($sql) ;
	if( $res == true )
	{
	    OutPut(true ) ;
	}
	else
	    OutPut(false , 20001) ;
}