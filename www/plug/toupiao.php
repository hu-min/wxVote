<?php 
	if(!defined("PATH")){ define("PATH",dirname(dirname(dirname(__FILE__))));}
	require_once (PATH."/lib/jssdk.php");
	include_once(PATH."/lib/conn.php");
	$jssdk = new JSSDK();
	$signPackage = $jssdk->GetSignPackage();
	
	$sql = "INSERT INTO `statistics`(name,pv) VALUES ('toupiao',1) 
		ON DUPLICATE KEY UPDATE pv=pv+1";
	if(!$conn->query($sql)){
		file_put_contents(PATH.'/log/mySQL.txt', "插入数据错误，www/plug/toupiao.php;第11行".$conn->error);
	}
	
?>
<!DOCTYPE html>
<?php 
	include_once PATH."/www/plug/conf.php" ;//加载页面配置
	include_once PATH."/www/plug/config.php" ;//加载活动配置	
	include_once PATH."/www/plug/getNum.php";
	
	
	//如果没到开始时间则跳转到活动宣传页面
	//如果超过结束时间则跳转3到活动结果页面
	$now = date("y-m-d H:i:s");
		 if($end&&strtotime($end)<strtotime($now)){
		 	echo "<a style='display: block;
    z-index: 555;
    color:#ff756b;
    background-color: #ddd;
    font-size: 3em;
    position: absolute;
    top:0px;
    width: 100%;
    text-align: center;'>活动已结束，请下次再来！</a>";
		 }
	
?>

	
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title><?php echo $title; ?></title>
  <link rel="stylesheet" type="text/css" href=<?php echo $tab_css_url; ?> />
  
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
		height 		: 91%;
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
  		/*top: -2px;*/
  		border: 0;
  		width: 100%;
  		display: block;
  	}
  	.web-box,.web-box-ul{
  		line-height : 2.5em;
  	}
  	.web-box-ul li{
  		margin-top: 2em;
	    width: 33%;
	    float: left;
	    border-right: 1px solid #dea91a;
  	}
  	li span.text{
  		font-size: 2.5em;
  	}
  	.web-header li span {
  		
  		font-size: 2.5em;
  		font-family: arial,\5b8b\4f53,Microsoft YaHei;
  		padding: 2px 0;
	    width: 100%;
	    display: block;
	    text-align: center;
	    color: #ec6941;
    }
  	.max-x-width{
  		width : 100%;
  	}
  	.search {
  		padding: 2em;
  		/*position  : relative;
  		top 	  : 10px;*/
	    background: #ffdccb;
	}
	.search form{
		
		padding : 2em 0;
		height  : 7em;
		width 	: 75%;
		padding : 10px;
		margin 	: 0 auto;
	}
	.search .input-box{
		background-color: white;
		text-align: center;
		border 	: 0px;
		border-radius: 1em 0 0 1em;
		float 	: left;
		height  :100%;
		width 	: 80%;
	}
	.search .but-box{
		background-color: #71C8CB;
		text-align: center;
		float: left;
		height 	: 100%;
		border 	: 0;
	/*	border-radius: 0 1em 1em 0;*/
		width   : 20%
	}
	.form-box input{
		margin: auto;
		border : 0;
		font-size: 3em;
		width: 90%;
		height: 100%;
		display: inline;
	}
	#searchBtn{
		color: white;
		background-color: #71C8CB;
	}
	.form-box{
		background-color: #71C8CB;
		height: 100%;
		border: 0.2em solid #71c8cb;
		border-radius: 1em;
		
	}
  </style>
  <style type="text/css"> 

  
  a:link {
  	color: #F9493C;
  	text-decoration: none;
  }
  
 a,a:visited {
  	/*color: #fff;*/
  	text-decoration: none;
  }
  
  a:hover {
  	/*color: #fff;*/
  	text-decoration: none;
  }
  
  ul {
  	list-style: none;
  }
  /*选项卡*/
  
  #Tab1 {
  	background-color: #FFDCCB;
  	width: 100%;
  	margin: 0px;
  	padding: 0px;
  /*	margin: 0 auto;*/
  }
  /*菜单class*/
  
  .Menubox {
  	width: 100%;
  	height: 2em;
  	/*line-height: 28px;*/
  }
  
  .Menubox ul {
  	margin: 0px;
  	padding: 0px;
  }
  .Menubox .li-box{
  	padding: 4em 0;
  	float: left;
  	width: 33%;
  }
  .Menubox li {
  	margin: 0 auto;
  	display: block;
  	cursor: pointer;
  	width: 80%;
  	padding: 0.6em;
  	text-align: center;
  	background : #FF756B;
  	color: white;
  	font-weight: bold;
  	font-size: 2.5em;
  	font-family: arial,\5b8b\4f53,Microsoft YaHei;;
  }
  
  .Menubox li.hover {
  	width: 85%;
  	background: #F9493C;
  	border-left: 1px solid #A8C29F;
  	border-top: 1px solid #A8C29F;
  	border-right: 1px solid #A8C29F;
  	font-weight: bold;
  	/*height: 27px;*/
  	/*line-height: 27px;*/
  }
  
  .Contentbox {
  	clear: both;
  	margin-top: 0px;
  /*	border: 1px solid #A8C29F;*/
  	border-top: none;
  	text-align: center;
  	padding-top: 8px;
  }
  .Contentbox h3{
  	color: #FFE89A;
  	font-size: 3em;
  }
   </style>
   		<style>
			/*TOP300样式*/
			.rank300-box{
				width: 95%;
				margin: 0 auto;
			}
			.rank300-box ul{
				list-style: none;
			}
			.rank300-box .rank-head{
				font-size: 2em;
				padding: 1em 0;
			}
			.rank300-box .rank-head span{
				display: inline-block;
			    width: 20%;
			    text-align: center;
				color: #EC6941;
			}
			.rank300-box li {
				border-bottom: 1px solid #EC6941;
				padding: 0.6em 0;
			}
			
			.list span{
				font-size: 2.5em;
  				font-family: arial,\5b8b\4f53,Microsoft YaHei;
				display: inline-block;
			    width: 20%;
			    text-align: center;
			}
			
			.join-box{
				font-size: 3em;
				width: 100%;
				padding: 5em 0;
			}
			.join-box a{
				color: #FF756B;
				font-size: 1.3em;
				padding: 0.2em 0;
				width: 65%;
				border-radius: 1em;
				display: block;
				text-align: center;
				margin: 0 auto;
				background-color: white;
			}
			
	/*底部内容样式*/
	
	.box{
		background-color: #feccb3;
		width: 100%;
		font-size: 2em;
		color: #f32343;
	}
	.box .title{
		background-color: #ff756b;
		border-radius:0em 0.2em 0.2em 0;
	}
	.box-title{
	width: 40%;
    padding: 0.2em 0;
    text-align: center;
    font-size: 1.2em;
    color: white;
    background: #ff756b;
    border-radius: 0 1em 1em 0;
	}
	.conter{
		margin: 0 auto;
		width : 92%;
	}
	.conter p{
		margin: 1em 0;
		font-size: 1.2em;
	}
		</style>
		
