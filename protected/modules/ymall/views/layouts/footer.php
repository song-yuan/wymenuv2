<?php
if(isset($_GET['wuyimenusysosyoyhmac']))
{
	$_SESSION['smac']=$_GET['wuyimenusysosyoyhmac'];
}
?>
<!DOCTYPE html>
<html>

	<head>
		<meta charset="utf-8">
		<title>壹点吃商城系统</title>
		<meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-status-bar-style" content="black">

		<link rel="stylesheet" href="<?php echo  Yii::app()->request->baseUrl; ?>/css/ymall/mui.min.css">
		<link rel="stylesheet" type="text/css" href="<?php echo  Yii::app()->request->baseUrl; ?>/css/ymall/feedback-page.css" />
	</head>
	<body>
	<?php echo $content; ?>

	<nav class="mui-bar mui-bar-tab">
			<a class="mui-tab-item mui-active" href="#tabbar">
				<span class="mui-icon mui-icon-home"></span>
				<span class="mui-tab-label">首页</span>
			</a>
			<a class="mui-tab-item" href="#tabbar-with-chat">
				<span class="mui-icon mui-icon-list"></span>
				<span class="mui-tab-label">分类</span>
			</a>
			<a class="mui-tab-item" href="<?php echo $this->createUrl('ymallcart/index',array('companyId'=>$this->companyId));?>">
				<span class="mui-icon iconfont icon-cart"><span class="mui-badge">9</span></span>
				<span class="mui-tab-label">购物车</span>
			</a>
			<a class="mui-tab-item" href="#tabbar-with-map">
				<span class="mui-icon mui-icon-contact"></span>
				<span class="mui-tab-label">我的</span>
			</a>
	</nav>
	</body>
	<script src="<?php echo  Yii::app()->request->baseUrl; ?>/js/ymall/mui.min.js "></script>
	<script src="<?php echo  Yii::app()->request->baseUrl; ?>/js/ymall/mui.view.js "></script>
	<script src="<?php echo  Yii::app()->request->baseUrl; ?>/js/ymall/app.js"></script>

</html>