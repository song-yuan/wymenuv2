<?php 
  /*
   * statuse 0 图片格式不对 1 上传成功
   * 
   */
  
   $path="uploads/company_".$dpid."/weixin/card/"; //上传路径 
  if(!file_exists($path)) 
	{ 
	  //检查是否有该文件夹，如果没有就创建，并给予最高权限 
	 $res = mkdir($path, 0777, true);
	}//END IF 
	//允许上传的文件格式 
	$tp = array("image/pjpeg","image/jpeg","image/png"); 
	//检查上传文件是否在允许上传的类型 
	if(!in_array($_FILES["filename"]["type"],$tp)) 
	{ 
		 $url = 0;
		 exit; 
	}//END IF 
	if($_FILES["filename"]["name"]) 
	{ 
		$now = time();
		$imgname = $_FILES["filename"]["name"]; //获取上传的文件名称
		$filetype = pathinfo($imgname, PATHINFO_EXTENSION);//获取后缀
		$name = date('YmdHid',time()).rand(100,999).".".$filetype;
	
		//文件名称 取新名称
		$file2 = $path.$name;
		$flag=1; 
	}//END IF 
	if($flag) $result=move_uploaded_file($_FILES["filename"]["tmp_name"],$file2); 
	//特别注意这里传递给move_uploaded_file的第一个参数为上传到服务器上的临时文件 
	if($result) 
	{ 
	  $url = $file2;
	  echo $url;
	  exit;
	} 
?>