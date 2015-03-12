<?php
/* @var $this ProductController */
	Yii::app()->clientScript->registerCssFile('../css/cartlist.css');
	$orderPrice = 0;
	$orderNum = 0;
	$orderPricePay = 0;
	$orderPayNum = 0;
	$orderList = new OrderList($this->siteNoId);
	if($orderList->order){
		$orderProductList = $orderList->OrderProductList($orderList->order['lid'],0);
		$orderProductListPay = $orderList->OrderProductList($orderList->order['lid'],1);
		$price = $orderList->OrderPrice(0);
		$priceArr = explode(':',$price);
		$orderPrice = $priceArr[0];
		$orderNum = $priceArr[1];
		
		$pricePay = $orderList->OrderPrice(1);
		$pricePayArr = explode(':',$pricePay);
		$orderPricePay = $pricePayArr[0];
		$orderPayNum = $pricePayArr[1];
	}else{
		$orderProductList = array();
		$orderProductListPay = array();
	}
	
?>

	<div class="top"><a href="index"><div class="back"><img src="../img/product/back.png" /> 返回</div></a><a id="order" href="javascript:;"><button class="create-order">下单</button></a></div>
	<form action="order?orderId=<?php echo $orderList->order?$orderList->order['lid']:0;?>" method="post">
	<div class="order-top"><div class="order-top-left"><span>￥<?php echo Money::priceFormat($orderPrice);?> 共<?php echo $orderNum;?>份</span></div><div class="order-top-right" style="color:#ff8c00">全单口味<img src="../img/product/down-arrow.png" /></div></div>
	<?php if($orderProductList):?>
	<?php foreach($orderProductList as $key=>$orderProduct):?>
		<div class="order-category"><?php echo OrderList::GetCatoryName($key);?></div>
		<?php foreach($orderProduct as $order):?>
		<div class="order-product">
			<div class="order-product-left"><img src="<?php echo $order['main_picture'];?>" /></div>
			<div class="order-product-right">
				<div class="right-up"><?php echo $order['product_name'];?></div>
		               <div class="right-middle"><span class="minus" >-</span><input type="text" name="<?php echo $order['product_id'];?>" value="<?php echo $order['amount'];?>" readonly="true"/><span class="plus">+</span></div>
				<div class="right-down">
		                    <div class="right-down-left">￥<?php echo $order['price'];?></div>
				 <div class="right-down-right" style="color:#ff8c00">口味<img src="../img/product/down-arrow.png" /></div>	
				</div>
			</div>
			<div class="clear"></div>
		</div>
		<?php endforeach;?>
	<?php endforeach;?>
	</form>
	<?php endif;?>
	<?php if($orderProductListPay):?>
	<div style="color:#555da8;border-top:2px solid;margin-top: 5px">
	<div class="order-top"><div class="order-top-left">下单总额 :<span> <?php echo Money::priceFormat($orderPricePay);?></span></div><div class="order-top-right"><button class="online-pay">在线支付</button></div></div>
	<div class="order-time"><div class="order-time-left"><?php echo date('Y-m-d H:i:s',time());?></div><div class="order-time-right" style="color:#ff8c00">全单有话要说<img src="../img/product/down-arrow.png" /></div></div>
	<?php foreach($orderProductListPay as $key=>$orderProduct):?>
		<div class="order-category"><?php echo OrderList::GetCatoryName($key);?></div>
	   <?php foreach($orderProduct as $order):?>
		<div class="order-product">
			<div class="order-product-left"><img src="<?php echo $order['main_picture'];?>" /></div>
			<div class="order-product-right">
				<div class="right-up"><?php echo $order['product_name'];?></div>
				<div class="right-down">
		                    <div class="right-down-left">￥<?php echo $order['price'];?>/例 X <font color="#ff8c00"><?php echo $order['amount'];?>例</font></div>
				 <div class="right-down-right" style="color:#ff8c00">口味<img src="../img/product/down-arrow.png" /></div>	
				</div>
			</div>
			<div class="clear"></div>
		</div>
		<?php endforeach;?>
	<?php endforeach;?>
	<?php endif;?>
<script>
	$(function(){
		$('.minus').click(function(){
			var input = $(this).siblings('input');
			var num = input.val();
			if(num > 0){
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
		$('#order').click(function(){
			$('form').submit();
		});
	});
</script>