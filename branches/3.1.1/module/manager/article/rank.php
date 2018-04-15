<?php
/**
 * Created by PhpStorm.
 * User: Joooo
 * Date: 2016/12/6
 * Time: 23:10
 */


$Mem = $GLOBALS['memcache'] ;
$Temp = $Mem->get('manager_article_rank') ;
if( !$Temp )
{
    rank($Mem);
}
else
{
    OutPutList(true, "", $Temp);
}



function rank()
{
    $labelid=GetParam('labelid');
    $orderBy=GetParam('orderBy');

    if($orderBy==null){
        if($labelid==null)
        {
            $sql="select  articleid , title , publisherid ,  publishtime , pvnum, agreenum , summary, labelid, isMarkdown, mcontent, tagnames, isTop, isQuality
        from article order by agreenum desc limit 10";
            $res=MySqlQuerys($sql);
        }
        else
        {
            $sql="select  articleid , title , publisherid ,  publishtime , pvnum, agreenum , summary, labelid, isMarkdown, mcontent, tagnames, isTop, isQuality
        from article where labelid='$labelid' order by agreenum desc limit 10";
            $res=MySqlQuerys($sql);
        }
        $len = count($res);
        for ($i = 0; $i < $len; $i++)
        {
            $uid = $res[$i]['publisherid'];
            $sql = "select nick , avatarUrl from users where uid = '$uid' ";
            $result = MySqlQuery($sql);
            $res[$i]['publishername'] = $result['nick'];
            $res[$i]['avatarUrl'] = $result['avatarUrl'];
        }
    }
    else if($orderBy=="publishtime"){
        if($labelid==null)
        {
            $sql="select  articleid , title , publisherid ,  publishtime , pvnum, agreenum , summary, labelid, isMarkdown, mcontent, tagnames, isTop, isQuality
        from article order by publishtime desc limit 10";
            $res=MySqlQuerys($sql);
        }
        else
        {
            $sql="select  articleid , title , publisherid ,  publishtime , pvnum, agreenum , summary, labelid, isMarkdown, mcontent, tagnames, isTop, isQuality
        from article where labelid='$labelid' order by publishtime desc limit 10";
            $res=MySqlQuerys($sql);
        }
        $len = count($res);
        for ($i = 0; $i < $len; $i++)
        {
            $uid = $res[$i]['publisherid'];
            $sql = "select nick , avatarUrl from users where uid = '$uid' ";
            $result = MySqlQuery($sql);
            $res[$i]['publishername'] = $result['nick'];
            $res[$i]['avatarUrl'] = $result['avatarUrl'];
        }
    }

    OutPutList(true, "", $res);
}