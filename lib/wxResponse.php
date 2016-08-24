<?php  
	/*
	 * 微信事件响应
	 */ 
	if(!defined("PATH")){define("PATH", dirname(dirname(__FILE__))); }
	require_once(PATH."/lib/conn.php");
	require_once(PATH.'/lib/jssdk.php');
	$conn->query("set names utf8");
	$jssdk = new JSSDK("wx2b823442f4ed975c", "63dfb9ce066d7ecce0aef4be6280bf1d",PATH);
	
	$eventMap = array('subscribe'=>'onSubscribe','unsubscribe'=>'onUnsubscribe');
	class EventMap{
		/*
		 * 获取POST数据
		 */	
		private function getPost(){
			//获取微信服务器POST请求中的数据，POST的XML类型数据不能用$_POST来获取
			$postStr = isset($GLOBALS["HTTP_RAW_POST_DATA"]) ? $GLOBALS["HTTP_RAW_POST_DATA"] : false;
			if(!$postStr){  
	            $postStr = file_get_contents("php://input");  
	  			return $postStr;
			}  
		},
		
		public function onSubscribe(){
//			$postStr = isset($GLOBALS["HTTP_RAW_POST_DATA"]) ? $GLOBALS["HTTP_RAW_POST_DATA"] : false;
//			if(!$postStr){  
//	            $postStr = file_get_contents("php://input");  
//	  		}  
			$postStr = $this->getPost();
	
	      	//extract post data
			if (!empty($postStr)){
	                /* libxml_disable_entity_loader is to prevent XML eXternal Entity Injection,
	                   the best way is to check the validity of xml by yourself */
	                libxml_disable_entity_loader(true);
									//解析xml
	              	$postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
	                $fromUsername = $postObj->FromUserName;  //微信用户名
	                $toUsername = $postObj->ToUserName;			//开发者微信号
	                $msgType	= $postObj->MsgType;				//消息类型
	                $EventKey	= isset($postObj->EventKey)?$postObj->EventKey : false;
	               	
	                $time = time();
	                $textTpl = "<xml>
								<ToUserName><![CDATA[%s]]></ToUserName>
								<FromUserName><![CDATA[%s]]></FromUserName>
								<CreateTime>%s</CreateTime>
								<MsgType><![CDATA[%s]]></MsgType>
								<Content><![CDATA[%s]]></Content>
								</xml>";             
					if($msgType == 'event')
	                {
	                	if($postObj->Event == 'subscribe'){
							//保存用户数据
							$user = $GLOBALS['jssdk']->getUser($fromUsername);
							//伪造unionid
						//	$user->unionid = '34f';
							if($user)
							{
							//	var_dump($user);
								//插入数据指令
								/*
								 $user->subscribe,'$user->unionid','$user->nickname',$user->sex,'$user->language',
								'$user->city','$user->province','$user->country','$user->headimgurl',$user->subscribe_time,'$user->remark','$user->groupid' 
								 */
								$sql = "INSERT INTO `wx_user_list`(`subscribe`, `unionid`, `nickname`, `sex`, `language`, 
								`city`, `province`, `country`, `headimgurl`, `subscribe_time`, `remark`, `groupid`) 
								VALUES ($user->subscribe,'$user->unionid','$user->nickname',$user->sex,'$user->language',
								'$user->city','$user->province','$user->country','$user->headimgurl',$user->subscribe_time,'$user->remark','$user->groupid') 
								ON DUPLICATE KEY UPDATE  subscribe=$user->subscribe,unionid='$user->unionid',nickname='$user->nickname',sex='$user->sex',language='$user->language',
								city='$user->city',province='$user->province',country='$user->country',headimgurl='$user->headimgurl',subscribe_time='$user->subscribe_time',remark='$user->remark',groupid='$user->groupid'";
								
//								//预处理及绑定查询语句
//								$stmt = $conn->prepare($sql);
//								$stmt->bind_param("sssisssssiss", $user->subscribe,$user->unionid,$user->nickname,$user->sex,$user->language,
//								$user->city,$user->province,$user->country,$user->headimgurl,$user->subscribe_time,$user->remark,$user->groupid);
//								//执行语句
//								$stmt->execute();
								
								if(!$GLOBALS['conn']->query($sql))
								{
									echo $conn->error();
									$date = date("Y-m-d H:i:s");
								    $errstr = $date."插入数据错误：".$conn->error()."\r\n";
								    file_put_contents(PATH."/log/mySQL_log.txt", $errstr,FILE_APPEND);
								}else{
								    $contentStr = "欢迎光临彭亮的测试公众号!\n回复：";
									$contentStr += "'TP123'的方式投票。"; 
								}
								
							}
						
						$contentStr = "欢迎光临彭亮的测试公众号!\n回复：";
						$contentStr .= "'TP123'的方式投票。";
	              		
	              		if($EventKey){ $contentStr .= "\n二维码的KEY为：$EventKey";}
						
	              		$msgType = "text";
	                	
	                	$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
	                	echo $resultStr;
	                	}
						
	                }else{
	                	echo "Input something...";
	                }
	
	        }else {
	        	echo "";
	        	exit;
	        }
		},
		
		public function onUnsubscribe(){
			
		},
		/*
		 * 当用户扫描二维码时触发
		 */
		public function onScan(){
			$postStr = $this->getPost();
			
			if (!empty($postStr)){
	            /* libxml_disable_entity_loader is to prevent XML eXternal Entity Injection,
	            the best way is to check the validity of xml by yourself */
	            libxml_disable_entity_loader(true);
				//解析xml
              	$postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
                $fromUsername = $postObj->FromUserName;  //微信用户名
                $toUsername = $postObj->ToUserName;		//开发者微信号
	            $msgType	= $postObj->MsgType;		//消息类型
	            $Event		= $postObj->Event;			//事件类型
	            $EventKey	= $postObj->EventKey;		//二维码参数	
	               	
	            $time = time();
	            $textTpl = "<xml>
							<ToUserName><![CDATA[%s]]></ToUserName>
							<FromUserName><![CDATA[%s]]></FromUserName>
							<CreateTime>%s</CreateTime>
							<MsgType><![CDATA[%s]]></MsgType>
							<Content><![CDATA[%s]]></Content>
							</xml>";             
				if($msgType == 'event')
	            {
	               	if($postObj->Event == 'subscribe'){
						//获取用户数据
						$user = $GLOBALS['jssdk']->getUser($fromUsername);
						
					}
				}
				$contentStr = "欢迎光临彭亮的测试公众号!\n回复：";
				$contentStr .= "'TP123'的方式投票。";
				
				if(!empty($EventKey)){ $contentStr .= "\n二维码的KEY为：$EventKey";}
				
	       		$msgType = "text";
	            	
	            $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
	            echo $resultStr;
			}
		}
	}	
?>