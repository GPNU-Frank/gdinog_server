<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/2/1
 * Time: 9:12
 */

require './plugins/upload/UploadUtil.php';


$upload = new Upload();
$result =  $upload -> GetUploadFile(array("xml"));

$SimpleXML = simplexml_load_file($result['path']) ;


foreach( $SimpleXML->item as  $t ) {
    //echo 'pass0<br>' ;
    $title = $t->title;
    $time_limit = $t->time_limit;
    $memory_limit = $t->memory_limit;
    $description = $t->description;
    $input = $t->input;
    $output = $t->output;
    $sample_input = $t->sample_input;
    $sample_output = $t->sample_output;
    $test_input = $t->test_input;
    $test_output = $t->test_output;
    $hint = $t->hint;
    $source = $t->source;
    $spj = $t->spj ;


    //$description = strip_tags($description) ;
    /*
    $description = unhtml($description) ;
    $input = unhtml($input) ;
    $output = unhtml($output) ;
    */

    $title = check($title) ;
    $description = check($description) ;
    $input = check($input) ;
    $output = check($output) ;
    $sample_input = check($sample_input) ;
    $sample_output = check($sample_output) ;
    $test_input = check($test_input) ;
    $test_output = check($test_output) ;
    $hint = check($hint) ;
    $source = check($source) ;




    $sql = "INSERT into problem (title,time_limit,memory_limit,
				  description,input,output,sample_input,sample_output,hint,source,spj,in_date,defunct,problem_type)
					VALUES('$title','$time_limit','$memory_limit','$description','$input','$output',
					'$sample_input','$sample_output','$hint','$source','$spj','$now','Y',3)";
    echo "<br><br>" ;
    echo $sql ;
    echo "<br><br>" ;
    //echo $description ;
    //echo "<br><br>" ;
    $pid = MySqlInsert($sql);

    SaveInFile($pid,$test_input,$test_output,$sample_input,$sample_output ) ;
}

OutPut(true) ;

function check($str)
{

    return mysqli_escape_string($GLOBALS['link'] ,$str ) ;
}


