<?php   
////连接数据库
// $conn = mysqli_connect("localhost","weixin","webweixin","weixin");
// if (mysqli_connect_errno($conn)) 
// { 
//  echo "连接 MySQL 失败: " . mysqli_connect_error(); 
// } 
//// mysqli_select_db("weixin",$conn) or die("数据库访问错误".mysqli_error());  
// mysqli_query("set names gb232");  
 
 $servername = "localhost";
$username = "weixin";
$password = "webweixin";
$dbname = "weixin";

// 创建连接
$conn = new mysqli($servername, $username, $password, $dbname);
// 检测连接
if ($conn->connect_error) {
    die("连接失败: " . $conn->connect_error);
} 
 $conn->query("set names gb232");
?>  