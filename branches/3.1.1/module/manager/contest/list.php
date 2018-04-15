<?php
    include 'contest.php';

    $filter = Filter(array(GetParam('type'),  GetParam('state')),array('=','='),array('type','state'));
    $res = Page("contest", "contest_id", $filter, "*", 'DESC');
    
    
    for($i = 0; $i < count($res) ; $i++){
        $contest_id = $res[$i]['contest_id'];
        $temp = new contest($contest_id);
        if($temp->CheckEnd()){
            $res[$i]['state'] = 0;
        }else{
            $res[$i]['state'] = 1;
        }
    }
    
    OutPutList(true , '' , $res ) ; 
?>


























