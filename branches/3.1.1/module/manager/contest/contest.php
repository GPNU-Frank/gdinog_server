<?php
class contest{
    
    private $contest_id;


    public function __construct($contest_id) {
        $this -> contest_id = $contest_id;
    }
    
    function CheckEnd(){
        $now_time = time();
        $res = MySqlQuery("select state,end from contest where contest_id = '$this->contest_id'");
        $end_time = strtotime($res['end']);
        if($now_time > $end_time){
            if($res['state'] != 0){
                
                MySqlUpdate("update contest set state = '0' where contest_id = '$this->contest_id'");
            }
            return true;
        }else{
            if($res['state'] != 1){
                MySqlUpdate("update contest set state = '1' where contest_id = '$this->contest_id'");
            }
            return false;
        }
    }
    
}
?>


























