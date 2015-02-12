<?php
/* @var $this ProductController */
	Yii::app()->clientScript->registerCssFile('../css/confirmorder.css');
?>
<!--<div class="top"><a href="index"><div class="back">返回</div></a></div>-->
<div class="order-top"><div class="order-top-left">下单总额 :<span> 36.00</span></div><div class="order-top-right"><a href="order"><div class="pay-order">支付宝付款</div></a></div></div>
<div class="order-time"><div class="order-time-left"><?php echo date('Y-m-d H:i:s',time());?></div><div class="order-time-right">全单做法口味选择<img src="../img/product/down-arrow.png" /></div></div>

<div class="order-category">红烧类</div>
<div class="order-product">
	<div class="order-product-left"><img src="../img/product/gadfadsf.png" /></div>
	<div class="order-product-right">
		<div class="right-up">
		 <div class="right-up-left">番茄炒蛋</div>
		 <div class="right-up-right"><strike>￥28.00/例 </strike>现价￥18.00</div>
		</div>
		<div class="right-down">
		  <div class="right-down-left"><span class="minus" >-</span><input type="text" name="name" value="1" readonly="true"/><span class="plus">+</span></div>
		 <div class="right-down-right">做法口味选择<img src="../img/product/down-arrow.png" /></div>	
		</div>
	</div>
	<div class="clear"></div>
</div>

<div class="order-category">红烧类</div>
<div class="order-product">
	<div class="order-product-left"><img src="../img/product/gadfadsf.png" /></div>
	<div class="order-product-right">
		<div class="right-up">
		 <div class="right-up-left">番茄炒蛋</div>
		 <div class="right-up-right"><strike>￥28.00/例 </strike>现价￥18.00</div>
		</div>
		<div class="right-down">
		  <div class="right-down-left"><span class="minus" >-</span><input type="text" name="name" value="1" readonly="true"/><span class="plus">+</span></div>
		 <div class="right-down-right">做法口味选择<img src="../img/product/down-arrow.png" /></div>	
		</div>
	</div>
	<div class="clear"></div>
</div>

<div class="order-category">红烧类</div>
<div class="order-product">
	<div class="order-product-left"><img src="../img/product/gadfadsf.png" /></div>
	<div class="order-product-right">
		<div class="right-up">
		 <div class="right-up-left">番茄炒蛋</div>
		 <div class="right-up-right"><strike>￥28.00/例 </strike>现价￥18.00</div>
		</div>
		<div class="right-down">
		  <div class="right-down-left"><span class="minus" >-</span><input type="text" name="name" value="1" readonly="true"/><span class="plus">+</span></div>
		 <div class="right-down-right">做法口味选择<img src="../img/product/down-arrow.png" /></div>	
		</div>
	</div>
	<div class="clear"></div>
</div>

<div class="order-product">
	<div class="order-product-left"><img src="../img/product/gadfadsf.png" /></div>
	<div class="order-product-right">
		<div class="right-up">
		 <div class="right-up-left">番茄炒蛋</div>
		 <div class="right-up-right"><strike>￥28.00/例 </strike>现价￥18.00</div>
		</div>
		<div class="right-down">
		  <div class="right-down-left"><span class="minus" >-</span><input type="text" name="nums" value="1" readonly="true"/><span class="plus">+</span></div>
		 <div class="right-down-right">做法口味选择<img src="../img/product/down-arrow.png" /></div>	
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