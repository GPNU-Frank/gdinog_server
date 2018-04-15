<?php
/*
获取传递的参数
@key 参数名
@error_msg_id 若不为null时 传入值为空值时报错并停止执行
@$defaultValue 若该值存在error_msg_id失效 传入值是空值时自动设置 defaultValue 为默认值
@isNumber 是否为数值型 若true 时 不是数值型报错并停止执行
*/

function GetParam($key,$error_msg_id = null,$defaultValue = null,$isNumber = false,$length = null){
    $param = isset($_REQUEST[$key]) ? trim($_REQUEST[$key]) : null;
    if ($param == null){
        if ($defaultValue != null){
            $param = $defaultValue;
        }else if ($error_msg_id != null){
            OutPut (false, $error_msg_id );
            exit (1);
        }
    }

    if ($param !== null && $param !== '' && $isNumber && !is_numeric($param)){//限制是否为数字
        OutPut (false, 20001 );
        exit (1);
    }

    if ($param !== null && $length !== null && is_numeric($length)){//长度限制
        if(strlen($param) > $length){
            OutPut (false, 20004 );
            exit (1);
        }
    }

    return $param;
}

function Filter($params ,$strs,$strs1){
    $txt = "";
    for ($i=0; $i < count($params); $i++) {
        if ($params[$i] ===0 || $params[$i] != null){
            if($txt == ""){
                $txt = $txt . " where ";
            }else{
                $txt = $txt . " and ";
            }
            if($strs[$i] == "="){
                $txt = $txt . " " . $strs1[$i] . " = '" .$params[$i] . "'";
            }else if($strs[$i] == "%"){
                $txt = $txt . " " . $strs1[$i] . " like '%" .$params[$i] . "%'";
            }else if($strs[$i] == "+"){
                $txt = $txt . " " . $params[$i];
                //这个直接加上一句话 strs1没有用到
            }
        }
    }
    return $txt;
}

function GetCookie($key , $false = true){
    //使用数据库
    //print("select $key from users where cookie = '".GetParam('cookie',10011)."'");
    $row = MySqlQuery("select $key from users where cookie = '".GetParam('cookie')."'");

    if($row[$key] !== null)
        return $row[$key];
    else
    {
        if( $false  )
            OutPut(false,10011);
        else
            return null ;
    }

}

function Page($tableName,$orderId = "",$filter = "",$select = "*",$sort = "DESC"){
    global $page;
    global $pagesize;
    global $maxPage;
    global $liMitText;

    if($orderId == null)
        $orderId == "";
    if($filter == null)
        $filter == "";
    if($select == null)
        $select == "*";
    if($sort == null)
        $sort == "DESC";
    //echo "SELECT count(*) from ".$tableName." ".$filter;
    $row = MySqlQuery("SELECT count(*) from ".$tableName." ".$filter);
    $maxPage = ceil($row["count(*)"] / $pagesize); //最大值
    $liMitText = ' Limit '.($pagesize * ($page - 1)).",".$pagesize; //限制

    if($orderId != null && $orderId != ""){
        $orderText = ' ORDER BY   '.$orderId .'  '.$sort.' ';
    }
    //echo $maxPage;
    //echo $liMitText;
    //echo "<br>" ;
    //echo 'SELECT '.$select.' FROM `'.$tableName.'`'.$filter.$orderText.$liMitText;
    //echo "<br>" ;

    return MySqlQuerys('SELECT '.$select.' FROM `'.$tableName.'`'.$filter.$orderText.$liMitText );
}

/**
 * @param $sql
 * @return bool
 * update by shanxuan
 * because I need the insertid..
 */

function MySqlUpdate($sql)
{
    $res = mysqli_query($GLOBALS['link'], $sql) or die(mysqli_errno()) ;
    return $res ;
}

function MySqlInsert($sql,$sql1 = null,$error_msg_id = null){
    if($sql1 != null){
        $row = MySqlQuery($sql1);
        if ($row['count(*)'] >= 1){
            OutPut(false,$error_msg_id);
        }
    }
    $res = mysqli_query ($GLOBALS['link'], $sql ) or die ( mysqli_error () );
    if( $res == false )		return false ;
    $pid = mysqli_insert_id($GLOBALS['link']) ;
    return $pid;
}

function MySqlQuery($sql){
    $result = mysqli_query ($GLOBALS['link'] ,  $sql ) or die ( mysqli_error ());
    $row = mysqli_fetch_assoc ( $result );
    mysqli_free_result ( $result );
    return $row;
}

