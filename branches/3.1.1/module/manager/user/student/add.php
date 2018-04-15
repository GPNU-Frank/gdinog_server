<?php
/**
* 增加一个学生
 */

Privilege( null , addstudent , addstudent ) ;

function addstudent()
{
    $code = GetParam("code") ;
    $nick = GetParam('nick') ;
    $sex = GetParam('sex') ;
    $academy = GetParam("academy_id") ;
    $grade = GetParam("grade") ;
    $class_id = GetParam("class_id") ;
    $pwd = pwGen("123456") ;
    $ip = $_SERVER ['REMOTE_ADDR']  ;


    $sql = "INSERT INTO users(user_id  , code ,ip , password , nick , school , identity , sex , academy , class_id , grade )
                      values('$code' , '$code' , '$ip' , '$pwd' , '$nick' , 1 , 1 , {$sex} , {$academy} , '$class_id' , {$grade
                      } )";
    $res = MySqlInsert($sql) ;

    $sql = "update class set studentnum = studentnum + 1 where class_id = '$class_id'" ;
    MySqlUpdate($sql) ;

    if( $res != false )
        OutPut(true) ;
    else
        OutPut(false ,20001) ;
        //http://114.215.99.34/gdinoj/server/?manager.user.student.add&code=1111&nick=test&sex=1&academy=1&grade=2016&class_id=11234&cookie=506c9b3e1ad16347ebbe7125c6a6c57a
}