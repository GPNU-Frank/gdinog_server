<?php
/**
 * Created by PhpStorm.
 * User: chx
 * Date: 16/8/6
 * Time: 下午8:08
 */

articlelist() ;

function articlelist()
{
    $tagid = GetParam('labelid', 20001 );
    $sqlAdd = $tagid != -1 ?  " articleid in ( SELECT articleid FROM `article` where labelid = '$tagid' )" : "" ;
    $type = 2 ;         //知识库为2
    $title = GetParam( 'title' ) ;

    //Filter
    $filter = Filter( array($sqlAdd , $type , $title) , array("+" , "=" , "%" ) , array(null , "type" , "title") ) ;

    $arr = Page( "article" , "articleid" ,  $filter , " articleid , title , publisherid ,  publishtime , pvnum, agreenum , summary, labelid, isMarkdown, mcontent, tagnames"  ) ;

    $len = count($arr) ;

    //echo '</br>' ;
    //echo $len ;
    //echo '</br>' ;
    for( $i = 0 ; $i<$len ; $i++ )
    {
        $uid = $arr[$i]['publisherid'] ;
        $sql = "select nick , avatarUrl from users where uid = '$uid' " ;

        $res = MySqlQuery($sql) ;

        $arr[$i]['publishername'] = $res['nick'] ;
        $arr[$i]['avatarUrl'] = $res['avatarUrl'] ;
    }

    OutPutList(true , "" , $arr ) ;

}
//http://localhost/gdinoj/trunk/server/?action=article.knowledge.list&page=1&pagesize=100&labelid=4&title=AA