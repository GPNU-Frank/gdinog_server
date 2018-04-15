<?php
    Privilege(null,null,UpdateDefunct);

    function UpdateDefunct(){
        $uid = GetParam('uid',20001) ;
        $defunct = GetParam('defunct',20001) ;
        $defunct = strtoupper($defunct);
        $sql = "UPDATE users SET defunct = '$defunct' WHERE uid = '$uid'" ;
        MySqlUpdate($sql) ;
        OutPut(true , "") ;
    }