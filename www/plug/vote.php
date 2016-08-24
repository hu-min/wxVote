<?php 
	/*
	 * 投票处理函数
	 */	
	if(!defined('PATH')){define('PATH', dirname(dirname(dirname(__FILE__))));}
	function vote($number){
		include_once(PATH."/www/plug/config.php");
		include(PATH."/lib/conn.php");
		include_once(PATH."/lib/jssdk.php");
		
		//配置投票一次加多少积分
		$credits = 20;
		
		 
		 /*
		 * 1.检查是否需要关注才能投票
		 * 2.检查该用户今天投了多少票
		 * 3.检查该用户今天是否重复投了同一个人
		 * 4.检查投票的目标是否存在
		 * 5.检查该用户所在地区是否允许投票
		 */
		 $now = date("y-m-d H:i:s");
		 if($end&&strtotime($end)<strtotime($now)){
		 	return "活动已结束，请下次再来！";
		 }
//		 file_put_contents(PATH.'/log/mySQL.txt',$now,FILE_APPEND);
		 //验证获取用户信息
		 $postStr = isset($GLOBALS["HTTP_RAW_POST_DATA"]) ? $GLOBALS["HTTP_RAW_POST_DATA"] : false;
		if(!$postStr){  
            $postStr = file_get_contents("php://input");  
   	 	}  

		 //xml安全校验
		libxml_disable_entity_loader(true);
		//解析xml
		$postObj = simplexml_load_string($postStr,'SimpleXMLElement', LIBXML_NOCDATA);
		$OpenId = $postObj->FromUserName;  //微信用户名
		 
		 $jssdk = new JSSDK();
		 $user = $jssdk->getUser($OpenId);
		 
		 //伪造unionid
		// $user->unionid = '34f';
		 
		//1.如果要关注才能投票则检查该用户是否关注
		if($voteSub)
		{
			if($user->subscribe != 1){
				$msgType = "text";
                return $contentStr = "请先关注公众号!";
			}
		}
		//2.检查该用户今天投了多少票
		$sql = "select * from wx_act_poll where unionid='$user->unionid' and to_days(date) = to_days(now()) ";
		//$sql = "SELECT * FROM wx_act_poll Where unionid=$user->unionid and";
		$result = $conn->query($sql);//先获取结果集  
		$data = array();
		if($result){//判断结果集是否存在  
		   if($row=$result->num_rows){//判断结果集里面有没有数据  
		        while($row=$result->fetch_assoc()){//遍历所有记录 ,每次从结果集中取出一条记录 
		           $data[]=$row;  
		        }     
				if(sizeof($data)>=$voteNum){
					return "您今天投票次数已达到上限，请明天再来！";
				}
		    }else{//结果集里面没有数据，这里的mysql_num_rows($result)=0  
		         
		    }  
		}else{//结果集不存在，则是查询异常  
		     $date = date("Y-m-d H:i:s");
			$errstr = $date."插入投票数据错误：PATH:'/WWW/PLUG/VOTE.PHP',第59行！".$conn->error."\r\n";
			file_put_contents(PATH."/log/mySQL_log.txt", $errstr,FILE_APPEND);
			return "数据错误,请稍后再试！";  
		}  
		//3.判断是否允许重复投同一个人
		if($voteC){
			for($i = 0; $i<sizeof($data);$i++){
				for($a=$i+1; $a < sizeof($data);$a++){
					if($data[$i]==$data[$a])
					{
						return "每天只能对同一个人投一票！";
					}
			}
			}
		}
		
		//4.检查投票目标是否存在
		$sql = "select poll_time ,id from wx_act_user where id='$number' limit 1";
		$result = $conn->query($sql);
		if($result){
			if($row = $result->num_rows){
				while($row=$result->fetch_assoc()){
					$dataP[]= $row;
				}
			}else{
					return "您投票的编号不存在，请输入正确的编号。";
			}
		}else{
			$date = date("Y-m-d H:i:s");
			$errstr = $date."插入投票数据错误：PATH:'/WWW/PLUG/VOTE.PHP',第90行！".$conn->error()."\r\n";
			file_put_contents(PATH."/log/mySQL_log.txt", $errstr,FILE_APPEND);
			return "数据错误,请稍后再试！";
		}
		
		//将票数计入无效票
		function notVote($num,$conn,$user){
			$time = time();
			//更新无效票
//			$sql = "UPDATE wx_act_user SET nopoll=nopoll+1,poll_time=curtime() WHERE id=$num";
			if(!$conn->query($sql)){
				$date = date("Y-m-d H:i:s");
				$errstr = $date."插入投票数据错误：PATH:'/WWW/PLUG/VOTE.PHP',第112行！".$conn->error()."\r\n";
				file_put_contents(PATH."/log/mySQL_log.txt", $errstr,FILE_APPEND);
				return false;
			}
			//记录投票数据
			$sql = "insert into wx_act_poll(`unionid`, date, poll_id, city) values('$user->unionid', CURDATE(), $num, '$user->city')";
			if(!$conn->query($sql)){
				$date = date("Y-m-d H:i:s");
				$errstr = $date."插入投票数据错误：PATH:'/WWW/PLUG/VOTE.PHP',第110行！".$conn->error."\r\n";
				file_put_contents(PATH."/log/mySQL_log.txt", $errstr,FILE_APPEND);
				return false;
			}
			return true;
		}
		//检查投票是否设置间隔
		if(!empty($voteTime)){
			$nowTime = date('H:i:s');
			$myTime = strtotime($dataP[0]['poll_time'] ) ;
			
			$res = floor(($myTime -$nowTime)%86400%60);//计算时间差，按秒算
			if( $res<$voteTime)
			{
				//投票间隔小于设定值则记为无效
				$r = notVote($number, $conn,$user);
				if($r){return '投票成功！';}else{return '数据错误！';};
			}
		}
		
		//5.该投票的地区是否允许投票(未完成)
		if(!empty($voteProvince)&&empty($voteCity))
		{
			//如果该地区不允许投票则投票计入无效票
			if($user->province !=$voteProvince){
				$r = notVote($number, $conn,$user);
				if($r){return '投票成功！';}else{return '数据错误！';};
			}
		}
		if(!empty($voteCity))
		{
			//如果该地区不允许投票则投票计入无效票
			if($user->city !=$voteCity){
				$r = notVote($number, $conn,$user);
				if($r){return '投票成功！';}else{return '数据错误！';};
			}
		}
		
		//6.将投票数据写入数据库
		$time = time();
		$sql = "UPDATE wx_act_user SET poll=poll+1,poll_time=curtime() WHERE id=$number";
		if(!$conn->query($sql))
		{
			file_put_contents(PATH.'/log/mySQL.txt', $time."更新数据错误：/www/plug/vote.php;第163行。".$GLOBALS["conn"]->error."\r\n",FILE_APPEND);
		}
		
		//记录投票数据
		$sql = "insert into wx_act_poll(`unionid`, date, poll_id, city) values('$user->unionid', CURDATE(), $number, '$user->city')";
		if(!$conn->query($sql)){
			$date = date("Y-m-d H:i:s");
			$errstr = $date."插入投票数据错误：PATH:'/WWW/PLUG/VOTE.PHP',第170行！".$conn->error."\r\n";
			file_put_contents(PATH."/log/mySQL_log.txt", $errstr,FILE_APPEND);
			return false;
		}
		
		if(!empty($credits))
		{
			//如果设置了加减积分则给用户增加积分
			include(PATH.'/lib/account.php');
			$account = new ACCOUNT();
			$addCredits = $account->addCredits($credits, $user->unionid);
			$getCredits = $account->getCredits($user->unionid);

			$conter = "投票成功！\n恭喜您获得了：$addCredits 积分。\n您现在共有：$getCredits 积分。";
		}
		

		$conter = isset($conter) ? $conter:"投票成功！";
		return $conter;
		
	}
?>