</head>
<body>
	
<div id="container">
	

  <header >
  	<div class="web-header">
  		<img class="bj max-x-width" src= <?php  echo $header_imgA ?> alt="" />
	  	<div class="web-box max-x-width">	
	  		<ul class="web-box-ul max-x-width">
	  		
	  			<li>
	  				<span class="text"><?php echo $act_a; ?></span>
	  				<span ><?php echo $act_a_value ?></span>
	  			</li>
	  		
	  			<li>
	  				<span class="text"><?php echo $act_b ?></span>
	  				<span><?php echo $act_b_value ?></span>
	  			</li>		
	  			
	  			<li>
	  				<span class="text"><?php echo $act_c ?></span>
	  				<span><?php echo $act_c_value ?></span>
	  			</li>
	  		
	  			
	  		</ul>
	  	</div>	
	  	
  		<img class="bj max-x-width" src="<?php echo $header_imgB ?>"/>
  		<div class="search max-x-width">
  			<form action="getdata.php" method="post" onsubmit="return onSearch();">
  				<div class="form-box max-x-width">
  					<div class="input-box">
  						<input type="search" id="search" value name="keyword" autocomplete="off" placeholder=<?php echo $keyword ?> />
  					</div>
  					<div class="but-box">
  						<input type="submit" value="搜索" name="searchid" id="searchBtn"/>
  					</div>
	  				<div class="clear-x-float"></div>
  				</div>
  				
  			</form>
  		</div>
  	</div>
  </header>
  <!--活动主体-->
  <section>
  	<div id="Tab1">
			<div class="Menubox">
				<ul>
					<div class="li-box">
						<li id="one1" onclick="setTab('one',1,4,getData)" class="hover">
						<?php echo $tab_A; ?>
						</li>
					</div>
					<div class="li-box">
						<li id="one2" onclick="setTab('one',2,4,getData)" >
						<?php echo $tab_B; ?>
						</li>
					</div>
					<div class="li-box">
						<li id="one3" onclick="setTab('one',3,4,getData)">
						<?php echo $tab_C; ?>
						</li>
					</div>
				</ul>
			</div>
			<div class="Contentbox">
				<div id="con_one_1" class="hover">
					<h3>加载数据中，请稍后。。。</h3>
				</div>
				<div id="con_one_2" style="display:none">
					<h3>加载数据中，请稍后。。。</h3>
				</div>
				<div id="con_one_3" style="display:none">
					<h3>加载数据中，请稍后。。。</h3>
				</div>
				<div id="con_one_4" style="display:none">
					<h3>加载数据中，请稍后。。。</h3>
				</div>
				<div id="con_one_5" style="display:none">
					<h3>加载数据中，请稍后。。。</h3>
				</div>
			</div>
			<div class="join-box">
				<a href=<?php echo $path."/join.php" ?> >我也要参加</a>
			</div>
			<img class="bj" src=<?php echo $tab_bottom_img; ?> />
	</div>
 	
  </section>
  <section class="box">
  	<div class="box-title" >--活动奖品--</div>
  	<div class="conter">
  		<p>活动时间：昨天到明天！</p>
  		<img class="max-x-width" src="<?php echo $imgurl."e583597594215d4c.jpg"; ?>" alt="" />
  	</div>
  	
  </section>
  
