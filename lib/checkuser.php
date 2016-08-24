<?php  
//用户中心，负责用户登录检测
if(!defined("PATH")){define("PATH", dirname(dirname(__FILE__))); }
session_start();  
  
//检测是否登录，若没登录则转向登录界面  
if(!isset($_SESSION['userid'])){  
    header("Location:".PATH."/www/private/login.html");  
    exit();  
}  
header("Content-Type: text/html;charset=utf-8;");
//包含数据库连接文件  
include('conn.php');  
$user_id = $_SESSION['userid'];  
$user_name = $_SESSION['username'];  
$user_query = $conn->query("select * from user_list where user_name = '$user_name' limit 1");  
$row =$result->fetch_assoc();  
echo '用户信息：<br />';  
echo '用户ID：',$user_id,'<br />';  
echo '用户名：',$user_name,'<br />';  
echo '<a href="login.php?action=logout">注销</a> 登录<br />';  
?>  