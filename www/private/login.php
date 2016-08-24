<?php 
	//登陆模块
	if(!defined("PATH")){define("PATH", dirname(dirname(dirname(__FILE__)))); }
	header("Content-Type: text/html;charset=utf-8;");
	//包含数据库连接文件  
	include(PATH.'/lib/conn.php'); 
?>
<?php  
	//登录  
	if( $_SERVER['REQUEST_METHOD'] == 'POST'){
		if(!isset($_POST['submit'])){  
		    exit('非法访问!');  
		} 
		session_start();  
		if(!empty($_SESSION['userid']))
		{
			//echo $_SESSION['userid'];
			header('Location:private/checkuser.php');

		} 
		//验证并过滤用户名
		$username = htmlspecialchars($_POST['username']); 
	//	$username = mysql_real_escape_string($username); 
		$password = MD5($_POST['password']);  
		 
		 
		//检测用户名及密码是否正确  
		$sql = "select user_id from user_list where user_name=? and password=? limit 1";
		//$sql = "SELECT `user_name`, `user_id`, `password` FROM `user_list` WHERE 1 LIMIT 0, 30 ";
		
		//预处理及绑定查询语句
		$stmt = $conn->prepare($sql);
		$stmt->bind_param("ss", $username, $password);
		//执行语句
		$stmt->execute();
		$check_query = $stmt->get_result();
		
	//	$check_query = $conn->query($sql);  
		if($result = $check_query->fetch_assoc()){  
		    //登录成功  
		    
		    $_SESSION['username'] = $username;  
		    $_SESSION['userid'] = $result['user_id'];  
		    echo $username.' 欢迎你！进入 <a href="checkuser.php">用户中心</a><br />';  
		    echo '点击此处 <a href="login.php?action=logout">注销</a> 登录！<br />';  
		//	echo "<script> history.go(-2);</script>";
			
		    exit;  
		} else {  
		    exit('登录失败！点击此处 <a href="javascript:history.back(-1);">返回</a> 重试');  
		}  
	} 
	  
	  
	//注销登录  
	if($_GET['action'] == "logout"){
		session_start();  
	    unset($_SESSION['userid']);  
	    unset($_SESSION['username']);  
	    echo '注销登录成功！点击此处 <a href="login.html">登录</a>';  
	    exit;  
	}  
  
?> 

