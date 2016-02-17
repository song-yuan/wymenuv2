<?php
	$baseUrl = Yii::app()->baseUrl;
	$this->setPageTitle('订单详情');
	$payYue = 0.00;
	if(!empty($orderPays)){
		foreach($orderPays as $orderPay){
			if($orderPay['paytype']==10){
				$payYue = $orderPay['pay_amount']; 
			}
		}
	}
?>

<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl;?>/css/mall/reset.css">
<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl;?>/css/mall/user.css">
<script type="text/javascript" src="<?php echo $baseUrl;?>/js/mall/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="<?php echo $baseUrl.'/js/layer/layer.js';?>"></script>


<div class="order-title">我的订单</div>
<div class="order-site"><div class="lt"><?php if($order['order_type']==1):?>桌号:<?php if($siteType){echo $siteType['name'];}?><?php echo $site['serial'];?><?php else:?>订单状态<?php endif;?></div><div class="rt"><?php if($order['order_status'] < 3) echo '<button class="specialbttn bttn_orange" status="'.$order['order_status'].'">待支付</button>';elseif($order['order_status'] == 3) echo '已支付';else echo '已完成';?></div><div class="clear"></div></div>
<?php if($address):?>
	<?php if($order['order_type']==2):?>
	<div class="address">
		<div class="location">
			<span>收货人：<?php echo $address['consignee'];?>   <?php echo $address['mobile'];?></span><br>
			<span class="add">收货地址：<?php echo $address['province'].$address['city'].$address['area'].$address['street'];?></span>
		</div>
	</div>
	<?php else:?>
	<div class="address">
		<div class="location">
			<span>预约人：<?php echo $address['consignee'];?>   <?php echo $address['mobile'];?></span><br />
			<span class="add">预约时间：<?php echo $order['appointment_time'];?></span>
		</div>
	</div>
	<?php endif;?>
<?php endif;?>
<div class="order-info">
	<?php foreach($orderProducts as $product):?>
	<div class="item">
		<div class="lt"><?php echo $product['product_name'];?><?php if($product['is_retreat']):?><span style="color:red">(已退)</span><?php endif;?></div><div class="rt">X<?php echo $product['amount'];?> ￥<?php echo $product['price'];?></div>
		<div class="clear"></div>
	</div>
	<?php endforeach;?>
	<div class="ht1"></div>
	<div class="item">
		<div class="lt">总计:</div><div class="rt">￥<?php echo $order['reality_total'];?></div>
		<div class="clear"></div>
	</div>
	<?php if($order['reality_total'] - $order['should_total'] - $payYue):?>
	
	<?php if($order['cupon_branduser_lid'] > 0):?>
	<div class="item">
		<div class="lt">优惠减免</div><div class="rt">￥<?php echo number_format($order['reality_total'] - $order['should_total'] - $payYue - $order['cupon_money'],2);?></div>
		<div class="clear"></div>
	</div>
	<div class="item">
		<div class="lt">现金券减免</div><div class="rt">￥<?php echo number_format($order['cupon_money'],2);?></div>
		<div class="clear"></div>
	</div>
	<?php else:?>
	<div class="item">
		<div class="lt">优惠减免</div><div class="rt">￥<?php echo number_format($order['reality_total'] - $order['should_total'] - $payYue,2);?></div>
		<div class="clear"></div>
	</div>
	<?php endif;?>
	
	<?php endif;?>
	<div class="ht1"></div>
	<?php if($payYue > 0):?>
	<div class="item" >
		<div class="lt">余额支付:</div><div class="rt">￥<span style="color:#FF5151"><?php echo $payYue;?></span></div>
		<div class="clear"></div>
	</div>
	<?php endif;?>
	<div class="item">
		<div class="lt">微信支付:</div><div class="rt">￥<span style="color:#FF5151"><?php echo $order['should_total'];?></span></div>
		<div class="clear"></div>
	</div>
	<div class="item">
		<div class="lt">合计:</div><div class="rt">￥<span style="color:#FF5151"><?php echo number_format($order['should_total'] + $payYue,2);?></span></div>
		<div class="clear"></div>
	</div>
</div>
<div class="close_window specialbttn bttn_orange">返回微信</div>
<?php if($redPack && $order['order_status'] > 2):?>
<?php 
	$title = '现金红包送不停！';
    $desc = '红包可以抵扣订单金额。点单优惠，尽在物易我要点单';
    $url = $this->createAbsoluteUrl('/mall/share',array('companyId'=>$this->companyId,'redptId'=>$redPack['lid']));
    $imgUrl = Yii::app()->request->hostInfo.$baseUrl.'/img/mall/144208iygyy9.png';
?>
<a href="javascipt:;" class="share"><img src="<?php echo $baseUrl.'/img/mall/144208iygyy9.png';?>" /></a>
<div class="popshare">
	<img src="<?php echo $baseUrl.'/img/mall/popup_share.png';?>" alt="">
</div>
<?php else:?>
<?php 
	$title = '物易我要点单';
    $desc = '物点单优惠，尽在物易我要点单';
    $url = $this->createAbsoluteUrl('/mall/index',array('companyId'=>$this->companyId));
    $imgUrl = Yii::app()->request->hostInfo.$baseUrl.'/img/mall/144208iygyy9.png';
?>
<?php endif;?>
<script>
    var title = '<?php echo $title;?>';
    var link = '<?php echo $url;?>';
    var desc = '<?php echo $desc;?>';
    var imgUrl = '<?php echo $imgUrl;?>';
</script>
<script src="<?php echo $baseUrl;?>/js/weixinshare.js"></script>

<script>
$(document).ready(function(){
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
		    
		});
	});
	$('.share').click(function(){
		$('.popshare').show();
	});
	$('.popshare').click(function(){
		$(this).hide();
	});
})
</script>