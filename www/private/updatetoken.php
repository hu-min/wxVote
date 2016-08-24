<?php 
if(!defined("PATH")){define("PATH", dirname(dirname(dirname(__FILE__)))); }
//	header("Content-Type:text/html;charset:utf-8;");
	require (PATH."/lib/jssdk.php")
//	require (PATH."/lib/get_access_token.php");
	require (PATH."/lib/util.php");
	//用户验证
	include (PATH."/lib/checkuser.php");
	$do	  = isset($_GET["do"]) ? $_GET["do"]:"";
	if($user_name != '2115797'){
		echo "您无权 操作此页面！";
		exit();
	}
	
	$is_updateToken = file_get_contents("updateToken.txt"); 
	
	
	if($do == "close")
	{
		
		file_put_contents("updateToken.txt", "false");
		echo ("关闭脚本！");
	}elseif($do == "open"){
		if($is_updateToken == "true"){
			echo ("脚本已经打开，请不要重复操作！");
			return ;
		}
		 
		//后台运行，将超时时间设为0；
		ignore_user_abort(true); 
		set_time_limit(0); 
		
		ob_end_clean();#清除之前的缓冲内容，这是必需的，如果之前的缓存不为空的话，里面可能有http头或者其它内容，导致后面的内容不能及时的输出
		header("Connection: close");//告诉浏览器，连接关闭了，这样浏览器就不用等待服务器的响应
		header("HTTP/1.1 200 OK");
		
		ob_start();#开始当前代码缓冲
		echo "打开脚本！";
		//下面输出http的一些头信息
		$size=ob_get_length();
		header("Content-Length: $size");
		ob_end_flush();#输出当前缓冲
		flush();#输出PHP缓冲
		
		sleep(1); //休眠一秒
		
		//后面的输出浏览器看不到，已经是后台运行了
//		$expires_in = myToken::getExpires_in();
//		$expires_in = empty($expires_in) ? 7200 : $expires_in;
		
		file_put_contents("updateToken.txt", "true");
		while(true) {
			$jssdk = new JSSDK("wx2b823442f4ed975c",'63dfb9ce066d7ecce0aef4be6280bf1d',PATH);
			$jssdk->getA
			
//			$is_updateToken = file_get_contents("updateToken.txt");
//			
//			if ( $is_updateToken == "true" ){
//				myToken::updateToken();
//			//	myToken::updateJsApiTicket();
//				$fp = fopen(PATH.'/log/token_log.txt',"a+"); 
//				$str = "token更新时间：".date("Y-m-d H:i:s")."\n\r"; 
//				fwrite($fp,$str); 
//				fclose($fp); 
////				$fp = fopen(PATH.'/log/jsapi_ticket.txt', "a+");
////				$str = "ticket更新时间：".date('Y-m-d h:i:s')."\n\r";
////				fwrite($fp, $str);
////				fclose($fp);
////				
//				sleep( $expires_in - 200 ); //提前一百秒更新token
			}else{
				exit();
			}
		}
	}else{
		echo  "请输入正确的指令！";
	} 
?>