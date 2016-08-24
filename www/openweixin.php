<?php
//认证和响应微信服务器请求
	if(!defined("PATH")){define("PATH", dirname(dirname(__FILE__))); }
	require_once("../lib/checkoutURL.php");
	require_once(PATH."/lib/wxResponse.php");
	
	$wechatObj = new wechatCallbackapiTest();
	
	
	
	if( $_SERVER['REQUEST_METHOD'] == 'GET'){
		//define your token
		define("TOKEN", "plweixintoken");
		
		$wechatObj->valid();
	}elseif($_SERVER['REQUEST_METHOD']=="POST"){
	//	$wechatObj->responseMsg();
		$wechatObj->EventListener($eventMap);
	}else{
	//	header("Location:".PATH."/www/404.php");
	}
?>