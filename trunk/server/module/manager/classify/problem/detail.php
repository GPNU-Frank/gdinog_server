<?php
/**
 * Created by PhpStorm.
 * User: Joooo
 * Date: 2017/1/20
 * Time: 20:40
 */

Privilege(null , null , detail , null ) ;

function detail()
{
    $tag_id=GetParam('tag_id', 20001);
    $sql="select * from oj_data.tag where tag_id=$tag_id";
    $res=MySqlQuery($sql);

    $grade=$res['grade'];
    $sql=="select tag_name from oj_data.tag where tag_id=$grade";
    $result=MySqlQuery($sql);
    $res['faeher_name']=$result['tag_name'];
    OutPut(true,'', $res);
}