function MySqlQuerys($sql){
    $result = mysqli_query ($GLOBALS['link'] ,  $sql ) or die ( mysqli_error ());
    $arr = array();
    while ( $item_row = mysqli_fetch_assoc ( $result )) {
        array_push($arr,$item_row);
    }
    mysqli_free_result ( $result );
    return $arr;
}


function OutPutList($success = "",$error_msg_id = "",$data = ""){
    global $maxPage;
    $list = array(
        'maxsize' => $maxPage,
        'list' => $data,
    );
    OutPut($success,$error_msg_id,$list);
}
/*
    输出函数
    @success bool 请求状态
    @msg string 服务器返回信息
    @data 内容数据
    return null
*/
function OutPut($success = "",$error_msg_id = "",$data = ""){
    if($error_msg_id == null){
        $msg = "";
    }else{
        $msg = $GLOBALS['msg_list'][$error_msg_id];
        if ($msg == null){
            $msg = "";
        }
    }
    $json = array(
        'success' => $success,
        'msg' => $msg,
        'data' => $data,
    );
    echo json_encode($json);
    exit(0);
}

function mkdata($pid,$filename,$input){
    global $OJ_DATA ;
    $basedir = $OJ_DATA ."/$pid";
    $fp = fopen ( $basedir . "/$filename", "w" );
    if($fp){
        $input = str_replace(  "\\n" , "\r\n" , $input ) ;
        fwrite($fp, $input);
        //fputs ( $fp, preg_replace ( "(\r\n)", "\n", $input ) );
        fclose ( $fp );
    }else{
        echo "Error while opening".$basedir . "/$filename ,try [chgrp -R www-data $OJ_DATA] and [chmod -R 771 $OJ_DATA ] ";
    }
}

//pwCheck("admin888",pwGen("admin888"))

function pwGen($password){
    $password=md5($password);
    $salt = sha1(rand());
    $salt = substr($salt, 0, 4);
    $hash = base64_encode( sha1($password . $salt, true) . $salt);
    return $hash;
}

function pwCheck($password,$saved)
{
    $svd=base64_decode($saved);
    $salt=substr($svd,20);
    $hash = base64_encode( sha1(md5($password) . $salt, true) . $salt );
    if (strcmp($hash,$saved)==0) return True;
    else return False;
}


//获取今日起始时间戳和结束时间戳
function ToDayFirstAndLast(){
    return array(
        mktime(0,0,0,date('m'),date('d'),date('Y')),
        mktime(0,0,0,date('m'),date('d')+1,date('Y'))-1
    );
}

//获取昨日起始时间戳和结束时间戳
function LastDayFirstAndLast(){
    return array(
        mktime(0,0,0,date('m'),date('d')-1,date('Y')),
        mktime(0,0,0,date('m'),date('d'),date('Y'))-1
    );
}

//获取本周起始时间戳和结束时间戳
function WeekFirstAndLast(){
    return array(
        mktime(0,0,0,date('m'),date('d')-date('w'),date('Y')),
        mktime(23,59,59,date('m'),date('d')-date('w')+6,date('Y'))
    );
}

//获取上周起始时间戳和结束时间戳
function LastWeekFirstAndLast(){
    return array(
        mktime(0,0,0,date('m'),date('d')-date('w')+1-7,date('Y')),
        mktime(23,59,59,date('m'),date('d')-date('w')+7-7,date('Y'))
    );
}

//获取一个月的第一天和最后一天
function MonthFirstAndLast(){
    return array(
        mktime(0,0,0,date('m'),1,date('Y')),
        mktime(23,59,59,date('m'),date('t'),date('Y'))
    );
}
function SaveInFile( $pid , $test_input , $test_output , $sample_input , $sample_output )
{

    $basedir = $GLOBALS['OJ_DATA']."/$pid";
    $OJ_DATA = $GLOBALS['OJ DATA'] ;
    $link = $GLOBALS['link'] ;

    mkdir ( $basedir );

    if(strlen($sample_output)&&!strlen($sample_input)) $sample_input="0";

    if(strlen($sample_input)) mkdata($pid,"sample.in",$sample_input,$OJ_DATA);

    if(strlen($sample_output))mkdata($pid,"sample.out",$sample_output,$OJ_DATA);

    if(strlen($test_output)&&!strlen($test_input)) $test_input="0";

    if(strlen($test_input))mkdata($pid,"test.in",$test_input,$OJ_DATA);

    if(strlen($test_output))mkdata($pid,"test.out",$test_output,$OJ_DATA);

    //这句话是什么意思呢
    //$sql="insert into `privilege` (`user_id`,`rightstr`)  values('".GetCookie('user_id')."','p$pid')";

    //$res = mysqli_query( $link , $sql);

}

