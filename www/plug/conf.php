<?php
	$host = isset($_SERVER['HTTP_X_FORWARDED_HOST']) ? $_SERVER['HTTP_X_FORWARDED_HOST'] : (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '');
	$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
	$path	= "/plug";
	$imgurl  = $protocol.$host.$path."/img/";
	$jsurl	= $protocol.$host.$path."/js/";
	$css_url= $protocol.$host.$path."/css/";
	
	
	$wxUrl	= '';		//微信公众号的URL
	$tab_css_url= $css_url."tab.css";
	$ajax_js = $jsurl."ajax.js";
	$tab_url = $protocol.$host.$path."/getdata.php"; //获取投票数据
	$head_color  = "#ffe89a";  //头部背景颜色
	$title		 = "投票活动";
	$header_imgA = $imgurl."h.jpg";//头部图片
	$header_imgB = $imgurl."mw_004.jpg";//头部下边
	$tab_bottom_img= $imgurl."mw_005.jpg"; //主体底部图片
	//头部数据
	$act_a		= "已报名";
	$act_a_value= "32";
	$act_b		= "投票人次";
	$act_b_value= "300";
	$act_c		= "浏览量";
	$act_c_value= "3";
 	
	$keyword	= "搜名字或编号";
	
	$tab_A	= '最新参赛';
	$tab_B	= '投票排行';
	$tab_C	= 'TOP300';
	$list_len = 10; //配置列表长度
	$page_len = 4;	//配置索引页长度
	$rank_len = 15; //配置rank列表长度
?>