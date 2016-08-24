<?php
	if(!defined('PATH')){ define('PATH', dirname(dirname(dirname(__FILE__))));}
	require_once(PATH.'/lib/uploadimg.php');
	require(PATH.'/lib/jssdk.php');
	header("Content-Type:text/html; charset=utf-8");
	//包含数据库连接文件  
	include(PATH.'/lib/conn.php');
	
	//参加活动页面
	
	$jssdk = new JSSDK();
	
	if($_SERVER['REQUEST_METHOD'] == "POST"){
		if(!isset($_POST['submit']))
		{
			exit ('非法访问');
		}
		//配置投票一次加多少积分
		$credits = 20;
		
		/*
		 *验证是否关注公众账号和是否重复参加活动
		 *  
		 * 1.获取OpenID
		 * 2.通过OpenID获取用户信息，成功则继续，失败请用户关注
		 * 3.获取基本信息后验证是否已经参加了活动，没有则继续，有则返回：“请不要重复参加活动！”
		 * 
		*/
		//1.获取OpenID  先硬编码来替代
		$openid =  htmlspecialchars($_POST['openid']); 
		$user = $jssdk->getUser($openid);
		//伪造unionid
	//	$user->unionid = '34f';
		//查找是否参加了活动
		$sql = "select poll_time id from wx_act_user where unionid=? limit 1";
		
		//预处理及绑定查询语句
		$stmt = $conn->prepare($sql);
		$stmt->bind_param("s", $user->unionid);
		//执行语句
		$stmt->execute();
		$result = $stmt->get_result();
		
	//	$result = $conn->query($sql);
		if($result){
			if($row = $result->num_rows){
				echo "<conter>每个微信号只能参加一次，请不要重复参加。</conter>";
				echo " <br><center><a href='javascript:history.back(-1);'>点击返回上一页</a></center>";
				exit;
			}
			
		}else{
			$date = date("Y-m-d H:i:s");
			$errstr = $date."查找数据错误：PATH:'/WWW/PLUG/join.PHP'第45行".$conn->error."\r\n";
			file_put_contents(PATH."/log/mySQL_log.txt", $errstr,FILE_APPEND);
			echo "<conter>数据错误，请稍后再试。</conter>";
			echo " <br><center><a href='javascript:history.back(-1);'>点击返回上一页</a></center>";
			exit;
		}
		
		
		//校验上传数据是否合法
		//验证并过滤用户名
		//mysql_real_escape_string：过滤sql中的关键字符
		$name = htmlspecialchars($_POST['name']); 
	//	$name = mysql_real_escape_string($name);
		$describe = htmlspecialchars($_POST['describe']);
	//	$describe = mysql_real_escape_string($describe); 
		
		if(mb_strlen($name) > 10){
			echo "<center>名字过长</center>";
		    echo " <br><center><a href='javascript:history.back(-1);'>点击返回上一页</a></center>";
			exit;
		}
		if(empty($describe))
		{
			echo "<center>描述不能为空</center>";
		    echo " <br><center><a href='javascript:history.back(-1);'>点击返回上一页</a></center>";
			exit;
		}
		if(mb_strlen($describe) > 30) {
			echo "<center>描述过长</center>";
		    echo " <br><center><a href='javascript:history.back(-1);'>点击返回上一页</a></center>";
			exit;
		}
		if( preg_match('/\\d+/',$name,$matchs1) == 1)
		{
			echo "<center>名字里不能包含数字</center>";
		    echo " <br><center><a href='javascript:history.back(-1);'>点击返回上一页</a></center>";
			exit;
		}
		
		//过滤中文的正则表达式 
	//	$pattern = /[u4e00-u9fa5]/;
	//	$preg	= '/[x{4e00}-x{9fff}]+/u';  //匹配中文的正则表达式
		$preg ='/[\x{4e00}-\x{9fa5}\w]+/u'; //匹配中英文和数字的正则正则表达式\w表示英文和数字
		preg_match_all($preg ,$name,$matches);
		$name = join('', $matches[0]);
		
		preg_match_all($preg ,$describe,$matches);
		$describe = join('', $matches[0]);
	
		function success($uploadfile){
			GLOBAL $fileName;
			$fileName = $uploadfile;
			echo "<link src='./css/echoElm.css'>";
//			echo "<center>您的文件已经上传完毕 上传图片预览: </center><br><center><img src='./tpimg/$uploadfile'></center>";  
			echo"<br><center><a href='javascript:history.go(-1)'>继续上传</a></center>";
		}
		function cancel(){
			echo "<center>上传失败！";  
			echo"<br><center><a href='javascript:history.go(-1)'>重新上传</a></center>";
			exit;
		}
		
		//上传图片
		$success = 'success';// 将函数名保存后当作为参数传递进去
		$cancel = 'cancel';
		$path = uploadimg(PATH.'/www/plug/tpimg/', $success, $cancel);
		
		if(empty($fileName))
		{
			echo"<br><center><a href='javascript:history.go(-1)'>请上传图片！</a></center>";
			exit;
		}else{
			include(PATH."/lib/util.php");
			$path = util::getHost();
			$path .= "/plug/tpimg/".$fileName;
			$now = date("y-m-d H:i:s");
			
			//将数据存入数据库
			$sql = "INSERT INTO `wx_act_user`(`unionid`,`name`,`describe`,`img_url`,`time`)  
			VALUES(?,?,?,?,now())";
			$conn->query("set names utf8");
			
			//预处理及绑定查询语句
			$stmt = $conn->prepare($sql);
			$stmt->bind_param("ssss", $user->unionid, $name, $describe, $path);
		
			
			if($stmt->execute()){
				echo "<center>恭喜你成功参加活动！";  
				if(!empty($credits))
				{
					//如果设置了加减积分则给用户增加积分
					include(PATH.'/lib/account.php');
					$account = new ACCOUNT();
					$addCredits = $account->addCredits($credits, $user->unionid);
					$getCredits = $account->getCredits($user->unionid);
					
					echo "<br><center>恭喜您成功参加活动！\r\n您获得了：".$addCredits."积分。\r\n您现在共有：".$getCredits."积分。</center>";
				}
				
				echo"<br><center><a href='javascript:history.go(-1)'>3秒后返回上一页，或点击返回上一页页</a></center>";
				echo "<br><script>setTimeout(function(){history.go(-1);},3000)</script>";
				exit;
			}else{
				//保存数据失败，删除图片
				$result = unlink ('./tpimg/'.$fileName); 
				if ($result == false) { 
				//删除失败 
				echo('删除失败');
				} else { 
				//成功失败
				echo "成功删除";
				} 
				
			//	echo $conn->error;
				$date = date("Y-m-d H:i:s");
				$errstr = $date."插入投票数据错误：PATH:'/WWW/PLUG/join.PHP'".$conn->error."\r\n";
				file_put_contents(PATH."/log/mySQL_log.txt", $errstr,FILE_APPEND);
				echo "<center>数据错误！</center>";  
				echo"<br><center><a href='javascript:history.go(-1)'>请稍后继续参加。</a></center>";
				exit;
			}
		}
	}
	
	
