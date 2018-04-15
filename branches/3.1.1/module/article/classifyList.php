<?php
/**
 * Created by PhpStorm.
 * User: chx
 * Date: 16/8/6
 * Time: 下午10:29
 */

classifylist();
//http://localhost/gdinoj/trunk/server/?action=article.classifyList&pid=-1

function classifylist()
{
    $pid = GetParam('pid', 20001);
    $sql = "select labelid , name , pid , discription as description , type,iconUrl,bannerUrl from label where pid = '$pid'";
    //echo $sql;
    $res = MySqlQuerys($sql);

    $ressize = count($res) ;
    //echo $ressize ;
    for( $i = 0 ; $i<$ressize ; $i++ ) {
        $labelid = $res[$i]['labelid'] ;
        $sql = "SELECT count(*) as num from article_label where labelid = '$labelid'";
        //echo $sql ;
        $temp = MySqlQuery($sql) ;
        $res[$i]['articlenum'] = $temp['num'] ;
    }


    OutPutList(true, "", $res);
}