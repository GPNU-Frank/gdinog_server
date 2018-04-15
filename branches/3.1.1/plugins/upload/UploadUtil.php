<?php
class Upload
{

	function 	GetTestFiles($type_arr = null, $path = null, $size = null)
	{

		$result = array(
				'success' => false,
				'msg' => "",
				'type' => '',
				'path' => '',
		);
		$result = $this->getuploadfiles("inputfile", $result, $type_arr, $path);
		if ($result['success'] == false) {
			$result['success'] = false;
			return $result;
		}

		$result =  $this->getuploadfiles("outputfile", $result, $type_arr, $path);
		if ($result['success'] == false) {
			$result['success'] = false;
			return $result;
		}


		$result['success'] = true;
		$result['type'] = null;
		$result['path'] = null;
		return $result;

	}

	function  getuploadfiles($name = null, $result = null, $type_arr, $path)
	{
		$Filename = array();
		$filetype = array();

		if ($type_arr) {

			foreach ($_FILES[$name]['name'] as $filename) {
				$isAllowType = false;
				$file_types = explode(".", $filename);
				$file_type = $file_types[count($file_types) - 1];
				foreach ($type_arr as $type) {
					if ($file_type == $type) {
						$isAllowType = true;
					}
				}
				if (!$isAllowType) {
					$result['success'] = false;
					$result['msg'] = "错误的类型";
					return $result;
				}

				$name_arr = explode('.', $filename);
				$type = end($name_arr);
				array_push($filetype, $type);
				array_push($Filename, $name_arr[0]);

			}

		}

		$file_error = false;
		foreach ($_FILES[$name]["error"] as $filestate) {
			if ($filestate > 0) {
				$file_error = true;
				break;
			}

		}

		if ($file_error == true) {
			echo "Return Code: " . $_FILES[$name]["error"] . "<br />";
		} else {

			$savePaths = array();

			$num = count($_FILES[$name]["tmp_name"]);
			for ($i = 0; $i < $num; $i++) {
				$filePath = $_FILES[$name]["tmp_name"][$i];
				$md5 = md5_file($filePath);
				$savePath = $path . $Filename[$i] . "." . $filetype[$i];
				if (file_exists($savePath) && $md5 == md5_file($savePath)) {
					//echo $savePath . "已经存在";
				} else {
					move_uploaded_file($filePath, $savePath);
					//echo $savePath;
				}
				array_push($savePaths, $savePath);
			}
			$result['success'] = true;
			return $result;
		}


	}

	function GetUploadFile($type_arr = null, $path = null, $size = null)
	{

		$result = array(
				'success' => false,
				'msg' => "",
				'type' => '',
				'path' => '',
		);

		if ($type_arr) {
			$isAllowType = false;
			$file_types = explode(".", $_FILES ['file'] ['name']);
			$file_type = $file_types[count($file_types) - 1];
			foreach ($type_arr as $type) {
				if ($file_type == $type) {
					$isAllowType = true;
				}
			}
			if (!$isAllowType) {
				$result['success'] = false;
				$result['msg'] = "错误的类型".$file_type;
				return $result;
			}
		}

		if ($_FILES["file"]["error"] > 0) {
			echo "Return Code: " . $_FILES["file"]["error"] . "<br />";
		} else {
			$filePath = $_FILES["file"]["tmp_name"];
			$md5 = md5_file($filePath);
			$name_arr = explode('.', $_FILES["file"]["name"]);
			$type = end($name_arr);

            if ( !$path )
                $savePath = "upload/excel/" . $md5 . "." . $type;
            else
                $savePath = $path . $md5 . "." . $type ;


			if (file_exists($savePath) && $md5 == md5_file($savePath)) {
				//echo $savePath . "已经存在";
			} else {
				move_uploaded_file($filePath, $savePath);
				//echo $savePath;
			}
			$result['success'] = true;
			$result['type'] = $type;
			$result['path'] = $savePath;
			return $result;
		}
	}
}
?>