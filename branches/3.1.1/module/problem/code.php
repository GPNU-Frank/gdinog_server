<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/3/4
 * Time: 21:28
 */

getcode() ;

function getcode()
{
    $solution_id = GetParam('solution_id' , 20001 ) ;
    
    $sql = "select source from source_code where solution_id = {$solution_id}" ;
    $res = MySqlQuery($sql) ;
    $res1 = MySqlQuery("select language from solution where solution_id = '$solution_id'");
    $data = array(
        'source_code' => $res['source'],
        'language' => (int)$res1['language'],
        
    );
    
    OutPut(true ,"" , $data) ;
}