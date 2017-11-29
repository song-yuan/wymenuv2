<?php
if(isset($_GET['wuyimenusysosyoyhmac']))
{
	$_SESSION['smac']=$_GET['wuyimenusysosyoyhmac'];
}

/* @var $this \yii\web\View */
/* @var $content string */


?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>壹点吃商城系统</title>
		<link rel='icon' href="/wymenuv2/img/yidianchilogo.ico" type='image/x-ico' />
		<link rel='icon' href="/img/yidianchilogo.ico" type='image/x-ico' />
		<meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-status-bar-style" content="black">

		<link rel="stylesheet" href="<?php echo  Yii::app()->request->baseUrl; ?>/css/ymall/mui.min.css">
		<link rel="stylesheet" type="text/css" href="<?php echo  Yii::app()->request->baseUrl; ?>/css/ymall/feedback-page.css" />
		<link href="<?php echo  Yii::app()->request->baseUrl; ?>/css/ymall/iconfont.css" rel="stylesheet"/>
		<link rel="stylesheet" type="text/css" href="<?php echo  Yii::app()->request->baseUrl; ?>/css/ymall/ymall.css"/>
		<style>
			/*.mui-hbar{background-color: #B22222!important;color:white;}*/
			.mui-hbar{background-color: #FF8C00!important;color:white;}
			.mui-a{color:yellow!important;font-weight: 900;}
			/* .mui-bar-tab .mui-tab-item {color:#FF8C00;} */
			.mui-bar-tab .mui-tab-item {color:#fff;}
		</style>
		<link rel="stylesheet" type="text/css" href="<?php echo  Yii::app()->request->baseUrl; ?>/css/ymall/app.css" />
		<link href="<?php echo  Yii::app()->request->baseUrl; ?>/css/ymall/mui.picker.css" rel="stylesheet" />
		<link href="<?php echo  Yii::app()->request->baseUrl; ?>/css/ymall/mui.picker.min.css" rel="stylesheet" />
		<link href="<?php echo  Yii::app()->request->baseUrl; ?>/css/ymall/mui.poppicker.css" rel="stylesheet" />
    	<script type="text/javascript" src="<?php echo  Yii::app()->request->baseUrl; ?>/scripts/flipsnap.js"></script>
		<script src="<?php echo  Yii::app()->request->baseUrl; ?>/js/ymall/mui.js "></script>
		<script src="<?php echo  Yii::app()->request->baseUrl; ?>/js/ymall/mui.view.js "></script>
		<script src="<?php echo  Yii::app()->request->baseUrl; ?>/js/ymall/app.js"></script>
		<script src="<?php echo  Yii::app()->request->baseUrl; ?>/js/ymall/jquery-1.7.1.min.js"></script>
		<script src="<?php echo  Yii::app()->request->baseUrl; ?>/js/ymall/mui.picker.js"></script>
		<script src="<?php echo  Yii::app()->request->baseUrl; ?>/js/ymall/mui.poppicker.js"></script>
		<script src="<?php echo  Yii::app()->request->baseUrl; ?>/js/ymall/mui.picker.min.js"></script>
		<script src="<?php echo  Yii::app()->request->baseUrl; ?>/js/ymall/city.data.js" type="text/javascript" charset="utf-8"></script>
		<script src="<?php echo  Yii::app()->request->baseUrl; ?>/js/ymall/city.data-3.js" type="text/javascript" charset="utf-8"></script>
	</head>
	<body>
	<?php echo $content; ?>

	<nav class="mui-bar mui-bar-tab mui-hbar ">
			<a class="mui-tab-item <?php $cname=Yii::app()->controller->id;if ($cname=='product')echo 'mui-a';?>" id="index" href="<?php echo $this->createUrl('product/index',array('companyId'=>$this->companyId));?>">
				<span class="mui-icon mui-icon-home"></span>
				<span class="mui-tab-label">首页</span>
			</a>
			<a class="mui-tab-item <?php $cname=Yii::app()->controller->id;if ($cname=='kind'||$cname=='productdetail')echo 'mui-a';?> " id="kind" href="<?php echo $this->createUrl('kind/kind',array('companyId'=>$this->companyId));?>">
				<span class="mui-icon mui-icon-list"></span>
				<span class="mui-tab-label">分类</span>
			</a>
			<a class="mui-tab-item <?php $cname=Yii::app()->controller->id;if ($cname=='ymallcart')echo 'mui-a';?> " id="cart" href="<?php echo $this->createUrl('ymallcart/index',array('companyId'=>$this->companyId));?>">
				<span class="mui-icon iconfont icon-cart"><span class="mui-badge" id="car_num"><?php echo $this->getCartsnum(); ?></span></span>
				<span class="mui-tab-label">购物车</span>
			</a>
			<a class="mui-tab-item <?php $cname=Yii::app()->controller->id;if ($cname=='myinfo'||$cname=='address')echo 'mui-a';?> " id="my" href="<?php echo $this->createUrl('myinfo/index',array('companyId'=>$this->companyId));?>">
				<span class="mui-icon mui-icon-contact"></span>
				<span class="mui-tab-label">我的</span>
			</a>
	</nav>
	</body>

	<script type="text/javascript" charset="utf-8">
		mui.init();
		// 解决 所有a标签 导航不能跳转页面
		// mui('body').on('tap','a',function(){document.location.href=this.href;});


	var button = document.getElementById('index');
	button.addEventListener('tap',function(){
	location.href="<?php echo $this->createUrl('product/index',array('companyId'=>$this->companyId));?>";
	});
	var button3 = document.getElementById('kind');
	button3.addEventListener('tap',function(){
	location.href="<?php echo $this->createUrl('kind/kind',array('companyId'=>$this->companyId));?>";
	});
	var button2 = document.getElementById('cart');
	button2.addEventListener('tap',function(){
	location.href="<?php echo $this->createUrl('ymallcart/index',array('companyId'=>$this->companyId));?>";
	});
	var button1 = document.getElementById('my');
	button1.addEventListener('tap',function(){
	location.href="<?php echo $this->createUrl('myinfo/index',array('companyId'=>$this->companyId));?>";
	});
	mui('.mui-bar').on('tap','.mui-tab-item',function(){
		$('.mui-tab-item').removeClass('mui-a');
		$(this).addClass('mui-a');
	});
	</script>
</body>
</html>

