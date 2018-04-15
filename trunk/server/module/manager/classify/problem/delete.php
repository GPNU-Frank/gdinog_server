<?php
/**
 * Created by PhpStorm.
 * User: Joooo
 * Date: 2017/1/20
 * Time: 20:42
 */

Privilege(null , null , delete , null ) ;

function delete()
{
    $tag_id=GetParam('tag_id', 20001);
    $sql="delete from tag where tag_id=$tag_id";
    MySqlUpdate($sql);
    $sql="delete from problem_tag where tag_id=$tag_id";
    MySqlUpdate($sql);

    $sql="select tag_id from tag where grade=$tag_id";
    $res=MySqlQuerys($sql);

    for ($i=0; $i<count($res); $i++)
    {
        $tag_id=$res[$i]['tag_id'];
        $sql="delete from problem_tag where tag_id=$tag_id";
        MySqlUpdate($sql);
    }
    OutPut(true);
}