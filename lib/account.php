<?php
	/*
	 * 微信用户账户管理类
	 */	
	if(!defined('PATH')){define('PATH',dirname(dirname(__FILE__)));}
	include(PATH.'/lib/conn.php');
	
	class ACCOUNT{
		public function addCredits($credits,$unionid){
			//增加积分
		//	include(PATH.'/lib/conn.php');
			$sql = "insert into `wx_user_account`(credits,unionid) values($credits,'$unionid') 
			on duplicate key update credits=$credits+credits";
			if($GLOBALS["conn"]->query($sql)){
			//	$conn->close();
				return $credits;
			}else{
				$time = date('y-m-d H:i:s');
				file_put_contents(PATH.'/log/mySQL.txt', $time."更新数据错误：/lib/account.php;addCredits函数。".$GLOBALS["conn"]->error."\r\n",FILE_APPEND);
				return false;
			}
		}
		public function redCredits($credits,$unionid){
			//减少积分
		//	include(PATH.'/lib/conn.php');
			$sql = "update wx_user_account set credits=credits-$credits where unionid='$unionid'";
			if($GLOBALS["conn"]->query($sql)){
				return $credits;
			}else{
				$time = date('y-m-d H:i:s');
				file_put_contents(PATH.'/log/mySQL.txt',$time. "更新数据错误：/lib/account.php;redCredits函数。".$GLOBALS["conn"]->error."\r\n",FILE_APPEND);
				return false;
			}
		}
		public function getCredits($unionid=''){
			//查询积分,unionid为空则返回所有人的积分
		//	include(PATH.'/lib/conn.php');
			if(empty($unionid)){
				$sql = "select credits from wx_user_account";
				$reselt = $GLOBALS["conn"]->query($sql);
				if($result->num_rows > 0)
				{
					
					$arr = array();
					while($res = $result->fetch_assoc());
					{
						$arr[] = $res['credits'];
					}
					return $arr;
				}else{
					$time = date('y-m-d H:i:s');
					file_put_contents(PATH.'/log/mySQL.txt', $time."查询数据错误：/lib/account.php;getCredits函数。".$GLOBALS["conn"]->error."\r\n",FILE_APPEND);
				return false;
				}
				
			}else{
				
				$sql = "select credits from wx_user_account where unionid='$unionid'";
				$result = $GLOBALS["conn"]->query($sql);
				if($result->num_rows > 0)
				{
				//	$GLOBALS["conn"]->close();
					$res = $result->fetch_assoc();
					return $res["credits"];
				}else{
					$time = date('y-m-d H:i:s');
					file_put_contents(PATH.'/log/mySQL.txt', $time."查询数据错误：/lib/account.php;getCredits函数。".$GLOBALS["conn"]->error."\r\n",FILE_APPEND);
				return false;
				}
			}
			
		}
	}
?>