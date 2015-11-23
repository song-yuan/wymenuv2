<?php
	$baseUrl = Yii::app()->baseUrl;
	$this->setPageTitle('订单');
?>

<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl;?>/css/mall/style.css">
<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl;?>/css/mall/order.css">
<script type="text/javascript" src="<?php echo $baseUrl;?>/js/mall/Adaptive.js"></script>
<script type="text/javascript" src="<?php echo $baseUrl;?>/js/mall/jquery-1.9.1.min.js"></script>
<div class="order-title">订单详情</div>
<div class="order-info">
	<?php foreach($orderProducts as $product):?>
	<div class="item">
		<div class="lt"><?php echo $product['product_name'];?></div><div class="rt">X<?php echo $product['amount'];?> ￥<?php echo $product['price'];?></div>
		<div class="clear"></div>
	</div>
	<?php endforeach;?>
</div>
<footer>
    <div class="ft-lt">
        <p>￥<span id="total" class="total"><?php echo $order['should_total']?></span></p>
    </div>
    <div class="ft-rt">
        <p><a href="#">去付款</a></p>
    </div>
    <div class="clear"></div>
</footer>