?>
<!DOCTYPE html>
<?php include_once PATH."/www/plug/conf.php" ;//加载配置?>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title><?php echo $title; ?></title>
  
  <style type="text/css">
	  	/** 重置大多数选择器  */
	* {
		margin: 0;
		padding:0;
		-webkit-box-sizing: border-box;
		-moz-box-sizing: border-box;
		box-sizing: border-box;
	}
	h1,h2,h3,h4,h5,h6,p { margin: 0px 0px;}
	ol,ul,dl { list-style-position: inside;}
	html,body{
		width :100%;
		height: 100%;
		margin: 0;
		padding: 0;
		font: 1em "微软雅黑", verdana, helvetica, arial, sans-serif;
		color 			: #444;
		background-color: #fff;
	}
	a { text-decoration: none;}
		a:link, a:visited {color : inherit;}
		a:hover { text-decoration: underline;}
	strong {
		font-weight: 800;
		color      : #000;
	}
	/*清除浮动*/
	.clear-x-float {
	height 		: 0 		!important;
	float 		: none 		!important;
	visibility 	: hidden 	!important;
	clear 		: both 		!important;
	}
	
	#container {
		position : absolute;
		top 	 : 0px;
		left 	 : 0px;
		bottom 	 : 0px;
		right  	 : 0px;
		
		min-height : 320px;
		min-width  : 180px;
		max-width: 1000px;
		overflow   : auto;
		height 		: 100%;
		background-color : #fff;
		border-radius 	 : 0px 0px 0px 0px;  
	}

  	
  	header,section {
  		position: relative;
  		top 	: 0px;
  		margin 	: 0 auto;
  		width 	: 100%;
  	}
  	ul,ol,li{
  		list-style: none;
  	}
  	.web-header{
  		background-color: <?php echo $head_color; ?> ;
  		width 	: 100%;
  	}
  	img.bj{
  		position: relative;
  		top: -2px;
  		border: 0;
  		width: 100%;
  		display: block;
  	}
	
  	.max-x-width{
  		width : 100%;
  	}
	 </style>
	 <style type="text/css">
	 	/*表单样式*/
	 	form{
	 		color: #F44336;
	 		font-size: 1em;
	 		width: 100%;
	 	}
	 	p{
	 		margin: 3em auto;
	 		width: 80%;
	 		text-align: center;
	 	}
	 	label{
	 		font-size: 3em;
	 		text-align: left;
	 		margin: 0.3em 0;
	 		display: block;
	 	}
	 	input{
	 		font-size: 3em;
	 		height: 1.5em;
	 		width: 100%;
	 	}
	 	#inputImg img{
	 		display: none;
	 	}
	 	#describe{
	 		font-size: 3em;
	 		width: 100%;
	 		/*height: 30em;*/
	 	}
	 	#sure,#cancel{
		 background-color: rgba(255, 204, 154, 0.96);
	    /* padding: 1.1em 0; */
	    color: #fff;
	    line-height: 1em;
	    text-align: center;
	    margin: 0.2em 0;
	    font-size: 3.4em;
	    border-radius: 0.4em;
	 	}
	 </style>
	 </head>
	<body>
	
	<div id="container">
	

  <header >
  	<div class="web-header">
  		<img class="bj max-x-width" src= <?php  echo $header_imgA ?> alt="" />
	  	<div class="web-box max-x-width">	
	  		<form name="joinForm" method="post" action="join.php" onSubmit="return " enctype="multipart/form-data">  
				<p>  
					<label for="name" class="label">姓名:</label>  
					<input id="name" name="name" type="text" class="input"placeholder="请填写您的姓名" />  
				</p>  
				<p>  
					<label for="openid" class="label">openid:</label> 
					<input id="openid" name="openid"  placeholder="关注公众号并回复'ID'获取" ></textarea>   
				</p> 
				<p>  
					<label for="describe" class="label">描述:</label> 
					<textarea id="describe" name="describe" placeholder="请填写您详细描述" rows="3" cols="15" maxlength="30"></textarea>   
				</p> 
				 <p id="inputImg">  
					<label for="img" class="label">上传照片:</label>  
					<input id="img" name="img" type="file" class="input" accept="image/*" value=""/>  
					<img src="" alt="" />
				</p>
				<p>  
					<input type="submit" name="submit" value="确 定  " id="sure" /> 
					<input type="button" id="cancel" value="取消" onclick="history.go(-1)" /> 
				</p>  
			</form>  
	  	</div>
	  	<img class="bj max-x-width" src="<?php echo $header_imgB ?>"/>
	  </div>		
	</header>
	</div>
	<script>
		var inputImg = document.getElementById("img");
		if(typeof FileReader ==='undefined')
		{
			inputImg.setAttribute('disabled','disabled');
		}else{
			ininputImg.addEventListener('change',readFile,false);
		}
		function readFile(){
			var file = this.files[0];
			if(!/image\/\w+/.test(file.type)){
				alert("文件必须为图片");
				return false;
			}
			var reader = new FileReader,
				pv = document.getElementById('inputImg'); 
			reader.readAsDataURL(file);
			//当文件读取成功则可以调取上传接口
			reader.onload = function(e){
				var img = document.querySelector('img');
				
	            img.style.width = '100%';
	            img.style.height = '100%';
	            img.style.display = 'block';
	            img.src = e.target.result;
	            pv.appendChild(img);
			}
		}
		
	</script>
</body>
</html>