<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/1/27
 * Time: 21:00
 *
 * shanxuan 2016/01/27
 */

//分页查询，以时间进行排序
$list = Page("news","time","LEFT JOIN users ON users.uid = news.uid ","news_id,news.uid,title,time,importance,nick");
OutPutList(true,"",$list);