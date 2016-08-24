<?php 
	if(!defined("PATH")){define("PATH", dirname(dirname(dirname(__FILE__)))); }
	require_once(PATH.'/lib/util.php');
	header("Content-type: text/json; charset=utf-8");
	//获取活动数据
	if( $_SERVER['REQUEST_METHOD'] == 'GET'){
		if( isset($_GET['type'])&&$_GET["type"] == "act"&&$_GET["do"]=="all" )
		{
		//获取全部数据
		include(PATH."/lib/conn.php");
		   
		 $sql="select * from wx_act_user";  
		 $rs=$conn->query($sql);  
		 if(!$rs){  
		     if(empty($rs)){  
		       //  echo 'empty res!';  
		     }  
		    // echo 'get failed !';  
		     exit;  
		 }  
		   
		 //新建数组  
		 $arr=array();  
		 //遍历  
		   
		 //1.直接输出结果  
		 //mysql_fetch_array:每次从结果集中取出一行作为数组,其他类似。  
		 while($row=$rs->fetch_assoc()){  
		   $arr[]=$row;  
		 }  
		   
//		 //2.转换为对象，处理数据  
//		 class Stu{  
//		     public $name;  
//		     public $age;  
//		 }  
//		   
//		 while($row=mysql_fetch_object($rs)){  
//		     $s=new Stu();  
//		     $s->name=$row->sname;  
//		     $s->age=$row->sage;  
//		     //填充数组  
//		     $arr[]=$s;  
//		 }  
//		   
//		 //对变量进行json编码  
//		 echo json_encode(array("state"=>"success",'student'=>$arr));  
//		   
//		 //释放结果  
//		 mysql_free_result($rs);  
//		//关闭连接  
//		 //通常不需要使用 mysql_close()，因为已打开的非持久连接会在脚本执行完毕后自动关闭  
//		 mysql_close();   

//			$arr = array(
//				array( 
//					'id' 		=> 1,
//					'unionid'	=> 'dsi',
//					'name'		=> '名字',
//					'poll'		=> 55,
//					'nopoll'	=> 3,
//					'describe'	=> '描述描述，我是描述',
//					'time'		=> '2016-7-21 17:39',  //参与时间
//					'poll_time' => '17:34',
//					'img_url'	=> 'http://15385md623.51mypc.cn/plug/img/toupiao1.jpg'
//				),
//				array( 
//					'id' 		=> 1,
//					'unionid'	=> 'dsi',
//					'name'		=> '名字',
//					'poll'		=> 55,
//					'nopoll'	=> 3,
//					'describe'	=> '描述描述，我是描述',
//					'time'		=> '2016-7-21 17:39',  //参与时间
//					'poll_time' => '17:34',
//					'img_url'	=> 'http://15385md623.51mypc.cn/plug/img/toupiao2.jpg'
//				),
//				array( 
//					'id' 		=> 1,
//					'unionid'	=> 'dsi',
//					'name'		=> '名字',
//					'poll'		=> 55,
//					'nopoll'	=> 3,
//					'describe'	=> '描述描述，我是描述',
//					'time'		=> '2016-7-21 17:39',  //参与时间
//					'poll_time' => '17:34',
//					'img_url'	=> 'http://15385md623.51mypc.cn/plug/img/toupiao3.jpg'
//				),
//				array( 
//					'id' 		=> 1,
//					'unionid'	=> 'dsi',
//					'name'		=> '名字',
//					'poll'		=> 55,
//					'nopoll'	=> 3,
//					'describe'	=> '描述描述，我是描述',
//					'time'		=> '2016-7-21 17:39',  //参与时间
//					'poll_time' => '17:34',
//					'img_url'	=> 'http://15385md623.51mypc.cn/plug/img/toupiao1.jpg'
//				),
//				array( 
//					'id' 		=> 1,
//					'unionid'	=> 'dsi',
//					'name'		=> '名字',
//					'poll'		=> 55,
//					'nopoll'	=> 3,
//					'describe'	=> '描述描述，我是描述',
//					'time'		=> '2016-7-21 17:39',  //参与时间
//					'poll_time' => '17:34',
//					'img_url'	=> 'http://15385md623.51mypc.cn/plug/img/toupiao1.jpg'
//				),
//				array( 
//					'id' 		=> 1,
//					'unionid'	=> 'dsi',
//					'name'		=> '名字',
//					'poll'		=> 55,
//					'nopoll'	=> 3,
//					'describe'	=> '描述描述，我是描述',
//					'time'		=> '2016-7-21 17:39',  //参与时间
//					'poll_time' => '17:34',
//					'img_url'	=> 'http://15385md623.51mypc.cn/plug/img/toupiao2.jpg'
//				),
//				array( 
//					'id' 		=> 1,
//					'unionid'	=> 'dsi',
//					'name'		=> '名字',
//					'poll'		=> 55,
//					'nopoll'	=> 3,
//					'describe'	=> '描述描述，我是描述',
//					'time'		=> '2016-7-21 17:39',  //参与时间
//					'poll_time' => '17:34',
//					'img_url'	=> 'http://15385md623.51mypc.cn/plug/img/toupiao1.jpg'
//				),
//				array( 
//					'id' 		=> 1,
//					'unionid'	=> 'dsi',
//					'name'		=> '名字',
//					'poll'		=> 55,
//					'nopoll'	=> 3,
//					'describe'	=> '描述描述，我是描述',
//					'time'		=> '2016-7-21 17:39',  //参与时间
//					'poll_time' => '17:34',
//					'img_url'	=> 'http://15385md623.51mypc.cn/plug/img/toupiao3.jpg'
//				),
//				array( 
//					'id' 		=> 1,
//					'unionid'	=> 'dsi',
//					'name'		=> '名字',
//					'poll'		=> 55,
//					'nopoll'	=> 3,
//					'describe'	=> '描述描述，我是描述',
//					'time'		=> '2016-7-21 17:39',  //参与时间
//					'poll_time' => '17:34',
//					'img_url'	=> 'http://15385md623.51mypc.cn/plug/img/toupiao1.jpg'
//				),
//				array( 
//					'id' 		=> 1,
//					'unionid'	=> 'dsi',
//					'name'		=> '名字',
//					'poll'		=> 55,
//					'nopoll'	=> 3,
//					'describe'	=> '描述描述，我是描述',
//					'time'		=> '2016-7-21 17:39',  //参与时间
//					'poll_time' => '17:34',
//					'img_url'	=> 'http://15385md623.51mypc.cn/plug/img/toupiao2.jpg'
//				),
//				array( 
//					'id' 		=> 1,
//					'unionid'	=> 'dsi',
//					'name'		=> '名字',
//					'poll'		=> 55,
//					'nopoll'	=> 3,
//					'describe'	=> '描述描述，我是描述',
//					'time'		=> '2016-7-21 17:39',  //参与时间
//					'poll_time' => '17:34',
//					'img_url'	=> 'http://15385md623.51mypc.cn/plug/img/toupiao1.jpg'
//				),
//				array( 
//					'id' 		=> 1,
//					'unionid'	=> 'dsi',
//					'name'		=> '名字',
//					'poll'		=> 55,
//					'nopoll'	=> 3,
//					'describe'	=> '描述描述，我是描述',
//					'time'		=> '2016-7-21 17:39',  //参与时间
//					'poll_time' => '17:34',
//					'img_url'	=> 'http://15385md623.51mypc.cn/plug/img/toupiao3.jpg'
//				),
//				array( 
//					'id' 		=> 1,
//					'unionid'	=> 'dsi',
//					'name'		=> '名字',
//					'poll'		=> 55,
//					'nopoll'	=> 3,
//					'describe'	=> '描述描述，我是描述',
//					'time'		=> '2016-7-21 17:39',  //参与时间
//					'poll_time' => '17:34',
//					'img_url'	=> 'http://15385md623.51mypc.cn/plug/img/toupiao1.jpg'
//				),
//				array( 
//					'id' 		=> 1,
//					'unionid'	=> 'dsi',
//					'name'		=> '名字',
//					'poll'		=> 55,
//					'nopoll'	=> 3,
//					'describe'	=> '描述描述，我是描述',
//					'time'		=> '2016-7-21 17:39',  //参与时间
//					'poll_time' => '17:34',
//					'img_url'	=> 'http://15385md623.51mypc.cn/plug/img/toupiao2.jpg'
//				),
//				array( 
//					'id' 		=> 1,
//					'unionid'	=> 'dsi',
//					'name'		=> '名字',
//					'poll'		=> 55,
//					'nopoll'	=> 3,
//					'describe'	=> '描述描述，我是描述',
//					'time'		=> '2016-7-21 17:39',  //参与时间
//					'poll_time' => '17:34',
//					'img_url'	=> 'http://15385md623.51mypc.cn/plug/img/toupiao3.jpg'
//				),
//				array( 
//					'id' 		=> 1,
//					'unionid'	=> 'dsi',
//					'name'		=> '名字',
//					'poll'		=> 55,
//					'nopoll'	=> 3,
//					'describe'	=> '描述描述，我是描述',
//					'time'		=> '2016-7-21 17:39',  //参与时间
//					'poll_time' => '17:34',
//					'img_url'	=> 'http://15385md623.51mypc.cn/plug/img/toupiao2.jpg'
//				),
//				array( 
//					'id' 		=> 1,
//					'unionid'	=> 'dsi',
//					'name'		=> '名字',
//					'poll'		=> 55,
//					'nopoll'	=> 3,
//					'describe'	=> '描述描述，我是描述',
//					'time'		=> '2016-7-21 17:39',  //参与时间
//					'poll_time' => '17:34',
//					'img_url'	=> 'http://15385md623.51mypc.cn/plug/img/toupiao1.jpg'
//				),
//				array( 
//					'id' 		=> 1,
//					'unionid'	=> 'dsi',
//					'name'		=> '名字',
//					'poll'		=> 55,
//					'nopoll'	=> 3,
//					'describe'	=> '描述描述，我是描述',
//					'time'		=> '2016-7-21 17:39',  //参与时间
//					'poll_time' => '17:34',
//					'img_url'	=> 'http://15385md623.51mypc.cn/plug/img/toupiao1.jpg'
//				),
//				array( 
//					'id' 		=> 1,
//					'unionid'	=> 'dsi',
//					'name'		=> '名字',
//					'poll'		=> 55,
//					'nopoll'	=> 3,
//					'describe'	=> '描述描述，我是描述',
//					'time'		=> '2016-7-21 17:39',  //参与时间
//					'poll_time' => '17:34',
//					'img_url'	=> 'http://15385md623.51mypc.cn/plug/img/toupiao3.jpg'
//				),
//				array( 
//					'id' 		=> 1,
//					'unionid'	=> 'dsi',
//					'name'		=> '名字',
//					'poll'		=> 55,
//					'nopoll'	=> 3,
//					'describe'	=> '描述描述，我是描述',
//					'time'		=> '2016-7-21 17:39',  //参与时间
//					'poll_time' => '17:34',
//					'img_url'	=> 'http://15385md623.51mypc.cn/plug/img/toupiao1.jpg'
//				),
//				array( 
//					'id' 		=> 1,
//					'unionid'	=> 'dsi',
//					'name'		=> '名字',
//					'poll'		=> 55,
//					'nopoll'	=> 3,
//					'describe'	=> '描述描述，我是描述',
//					'time'		=> '2016-7-21 17:39',  //参与时间
//					'poll_time' => '17:34',
//					'img_url'	=> 'http://15385md623.51mypc.cn/plug/img/toupiao2.jpg'
//				),
//				array( 
//					'id' 		=> 1,
//					'unionid'	=> 'dsi',
//					'name'		=> '名字',
//					'poll'		=> 55,
//					'nopoll'	=> 3,
//					'describe'	=> '描述描述，我是描述',
//					'time'		=> '2016-7-21 17:39',  //参与时间
//					'poll_time' => '17:34',
//					'img_url'	=> 'http://15385md623.51mypc.cn/plug/img/toupiao2.jpg'
//				),
//				array( 
//					'id' 		=> 1,
//					'unionid'	=> 'dsi',
//					'name'		=> '名字',
//					'poll'		=> 55,
//					'nopoll'	=> 3,
//					'describe'	=> '描述描述，我是描述',
//					'time'		=> '2016-7-21 17:39',  //参与时间
//					'poll_time' => '17:34',
//					'img_url'	=> 'http://15385md623.51mypc.cn/plug/img/toupiao3.jpg'
//				),
//				array( 
//					'id' 		=> 1,
//					'unionid'	=> 'dsi',
//					'name'		=> '名字',
//					'poll'		=> 55,
//					'nopoll'	=> 3,
//					'describe'	=> '描述描述，我是描述',
//					'time'		=> '2016-7-21 17:39',  //参与时间
//					'poll_time' => '17:34',
//					'img_url'	=> 'http://15385md623.51mypc.cn/plug/img/toupiao2.jpg'
//				),
//				array( 
//					'id' 		=> 1,
//					'unionid'	=> 'dsi',
//					'name'		=> '名字',
//					'poll'		=> 55,
//					'nopoll'	=> 3,
//					'describe'	=> '描述描述，我是描述',
//					'time'		=> '2016-7-21 17:39',  //参与时间
//					'poll_time' => '17:34',
//					'img_url'	=> 'http://15385md623.51mypc.cn/plug/img/toupiao1.jpg'
//				),
//				array( 
//					'id' 		=> 1,
//					'unionid'	=> 'dsi',
//					'name'		=> '名字',
//					'poll'		=> 55,
//					'nopoll'	=> 3,
//					'describe'	=> '描述描述，我是描述',
//					'time'		=> '2016-7-21 17:39',  //参与时间
//					'poll_time' => '17:34',
//					'img_url'	=> 'http://15385md623.51mypc.cn/plug/img/toupiao2.jpg'
//				),
//				array( 
//					'id' 		=> 1,
//					'unionid'	=> 'dsi',
//					'name'		=> '名字',
//					'poll'		=> 55,
//					'nopoll'	=> 3,
//					'describe'	=> '描述描述，我是描述',
//					'time'		=> '2016-7-21 17:39',  //参与时间
//					'poll_time' => '17:34',
//					'img_url'	=> 'http://15385md623.51mypc.cn/plug/img/toupiao1.jpg'
//				),
//				array( 
//					'id' 		=> 1,
//					'unionid'	=> 'dsi',
//					'name'		=> '名字',
//					'poll'		=> 55,
//					'nopoll'	=> 3,
//					'describe'	=> '描述描述，我是描述',
//					'time'		=> '2016-7-21 17:39',  //参与时间
//					'poll_time' => '17:34',
//					'img_url'	=> 'http://15385md623.51mypc.cn/plug/img/toupiao3.jpg'
//				),
//				array( 
//					'id' 		=> 1,
//					'unionid'	=> 'dsi',
//					'name'		=> '名字',
//					'poll'		=> 55,
//					'nopoll'	=> 3,
//					'describe'	=> '描述描述，我是描述',
//					'time'		=> '2016-7-21 17:39',  //参与时间
//					'poll_time' => '17:34',
//					'img_url'	=> 'http://15385md623.51mypc.cn/plug/img/toupiao3.jpg'
//				),
//				array( 
//					'id' 		=> 1,
//					'unionid'	=> 'dsi',
//					'name'		=> '名字',
//					'poll'		=> 55,
//					'nopoll'	=> 3,
//					'describe'	=> '描述描述，我是描述',
//					'time'		=> '2016-7-21 17:39',  //参与时间
//					'poll_time' => '17:34',
//					'img_url'	=> 'http://15385md623.51mypc.cn/plug/img/toupiao2.jpg'
//				),
//				array( 
//					'id' 		=> 1,
//					'unionid'	=> 'dsi',
//					'name'		=> '名字',
//					'poll'		=> 55,
//					'nopoll'	=> 3,
//					'describe'	=> '描述描述，我是描述',
//					'time'		=> '2016-7-21 17:39',  //参与时间
//					'poll_time' => '17:34',
//					'img_url'	=> 'http://15385md623.51mypc.cn/plug/img/toupiao1.jpg'
//				),
//				array( 
//					'id' 		=> 1,
//					'unionid'	=> 'dsi',
//					'name'		=> '名字',
//					'poll'		=> 55,
//					'nopoll'	=> 3,
//					'describe'	=> '描述描述，我是描述',
//					'time'		=> '2016-7-21 17:39',  //参与时间
//					'poll_time' => '17:34',
//					'img_url'	=> 'http://15385md623.51mypc.cn/plug/img/toupiao1.jpg'
//				),
//				array( 
//					'id' 		=> 1,
//					'unionid'	=> 'dsi',
//					'name'		=> '名字',
//					'poll'		=> 55,
//					'nopoll'	=> 3,
//					'describe'	=> '描述描述，我是描述',
//					'time'		=> '2016-7-21 17:39',  //参与时间
//					'poll_time' => '17:34',
//					'img_url'	=> 'http://15385md623.51mypc.cn/plug/img/toupiao2.jpg'
//				),
//				array( 
//					'id' 		=> 1,
//					'unionid'	=> 'dsi',
//					'name'		=> '名字',
//					'poll'		=> 55,
//					'nopoll'	=> 3,
//					'describe'	=> '描述描述，我是描述',
//					'time'		=> '2016-7-21 17:39',  //参与时间
//					'poll_time' => '17:34',
//					'img_url'	=> 'http://15385md623.51mypc.cn/plug/img/toupiao2.jpg'
//				),
//				array( 
//					'id' 		=> 1,
//					'unionid'	=> 'dsi',
//					'name'		=> '名字',
//					'poll'		=> 55,
//					'nopoll'	=> 3,
//					'describe'	=> '描述描述，我是描述',
//					'time'		=> '2016-7-21 17:39',  //参与时间
//					'poll_time' => '17:34',
//					'img_url'	=> 'http://15385md623.51mypc.cn/plug/img/toupiao2.jpg'
//				),
//				array( 
//					'id' 		=> 1,
//					'unionid'	=> 'dsi',
//					'name'		=> '名字',
//					'poll'		=> 55,
//					'nopoll'	=> 3,
//					'describe'	=> '描述描述，我是描述',
//					'time'		=> '2016-7-21 17:39',  //参与时间
//					'poll_time' => '17:34',
//					'img_url'	=> 'http://15385md623.51mypc.cn/plug/img/toupiao1.jpg'
//				),
//				array( 
//					'id' 		=> 1,
//					'unionid'	=> 'dsi',
//					'name'		=> '名字',
//					'poll'		=> 55,
//					'nopoll'	=> 3,
//					'describe'	=> '描述描述，我是描述',
//					'time'		=> '2016-7-21 17:39',  //参与时间
//					'poll_time' => '17:34',
//					'img_url'	=> 'http://15385md623.51mypc.cn/plug/img/toupiao2.jpg'
//				),
//				array( 
//					'id' 		=> 1,
//					'unionid'	=> 'dsi',
//					'name'		=> '名字',
//					'poll'		=> 55,
//					'nopoll'	=> 3,
//					'describe'	=> '描述描述，我是描述',
//					'time'		=> '2016-7-21 17:39',  //参与时间
//					'poll_time' => '17:34',
//					'img_url'	=> 'http://15385md623.51mypc.cn/plug/img/toupiao1.jpg'
//				),
//				array( 
//					'id' 		=> 1,
//					'unionid'	=> 'dsi',
//					'name'		=> '名字',
//					'poll'		=> 55,
//					'nopoll'	=> 3,
//					'describe'	=> '描述描述，我是描述',
//					'time'		=> '2016-7-21 17:39',  //参与时间
//					'poll_time' => '17:34',
//					'img_url'	=> 'http://15385md623.51mypc.cn/plug/img/toupiao1.jpg'
//				),
//				array( 
//					'id' 		=> 1,
//					'unionid'	=> 'dsi',
//					'name'		=> '名字',
//					'poll'		=> 55,
//					'nopoll'	=> 3,
//					'describe'	=> '描述描述，我是描述',
//					'time'		=> '2016-7-21 17:39',  //参与时间
//					'poll_time' => '17:34',
//					'img_url'	=> 'http://15385md623.51mypc.cn/plug/img/toupiao3.jpg'
//				),
//				array( 
//					'id' 		=> 1,
//					'unionid'	=> 'dsi',
//					'name'		=> '名字',
//					'poll'		=> 55,
//					'nopoll'	=> 3,
//					'describe'	=> '描述描述，我是描述',
//					'time'		=> '2016-7-21 17:39',  //参与时间
//					'poll_time' => '17:34',
//					'img_url'	=> 'http://15385md623.51mypc.cn/plug/img/toupiao2.jpg'
//				),
//				array( 
//					'id' 		=> 1,
//					'unionid'	=> 'dsi',
//					'name'		=> '名字',
//					'poll'		=> 55,
//					'nopoll'	=> 3,
//					'describe'	=> '描述描述，我是描述',
//					'time'		=> '2016-7-21 17:39',  //参与时间
//					'poll_time' => '17:34',
//					'img_url'	=> 'http://15385md623.51mypc.cn/plug/img/toupiao1.jpg'
//				),
//				array( 
//					'id' 		=> 1,
//					'unionid'	=> 'dsi',
//					'name'		=> '名字',
//					'poll'		=> 55,
//					'nopoll'	=> 3,
//					'describe'	=> '描述描述，我是描述',
//					'time'		=> '2016-7-21 17:39',  //参与时间
//					'poll_time' => '17:34',
//					'img_url'	=> 'http://15385md623.51mypc.cn/plug/img/toupiao3.jpg'
//				),
//				array( 
//					'id' 		=> 1,
//					'unionid'	=> 'dsi',
//					'name'		=> '名字',
//					'poll'		=> 55,
//					'nopoll'	=> 3,
//					'describe'	=> '描述描述，我是描述',
//					'time'		=> '2016-7-21 17:39',  //参与时间
//					'poll_time' => '17:34',
//					'img_url'	=> 'http://15385md623.51mypc.cn/plug/img/toupiao1.jpg'
//				),
//				array( 
//					'id' 		=> 1,
//					'unionid'	=> 'dsi',
//					'name'		=> '名字',
//					'poll'		=> 55,
//					'nopoll'	=> 3,
//					'describe'	=> '描述描述，我是描述',
//					'time'		=> '2016-7-21 17:39',  //参与时间
//					'poll_time' => '17:34',
//					'img_url'	=> 'http://15385md623.51mypc.cn/plug/img/toupiao2.jpg'
//				),
//				array( 
//					'id' 		=> 1,
//					'unionid'	=> 'dsi',
//					'name'		=> '名字',
//					'poll'		=> 55,
//					'nopoll'	=> 3,
//					'describe'	=> '描述描述，我是描述',
//					'time'		=> '2016721',  //参与时间
//					'poll_time' => '17:34',
//					'img_url'	=> 'http://15385md623.51mypc.cn/plug/img/toupiao1.jpg'
//				),
//				array( 
//					'id' 		=> 1,
//					'unionid'	=> 'dsi',
//					'name'		=> '名字',
//					'poll'		=> 55,
//					'nopoll'	=> 3,
//					'describe'	=> '描述描述，我是描述',
//					'time'		=> '2016721',  //参与时间
//					'poll_time' => '17:34',
//					'img_url'	=> 'http://15385md623.51mypc.cn/plug/img/toupiao2.jpg'
//				),
//				array( 
//					'id' 		=> 1,
//					'unionid'	=> 'dsi',
//					'name'		=> '名字',
//					'poll'		=> 55,
//					'nopoll'	=> 3,
//					'describe'	=> '描述描述，我是描述',
//					'time'		=> '2016721',  //参与时间
//					'poll_time' => '17:34',
//					'img_url'	=> 'http://15385md623.51mypc.cn/plug/img/toupiao3.jpg'
//				),
//				array( 
//					'id' 		=> 1,
//					'unionid'	=> 'dsi',
//					'name'		=> '名字',
//					'poll'		=> 55,
//					'nopoll'	=> 3,
//					'describe'	=> '描述描述，我是描述',
//					'time'		=> '2016721',  //参与时间
//					'poll_time' => '17:34',
//					'img_url'	=> 'http://15385md623.51mypc.cn/plug/img/toupiao1.jpg'
//				),
//				array( 
//					'id' 		=> 1,
//					'unionid'	=> 'dsi',
//					'name'		=> '名字',
//					'poll'		=> 55,
//					'nopoll'	=> 3,
//					'describe'	=> '描述描述，我是描述',
//					'time'		=> '2016721',  //参与时间
//					'poll_time' => '17:34',
//					'img_url'	=> 'http://15385md623.51mypc.cn/plug/img/toupiao2.jpg'
//				)
//			);
			//JSON编码时加JSON_UNESCAPED_UNICODE才能解析出中文
			$data = json_encode($arr , JSON_UNESCAPED_UNICODE);
		//	file_put_contents('../../log/token_log.txt',$data);
			//释放结果  
			// mysql_free_result($rs);  
			//关闭连接  
			 //通常不需要使用 mysql_close()，因为已打开的非持久连接会在脚本执行完毕后自动关闭  
			 $conn->close();   
		//	echo json_encode($arr, JSON_FORCE_OBJECT);
			echo $data;
		
		} 
		
		if(isset($_GET['type'])&&$_GET['type']== 'act'&&$_GET['do']=="index"&&$_GET['index'])
		{
			include(PATH."/lib/conn.php");
			$conn->query("set names utf8");
			//按名字或id索引查找
			//mysql_real_escape_string：过滤sql中的关键字符
			$indexStr = htmlspecialchars($_GET['index']); 
		//	$indexStr = mysql_real_escape_string($indexStr);
			
			if(mb_strlen($indexStr) > 10){
				//长度大于10
				echo 0;
				exit;
			}
			if( preg_match_all('/\\d+/',$indexStr,$matchs1))
			{
				//提取数字
				$num = join('',$matchs1[0]);
				include(PATH."/lib/conn.php");
				$sql = "select * from wx_act_user where id=? limit 1";
				
				//预处理及绑定查询语句
				$stmt = $conn->prepare($sql);
				$stmt->bind_param("i", $num);
				//执行语句
				$stmt->execute();
				$result = $stmt->get_result();
				
			//	$result = $conn->query($sql);
				
				if($result){
					$result = $result->fetch_assoc();
					//获取排名
					$id = $result['id'];
					$sql = "SELECT count(*)  from (SELECT * FROM `wx_act_user` ORDER BY poll DESC)
					 as a where poll >= (SELECT poll from `wx_act_user` where id = '$id')";
					
					$row = $conn->query($sql);
					$rank = $row->fetch_assoc();
					
					$arr = array();
					$arr["type"] = 'num';
					$arr['rank'] = $rank['count(*)'];
					$arr['data'] = $result;
					$res = json_encode($arr,JSON_UNESCAPED_UNICODE);
					echo $res;//var_dump($res);
					exit;
				}else{
					//查找数据错误
				}
			}
			$preg ='/[\x{4e00}-\x{9fa5}\w]+/u'; //匹配中英文和数字的正则正则表达式\w表示英文和数字
			if(preg_match_all($preg, $indexStr,$matchs))
			{
				//提取名字
				$name = join('',$matchs[0]);
				include(PATH."/lib/conn.php");
				$sql = "select * from wx_act_user where `name`=?";
				
				//预处理及绑定查询语句
				$stmt = $conn->prepare($sql);
				$stmt->bind_param("s", $name);
				//执行语句
				$stmt->execute();
				$result = $stmt->get_result();
				
				$arr = array();
			//	$result = $conn->query($sql);
				if($result)
				{
					while($res = $result->fetch_assoc()){
						$arr[]=$res; 
					}
					$array = array();
					$array['type'] = 'list';
					$array['data'] = $arr;
					$res = json_encode($array,JSON_UNESCAPED_UNICODE);
					
					echo $res;
					exit;
				}
				
			}
		}
		
		if(isset($_GET['type'])&&$_GET['type']== 'act'&&$_GET['do']=="pollpage"&&!empty($_GET['page'])){
			//如果按页获取数据
			$length = empty($_GET['length'])? 20:$_GET['length'];  //每页的长度
			$page	= $_GET['page'];
			$pageMin= $page-1; 
			$pageMin= $pageMin*$length;
			$pageMax= $page*$length;
			//连接数据库
			include(PATH."/lib/conn.php");
			$conn->query("set names utf8");
			
			$sql = "select * from wx_act_user order by poll desc limit $pageMin,$pageMax"; 
			$result = $conn->query($sql);
			$arr = array();
			if($result)
			{
				while($row = $result->fetch_assoc()){
					$arr[] = $row; 
				}
			}
			
			//获取列表总长度
			$sql = "select count(*) from wx_act_user";
			$result = $conn->query($sql);
			$row = $result->fetch_assoc();
			
			$array['length'] = $row['count(*)'];
			$array['data'] = $arr;
			$res = json_encode($array,JSON_UNESCAPED_UNICODE);
			
			echo $res;
			exit;
		}

		if(isset($_GET['type'])&&$_GET['type']== 'act'&&$_GET['do']=="timepage"&&!empty($_GET['page'])){
			//如果按页获取最新用户数据,
			$length = empty($_GET['length'])? 20:$_GET['length'];  //每页的长度
			$page	= $_GET['page'];
			$pageMin= $page-1; 
			$pageMin= $pageMin*$length;
			$pageMax= $page*$length;
			//连接数据库
			include(PATH."/lib/conn.php");
			$conn->query("set names utf8");
			
			$sql = "select * from wx_act_user order by time desc limit $pageMin,$pageMax"; 
			$result = $conn->query($sql);
			$arr = array();
			if($result)
			{
				while($row = $result->fetch_assoc()){
					$arr[] = $row; 
				}
			}else{
				echo $conn->error();
			}
			
			//获取列表总长度
			$sql = "select count(*) from wx_act_user";
			$result = $conn->query($sql);
			$row = $result->fetch_assoc();
			
			$array['length'] = $row['count(*)'];
			$array['data'] = $arr;
			$res = json_encode($array,JSON_UNESCAPED_UNICODE);
			
			echo $res;
			exit;
		}
		if(isset($_GET['type'])&&$_GET['type']== 'act'&&$_GET['do']=="toppage"&&!empty($_GET['page']))
		{
			//如果按页获取数据
			$length = empty($_GET['length'])? 100:$_GET['length'];  //每页的长度
			$page	= $_GET['page'];
			$pageMin= $page-1; 
			$pageMin= $pageMin*$length;
			$pageMax= $page*$length;
			//连接数据库
			include(PATH."/lib/conn.php");
			$conn->query("set names utf8");
			
			$sql = "select * from wx_act_user order by poll desc limit $pageMin,$pageMax"; 
			$result = $conn->query($sql);
			$arr = array();
			if($result)
			{
				while($row = $result->fetch_assoc()){
					$arr[] = $row; 
				}
			}
			
			//获取列表总长度
			$sql = "select count(*) from wx_act_user";
			$result = $conn->query($sql);
			$row = $result->fetch_assoc();
			
			$array['length'] = $row['count(*)'];
			$array['data'] = $arr;
			$res = json_encode($array,JSON_UNESCAPED_UNICODE);
			
			echo $res;
			exit;
		}
	}elseif($_SERVER['REQUEST_METHOD']=="POST"){
		
	}else{
		header("Location:".PATH."/www/404.php");
	}
	
	
?>