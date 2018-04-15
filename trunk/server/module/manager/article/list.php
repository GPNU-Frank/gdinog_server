<?php
/**
 * Created by PhpStorm.
 * User: Joooo
 * Date: 2016/9/23
 * Time: 13:43
 */
articlelist() ;
function articlelist()
{
    $tagid = GetParam('labelid', 20001);
    $sqlAdd = $tagid != -1 ? " articleid in ( SELECT articleid FROM `article_label` where labelid = '$tagid' )" : "";
    $type = 2;         //知识库为2
    $title = GetParam('title');

    //Filter
    $filter = Filter(array($sqlAdd, $type, $title), array("+", "=", "%"), array(null, "type", "title"));

    $arr = Page("article", "articleid", $filter, " articleid , title , publisherid ,  publishtime , pvnum, agreenum , summary, labelid, isMarkdown, tagnames, isTop, isQuality");

    $len = count($arr);

    //echo '</br>' ;
    //echo $len ;
    //echo '</br>' ;
    for ($i = 0; $i < $len; $i++) {
        $uid = $arr[$i]['publisherid'];
        $sql = "select nick , avatarUrl from users where uid = '$uid' ";

        $res = MySqlQuery($sql);

        $arr[$i]['publishername'] = $res['nick'];
        $arr[$i]['avatarUrl'] = $res['avatarUrl'];
    }

    OutPutList(true, "", $arr);
}
//http://localhost/gdinoj/trunk/server/?action=manager.article.list&page=1&pagesize=10&labelid=5