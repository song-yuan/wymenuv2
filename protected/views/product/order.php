<?php
/* @var $this ProductController */
	Yii::app()->clientScript->registerCssFile('../css/order.css');
?>
<div class="top">我的订单</div>
<div class="product">
	<div class="product-up">
		<div class="product-up-left">番茄炒蛋</div>
		<div class="product-up-right">￥28.00/例</div>
	</div>
	<div class="product-down">
		口味要求:少油  少盐
	</div>
</div>

<div class="product">
	<div class="product-up">
		<div class="product-up-left">番茄炒蛋</div>
		<div class="product-up-right">￥28.00/例</div>
	</div>
	<div class="product-down">
		口味要求:少油  少盐
	</div>
</div>

<div class="product">
	<div class="product-up">
		<div class="product-up-left">番茄炒蛋</div>
		<div class="product-up-right">￥28.00/例</div>
	</div>
	<div class="product-down">
		口味要求:少油  少盐
	</div>
</div>
<div class="product">
	<div class="product-up">
		<div class="product-up-left">番茄炒蛋</div>
		<div class="product-up-right">￥28.00/例</div>
	</div>
	<div class="product-down">
		口味要求:少油  少盐
	</div>
</div>

<div class="order-info">
	<div class="order-time"><?php echo date('Y-m-d H:i',time()); ?></div>
	<div class="order-price">订单总额:36.00</div>
	<div class="order-discount">优惠金额:36.00</div>
	<div class="order-num">2份</div>
</div>
<div class="order-taste">全单口味要求:不放辣</div>

<div class="btn confirm">确认</div>
<div class="btn back">返回</div>
