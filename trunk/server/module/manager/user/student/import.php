<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/2/24
 * Time: 11:34
 */

	require './plugins/excel/PHPExcel.php';
	require './plugins/upload/UploadUtil.php';


	$upload = new Upload();
	$result =  $upload -> GetUploadFile(array("xlsx","xls"));

	if($result['success']){


        $data = ReadExcel($result['path'],$result['type'] == 'xls' ? 'Excel5' : 'Excel2007');

        $i = 0 ;
        $false_num = 0 ;
        $false_arr = array() ;
        $success_arr = array() ;

        foreach($data as $item )
        {
            $i++ ;
            if( $i == 1  )	continue ;

            //get item
            $code = $item[0] ;
            $nick = $item[1] ;
            $sex = $item[2] ;
            $academy = $item[3] ;
            $grade = $item[4] ;
            $class_id = $item[5];
            $pwd = pwGen("123456") ;
            $ip = $_SERVER ['REMOTE_ADDR']  ;

            $temp['code'] = $code ; $temp['name'] = $nick ;

            //change academy , class_id
            $sql = "select id from school where name = '$academy'" ;
            $res = MySqlQuery($sql) ;
            $academy = $res['id'] ;

            $sql = "select class_id from class where class_name = '$class_id'" ;
            $res = MySqlQuery($sql) ;
            $class_id = $res['class_id'] ;

            if($sex == "男")     $sex = 1 ;
            else                 $sex = 0 ;


            //判断userid是否重复
            $row = MySqlQuery("SELECT `user_id` FROM `users` WHERE `users`.`user_id` = '" . $code . "'");
            if (Count($row) != 0) {
                $false_num++ ;
                array_push($false_arr , $temp ) ;
                continue ;
            }
            $sql = "INSERT INTO users(user_id  , code ,ip , password , nick , school , identity , sex , academy , class_id , grade )
                      values('$code' , '$code' , '$ip' , '$pwd' , '$nick' , 1 , 1 , {$sex} , {$academy} , '$class_id' , {$grade
                      } )";
            $res = MySqlInsert($sql) ;
            if( $res == false  )
            {
                array_push($false_arr , $temp ) ;
                $false_num++ ;
            }
            else
            {
                $sql = "update class set studentnum = studentnum + 1 where class_id = '$class_id'" ;
                MySqlUpdate($sql) ;
                array_push($success_arr , $temp ) ;
            }

        }

        $Res['inserted'] = $success_arr ;
        $Res['uninserted'] = $false_arr ;
        if( $false_num > 0 )
        {
            OutPut(false , null , $Res ) ;
        }
        else
            OutPut(true,null,$Res );
    }else{
        OutPut(false,null,$result['msg']);
    }

//version Excel5,Excel2007
function ReadExcel($filename,$version = 'Excel2007',$encode='utf-8'){
    $objReader = PHPExcel_IOFactory::createReader($version);
    $objReader->setReadDataOnly(true);
    $objPHPExcel = $objReader->load($filename);
    $objWorksheet = $objPHPExcel->getActiveSheet();
    $highestRow = $objWorksheet->getHighestRow();
    $highestColumn = $objWorksheet->getHighestColumn();
    $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
    $excelData = array();
    for ($row = 1; $row <= $highestRow; $row++) {
        for ($col = 0; $col < $highestColumnIndex; $col++) {
            $excelData[$row][] =(string)$objWorksheet->getCellByColumnAndRow($col, $row)->getValue();
        }
    }
    return $excelData;
}


?>