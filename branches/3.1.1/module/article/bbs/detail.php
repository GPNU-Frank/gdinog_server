<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/5/11
 * Time: 12:55
 */
bbsdetail(bsdetail , bbsdetail , bbsdetail ) ;


function bbsdetail()
{
    $articleid = GetParam('articleid' , 20001 ) ;
    $sql = "select * , nick from article , users  where articleid = $articleid AND uid = publisherid" ;
    $res = MySqlQuery($sql) ;

    //commentlistt
    $sql = "select commentid , nick as publishername , avatarUrl , content , commenttime , summary from comment , users where articleid = $articleid  AND uid = publisherid " ;
    $temp = MySqlQuerys($sql) ;

    //点赞量
    $num = count($temp) ;
    for($i = 0 ; $i<$num ; $i++ )
    {
        $id = $temp[$i]['commentid'] ;
        $sql = "select count(*) as num from useragree where type = 2 AND id = $id " ;
        $tt = MySqlQuery($sql) ;
        $temp[$i]['useragree'] = $tt['num'] ;
    }

    $res['commentlist'] = $temp ;

    OutPut(true , "" , $res ) ;


}