function savesample( $pid ,  $sample_input , $sample_output )
{

    $basedir = $GLOBALS['OJ_DATA']."/$pid";
    $OJ_DATA = $GLOBALS['OJ DATA'] ;
    $link = $GLOBALS['link'] ;

    mkdir ( $basedir );

    if(strlen($sample_output)&&!strlen($sample_input)) $sample_input="0";

    if(strlen($sample_input)) mkdata($pid,"sample.in",$sample_input,$OJ_DATA);

    if(strlen($sample_output))mkdata($pid,"sample.out",$sample_output,$OJ_DATA);

}

function unhtml($content){                             //定义自定义函数的名称
    $content = preg_replace( "@<script(.*?)</script>@is", "", $content );
    $content = preg_replace( "@<iframe(.*?)</iframe>@is", "", $content );
    $content = preg_replace( "@<style(.*?)</style>@is", "", $content );
    $content = preg_replace( "@<(.*?)>@is", "", $content );
    $content = preg_replace("/(\s|\&nbsp\;|　|\xc2\xa0)/", "", strip_tags($content));
    $content = preg_replace("/(\s|\hellip\;|　|\xc2\xa0)/", "", strip_tags($content));
    return $content;                              //删除文本中首尾的空格
}

function Privilege($student = null,$teacher = null,$manager = null,$other = null){
    $identify = GetCookie('identity');
    //echo $identify ;
    switch ($identify) {
        case '0':
            if($other != null)
                $other();
            break;
        case '1':
            if($student != null)
                $student();
            break;
        case '2':
            if($teacher != null)
                $teacher();
            break;
        case '3':
            if($manager != null)
                $manager();
            break;
    }
    OutPut(false,20003);
}

function downloads($name){

    if (!file_exists($name)){
        header("Content-type: text/html; charset=utf-8");
        echo "File not found!";
        exit;
    } else {
        $file = fopen($name,"r");
        Header("Content-type: application/octet-stream");
        Header("Accept-Ranges: bytes");
        Header("Accept-Length: ".filesize(  $name));
        Header("Content-Disposition: attachment; filename=".$name);
        echo fread($file, filesize($name));
        fclose($file);
    }
}

function my_sort($arrays,$sort_key,$sort_order=SORT_ASC,$sort_type=SORT_NUMERIC ){
    if(is_array($arrays)){
        foreach ($arrays as $array){
            if(is_array($array)){
                $key_arrays[] = $array[$sort_key];
            }else{
                return false;
            }
        }
    }else{
        return false;
    }
    array_multisort($key_arrays,$sort_order,$sort_type,$arrays);
    return $arrays;
}

