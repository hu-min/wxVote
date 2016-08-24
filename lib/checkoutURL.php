<?php
/**
  * wechat php test
 * 校验开发号URL，处理响应
  */
if(!defined("PATH")){define("PATH", dirname(dirname(__FILE__))); }
class wechatCallbackapiTest
{
	//定义公用校验方法
	public function valid()
    {
        $echoStr = $_GET["echostr"];

        //valid signature , option
        if($this->checkSignature()){
        	echo $echoStr;
        	exit;
        }
    }
	//获取消息类型
	public function getMsgType(){
		//获取微信服务器POST请求中的数据，POST的XML类型数据不能用$_POST来获取
		$postStr = $postStr = isset($GLOBALS["HTTP_RAW_POST_DATA"]) ? $GLOBALS["HTTP_RAW_POST_DATA"] : false;
		if(!$postStr){  
            $postStr = file_get_contents("php://input");  
    }  

      	//extract post data
		if (!empty($postStr)){
       /* libxml_disable_entity_loader is to prevent XML eXternal Entity Injection,
       the best way is to check the validity of xml by yourself */
       libxml_disable_entity_loader(true);
			//解析xml
      $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
      $msgType	= $postObj->MsgType;					//消息类型，
			
			return $msgType;
		}
	}
	//获取用户发送的信息
	public function getMsg(){
		//获取微信服务器POST请求中的数据，POST的XML类型数据不能用$_POST来获取
		$postStr = isset($GLOBALS["HTTP_RAW_POST_DATA"]) ? $GLOBALS["HTTP_RAW_POST_DATA"] : false;
		if(!$postStr){  
            $postStr = file_get_contents("php://input");  
    }  

      	//extract post data
		if (!empty($postStr)){
       /* libxml_disable_entity_loader is to prevent XML eXternal Entity Injection,
       the best way is to check the validity of xml by yourself */
       libxml_disable_entity_loader(true);
			//解析xml
      $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
      $msgType	= $postObj->MsgType;					//消息类型，
      $keyword = trim($postObj->Content);			//文本消息内容，trim（）将消息两边的空格消除方便对比
      $msgId	= $postObj->MsgId;							//消息ID，
      $arr = array('msgType'=>$msgType,'content'=>$keyword,'msgId'=>$msgId);
	  	
	  	return $arr;
		}
		return false;
	}
	//订阅事件
	public function EventListener($EventMap){
			//获取微信服务器POST请求中的数据，POST的XML类型数据不能用$_POST来获取
		$postStr = isset($GLOBALS["HTTP_RAW_POST_DATA"]) ? $GLOBALS["HTTP_RAW_POST_DATA"] : false;
		if(!$postStr){  
            $postStr = file_get_contents("php://input");  
    }  

      	//extract post data
		if (!empty($postStr)){
       /* libxml_disable_entity_loader is to prevent XML eXternal Entity Injection,
       the best way is to check the validity of xml by yourself */
       libxml_disable_entity_loader(true);
			//解析xml
      $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
      $msgType	= $postObj->MsgType;					//消息类型，
      
      //消息去重，避免重复响应
      $wxKeys = json_decode(file_get_contents(PATH."/conf/wxkey.json"),true);
      if($postObj->MsgId){
    		foreach($wxKeys as $value){
    			if(isset($value["msgid"])&&$value["msgid"] == $postObj->MsgId ){ exit; }
    		}  	
      	$keyArr = array();
				$keyArr["msgid"] = (string)$postObj->MsgId; 
				//以队列的方式从头部添加元素（先进先出）unshift：从头部插入| push:从尾部插入 | shift:从头部移除 | pop：从尾部移除
		  	array_unshift($wxKeys,$keyArr);
				
			  if(sizeof($wxKeys)>10){ array_pop($wxKeys); }  //以队列的方式从尾部移除元素
				file_put_contents(PATH."/conf/wxkey.json", json_encode($wxKeys));
			
      }else{
      	foreach($wxKeys as $value){
    			if(isset($value["fromusername"])&&$value["fromusername"] == $postObj->FromUserName&& $value["createtime"] == $postObj->CreateTime)
    			{
    				exit; 
					}
    		}  	
		
      	$keyArr = array();
				$keyArr["fromusername"] = (string)$postObj->FromUserName;
				$keyArr["createtime"] = (string)$postObj->CreateTime;
				//以队列的方式添加元素（先进先出）
		  	array_unshift($wxKeys,$keyArr);
			  if(sizeof($wxKeys)>10){ array_pop($wxKeys); }  //以队列的方式移除元素
				file_put_contents(PATH."/conf/wxkey.json", json_encode($wxKeys,JSON_UNESCAPED_UNICODE));
      }
      
      
      //如果消息类型是事件类型
      if($msgType == 'event')
	  	{
	  		switch($postObj->Event){
	  			//订阅事件 ，如果是扫描二维码，关注后微信会将带场景值关注事件推送给开发者
					case 'subscribe':
						$EventMap['subscribe']&&$EventMap['subscribe']();
						break;
					
						//取消订阅事件
					case 'unsubscribe':
						$EventMap['unsuscribe']&&$EventMap['unsuscribe']();
						break;
					
						//扫描二维码时，如果用户已经关注公众号，则微信会将带场景值扫描事件推送给开发者
					case 'SCAN':
						$EventMap['SCAN']&&$EventMap['SCAN']();
						break;
					
						/*
						 * 用户同意上报地理位置后，每次进入公众号会话时，都会在进入时上报地理位置，
						 * 或在进入会话后每5秒上报一次地理位置，公众号可以在公众平台网站中修改以上设置。
						 * 上报地理位置时，微信会将上报地理位置事件推送到开发者填写的URL
						 */
					
					case 'LOCATION':
						$EventMap['LOCATION']&&$EventMap['LOCATION']();
						break;	
						
						//自定义菜单点击事件，用户点击自定义菜单后，
						//微信会把点击事件推送给开发者，请注意，点击菜单弹出子菜单，不会产生上报。
					case 'CLICK':
						$EventMap['CLICK']&&$EventMap['CLICK']();
						break;
				
						//点击菜单跳转链接时的事件推送
					case 'VIEW':
						$EventMap['VIEW']&&$EventMap['VIEW']();
						break;	
	  		}
	  		
	  	}elseif($msgType == 'text'){
	  		//如果消息类型是 文本
	  		include_once(PATH.'/lib/responseMsg.php');
				onText();
	  	}
		} 
	}
	
