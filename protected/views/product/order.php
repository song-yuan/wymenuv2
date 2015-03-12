<?php
/* @var $this ProductController */
	Yii::app()->clientScript->registerCssFile('../css/order.css');
	$orderPrice = 0;
	$orderNum = 0;
	$orderList = new OrderList($this->siteNoId);
	if($orderList->order){
		$orderProductList = $orderList->OrderProductList($orderList->order['lid'],0);
		$price = $orderList->OrderPrice(0);
		$priceArr = explode(':',$price);
		$orderPrice = $priceArr[0];
		$orderNum = $priceArr[1];
	}else{
		$orderProductList = array();
	}
?>
<form action="orderList?confirm=1&orderId=<?php echo $orderList->order['lid'];?>" method="post">
<div class="top">我的订单</div>
<div class="product-title">订单已经被锁定，其他人不能修改，需要最终修改数量和口味点击<img src="../img/product/down-arrow.png" /></div>
<?php foreach($orderProductList as $key=>$orderProduct):?>
	<div class="order-category"><?php echo OrderList::GetCatoryName($key);?></div>
	<?php foreach($orderProduct as $order):?>
		<div class="product">
		<div class="product-up">
			<div class="product-up-left"><?php echo $order['product_name'];?></div>
		</div>
		<div class="product-middle">
			<font color="#ff8c00">口味要求</font><img src="../img/product/down-arrow.png" />:少油  少盐
		</div>
	        <div class="product-down">￥<?php echo $order['price'];?>/例 X <font color="#ff8c00"><?php echo $order['amount'];?>例</font><img src="../img/product/down-arrow.png" /></div>
		</div>
	<?php endforeach;?>
<?php endforeach;?>
	<div class="order-info">
		<div class="order-time"><?php echo date('Y-m-d H:i',time()); ?></div>
		<div class="order-price">订单总额:<?php echo Money::priceFormat($orderPrice); ?></div>
	</div>
	<div class="order-taste"><font color="#ff8c00">全单口味要求</font><img src="../img/product/down-arrow.png" />:不放辣</div>
</form>

<a id="comfirm-order" href="javascript:;"><div class="btn confirm">确认</div></a>
<a href="orderList"><div class="btn back">返回</div></a>
<script type="text/javascript">
	$('#comfirm-order').click(function(){
		$('form').submit();
	})
</script>