function GetProType()
{
    $pid=$_REQUEST['pid'];
    $sql="select problem_type from problem where problem_id=".$pid;
    $res=MySqlQuery($sql);
    return $res['problem_type'];
}
function setContestFinish($contest_id, $uid, $problem_id, $accpet, $in_date)
{
    $sql="select finish, submit from contest_finish where contest_id=$contest_id and uid=$uid";
    $res=MySqlQuery($sql);
    //contest_finish没有记录说明该用户在该竞赛是第一次提交，需要插入信息
    if($res==null)
    {
        //处理题目完成信息和提交信息
        $sql="select rank from contest_problem where contest_id=$contest_id and problem_id=$problem_id";
        $result=MySqlQuery($sql);
        $rank=$result['rank'];
        $sql="select count(*) as num from contest_problem where contest_id=$contest_id";
        $result=MySqlQuery($sql);
        $count=$result['num'];

        $finish=array();
        $submit=array();
        for($i=0; $i<$count; $i++)
        {
            if($i==$rank && $accpet==4) array_push($finish, 1);
            else array_push($finish, 0);

            if($i==$rank) array_push($submit, 1);
            else array_push($submit, 0);
        }
        $finish=implode("", $finish);
        $submit=implode(",", $submit);
        //获取做这道题所耗费的时间
        $all_time=0;
        $sql="insert into contest_finish(contest_id, uid, finish, submit, all_time, accept_num, submit_num) values($contest_id, $uid, '$finish','$submit', $all_time, 0, 1)";
        if($accpet==4)   //若通过则计算时间
        {
            $sql="select begin from contest where contest_id=$contest_id";
            $result=MySqlQuery($sql);
            $begin=$result['begin'];

            $date = strftime("%Y-%m-%d %H:%M",time());
            $now = date('Y-m-d H:i:s',time()) ;
            $begin=date('Y-m-d H:i:s', strtotime($begin));

            $all_time=strtotime($in_date)-strtotime($begin);

            $sql="insert into contest_finish(contest_id, uid, finish, submit, all_time, accept_num, submit_num) values($contest_id, $uid, '$finish', '$submit', $all_time, 1, 1)";
        }
       // echo 'before insert   ',$sql , '<br/>';
        MySqlUpdate($sql);
    }
    //contest_finish有记录该用户的信息说明需要更新信息即可
    else
    {
        $finish=$res['finish'];
        $submit=$res['submit'];

        //处理题目完成信息和提交信息
        $sql="select rank from contest_problem where contest_id=$contest_id and problem_id=$problem_id";
        $result=MySqlQuery($sql);
        $rank=$result['rank'];
       // echo "rank info  ",var_dump($result) ,"<br/>";
        $submit=explode(',', $submit);
        if($accpet==4)
        {
            if($finish[$rank]==1)      //已经通过的题目再次通过不再记录时间
            {
                $accpet=6;
                $submit[$rank]++;
            }
            else
            {
                $finish[$rank]='1';
                $submit[$rank]++;
            }
        }
        else $submit[$rank]++;
        $num=$submit[$rank]-1;//获取该题目的错误提交数,为该题的提交数-1，因为这次的提交是正确提交
        $submit=implode(',', $submit);

        //获取做这道题所耗费的时间
        $sql="update contest_finish set finish='$finish', submit='$submit', submit_num=submit_num+1 where contest_id=$contest_id and uid=$uid";
        if($accpet==4)   //若通过则计算时间
        {
            $sql="select begin from contest where contest_id=$contest_id";
            $result=MySqlQuery($sql);
            $begin=$result['begin'];

            $date = strftime("%Y-%m-%d %H:%M",time());
            $now = date('Y-m-d H:i:s',time()) ;
            $begin=date('Y-m-d H:i:s', strtotime($begin));

            $time=strtotime($in_date) -strtotime($begin)+$num*20*60;//每提交错一次罚时20分钟
                $sql="update contest_finish set finish='$finish', submit='$submit', all_time=all_time+$time, accept_num=accept_num+1, submit_num=submit_num+1 where contest_id=$contest_id and uid=$uid";
        }
        //echo 'before update   ',$sql , '<br/>';
        MySqlUpdate($sql);
    }
}

function setExamFinsih($exam_id, $uid, $problem_id, $accpet)
{
    $sql="select finish from exam_finish where exam_id=$exam_id and uid=$uid";
    $res=MySqlQuery($sql);

    $finish=$res['finish'];

    //处理题目完成信息和提交信息
    $sql="select rank from courses_exam_problem where exam_id=$exam_id and problem_id=$problem_id";
    $result=MySqlQuery($sql);
    $rank=$result['rank'];

    $finish=explode(',', $finish);
    //echo  var_dump($finish)."<br>";
    if($accpet==4)
    {
        if($finish[$rank]==1)   //若已经通过再次提交通过不记录通过次数，改为不通过插入数据库
        {
            $accpet=6;
        }
        else
        {
            $finish[$rank]=1;
        }
    }
    //echo var_dump($finish)."<br>";
    $finish=implode(',', $finish);
    //echo var_dump($finish)."<br>";

    if($accpet==4)
    {
        $sql1="update exam_finish set finish='$finish', accept_num=accept_num+1, submit_num=submit_num+1 where exam_id=$exam_id and uid=$uid";
        $sql2="update courses_exam_problem set accept_num=accept_num+1, submit_num=submit_num+1 where exam_id=$exam_id and problem_id=$problem_id";
    }
    else
    {
        $sql1="update exam_finish set finish='$finish',submit_num=submit_num+1 where exam_id=$exam_id and uid=$uid";
        $sql2="update courses_exam_problem set submit_num=submit_num+1 where exam_id=$exam_id and problem_id=$problem_id";
    }
    MySqlUpdate($sql1);
    MySqlUpdate($sql2);
}
?>