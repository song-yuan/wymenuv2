<?php
/* @var $this ProductController */
	Yii::app()->clientScript->registerCssFile('../css/order.css');
?>
<div class="top">我的订单</div>
<div class="product-title">订单已经被锁定，其他人不能修改，需要最终修改数量和口味点击<img src="../img/product/down-arrow.png" /></div>
<div class="product">
	<div class="product-up">
		<div class="product-up-left">番茄炒蛋</div>
	</div>
	<div class="product-middle">
		<font color="#ff8c00">口味要求</font><img src="../img/product/down-arrow.png" />:少油  少盐
	</div>
        <div class="product-down">￥1228.00/例 X <font color="#ff8c00">2例</font><img src="../img/product/down-arrow.png" /></div>
</div>

<div class="product">
	<div class="product-up">
		<div class="product-up-left">番茄炒蛋</div>
	</div>
	<div class="product-middle">
		<font color="#ff8c00">口味要求</font><img src="../img/product/down-arrow.png" />:少油  少盐
	</div>
        <div class="product-down">￥28.00/例 X <font color="#ff8c00">1例</font><img src="../img/product/down-arrow.png" /></div>
</div>

<div class="product">
	<div class="product-up">
		<div class="product-up-left">番茄炒蛋</div>
	</div>
	<div class="product-middle">
		<font color="#ff8c00">口味要求</font><img src="../img/product/down-arrow.png" />:少油  少盐
	</div>
        <div class="product-down">￥1228.00/例 X <font color="#ff8c00">2例</font><img src="../img/product/down-arrow.png" /></div>
</div>

<div class="product">
	<div class="product-up">
		<div class="product-up-left">番茄炒蛋</div>
	</div>
	<div class="product-middle">
            <font color="#ff8c00">口味要求</font><img src="../img/product/down-arrow.png" />:少油  少盐 
	</div>
        <div class="product-down">￥28.00/例 X <font color="#ff8c00">1例</font><img src="../img/product/down-arrow.png" /></div>
</div>

<div class="order-info">
	<div class="order-time"><?php echo date('Y-m-d H:i',time()); ?></div>
	<div class="order-price">订单总额:36.00</div>
</div>
<div class="order-taste"><font color="#ff8c00">全单口味要求</font><img src="../img/product/down-arrow.png" />:不放辣</div>

<a href="orderList"><div class="btn confirm">确认</div></a>
<a href="orderList"><div class="btn back">返回</div></a>