	//响应用户消息
    public function responseMsg()
    {
		//获取微信服务器POST请求中的数据，POST的XML类型数据不能用$_POST来获取
		$postStr = isset($GLOBALS["HTTP_RAW_POST_DATA"]) ? $GLOBALS["HTTP_RAW_POST_DATA"] : false;
		
		if(!$postStr){  
            $postStr = file_get_contents("php://input");  
    }  
		
      	//extract post data
		if (!empty($postStr)){
                /* libxml_disable_entity_loader is to prevent XML eXternal Entity Injection,
                   the best way is to check the validity of xml by yourself */
                libxml_disable_entity_loader(true);
								//解析xml
              	$postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
                $fromUsername = $postObj->FromUserName;  //微信用户名
                $toUsername = $postObj->ToUserName;			//开发者微信号
                
                $msgType	= $postObj->MsgType;					//消息类型，暂时用不上
                $keyword = trim($postObj->Content);			//文本消息内容，trim（）将消息两边的空格消除方便对比
                $msgId	= $postObj->MsgId;							//消息ID，暂时用不上
                $time = time();
                $textTpl = "<xml>
							<ToUserName><![CDATA[%s]]></ToUserName>
							<FromUserName><![CDATA[%s]]></FromUserName>
							<CreateTime>%s</CreateTime>
							<MsgType><![CDATA[%s]]></MsgType>
							<Content><![CDATA[%s]]></Content>
							<FuncFlag>0</FuncFlag>
							</xml>";             
				if(!empty( $keyword ))
                {
              		$msgType = "text";
                	$contentStr = "欢迎光临彭亮的测试公众号!";
                	$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                	echo $resultStr;
                }else{
                	echo "Input something...";
                }

        }else {
        	echo "";
        	exit;
        }
    }
	
	//定义校验方法	
	private function checkSignature()
	{
        // you must define TOKEN by yourself
        if (!defined("TOKEN")) {
            throw new Exception('TOKEN is not defined!');
        }
        
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];
        		
		$token = TOKEN;
		$tmpArr = array($token, $timestamp, $nonce);
        // use SORT_STRING rule
		sort($tmpArr, SORT_STRING);
		$tmpStr = implode( $tmpArr );
		$tmpStr = sha1( $tmpStr );
		
		if( $tmpStr == $signature ){
			return true;
		}else{
			return false;
		}
	}
}

?>