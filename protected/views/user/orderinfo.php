<?php
	$baseUrl = Yii::app()->baseUrl;
	$this->setPageTitle('订单详情');
?>

<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl;?>/css/mall/reset.css">
<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl;?>/css/mall/user.css">
<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl;?>/css/weui.min.css">
<script type="text/javascript" src="<?php echo $baseUrl;?>/js/mall/jquery-1.9.1.min.js"></script>


<div class="order-title">我的订单</div>
<div class="order-site"><div class="lt"><?php if($order['order_type']==1):?>桌号:<?php if($siteType){echo $siteType['name'];}?><?php echo $site['serial'];?><?php else:?>订单状态<?php endif;?></div><div class="rt"><?php if($order['order_status'] < 3) echo '<button class="payOrder specialbttn bttn_orange" status="'.$order['order_status'].'">待支付</button>';elseif($order['order_status'] == 3) echo '已支付';else echo '已完成';?></div><div class="clear"></div></div>
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
		<!-- 其他费用 -->
	<?php if($order['order_type']==1):?>
	<div class="item">
		<div class="lt">餐位费:</div>
		<div class="rt">X1 ￥<?php echo $seatingFee?number_format($seatingFee,2):'免费';?></div>
		<div class="clear"></div>
	</div>
	<?php else:?>
	<div class="item">
		<div class="lt">包装费:</div>
		<div class="rt">X1 ￥<?php echo $packingFee?number_format($packingFee,2):'免费';?></div>
		<div class="clear"></div>
	</div>
	<div class="item">
		<div class="lt">配送费:</div>
		<div class="rt">X1 ￥<?php echo $freightFee?number_format($freightFee):'免费';?></div>
		<div class="clear"></div>
	</div>
	<?php endif;?>
	
	<div class="item">
		<div class="lt">总计:</div><div class="rt">￥<?php echo $order['reality_total'];?></div>
		<div class="clear"></div>
	</div>
	<?php if($order['reality_total'] - $order['should_total'] - $order['yue_total']):?>
	
	<?php if($order['cupon_branduser_lid'] > 0):?>
	<div class="item">
		<div class="lt">会员减免</div><div class="rt">￥<?php echo number_format($order['reality_total'] - $order['should_total'] - $order['yue_total'] - $order['cupon_money'],2);?></div>
		<div class="clear"></div>
	</div>
	<div class="item">
		<div class="lt">现金券减免</div><div class="rt">￥<?php echo number_format($order['cupon_money'],2);?></div>
		<div class="clear"></div>
	</div>
	<?php else:?>
	<div class="item">
		<div class="lt">会员减免</div><div class="rt">￥<?php echo number_format($order['reality_total'] - $order['should_total'] - $order['yue_total'],2);?></div>
		<div class="clear"></div>
	</div>
	<?php endif;?>
	
	<?php endif;?>
	<div class="ht1"></div>
	<?php if($order['yue_total'] > 0):?>
	<div class="item" >
		<div class="lt">余额支付:</div><div class="rt">￥<span style="color:#FF5151"><?php echo number_format($order['yue_total'],2);?></span></div>
		<div class="clear"></div>
	</div>
	<?php endif;?>
	<div class="item">
		<div class="lt">微信支付:</div><div class="rt">￥<span style="color:#FF5151"><?php echo number_format($order['should_total'],2);?></span></div>
		<div class="clear"></div>
	</div>
	<div class="item">
		<div class="lt">合计:</div><div class="rt">￥<span style="color:#FF5151"><?php echo number_format($order['should_total'] + $order['yue_total'],2);?></span></div>
		<div class="clear"></div>
	</div>
</div>

<?php if($model['order_status']< 3):?>
<div class="close_window specialbttn bttn_orange" order-id="<?php echo $order['lid'];?>" style="font-size:1.2em;">取消订单</div>
<?php endif;?>
 <!--BEGIN dialog1-->
<div class="weui_dialog_confirm" id="dialog1" style="display: none;">
    <div class="weui_mask" style="z-index:1005;"></div>
    <div class="weui_dialog" style="z-index:1006;">
        <div class="weui_dialog_hd"><strong class="weui_dialog_title">提示</strong></div>
        <div class="weui_dialog_bd" style="text-align:center;">是否要取消订单？</div>
        <div class="weui_dialog_ft">
            <a href="javascript:;" class="weui_btn_dialog default">取消</a>
            <a href="javascript:;" class="weui_btn_dialog primary">确定</a>
        </div>
    </div>
</div>
<!--END dialog1-->
 <!--BEGIN dialog2-->
<div class="weui_dialog_alert" id="dialog2" style="display: none;">
    <div class="weui_mask" style="z-index:1005;"></div>
    <div class="weui_dialog" style="z-index:1006;">
        <div class="weui_dialog_hd"><strong class="weui_dialog_title">提示</strong></div>
        <div class="weui_dialog_bd">订单取消失败</div>
        <div class="weui_dialog_ft">
            <a href="javascript:;" class="weui_btn_dialog primary">确定</a>
        </div>
    </div>
</div>
<!--END dialog2-->
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
	$('.payOrder').click(function(){
		var status = $(this).attr('status');
		if(parseInt(status) < 2){
			location.href = '<?php echo $this->createUrl('/mall/order',array('companyId'=>$this->companyId,'orderId'=>$order['lid']));?>';
		}else{
			location.href = '<?php echo $this->createUrl('/mall/payOrder',array('companyId'=>$this->companyId,'orderId'=>$order['lid'],'paytype'=>2));?>';
		}
	});
	var orderId = 0;
	$('.close_window').click(function(){
		orderId = $(this).attr('order-id');
		$('#dialog1').show();
	});
	$('#dialog1 .primary').click(function(){
		$.ajax({
			url:'<?php echo $this->createUrl('/user/ajaxCancelOrder',array('companyId'=>$this->companyId));?>',
			data:{orderId:orderId},
			success:function(data){
				if(parseInt(data)){
					history.go(0);
				}else{
					$('#dialog1').hide();
					$('#dialog2').show();
				}
			}
		});
	});
	$('#dialog1 .default').click(function(){
		$('#dialog1').hide();
	});	
	$('#dialog2 .primary').click(function(){
		$('#dialog2').hide();
	});	
	$('.share').click(function(){
		$('.popshare').show();
	});
	$('.popshare').click(function(){
		$(this).hide();
	});
})
</script>