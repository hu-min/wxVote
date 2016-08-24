<?php 
	/*
	 * 生成微信二维码
	 */	
	 if(!defined("PATH")){define("PATH", dirname(dirname(__FILE__))); }
	class wxQRCode {
		public static function create_ticket( $access_token, $action_name, $expire_seconds, $scene_id){
			//创建二维码ticket的接口地址
			$url = "https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=$access_token";
			
			//临时二维码
			if(strtoupper($action_name) == 'QR_SCENE')
			{
				//检查临时二维码的最长有效时间
				if( $expire_seconds >$1800 || !isset($expire_seconds) )
				{
					$expire_seconds = 1800;
				}
				
				$data = array("expire_seconds" => $expire_seconds,
							  "action_name" => "QR_SCENE",
							  "action_info" => array(
							  						"scene" => array( "scene_id" => $scene_id ))
							);
			}else if(strtoupper($action_name) == 'QR_LIMIT_SCENE')
			{
				//永久二维码
				$data = array("action_name" => "QR_LIMIT_SCENE",
							  "action_info" => array( "scene" =>array("scene_id" => $scene_id))
							 );
			}else{
				echo "二维码类型只能为  QR_SCENE 或 QR_LIMIT_SCENE!";
				return "";
			}
			
			$contentStr = json_encode($data);
			
			require_once(PATH."/lib/jssdk.php");
			
			$res = util::httpReq($url, "POST", $contentStr);
			$resObj = json_decode($res);
			
			//如果没有错误
			if(empty($resObj->errcode))
			{
				//返回ticket
				return $resObj->ticket;
			}else{
				return flase;
			}
		},
		/*
		 * 通过ticket取得二维码
		 */
		public function get_qrcode( $ticket ){
			$ticket = urlencode($ticket);
			$url = "https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=$ticket";
			
			$res = util::httpReq($url);
			
			return $res;
		}
		
	}
?>