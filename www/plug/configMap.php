<?php
	/*
	 * 投票活动配置映射
	 * 模板
	 */	
	 if(!defined('PATH')){define('PATH',dirname(dirname(dirname(__FILE__))));}
	include_once(PATH.'/www/private/checkuser.php');
	if($_SERVER['REQUEST_METHOD']=="POST"){
	//	$wechatObj->responseMsg();
		if(!isset($_POST['submit']))
		{
			exit ('非法访问');
		}
		 $conf = "<?php 
			"." $"."host = isset("."$"."_SERVER['HTTP_X_FORWARDED_HOST']) ? "."$"."_SERVER['HTTP_X_FORWARDED_HOST'] : (isset("."$"."_SERVER['HTTP_HOST']) ? "."$"."_SERVER['HTTP_HOST'] : ''); "
			."$"."protocol = (!empty("."$"."_SERVER['HTTPS']) && "."$"."_SERVER['HTTPS'] !== 'off' || "."$"."_SERVER['SERVER_PORT'] == 443) ? 'https://' : 'http://';"
			
			."$"."path	= "."'/plug';"   //活动路径：/plug
			."$"."imgurl  = "."$"."protocol."."$"."host."."$"."path."."'/img/'".";" //图片文件夹路径
			."$"."jsurl	= "."$"."protocol."."$"."host."."$"."path."."'/js/'".";"   //js文件夹路径
			."$"."css_url= "."$"."protocol."."$"."host."."$"."path."."'/css/'".";" //css文件夹路径
			
			."$"."wxUrl	= "."'%s'".";"		//微信公众号的URL  *
			."$"."tab_css_url= "."$"."css_url."."'tab.css'".";" //tab的cssurl路径
			."$"."ajax_js = "."$"."jsurl."."'ajax.js'".";" //引用的jsurl
			."$"."tab_url =". "$"."protocol."."$"."host."."$"."path."."'/getdata.php'".";" //获取投票数据
			."$"."head_color  =". "'#ffe89a'".";"  //头部背景颜色	
			."$"."title	 =". " '投票活动'".";"       //活动标题		
			."$"."header_imgA = "."$"."imgurl."."'h.jpg'".";"//头部图片			
			."$"."header_imgB = "."$"."imgurl."."'mw_004.jpg'".";"//头部下边图片	
			."$"."tab_bottom_img= "."$"."imgurl."."'mw_005.jpg'".";" //主体底部图片 
			//获取头部数据
			."include('./getNum.php');"    //获取ABC栏的值
			."$"."act_a	=" ."'已报名'".";"		//信息栏A的名称  	
			."$"."act_b	=". "'投票人次'".";"		//信息栏B的名称	
			."$"."act_c	=". "'TOP300'".";"		//信息栏C的名称	
		 	
			."$"."keyword	= "."'搜名字或编号'".";" //搜索栏内容
			
			."$"."tab_A	=" ."'最新参赛'".";"		//		
			."$"."tab_B	=". "'投票排行'".";"		//		
			."$"."tab_C	= "."'TOP300'".";"		//		
			."$"."list_len =". "10".";" //配置列表长度
			."$"."page_len =". "4".";"	//配置索引页长度
			."$"."rank_len =". "15".";" //配置rank列表长度
			."?>";
		
		/*
		 * 活动配置文件
		 * 如活动开始时间/结束时间
		 * 每人每天能投多少票等
		 */
		$config = "<?php " 
		." $"."start =". "'%s'".";"	//活动开始时间          *
		."$"."end =". "'%s'".";"	//结束时间	 	*
		
		."$"."voteSub =" ."%d".";" 	//是否需要关注才能投票		*
		."$"."voteNum =". "%d".";" 		//每人每天只能投10票	*
		."$"."voteC ="." %d".";"		//是否限制对同一人重复投票		*
		."$"."voteTime = "."%d".";"		//是否限制投票间隔			*
		."$"."voteCity = "."'%s'".";"	//是否限制投票城市			*
		."$"."voteProvince =". "'%s'".";"//是否限制投票省份 			*	
		." ?>";
		
		$startMap 	= !empty($_POST['start'])?$_POST['start']:'2016-5-23 20:00:00';
		$endMap 	= !empty($_POST['end'])?$_POST['end']:'2016-7-25 20:00:00';
		$voteSubMap = isset($_POST['voteSub'])?$_POST['voteSub']:0;
		$voteNumMap = isset($_POST['voteNum'])?$_POST['voteNum']:1;
		$voteCMap	= isset($_POST['voteC'])?$_POST['voteC']:0;
		$voteTimeMap= isset($_POST['voteTime'])?$_POST['voteTime']:0;
		$voteCityMap= !empty($_POST['voteCity'])?$_POST['voteCity']:0;
		$voteProvinc= !empty($_POST['voteProvince'])?$_POST['voteProvince']:0;
		var_dump($startMap);
		$resStr =  sprintf($config,$startMap,$endMap,$voteSubMap,$voteNumMap,$voteCMap,$voteTimeMap,$voteCityMap,$voteProvinc);
		file_put_contents('./config.php', $resStr);
		//header("Location:/plug/showConfig.php");
	}else{
	//	header("Location:".PATH."/www/404.php");
	}
	 
	
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1, user-scalable=no">
		<title>活动配置</title>
		<script type="text/javascript">
			
		</script>
		<style type="text/css">
			html,body{
				padding: 0;
				margin: 0;
				position: absolute;
				top: 0px;
				left: 0px;
				right: 0px;
				bottom: 0px;
			}
			.Contentbox{
				margin: 0 auto;
				width: 50%;
			}
			.title{
				text-align: content;
				font-size: 2em;
				margin: 1em;
			}
			.config-form{
				position: relative;
				top:1em;
			}
			.config-form label,input{
				display: block;
				width: 100%;
				margin: 0.2em auto;
			}
			.config-form p{
				width: 100%;
				margin: 1em auto;
			}
			#submit{
				width: 30%;
				font-size: 1.4em;
			}
		</style>
	</head>
	<body>
		<div class="Contentbox">
			<h6 class="title">配置活动</h6>
			<form action="configMap.php" method="post" class="config-form">
				<p>
					<label for="start" class="label">设置活动开始时间（如:2016-3-15 20:30:00)</label>
					<input type="text" name="start" class="input" />
				</p>
				<p>
					<label for="end" class="label">设置活动结束时间(如:2016-4-15 20:30:00)</label>
					<input type="text" name="end" class="input" />
				</p>
				<p>
					<label for="voteSub" class="label">设置是否需要关注才能投票<br>(0:需要，1:不需要)</label>
					<input type="number" name="voteSub" class="input" value="" />
				</p>
				<p>
					<label for="voteNum" class="label">设置每人每天能投多少票<br>(一个大于0的数)</label>
					<input type="number" name="voteNum" class="input" />
				</p>
				<p>
					<label for="voteC" class="label">设置是否限制给同一人投票<br>(1:可以，0:不可以)</label>
					<input type="number" name="voteC" class="input" />
				</p>
				<p>
					<label for="voteTime" class="label">设置投票间隔<br>(0:不设置间隔，5=5秒,10=10秒)</label>
					<input type="number" name="voteTime" class="input" />
				</p
				<p>
					<label for="voteCity" class="label">设置允许投票的城市<br>(钦州：在钦州以外的地方投票都记为无效，如果不设置可以输入：0)</label>
					<input type="text" name="voteCity" class="input" />
				</p>
				<p>
					<label for="voteProvince" class="label">设置允许投票的省份<br>(广西：在广西以外的地方投票都记为无效，如果不设置可以输入：0。如果设置了城市则省份无效)</label>
					<input type="text" name="voteProvince" class="input" />
				</p>
				
				<input type="submit" name="submit" value="提交" id="submit"/>
			</form>
		</div>
		
	</body>
</html>