</div>

<!--底部导航栏-->
  <div class="web-bottom">
  	<ul class="nav-box">
  		<li>
  			<a href="javascript:setTab('one',1,4,setContent)">
  				<img src="<?php echo $imgurl; ?>i1.png" />
  				<span >首页</span>
  			</a>
  		</li>
  		<li>
  			<a href="javascript:setTab('one',2,4,getData)">
  				<img src="<?php echo $imgurl; ?>i3.png" />
  				<span >排名</span>
  			</a>
  		</li>
  		<li>
  			<a href="">
  				<img src="<?php echo $imgurl; ?>i4.png" />
  				<span >攻略</span>
  			</a>
  		</li>
  	</ul>
  </div>
  

 <script>/*更换显示样式*/
 	var tab_index = 1 ,
 	 	one = 'one',
 	 	wxUrl =  <?php echo $wxUrl; ?>'',
 	 	list_length = <?php echo $list_len; ?>||10,          //列表长度
		page_length = <?php echo $page_len; ?>||4,			//索引页长度
		rank_length = <?php echo $rank_len; ?>||15,
 		tab_A_data=[],			//按时间排序的数据
 		tab_B_data=[],			//按票数排序的数据
 		tab_new_data=[],
 		tab_elm_A = document.getElementById("con_one_1"),
 		tab_elm_B = document.getElementById("con_one_2"),
 		tab_elm_C = document.getElementById("con_one_3"),
 		tab_elm_D = document.getElementById("con_one_4"),
 		tab_elm_E = document.getElementById("con_one_4");
 	//搜索索引，第几页，按票数还是参与时间获取数据
	function getData(index,page,type){
		if(index)
		{
			ajax({
				url  : "<?php echo $tab_url; ?>",
				type : "GET",
				data : {type:'act','do':'index',index:index},
				datatype:"json",
				success : function(res , xml){
					res = eval('('+res+')');
					tab_new_data = res;
					console.log(res);
					var elm = document.getElementById("con_one_5");
					setContent(5,elm,1);
				}
			});
			return true;
		}
		if(page){
			var myLength = type=='toppage'? rank_length:list_length;
			ajax({
				url 	: "<?php echo $tab_url; ?>",
				type	: "GET",
				data 	: {type:"act",'do':type,page:page,length:myLength},
				datatype:'json',
				success : function(res , xml){
					if(type=='pollpage')
					{
						res = eval('('+res+')');
						tab_B_data = res;
						var elm = document.getElementById("con_one_2");
						setContent(2,elm,page);
						
					}else if(type=='timepage'){
						res = eval('('+res+')');
						tab_A_data = res;
						var elm = document.getElementById("con_one_1");
						setContent(1,elm,page);
					}else if(type=='toppage'){
						res = eval('('+res+')');
						tab_new_data = res;
						var elm = document.getElementById("con_one_3");
						setContent(3,elm,page);
						console.log("tabnew:"+tab_A_data);
					}
				},
				fail	: function(status){
				//失败回调函数
				console.log("获取数据失败！");
			}
			});
			return true;
		}
		
		ajax({
			url		: "<?php echo $tab_url; ?>",
			type	: "GET",
			data	: {type:"act",'do':"all"},
			datatype: "json",
			success : function(res , xml){
				//获取成功的回调函数
			//	res = res.replace(/[\\]/g,'');
			//	res = res.replace(/[\[]/g, '"{"');
			//	res = res.replace(/[\]]/g, '"}"');
			//	res = eval("("+res+")" );
			//	res = res.parseJSON();
				//对数据排序
				res = eval('('+res+')');
				tab_A_data = tab_A_sort( res );
				tab_B_data = tab_B_sort( res );
				
				var elm = document.getElementById("con_one_1");
				setContent(1,elm,1);
			},
			fail	: function(status){
				//失败回调函数
				console.log("获取数据失败！");
			}
			
		});
	}
	function onSearch(){
		var search = document.getElementById('search');
		if(search.value){
			getData(search.value); 
		}
		return false;
	}
	//切换主体内容
	function setTab(name,cursel,n,callback){
		var type;
		switch(cursel){
			case 1:
				type = 'timepage';
				break;
			case 2:
				type = 'pollpage';
				break;
			case 3:
				type = 'toppage';
				break;
		}
		for(var i=1;i<=n;i++){
			var menu=document.getElementById(name+i);
			var con=document.getElementById("con_"+name+"_"+i);
			
			if(!!menu)menu.className=i==cursel?"hover":"";
			con.style.display=i==cursel?"block":"none";
			i==cursel ? callback&&callback( 0,1,type):"";
			//如果当前页面不是4，则刷新当前页面索引
			if(cursel != 4||cursel!=5){
				tab_index = cursel;
			}
		}
	}
	//d为1则打开投票页面，0关闭投票页面;id为需要投票的ID
	function toupiao(d, id){
		var elm = document.getElementById('toupiao');
		if(d){
			if(!!elm)
			{
				shade(1);
				elm.innerHTML = '<a class="close" href="javascript:toupiao(0);">X</a>'
							+	'<p>进入公众号,回复"TP"'+id+'给我投票哦！</p>'
							+	'<a class="inwx" href="'+wxUrl+'" >进入我们的公众号</a>'
							+	'<img style="margin: 0 auto; display:block;" src="./img/0.jpg">'
							+	'<p>这是测试号，请扫描二维码关注公众号</p>';
				elm.style.display = "block";	
			}else{
				shade(1);
				var div = document.createElement('div');
				div.id = "toupiao";
				div.innerHTML = '<a class="close" href="javascript:toupiao(0);">X</a>'
							+	'<p>进入公众号回复"TP"'+id+'给我投票哦！</p>'
							+	'<a class="inwx" href="'+wxUrl+'" >进入我们的公众号</a>'
							+	'<img style="margin:0 auto;  display:block;" src="./img/0.jpg">'
							+	'<p>这是测试号，请扫描二维码关注公众号</p>';
				div.style.display = "block";
				document.body.appendChild(div);	
			}
		}else{
			elm.style.display = "none";
			shade(0);
		}
	}
	function display(d){
		//控制详情页显示1显示0隐藏
		tab_elm_D.style.display = d==1?"block":"none";
	}
	//打开分享引导页或关闭分享引导页
	function fenxiang(d){
		var elm = document.getElementById('fenxiang');
		if(d){
			if(!!elm)
			{
				shade(1);
				
				elm.innerHTML = '<span></span>'
							+	'<p>请点击右上角"•••"<br />通过【发送给朋友】<br />选择想邀请的好友吧！</p>';
				elm.style.display = "block";	
				elm.addEventListener('click',function(e){fenxiang(0);});
			}else{
				shade(1)
				var div = document.createElement('div');
				
				div.id = "fenxiang";
				div.innerHTML = '<span"></span>'
							+	'<p>请点击右上角"•••"<br />通过【发送给朋友】<br />选择想邀请的好友吧！</p>';
				div.style.display = "block";
				document.body.appendChild(div);	
				div.onclick = function(e){fenxiang(0);};
			}
		}else{
			elm.style.display = "none";
			shade(0);
		}
	}
	//显示遮罩 1打开0关闭
	function shade(d){
		var elm = document.getElementById('shade');
		if(!!elm)
		{
			console.log(elm);
			elm.style.display = d==1? "block":"none";
		}else{
			var div = document.createElement('div');
			div.id = "shade";
			document.body.appendChild(div);	
			div.style.display = d ==1? "block":"none";	
		}
		
	}
	/*添加内容 */
	function setContent( cursel , elm , page , id){
		var pageIndex, index, container, 
			clear = '<div class="clear-x-float"></div>',
			direction = 0;
				
			page = page? page:1;
		switch ( cursel ){
			case 1:
			var
				left = '<div class="tab-box-left">',
				right= '<div class="tab-box-right">',
				tab_page = '<ul class="tab-page"> ',
				container = String()  
						+ '<div class="tab-box-user">'
					 		+ '<ul class="tab-ul">';
				
				for (index = 0; index  < tab_A_data['data'].length; ++index) {
					if(direction == 0){
						left += '<li class="tab-li">' 
							+ '<div class="tab-li-box">'
							+	'<i class="nomber">'
							+		tab_A_data['data'][index].id		
							+	'</i>'
							+	'<a href="javascript:getData('+tab_A_data['data'][index].id+');" class="img">'
							+		'<img src="'+ tab_A_data['data'][index].img_url+'"/>'
							+	'</a>'
							+	'<div class="clearfix">'
							+		'<p>'
							+			tab_A_data['data'][index].name 
							+			 '<br />'
							+			tab_A_data['data'][index].poll + '票'
							+		'</p>'
							+		'<a href="javascript:toupiao(1,'+ tab_A_data['data'][index].id+');" class="vote">投票</a>'
							+	'</div>'
							+ '</div>'
						+ '</li>';
					}else{
						right += '<li class="tab-li">' 
							+ '<div class="tab-li-box">'
							+	'<i class="nomber">'
							+		tab_A_data['data'][index].id		
							+	'</i>'
							+	'<a href="javascript:getData('+tab_A_data['data'][index].id+');" class="img">'
							+		'<img src="'+ tab_A_data['data'][index].img_url+'"/>'
							+	'</a>'
							+	'<div class="clearfix">'
							+		'<p>'
							+			tab_A_data['data'][index].name 
							+			 '<br />'
							+			tab_A_data['data'][index].poll +'票'
							+		'</p>'
							+		'<a href="javascript:toupiao(1,'+ tab_A_data['data'][index].id+');" class="vote">投票</a>'
							+	'</div>'
							+ '</div>'
						+ '</li>';
					}	
						direction = direction==0? 1:0;
					//	console.log(tab_A_data)
				}
				//获取最后一页的索引
				pageIndex = Math.ceil( tab_A_data['length']/list_length );//向上取整
			//	pageIndex = (tab_A_data.length%list_length > 0)? ++pageIndex : pageIndex;
				//如果当前页面大于1则渲染跳转第一页和上一页按钮
				var type = 'timepage';
				if(page > 1) 
				{
					tab_page += '<li><a href="javascript:getData(0,1,'+type+') ;">&lt;&lt;</a></li>'
								+ '<li><a href="javascript:getData(0,'+(page-1)+','+type+' );">&lt;</a></li>';
				}
				var len = (page + page_length) > pageIndex ? pageIndex : page + page_length;
				
					index = (page - page_length)>1?page-page_length:1;
				for (index; index <= len; index++) {
					if(page == index)
					{
						tab_page += '<li class="act">'
									+ '<a href="javascript:;">'+index+'</a>'
									+'</li>';
					}else{
						tab_page += '<li>'
									+ '<a href="javascript:getData(0,'+ index + ','+type+') ;">'+index+'</a>'
									+'</li>';
					}
				}
				//如果当前页面不是最后一页则渲染下一页和最后一页按钮
				if(page < pageIndex) 
				{
					tab_page += '<li><a href="javascript:getData(0,'+ (page+1) +','+type+') ;">&gt;</a></li>'
								+ '<li><a href="javascript:getData(0,'+ pageIndex + ','+type+' );">&gt;&gt;</a></li>';
				}
				right +='</div>'
				left +='</div>';
				tab_page += '</ul>'
				container += left+right+clear+'</ul>'+tab_page+'</div>';
				break;
	//---------------------排行列表----------------------------	
			case 2:
				var
				left = '<div class="tab-box-left">',
				right= '<div class="tab-box-right">',
				tab_page = '<ul class="tab-page"> ',
				container = String()  
						+ '<div class="tab-box-user">'
					 		+ '<ul class="tab-ul">';
					 		
				for (index = 0; index  < tab_B_data['data'].length; ++index) {
					if(direction == 0){
						left += '<li class="tab-li">' 
							+ '<div class="tab-li-box">'
							+	'<i class="nomber">'
							+		tab_B_data['data'][index].id		
							+	'</i>'
							+	'<a href="javascript:getData('+tab_B_data['data'][index].id+');" class="img">'
							+		'<img src="'+ tab_B_data['data'][index].img_url+'"/>'
							+	'</a>'
							+	'<div class="clearfix">'
							+		'<p>'
							+			tab_B_data['data'][index].name 
							+			 '<br />'
							+			tab_B_data['data'][index].poll + '票'
							+		'</p>'
							+		'<a href="javascript:toupiao(1,'+ tab_B_data['data'][index].id+');" class="vote">投票</a>'
							+	'</div>'
							+ '</div>'
						+ '</li>';
					}else{
						right += '<li class="tab-li">' 
							+ '<div class="tab-li-box">'
							+	'<i class="nomber">'
							+		tab_B_data['data'][index].id		
							+	'</i>'
							+	'<a href="javascript:getData('+tab_B_data['data'][index].id+');" class="img">'
							+		'<img src="'+ tab_B_data['data'][index].img_url+'"/>'
							+	'</a>'
							+	'<div class="clearfix">'
							+		'<p>'
							+			tab_B_data['data'][index].name 
							+			 '<br />'
							+			tab_B_data['data'][index].poll + '票'
							+		'</p>'
							+		'<a href="javascript:toupiao(1,'+ tab_B_data['data'][index].id+');" class="vote">投票</a>'
							+	'</div>'
							+ '</div>'
						+ '</li>';
					}	
						direction = direction==0? 1:0;
					//	console.log(tab_B_data)
				}
				//获取最后一页的索引
				var type = 'pollpage';
				pageIndex = Math.ceil( tab_B_data['length']/list_length );//向上取整
			//	pageIndex = (tab_B_data.length%list_length > 0)? ++pageIndex : pageIndex;
				//如果当前页面大于1则渲染跳转第一页和上一页按钮
				if(page > 1) 
				{
					tab_page += '<li><a href="javascript:getData(0,1,'+type+')  ;">&lt;&lt;</a></li>'
								+ '<li><a href="javascript:getData(0,'+(page-1)+','+type+' );">&lt;</a></li>';
				}
				var len = (page + page_length) > pageIndex ? pageIndex : page + page_length;
				
					index = (page - page_length)>1?page-page_length:1;
				for (index; index <= len; index++) {
					if(page == index)
					{
						tab_page += '<li class="act">'
									+ '<a href="javascript:;">'+index+'</a>'
									+'</li>';
					}else{
						tab_page += '<li>'
									+ '<a href="javascript:getData(0,'+index+','+type+' );">'+index+'</a>'
									+'</li>';
					}
				}
				//如果当前页面不是最后一页则渲染下一页和最后一页按钮
				if(page < pageIndex) 
				{
					tab_page += '<li><a href="javascript:getData(0,'+(page+1)+','+type+' ) ;">&gt;</a></li>'
								+ '<li><a href="javascript:getData(0,'+pageIndex+','+type+' );">&gt;&gt;</a></li>';
				}
				right +='</div>'
				left +='</div>';
				tab_page += '</ul>'
				container += left+right+clear+'</ul>'+tab_page+'</div>';
				break;
		//-------------------------第三页，排行列表	
			case 3:
				var list = '',
					tab_page = '<ul class="tab-page">';
				
				container = String()
						+'<div class="rank300-box ">'
						+ '<ul>'
						+	'<li class="rank-head">'
						+		'<span>排名</span>'
						+		'<span>编号</span>'
						+		'<span style="width: 40%;" >姓名</span>'
						+		'<span style="color: #FF756B;">票数</span>'
						+	'</li>';
				
				
				for(index = 0; index < tab_new_data['data'].length; index++){
					list += '<li class="list">'
							+	'<span>'+(index+1)+'</span>'
							+	'<span>'+ tab_new_data['data'][index].id +'</span>'
							+	'<span style="width: 40%;">'+ tab_new_data['data'][index].name +'</span>'
							+	'<span style="color: #FF756B;">'+tab_new_data['data'][index].poll+'</span>'
							+'</li>'
				}
				
				//获取最后一页的索引
				pageIndex = Math.ceil( tab_new_data['length']/list_length );//向上取整
			//	pageIndex = (tab_B_data.length%list_length > 0)? ++pageIndex : pageIndex;
				//如果当前页面大于1则渲染跳转第一页和上一页按钮
//				if(page > 1) 
//				{
//					tab_page += '<li><a href="javascript:setContent(1,tab_elm_A,1) ;">&lt;&lt;</a></li>'
//								+ '<li><a href="javascript:setContent(1,tab_elm_A,'+ (page-1) + ' );">&lt;</a></li>';
//				}
//				var len = (page + page_length) > pageIndex ? pageIndex : page + page_length;
//				console.log('索引长度'+len);
//					index = (page - rank_length)>1?page-rank_length:1;
				for (index=1; index < pageIndex; index++) {
					if(page == index)
					{
						tab_page += '<li class="act">'
									+ '<a href="javascript:;">'+(index-1)*rank_length+'-'+(page*rank_length)+'</a>'
									+'</li>';
					}else{
						tab_page += '<li>'
									+ '<a href="javascript:setContent(3,tab_elm_C,'+ index + ') ;">'+(index-1)*rank_length+'-'+(page*rank_length)+'</a>'
									+'</li>';
					}
				}
//				//如果当前页面不是最后一页则渲染下一页和最后一页按钮
//				if(page < pageIndex) 
//				{
//					tab_page += '<li><a href="javascript:setContent(1,tab_elm_A,'+ (page+1) +') ;">&gt;</a></li>'
//								+ '<li><a href="javascript:setContent(1,tab_elm_A,'+ pageIndex + ' );">&gt;&gt;</a></li>';
//				}
				tab_page += '</ul>'
				container += list + '</ul>'+ tab_page +'</div>';
				break;
		//-------------------详情页-------------------------------	
			case 4:
				pageIndex = tab_new_data.data;
				
				container = '<div class="tab-detail">'
						+		'<a href="javascript:setTab('+one+','+tab_index+',4);" class="close close-detail">X</a>'
						+		'<p class="num_box" style="padding:0;">'
						+			'<span class="number">'+pageIndex.id + '号&nbsp;'+pageIndex.name+'</span>'
						+			'<span>有效票：'+pageIndex.poll+'&nbsp;无效票：'+pageIndex.nopoll+'</span>'
						+		'</p>'
						+		'<p>'
						+			'<span>排名：'+tab_new_data.rank+'</span>'
						+		'</p>'
						+		'<p>描述：'+pageIndex.describe+'</p>'
						+		'<img class="detial-img" src='+ pageIndex.img_url+' alt="" />'
						+	'</div>'
						+	'<div class="a-but-box">'
						+		'<a class="a-but vote" href="javascript:toupiao(1,'+pageIndex.id+');">我要投票</a>'
						+		'<a class="a-but " href="javascript:setContent(2,tab_elm_D);">点击查看更多（没有更多了）</a>'
						+		'<a class="a-but" href="javascript:fenxiang(1);">点击分享到朋友圈</a>'
						+	'</div>';
				setTab('one',4,4);
				break;	
			case 5:
				if(tab_new_data.type =='num')
				{
					//如果是按编号搜索
					var data = tab_new_data.data;
					setContent(4,tab_elm_D,null,data.id);
				}else{
					if(tab_new_data.type =='list')
					{
						data = tab_B_sort(tab_new_data.data);
						
				var
				left = '<div class="tab-box-left">',
				right= '<div class="tab-box-right">',
				tab_page = '<ul class="tab-page"> ',
				container = String()  
						+ '<div class="tab-box-user">'
					 		+ '<ul class="tab-ul">';
					 		
				for (index = (page-1)*list_length; index < page * list_length && index < data.length; ++index) {
					if(direction == 0){
						left += '<li class="tab-li">' 
							+ '<div class="tab-li-box">'
							+	'<i class="nomber">'
							+		data[index].id		
							+	'</i>'
							+	'<a href="javascript:getData('+data[index].id+');" class="img">'
							+		'<img src="'+ data[index].img_url+'"/>'
							+	'</a>'
							+	'<div class="clearfix">'
							+		'<p>'
							+			data[index].name 
							+			 '<br />'
							+			data[index].poll + '票'
							+		'</p>'
							+		'<a href="javascript:toupiao(1,'+ data[index].id+');" class="vote">投票</a>'
							+	'</div>'
							+ '</div>'
						+ '</li>';
					}else{
						right += '<li class="tab-li">' 
							+ '<div class="tab-li-box">'
							+	'<i class="nomber">'
							+		data[index].id		
							+	'</i>'
							+	'<a href="javascript:getData('+data[index].id+');" class="img">'
							+		'<img src="'+ data[index].img_url+'"/>'
							+	'</a>'
							+	'<div class="clearfix">'
							+		'<p>'
							+			data[index].name 
							+			 '<br />'
							+			data[index].poll + '票'
							+		'</p>'
							+		'<a href="javascript:toupiao(1,'+ data[index].id+');" class="vote">投票</a>'
							+	'</div>'
							+ '</div>'
						+ '</li>';
					}	
						direction = direction==0? 1:0;
					//	console.log(tab_B_data)
				}
				//获取最后一页的索引
				pageIndex = Math.ceil( data.length/list_length );//向上取整
			//	pageIndex = (tab_B_data.length%list_length > 0)? ++pageIndex : pageIndex;
				//如果当前页面大于1则渲染跳转第一页和上一页按钮
				if(page > 1) 
				{
					tab_page += '<li><a href="javascript:setContent(5,tab_elm_E,1) ;">&lt;&lt;</a></li>'
								+ '<li><a href="javascript:setContent(5,tab_elm_E,'+ (page-1) + ' );">&lt;</a></li>';
				}
				var len = (page + page_length) > pageIndex ? pageIndex : page + page_length;
				
					index = (page - page_length)>1?page-page_length:1;
				for (index; index <= len; index++) {
					if(page == index)
					{
						tab_page += '<li class="act">'
									+ '<a href="javascript:;">'+index+'</a>'
									+'</li>';
					}else{
						tab_page += '<li>'
									+ '<a href="javascript:setContent(5,tab_elm_E,'+ index + ') ;">'+index+'</a>'
									+'</li>';
					}
				}
				//如果当前页面不是最后一页则渲染下一页和最后一页按钮
				if(page < pageIndex) 
				{
					tab_page += '<li><a href="javascript:setContent(5,tab_elm_E,'+ (page+1) +') ;">&gt;</a></li>'
								+ '<li><a href="javascript:setContent(5,tab_elm_E,'+ pageIndex + ' );">&gt;&gt;</a></li>';
				}
				right +='</div>'
				left +='</div>';
				tab_page += '</ul>'
				container += left+right+clear+'</ul>'+tab_page+'</div>';
				
				setTab('one',5,5);
					}
				}
				
				break;
			default:
				break;
		}
		
		elm.innerHTML = container;
	}
	window.onload = load;
	function load(){
		
		getData(0,1,'timepage');
	}
	//插入排序法对tab_A数据按时间进行降序排序
	function tab_A_sort (data){
		var temp, inner;
		for (var outer = 1; outer <= data.length - 1; ++outer) {
			temp = data[ outer ];			
			inner = outer;
			//如果前面的小于后面的则交换位置
			while( inner > 0 && (data[ inner - 1 ].time <= temp.time)){
				data[inner] = data[inner - 1];
				--inner;
			}
			data[inner] = temp;		
		}
		return data;
	}
	//按票数进行降序排序
	function tab_B_sort (data){
		var temp, inner;
		for (var outer = 1; outer <= data.length - 1; ++outer) {
			temp = data[ outer ];			
			inner = outer;
			//如果前面的小于后面的则交换位置
			while( inner > 0 && (data[ inner - 1 ].poll <= temp.poll)){
				data[inner] = data[inner - 1];
				--inner;
			}
			data[inner] = temp;		
		}
		return data;
	}
	
