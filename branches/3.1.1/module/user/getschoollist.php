<?php

GetSchoolList() ;

function GetSchoolList()
{
    $sql = "select name , id from school WHERE school_id = -1 AND academy_id = -1" ;
    $arr = Page('school', 'id', 'WHERE school_id = -1 AND academy_id = -1', 'name, id', 'desc');
    for($i = 0; $i < count($arr); $i++)
    {
        $arr[$i]['id'] = (int)$arr[$i]['id'];
    }
    OutPutList(true,'',$arr);
}