<?php
	$baseUrl = Yii::app()->baseUrl;
	$this->setPageTitle('个人中心');
?>

<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl;?>/css/mall/reset.css">
<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl;?>/css/mall/common.css">
<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl;?>/css/mall/members.css">
<script type="text/javascript" src="<?php echo $baseUrl;?>/js/mall/jquery-1.9.1.min.js"></script>
<script src="<?php echo $baseUrl;?>/js/mall/hammer.js"></script>
<script src="<?php echo $baseUrl;?>/js/mall/swipeout.js"></script>
<section class="my_address bg_lgrey2">
	<form action="">
		<ul class="complete_add">
			<li><label for="name">收货人</label><input type="text" placeholder="名字" value=""></li> 
			<li><label for="tel">手机号码</label><input type="text" placeholder="11位手机号码" value=""></li>
			<li><label for="area">选择地区</label><input type="text" placeholder="地区信息" value=""></li>
			<li><label for="receiver">详细地址</label><input type="text" placeholder="街道门牌信息" value=""></li>
			<li><label for="receiver">邮政编码</label><input type="text" placeholder="邮政编码" value=""></li>
			<li>
			<div class="left">设置为默认收货地址</span></div>
			<div class="right">
			<label><input type="checkbox" class="ios-switch green  bigswitch" checked /><div><div></div></div></label>
			</div>

			</li>
		</ul>
		<div class="bttnbar">
		<button class="bttn_black2 bttn_large" type="button"><a href="my_address.php">取消</a></button>
		<button class="bttn_black2 bttn_large" type="submit">保存</button>
		</div>
	</form>
</section>