</script>

</body>
<script src="<?php echo $ajax_js; ?>" type="text/javascript"></script>
<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<script src="https://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<script>
  /*
   * 注意：
   * 1. 所有的JS接口只能在公众号绑定的域名下调用，公众号开发者需要先登录微信公众平台进入“公众号设置”的“功能设置”里填写“JS接口安全域名”。
   * 2. 如果发现在 Android 不能分享自定义内容，请到官网下载最新的包覆盖安装，Android 自定义分享接口需升级至 6.0.2.58 版本及以上。
   * 3. 常见问题及完整 JS-SDK 文档地址：http://mp.weixin.qq.com/wiki/7/aaa137b55fb2e0456bf8dd9148dd613f.html
   *
   * 开发中遇到问题详见文档“附录5-常见错误及解决办法”解决，如仍未能解决可通过以下渠道反馈：
   * 邮箱地址：weixin-open@qq.com
   * 邮件主题：【微信JS-SDK反馈】具体问题
   * 邮件内容说明：用简明的语言描述问题所在，并交代清楚遇到该问题的场景，可附上截屏图片，微信团队会尽快处理你的反馈。
   */
  wx.config({
    debug: false,
    appId: '<?php echo $signPackage["appId"];?>',
    timestamp: '<?php echo $signPackage["timestamp"];?>',
    nonceStr: '<?php echo $signPackage["nonceStr"];?>',
    signature: '<?php echo $signPackage["signature"];?>',
    jsApiList: [
      // 所有要调用的 API 都要加到这个列表中
        'onMenuShareTimeline',
        'onMenuShareAppMessage'
    ]
  });
  wx.ready(function () {
    // 在这里调用 API
    wx.error(function(res){
            console.log(res);
        });
        
        wx.onMenuShareTimeline({
            title: '投票活动（测试）',
            link: 'http://15385md623.51mypc.cn/plug/toupiao.php', 
            imgUrl: 'http://15385md623.51mypc.cn/plug/img/h.jpg', 
            success: function () {
                
            },
            cancel: function () {
                
            }
        });

        
        wx.onMenuShareAppMessage({
            title: '投票活动（测试）', 
            desc: '投票活动（测试），此活动仅供测试', 
            link: 'http://15385md623.51mypc.cn/plug/toupiao.php', 
            imgUrl: 'http://15385md623.51mypc.cn/plug/img/h.jpg', 
            type: '', 
            dataUrl: '', 
            success: function () {
               
            },
            cancel: function () {
                
            }
        });
  });
  </script>


</html>
