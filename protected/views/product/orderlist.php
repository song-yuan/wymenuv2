<?php
/* @var $this ProductController */
	Yii::app()->clientScript->registerCssFile('../css/cartlist.css');
?>
<div class="top"><a href="index"><div class="back"><img src="../img/product/back.png" /> 返回</div></a><a href="order"><button class="create-order">下单</button></a></div>
<div class="order-top"><div class="order-top-left"><span>￥12336.00 共23份</span></div><div class="order-top-right" style="color:#ff8c00">全单口味<img src="../img/product/down-arrow.png" /></div></div>

<div class="order-category">红烧类</div>
<div class="order-product">
	<div class="order-product-left"><img src="../img/product/gadfadsf.png" /></div>
	<div class="order-product-right">
		<div class="right-up">番茄炒蛋 </div>
               <div class="right-middle"><span class="minus" >-</span><input type="text" name="name" value="1" readonly="true"/><span class="plus">+</span></div>
		<div class="right-down">
                    <div class="right-down-left">￥18.00</div>
		 <div class="right-down-right" style="color:#ff8c00">口味<img src="../img/product/down-arrow.png" /></div>	
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
		 <div class="right-down-right" style="color:#ff8c00">口味<img src="../img/product/down-arrow.png" /></div>	
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
		 <div class="right-down-right" style="color:#ff8c00">口味<img src="../img/product/down-arrow.png" /></div>	
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
		 <div class="right-down-right" style="color:#ff8c00">口味<img src="../img/product/down-arrow.png" /></div>	
		</div>
	</div>
	<div class="clear"></div>
</div>
<div class="order-product">
	<div class="order-product-left"><img src="../img/product/gadfadsf.png" /></div>
	<div class="order-product-right">
		<div class="right-up" style="color:#ff8c00">口乐 <img src="../img/product/down-arrow.png" /></div>
               <div class="right-middle"><span class="minus" >-</span><input type="text" name="name" value="1" readonly="true"/><span class="plus">+</span></div>
		<div class="right-down">
                    <div class="right-down-left">￥18.00</div>
		 <div class="right-down-right" style="color:#ff8c00">口味<img src="../img/product/down-arrow.png" /></div>	
		</div>
	</div>
	<div class="clear"></div>
</div>
<div style="color:#555da8;border-top:2px solid;margin-top: 5px">
<div class="order-top"><div class="order-top-left">下单总额 :<span> 36.00</span></div><div class="order-top-right"><button class="online-pay">在线支付</button></div></div>
<div class="order-time"><div class="order-time-left"><?php echo date('Y-m-d H:i:s',time());?></div><div class="order-time-right" style="color:#ff8c00">全单有话要说<img src="../img/product/down-arrow.png" /></div></div>
<div class="order-category">热菜</div>
<div class="order-product">
	<div class="ordered-product-left"><img src="../img/product/gadfadsf.png" /></div>
	<div class="ordered-product-right">
		<div class="right-up" style="color:#00F"><赠> 鸡翅 X2</div>
               <div class="right-middle">口味：微辣、中辣、少放盐</div>
		<div class="right-down">
                    <div class="right-down-left">￥18.00</div>
		 <div class="right-down-right" style="color:#ff8c00">有话要说<img src="../img/product/down-arrow.png" /></div>	
		</div>
	</div>
	<div class="clear"></div>
</div>
<div class="order-product">
	<div class="ordered-product-left"><img src="../img/product/gadfadsf.png" /></div>
	<div class="ordered-product-right">
		<div class="right-up" style="color:#F00"><退> 红烧鸡 X1</div>
               <div class="right-middle">口味：微辣、中辣、少放盐</div>
		<div class="right-down">
                    <div class="right-down-left">￥18.00</div>
		 <div class="right-down-right" style="color:#ff8c00">有话要说<img src="../img/product/down-arrow.png" /></div>	
		</div>
	</div>
	<div class="clear"></div>
</div>
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
		 var prevTop = 0,
    	currTop = 0;
		$(window).scroll(function() {
		    currTop = $(window).scrollTop();
		    if (currTop < prevTop) { 
		    //判断小于则为向上滚动
		    	if($(".top").is(':hidden')){
		    		$(".top").slideDown(800);
		    	}
		    } else {
		    	if($(".top").is(':visible')){
		    		$(".top").slideUp(800);
		    	}
		    }
		    //prevTop = currTop; 
		    //IE下有BUG，所以用以下方式
		    setTimeout(function(){prevTop = currTop},0);
		});
	});
</script>