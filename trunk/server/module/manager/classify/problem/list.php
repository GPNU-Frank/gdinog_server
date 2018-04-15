<?php
/**
 * Created by PhpStorm.
 * User: Joooo
 * Date: 2017/1/20
 * Time: 20:45
 */

Privilege(null , null , taglist , null ) ;

function taglist()
{
    $sql="select tag_id, tag_name, grade from oj_data.tag where grade=0 order by tag_id";
    $res=MySqlQuerys($sql);

    $cnt=count($res);
    for ($i=0; $i<$cnt; $i++)
    {
        $tag_id=$res[$i]['tag_id'];
        $sql="select tag_id, tag_name, grade from oj_data.tag where grade=$tag_id";
        $result=MySqlQuerys($sql);
        $res[$i]['list']=$result;
    }
    OutPut(true, '', $res);
}