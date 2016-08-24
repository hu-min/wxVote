<?php
	/*
	 * 显示配置数据
	 */	
	include('./config.php');
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
			.Contentbox{
				width: 60%;
				margin: 0 auto;	
			}
		</style>
	</head>
	<body>
		<div class="Contentbox">
			<h6>活动配置成功！</h6>
			<p>
				活动开始时间：
				<?php echo $start; ?>
			</p>
			<p>
				活动结束时间：
				<?php echo $end; ?>
			</p>
			<p>
				是否需要关注后才能投票：
				<?php if($voteSub!=0){echo '需要';}else{echo '不需要';}?>
			</p>
			<p>
				每人每天能投几票：
				<?php echo $voteNum.'票';?>
			</p>
			<p>
				能否重复投同一个人：
				
				<?php if($voteC==0){echo '能';}else{echo '不能';}?>
			</p>
			<p>
				投票间隔：
				
				<?php if($voteTime!=0){echo $voteTime.'秒';}else{echo 0;} ?>
			</p>
			<p>
				投票范围限定在：
				<br />
				<?php if(isset($voteCity)){echo $voteCity;}else{echo '不限城市';}?>
					<br />
				<?php if(isset($voteProvince)){echo $voteProvince;}else{echo '不限省份';}?>
			</p>

		</div>
		
	</body>
</html>