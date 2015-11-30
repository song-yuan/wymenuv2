<?php
	$baseUrl = Yii::app()->baseUrl;
	$this->setPageTitle('个人中心');
?>

<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl;?>/css/mall/reset.css">
<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl;?>/css/mall/common.css">
<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl;?>/css/mall/members.css">
<script type="text/javascript" src="<?php echo $baseUrl;?>/js/mall/jquery-1.9.1.min.js"></script>

<div class="toparea">
	<div class="maininfo">
	<div class="left">
		<img src="images/avatar.jpg" class="avatar">
	</div>
	
	<div class="overlay_blk overlay"></div>
	<div class="info">
		<span>微信昵称</span><br>
		<span class="small">会员卡号：00008813</span><br>
		<button type="button" class="bttnupdate">普通会员</button>
	</div>
</div>

</div>


<div class="listset withtop">
	<div class="arrowright"><a href="my_address.html">收货地址管理</a></div>
	<div class="arrowright"><a href="#">我的订单</a></div>
</div>
<!--
<div class="listset">
	<div class="arrowright"><a href="shopping_cart.php">购物车 <span class="dot bg_red"></span></a></div>
</div>
-->
<div class="listset">
	<div class="arrowright"><a href="#">余额 <span class="small font_l"> 150</span></a>
	</div>
</div>

<div class="listset">
	<div class="arrowright"><a href="#">现金券</a></div>
</div>