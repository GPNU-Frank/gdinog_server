<?php
/**
 * Created by PhpStorm.
 * User: Frank
 * Date: 2017/4/15
 * Time: 9:20
 */
$cnt = MySqlQuery("select count(*) as num from problem ");
echo $cnt['num'];


OutPutList(true,"",$cnt);
