<?php
    $type = GetParam('type',20001);
    $title = GetParam('title',20001);
    $content = GetParam('content',20001);
    $uid = GetCookie('uid');
    MySqlInsert("Insert Into feedback(type,uid,title,content) values('$type','$uid','$title','$content')");
    OutPut(TRUE);
?>