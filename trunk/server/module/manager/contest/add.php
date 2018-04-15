<?php


Privilege(null,AddContest,AddContest);

function AddContest(){
    $type = GetParam('type',20001);
    $title = GetParam('title',20001);
    $begin = GetParam('begin',20001);
    $end = GetParam('end',20001);
    $holder = GetCookie('uid');
    $password = GetParam('password');
    $problem_list = GetParam('problem_list');

    if($type==1)
    {
        $sql="insert into contest(title, begin, end, state, holder, type, password, score) values('$title', '$begin', '$end', 1, $holder, $type, '$password', 0)";
    }
    else
    {
        $sql="insert into contest(title, begin, end, state, holder, type, score) values('$title', '$begin', '$end', 1, $holder, $type, 0)";
    }
    $cid =  MySqlInsert($sql);
    if($cid)
    {
        $rank=0;
        if($problem_list != null){
            $problem_list = json_decode($problem_list);
            foreach ($problem_list as $pid) {
                MySqlInsert("insert into contest_problem(problem_id,contest_id, rank) values('$pid','$cid', $rank)");
                $rank++;
            }
        }
        OutPut(true,60001);

    }else
    {
        OutPut(false,60002);
    }

}