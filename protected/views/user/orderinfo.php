<?php
	$baseUrl = Yii::app()->baseUrl;
	$this->setPageTitle('订单详情');
?>

<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl;?>/css/mall/reset.css">
<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl;?>/css/mall/user.css">
<script type="text/javascript" src="<?php echo $baseUrl;?>/js/mall/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="<?php echo $baseUrl.'/js/layer/layer.js';?>"></script>


<div class="order-title">我的订单</div>
<div class="order-site"><div class="lt"><?php if($order['order_type']==1):?>桌号:<?php if($siteType){echo $siteType['name'];}?><?php echo $site['serial'];?><?php else:?>订单状态<?php endif;?></div><div class="rt"><?php if($order['order_status'] < 3) echo '<button class="specialbttn bttn_orange" status="'.$order['order_status'].'">待支付</button>';elseif($order['order_status'] == 3) echo '已支付';else echo '已完成';?></div><div class="clear"></div></div>
<div class="order-info">
	<?php foreach($orderProducts as $product):?>
	<div class="item">
		<div class="lt"><?php echo $product['product_name'];?><?php if($product['is_retreat']):?><span style="color:red">(已退)</span><?php endif;?></div><div class="rt">X<?php echo $product['amount'];?> ￥<?php echo $product['price'];?></div>
		<div class="clear"></div>
	</div>
	<?php endforeach;?>
	<div class="ht1"></div>
	<div class="item">
		<div class="lt">合计:</div><div class="rt">￥<?php echo $order['reality_total'];?></div>
		<div class="clear"></div>
	</div>
	<?php if($order['reality_total'] - $order['should_total']):?>
	
	<?php if($order['cupon_branduser_lid'] > 0):?>
	<div class="item">
		<div class="lt">优惠减免</div><div class="rt">￥<?php echo number_format($order['reality_total'] - $order['should_total'] - $order['cupon_money'],2);?></div>
		<div class="clear"></div>
	</div>
	<div class="item">
		<div class="lt">现金券减免</div><div class="rt">￥<?php echo number_format($order['cupon_money'],2);?></div>
		<div class="clear"></div>
	</div>
	<?php else:?>
	<div class="item">
		<div class="lt">优惠减免</div><div class="rt">￥<?php echo number_format($order['reality_total'] - $order['should_total'],2);?></div>
		<div class="clear"></div>
	</div>
	<?php endif;?>
	
	<?php endif;?>
	<div class="item">
		<div class="lt">实际支付:</div><div class="rt">￥<span style="color:#FF5151"><?php echo $order['should_total'];?></span></div>
		<div class="clear"></div>
	</div>
</div>
<div class="close_window specialbttn bttn_orange">返回微信</div>

<script>
$(document).ready(function(){
	<?php if(isset($msg)&&$msg):?>
	layer.msg('<?php echo $msg;?>');
	<?php endif;?>
	$('.specialbttn').click(function(){
		var status = $(this).attr('status');
		if(parseInt(status) < 2){
			location.href = '<?php echo $this->createUrl('/mall/order',array('companyId'=>$this->companyId,'orderId'=>$order['lid']));?>';
		}else{
			location.href = '<?php echo $this->createUrl('/mall/payOrder',array('companyId'=>$this->companyId,'orderId'=>$order['lid'],'paytype'=>2));?>';
		}
	});
	$('.close_window').click(function(){
		WeixinJSBridge.invoke('closeWindow',{},function(res){
		    alert(res.err_msg);
		});
	})
})
</script>