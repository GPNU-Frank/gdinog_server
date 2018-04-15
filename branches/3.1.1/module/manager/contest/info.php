<?php
    //http://localhost/gdinoj/trunk/server/?action=manager.contest.info&cookie=131d327a890e0e750e2f6ad9ec3a5f14&contest_id=1044&password=""
    include 'contest.php';
    $uid = GetCookie('uid');
    $password = GetParam('password');
    $contest_id = GetParam('contest_id',20001);
    
    $contest = new contest($contest_id);
    $contest ->CheckEnd();

    $sql="select type,password,title,begin,end,state from contest where contest_id = '$contest_id'";
    $res = MySqlQuery($sql);
    $pw = $res['password'];
    unset($res['password']);
    
    $problem_list = array();
    if($res['type'] == 0 || $res['type'] == 2 || ($res['type'] == 1 && $password == $pw))
        $problem_list = MySqlQuerys("select problem_id,title,accepted,submit,accepted as solved from problem where problem_id in ( select problem_id from contest_problem where contest_id = '$contest_id' ) ORDER  by problem_id ");
    
    foreach ($problem_list as $key => $problem){
            $sql = "select hasfinishpro({$uid} , {$problem['problem_id']}) as status" ;
            $temp = MySqlQuery($sql) ;
            $problem_list[$key]['finished'] = $temp['status'] ;
    }
    
    $data = array(
        'info' => $res,
        'list' => $problem_list,
    );
    OutPut(true,null,$data);
    
?>





















