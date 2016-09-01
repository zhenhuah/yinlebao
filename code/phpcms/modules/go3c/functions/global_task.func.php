<?php
//参数接收get,post,request,cookie
function getgpc($k, $var='R')
{
	switch($var) 
	{
		case 'G': $var = &$_GET; break;
		case 'P': $var = &$_POST; break;
		case 'C': $var = &$_COOKIE; break;
		case 'R': $var = &$_REQUEST; break;
	}
	
	return isset($var[$k]) ? $var[$k] : NULL;
}


/*
*  desc:上传文件:
*  @param: $prefix "此参数是在同一秒需要进行多个文件上传时，会生成的文件名是一样的，会上传失败，所以加入此参数进行设置文件名前缀修改"
*/
function uploaded_file($filename,$filepath="",$FileResetName="",$prefix = "")
{
	$absolute_path	= $_SERVER['DOCUMENT_ROOT'];
	//类型
	$global_file=array(
			0 => ".jpg",
			1 => ".gif",
			2 => ".png",
			3 => ".jpeg",
			4 => ".mpeg"
	);
    $file_type_check = 0 ;
	$uploaded_state = array();
	if(!$_FILES[$filename]['error'])
    {
		//判断文件格式,文件大小；
        $file_type = strrchr($_FILES[$filename]['name'], ".");
		$fileType = strtolower($file_type);
        if($_FILES[$filename]['size'] < 10485760)
        {
			$file_type_check = (array_search($fileType, $global_file) > -1) ? 1 : 0;
        }
    }else{
		//error_report("上传失败,上传类型不支持!");
		return $uploaded_state['msg'] = '上传失败,上传类型不支持!';
    }
	
    if($file_type_check)
    {
		$today = (empty($FileResetName)) ?
			md5(date("Y").date("m").date("d").date("H").date("i").date("s")).strrchr($_FILES[$filename]['name'], "."): $FileResetName.strrchr($_FILES[$filename]['name'], ".");
		if(!empty($prefix))
		{
			$today = $prefix.$today;
		}
        $monthpath = date("Y").date("m");
		if(empty($filepath))
		{
			$uploaddir = $absolute_path.'/uploadfile/'.$monthpath.'/';
			$httpdir = $monthpath.'/';
		}else{
			$uploaddir = $absolute_path.'/uploadfile/'.$filepath.'/';
			$httpdir = $filepath.'/';
		}

		if(!file_exists($uploaddir))    //检查文件目录是否存在；
        {
			mkdir ($uploaddir,0777);   //创建文件目录；
        }
            
		$uploadfile = $uploaddir."/". $today;
        $largeimg = $httpdir. $today;
        if(!move_uploaded_file($_FILES[$filename]['tmp_name'], $uploadfile ))//上传文件
        {
			return $uploaded_state['msg'] = '上传失败, 请重新上传您的附件!';
        }else{
			$uploaded_state['msg'] = '1';	//表示成功，其他都是上传失败
		}
	}else{
		return $uploaded_state['msg'] = '上传失败: 文件大小超过 2MB 或 格式错误!';
    }
	
	$uploaded_state['path'] = $largeimg;	//路径
    
	return $uploaded_state;
}


?>
