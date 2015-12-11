<?php
	$baseUrl = Yii::app()->baseUrl;
	$this->setPageTitle('订单详情');
?>

<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl;?>/css/mall/reset.css">
<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl;?>/css/mall/user.css">
<script type="text/javascript" src="<?php echo $baseUrl;?>/js/mall/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="<?php echo $baseUrl.'/js/layer/layer.js';?>"></script>


<div class="order-title">我的订单</div>
<div class="order-site"><div class="lt"><?php if($order['order_type']==1):?>桌号:<?php if($siteType){echo $siteType['name'];}?><?php echo $site['serial'];?><?php else:?>订单状态<?php endif;?></div><div class="rt"><?php if($order['order_status'] < 3) echo '<button class="specialbttn bttn_orange">待支付</button>';elseif($order['order_status'] == 3) echo '已支付';else echo '已完成';?></div><div class="clear"></div></div>
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
		<div class="lt">优惠金额:</div><div class="rt">￥<?php echo number_format($order['reality_total'] - $order['should_total'],2);?></div>
		<div class="clear"></div>
	</div>
	<?php endif;?>
	<div class="item">
		<div class="lt">实际支付:</div><div class="rt">￥<span style="color:#FF5151"><?php echo $order['should_total'];?></span></div>
		<div class="clear"></div>
	</div>
</div>
<script>
$(document).ready(function(){
	<?php if(isset($msg)&&$msg):?>
	layer.msg('<?php echo $msg;?>');
	<?php endif;?>
	$('.specialbttn').click(function(){
		location.href = '<?php echo $this->createUrl('/mall/payOrder',array('companyId'=>$this->companyId,'orderId'=>$order['lid'],'paytype'=>2));?>';
	});
})
</script>