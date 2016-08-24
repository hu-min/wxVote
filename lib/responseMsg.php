<?php 
/*
 * 处理微信消息响应
 */
	if(!defined("PATH")){define("PATH", dirname(dirname(__FILE__))); }
	require(PATH."/lib/conn.php");
	require_once(PATH.'/lib/jssdk.php');
	$conn->query("set names utf8");
	$jssdk = new JSSDK();
	
	$eventMap = array('text'=>'onText','image'=>'onImage');
	
	
	function onText(){
		$errStr = "请输入正确的指令，如：\n'TP'+数字的方式来投票。\n'ID'获取openID。";
		
		$postStr = isset($GLOBALS["HTTP_RAW_POST_DATA"]) ? $GLOBALS["HTTP_RAW_POST_DATA"] : false;
		if(!$postStr){  
            $postStr = file_get_contents("php://input");  
  		}  

		if(!empty($postStr))
		{
			//xml安全校验
			libxml_disable_entity_loader(true);
			//解析xml
			$postObj = simplexml_load_string($postStr,'SimpleXMLElement', LIBXML_NOCDATA);
			$fromUsername = $postObj->FromUserName;  //微信用户名
            $toUsername = $postObj->ToUserName;			//开发者微信号
            $msgType	= $postObj->MsgType;				//消息类型
            $keyword = trim($postObj->Content);			//文本消息内容，trim（）将消息两边的空格消除方便对比
            $time = time();
			
			$textTpl = "<xml>
							<ToUserName><![CDATA[%s]]></ToUserName>
							<FromUserName><![CDATA[%s]]></FromUserName>
							<CreateTime>%s</CreateTime>
							<MsgType><![CDATA[%s]]></MsgType>
							<Content><![CDATA[%s]]></Content>
						</xml>";  
						
			//消息去重验证，避免重复触发
						
						
			if(!empty($keyword))
			{
				$str = substr($keyword, 0,2);
				switch ($str) {
					//前两个字符为TP则做投票处理
					case 'TP':
						$num = substr($keyword, 2);
						//验证投票号码
						if(!preg_match_all('/\d+/',$num,$n)){$contentStr = $errStr; break;};
						include_once(PATH.'/www/plug/vote.php');
						$num = (int)join('',$n[0]);
						
						$contentStr =  vote($num);;
						break;
					case 'ID':
						$contentStr = $fromUsername;
						break;
					default:
						$contentStr = $errStr;
						break;
				}
				
				$msgType = "text";
                //$contentStr = "欢迎光临彭亮的测试公众号!";
                $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
				file_put_contents(PATH.'/log/mySQL.txt',"$resultStr",FILE_APPEND);
				
                echo  "$resultStr";
				
			}
		}
	}
?>