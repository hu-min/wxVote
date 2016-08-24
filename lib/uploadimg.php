<?php 
	//图片储存路径，成功回调函数，失败回调函数
	function uploadimg($path , $success,$cancel=''){
		if(empty($_FILES['img']['name'])){$cancel&&$cancel();}
		//验证上传图片类型
		function type()
		{
			return substr(strrchr($_FILES['img']['name'],'.'),1);
		}
		//允许的文件类型,先不让上传gif
		$type=array("jpg","bmp","jpeg","png");
		$text = 'jpg,bmp,jpeg,png';
		$uploaddir=$path;
		
		//允许上传的图片大小
		if($_FILES['img']['size']> 2097152){
			echo '只允许上传大小在2M内的图片。';
		}
		
		//strtolower:将字符串转换为小写字母
		//in_arry:在数组中搜索给定的值
		if(!in_array(strtolower(type()),$type))//如果不存在能上传的类型  
		{  
			$text=implode('.',$type);  
			echo "您只能上传以下类型文件: ",$text,"<br>"; 
			$cancell&&$cancell(); 
		}else {  
			$filename=explode(".",$_FILES['img']['name']);//把上传的文件名以“.”号为准做一个数组。  
			echo "文件类型：".$_FILES['img']['type'];
			$time=date("m-d-H-i-s");//去当前上传的时间  
			$filename[0]=$time.mt_rand(0,100);//取文件名t替换  
			$name=implode(".",$filename); //上传后的文件名  
			$uploadfile=$uploaddir.$name;//上传后的文件名地址  
		}  
		//move_uploaded_file:执行上传文件
		if(move_uploaded_file($_FILES['img']['tmp_name'],$uploadfile))  
		{
			//返回文件名
			$success&&$success($name);  
			return $name;
		}  
		else  
		{  
			$cancel&&$cancel(); 
			return false;
		} 
		
	}	
?>