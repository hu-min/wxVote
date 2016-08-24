
<?php
/*
 * 获取微信access_token
 */
 	
	myToken::init();
	class myToken
	{
		private static $path = "";
		
		public static function init(){
			$path =  dirname(dirname(__FILE__));
			self::$path = $path;
		}	
		
		//从文件中获取token
		public static function getToken (){
			$path = self::$path;
			$content = json_decode(file_get_contents($path."/conf/access_token.json"),true);
			
			if( empty($content["errcode"]) ){
				return $content["access_token"];
			}else{
				file_put_contents(self::$path."/log/token_log.txt", "获取access_token失败,errcode:".$content["errcode"]."; errmsg:".$content["errmsg"].date("Y-m-d H:i:s")."\n\r");
				return false;
			}
			
		}
		//获取token过期时间
		public static function getExpires_in(){
			$path = self::$path;
			$content = json_decode(file_get_contents($path."/conf/access_token.json"),true);
			
			if( empty($content["errcode"]) ){
				return $content["expires_in"];
			}else{
				file_put_contents(self::$path."/log/token_log.txt", "获取expires_in失败,errcode:".$content["errcode"]."; errmsg:".$content["errmsg"].date("Y-m-d H:i:s")."\n\r");
				return false;
			}
			
		}
		//更新token并将其写入文件
		public static function updateToken(){
			$url		= "https://api.weixin.qq.com/cgi-bin/token?";
			$grant_type = "client_credential";
			$appid		= "wx2b823442f4ed975c";
			$secret		= "63dfb9ce066d7ecce0aef4be6280bf1d";
			
			$url	= $url.'grant_type='.$grant_type."&appid=".$appid."&secret=".$secret;
			//获取token
			$content=  json_decode(file_get_contents($url) , true);
			//如果错误代码为空则将token写入文件
			if(empty($content["errcode"]))
			{
				$path = self::$path."/conf";
				if (!is_dir($path)){
				    mkdir($path,0777);  // 创建文件夹test,并给777的权限（所有权限）
				}
				$content = json_encode($content);
				$file = $path."/access_token.json";    // 写入的文件
				file_put_contents($file,$content);  // 最简单的快速的以默认覆盖的方式写入写入方法，
				
				return true;
			}else{
				file_put_contents(self::$path."/log/token_log.txt", "获取access_token失败".date("Y-m-d H:i:s")."\n\r");
				return false;
			}
		}
		//从文件中获取jsapi_ticket
//		public static function getJsApiTicket() {
			
			
//			$path = self::$path;
//			$content = json_decode(file_get_contents($path."/conf/jsapi_ticket.json"),true);
//			
//			if( empty($content["errcode"]) ){
//				return $content["jsapi_ticket"];
//			}else{
//				file_put_contents(self::$path."/log/jsapi_ticket.txt", "获取jsapi_ticket失败,errcode:".$content["errcode"]."; errmsg:".$content["errmsg"].date("Y-m-d H:i:s")."\n\r");
//				return false;
//			}
//		}
		//更新jsapi_ticket并保存在本地文件中
//		public static function updateJsApiTicket() {
//			$url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?type=jsapi&access_token=$accessToken";
//			//获取ticket
//			$content = json_decode(file_get_contents($url), true);
//			//如果错误代码为空或0则将ticket写入文件
//			if(empty($content["errcode"]))
//			{
//				$path = self::$path."/conf";
//				if (!is_dir($path))
//				{
//					mkdir($path,0777); //创建文件夹，并给777权限（所有权限）
//				}
//				$content = json_encode( $content );
//				$file = $path."/jsapi_ticket.json";
//				file_put_contents($file, $content); //默认覆盖的方式写入
//				
//				return true;
//			}else{
//				file_put_contents(self::$path."/log/jsapi_ticket.txt", "获取jsapi_ticket失败".date("Y-m-d H:i:s")."\n\r");
//				return false;
//			}
//			
////		    // jsapi_ticket 应该全局存储与更新，以下代码以写入到文件中做示例
////		    $data = json_decode(file_get_contents()));
////		    if ($data->expire_time < time()) {
////		      $accessToken = $this->getAccessToken();
////		      // 如果是企业号用以下 URL 获取 ticket
////		      // $url = "https://qyapi.weixin.qq.com/cgi-bin/get_jsapi_ticket?access_token=$accessToken";
////		      $url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?type=jsapi&access_token=$accessToken";
////		      $res = json_decode($this->httpGet($url));
////		      $ticket = $res->ticket;
////		      if ($ticket) {
////		        $data->expire_time = time() + 7000;
////		        $data->jsapi_ticket = $ticket;
////		        $this->set_php_file("jsapi_ticket.php", json_encode($data));
////		      }
////		    } else {
////		      $ticket = $data->jsapi_ticket;
////		    }
////		
////		    return $ticket;
//	  }
	}
?>