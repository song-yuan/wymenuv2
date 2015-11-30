<?php
	$baseUrl = Yii::app()->baseUrl;
	$this->setPageTitle('确认订单');
?>

<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl;?>/css/mall/style.css">
<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl;?>/css/mall/order.css">
<script type="text/javascript" src="<?php echo $baseUrl;?>/js/mall/Adaptive.js"></script>
<script type="text/javascript" src="<?php echo $baseUrl;?>/js/mall/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="<?php echo $baseUrl.'/js/layer/layer.js';?>"></script>

<div class="order-title">我的订单</div>
<div class="order-site">桌号:<?php echo $site['serial'];?></div>
<div class="order-info">
	<?php foreach($orderProducts as $product):?>
	<div class="item">
		<div class="lt"><?php echo $product['product_name'];?></div><div class="rt">X<?php echo $product['amount'];?> ￥<?php echo $product['price'];?></div>
		<div class="clear"></div>
	</div>
	<?php endforeach;?>
	<div class="ht1"></div>
	<div class="item">
		<div class="lt">合计:</div><div class="rt">￥<?php echo $order['reality_total'];?></div>
		<div class="clear"></div>
	</div>
	<?php if($order['reality_total'] - $order['should_total']):?>
	<div class="item">
		<div class="lt">优惠金额:</div><div class="rt">￥<?php echo $order['reality_total'] - $order['should_total'];?></div>
		<div class="clear"></div>
	</div>
	<?php endif;?>
</div>
<div class="order-copun">
	<div class="copun-lt">优惠券</div><div class="copun-rt"></div><div class="clear"></div></div>
</div>
<div class="order-paytype">
<div class="select-type">选择支付方式</div>
<div class="paytype">
	<div class="item on" paytype="2">线上支付</div>
	<div class="item" paytype="1" style="border:none;">现金支付</div>
</di>
</di>
<footer>
    <div class="ft-lt">
        <p>￥<span id="total" class="total"><?php echo $order['should_total'];?></span></p>
    </div>
    <div class="ft-rt">
        <p><a id="payorder" href="javascript:;">去付款</a></p>
    </div>
    <div class="clear"></div>
</footer>
<script type="text/javascript">
$(document).ready(function(){
	$('.paytype .item').click(function(){
		$('.item').removeClass('on');
		$(this).addClass('on');
	});
	$('#payorder').click(function(){
		var paytype = $('.on').attr('paytype');
		if(parseInt(paytype)==2){
			$.get('<?php echo $this->createUrl('/mall/getOrderStatus',array('companyId'=>$this->companyId,'orderId'=>$order['lid']))?>',function(msg){
				if(parseInt(msg) < 2){
					layer.msg('服务员确认后才能付款!');
				}else{
					location.href = '<?php echo $this->createUrl('/mall/payOrder',array('companyId'=>$this->companyId,'orderId'=>$order['lid']));?>&paytype='+paytype;
				}
			});
		}else{
			location.href = '<?php echo $this->createUrl('/mall/payOrder',array('companyId'=>$this->companyId,'orderId'=>$order['lid']));?>&paytype='+paytype;
		}
	});
});
</script>
