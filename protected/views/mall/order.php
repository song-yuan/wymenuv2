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
	<?php if($order['reality_total'] - $order['should_total']):?>
	<div class="ht1"></div>
	<div class="item">
		<div class="lt">优惠金额</div><div class="rt">￥<?php echo $order['reality_total'] - $order['should_total'];?></div>
		<div class="clear"></div>
	</div>
	<?php endif;?>
</div>
<footer>
    <div class="ft-lt">
        <p>￥<span id="total" class="total"><?php echo $order['should_total'];?></span></p>
    </div>
    <div class="ft-rt">
        <p><a id="payorder" order-status="<?php echo $order['order_status'];?>" href="javascript:;">去付款</a></p>
    </div>
    <div class="clear"></div>
</footer>
<script type="text/javascript">
$(document).ready(function(){
	$('#payorder').click(fucntion(){
		var status = $(this).attr('order-status');
		if(parseInt(status)!=2){
			alert('服务员确认后才能付款!');
		}else{
			location.href = '<?php echo $this->createUrl('/mall/payOrder',array('companyId'=>$this->companyId,'orderId'=>$order['lid']));?>'
		}
	});
});
</script>
