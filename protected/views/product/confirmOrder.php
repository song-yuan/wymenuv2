<?php
/* @var $this ProductController */
	Yii::app()->clientScript->registerCssFile('../css/cartlist.css');
?>
<div class="top"><a href="orderList"><div class="back">返回</div></a><a href="order"><button class="create-order">确认下单</button></a></div>
<div class="order-top"><div class="order-top-left">购物车总额 :<span> 36.00</span></div><div class="order-top-right">2份</div></div>
<div class="order-time"><div class="order-time-left"><?php echo date('Y-m-d H:i:s',time());?></div><div class="order-time-right">全单口味<img src="../img/product/down-arrow.png" /></div></div>

<div class="order-category">红烧类</div>
<div class="order-product">
	<div class="order-product-left"><img src="../img/product/gadfadsf.png" /></div>
	<div class="order-product-right">
		<div class="right-up">番茄炒蛋 </div>
               <div class="right-middle"><span class="minus" >-</span><input type="text" name="name" value="1" readonly="true"/><span class="plus">+</span></div>
		<div class="right-down">
                    <div class="right-down-left">￥18.00</div>
		 <div class="right-down-right">口味<img src="../img/product/down-arrow.png" /></div>	
		</div>
	</div>
	<div class="clear"></div>
</div>
<div class="order-product">
	<div class="order-product-left"><img src="../img/product/gadfadsf.png" /></div>
	<div class="order-product-right">
		<div class="right-up">红烧鸡</div>
               <div class="right-middle"><span class="minus" >-</span><input type="text" name="name" value="1" readonly="true"/><span class="plus">+</span></div>
		<div class="right-down">
                    <div class="right-down-left">￥18.00</div>
		 <div class="right-down-right">口味<img src="../img/product/down-arrow.png" /></div>	
		</div>
	</div>
	<div class="clear"></div>
</div>
<div class="order-category">冷菜类</div>
<div class="order-product">
	<div class="order-product-left"><img src="../img/product/gadfadsf.png" /></div>
	<div class="order-product-right">
		<div class="right-up">拌豆腐</div>
               <div class="right-middle"><span class="minus" >-</span><input type="text" name="name" value="1" readonly="true"/><span class="plus">+</span></div>
		<div class="right-down">
                    <div class="right-down-left">￥18.00</div>
		 <div class="right-down-right">口味<img src="../img/product/down-arrow.png" /></div>	
		</div>
	</div>
	<div class="clear"></div>
</div>
<div class="order-category">新年套餐</div>
<div class="order-product">
	<div class="order-product-left"><img src="../img/product/gadfadsf.png" /></div>
	<div class="order-product-right">
		<div class="right-up">鸡翅 </div>
               <div class="right-middle"><span class="minus" >-</span><input type="text" name="name" value="1" readonly="true"/><span class="plus">+</span></div>
		<div class="right-down">
                    <div class="right-down-left">￥18.00</div>
		 <div class="right-down-right">口味<img src="../img/product/down-arrow.png" /></div>	
		</div>
	</div>
	<div class="clear"></div>
</div>
<div class="order-product">
	<div class="order-product-left"><img src="../img/product/gadfadsf.png" /></div>
	<div class="order-product-right">
		<div class="right-up">口乐 <img src="../img/product/down-arrow.png" />更换</div>
               <div class="right-middle"><span class="minus" >-</span><input type="text" name="name" value="1" readonly="true"/><span class="plus">+</span></div>
		<div class="right-down">
                    <div class="right-down-left">￥18.00</div>
		 <div class="right-down-right">口味<img src="../img/product/down-arrow.png" /></div>	
		</div>
	</div>
	<div class="clear"></div>
</div>
<script>
	$(function(){
		$('.minus').click(function(){
			var input = $(this).siblings('input');
			var num = input.val();
			if(num > 1){
				num = num - 1;
			}
			input.val(num);			
		});
		$('.plus').click(function(){
			var input = $(this).siblings('input');
			var num = parseInt(input.val());
			num = num + 1;
			input.val(num);			
		});
	});
</script>