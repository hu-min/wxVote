<?php
	if(!defined("PATH")){define("PATH", dirname(dirname(dirname(__FILE__)))); }
	include_once(PATH."/lib/conn.php");
	
	//获取总参与人数
	$sql = "select count(*) from wx_act_user";
	$result = $conn->query($sql);
	$row = $result->fetch_assoc();
	$act_a_value= $row['count(*)'];
	
	//获取总投票数
	$sql = "select count(*) from wx_act_poll";
	$result = $conn->query($sql);
	$row = $result->fetch_assoc();
	$act_b_value= $row['count(*)'];
	
	//获取网页的浏览量
	$sql = "select pv from statistics where name='toupiao' limit 1";
	$result = $conn->query($sql);
	$row = $result->fetch_assoc();
	$act_c_value= $row['pv'];
		
?>