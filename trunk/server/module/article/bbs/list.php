<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/5/10
 * Time: 21:21
 */


Privilege( bbslist  , bbslist , bbslist , bbslist ) ;
bbslist() ;

function bbslist()
{
    //按照标签进行查询
    $label = GetParam('label',null,-1,true); //默认-1则全选
    $sqlAdd = $label != -1 ?  " articleid in ( SELECT articleid FROM `article_label` where labelid = '$label' )" : "" ;

    //按照标题进行查询
    $title = GetParam('title' ) ;

    //过滤器
    $filter = Filter( array( $sqlAdd , $title ) , array( '+' , '%' ) , array("" , "title" )   ) ;
    $res = Page('article' , "" , $filter , "*" ) ;


    $resnum = count($res) ;
    for( $i = 0 ; $i<$resnum ; $i++ )
    {
        $articleid = $res[$i]['articleid'] ;
        $publisherid = $res[$i]['publisherid'];

        $sql = "select nick from users where uid = $publisherid" ;
        $temp = MySqlQuery($sql) ;
        $res[$i]['nick'] = $temp['nick'] ;


        #echo $res[$i]['nick'] ;


        //标签
        $sql = "select labelid from article_label where articleid = $articleid " ;
        $labelarray = array() ;
        $temp = MySqlQuerys($sql) ;
        $size = count($temp) ;

        echo  $size ;

        for( $i = 0 ; $i<$size ; $i++ )
        {
            $tt = $temp[$i]['labelid'] ;
            $sql = "select name from label where labelid = $tt " ;
            $tt2 = MySqlQuery($sql) ;
            array_push($labelarray , $tt2['name'] ) ;
        }
        $res[$i]['labels'] = $labelarray ;

    }

    OutPutList(true , "" , $res ) ;

}