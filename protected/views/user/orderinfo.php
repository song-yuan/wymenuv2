<?php
	$baseUrl = Yii::app()->baseUrl;
	$this->setPageTitle('我的订单');
	
	$orderTatsePrice = 0.00;
?>

<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl;?>/css/mall/reset.css">
<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl;?>/css/mall/user.css">
<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl;?>/css/mall/order.css">
<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl;?>/css/weui.min.css">


<div class="order-title">取餐号: <?php echo substr($order['lid'], -4);?></div>
<div class="order-site">
	<div class="lt"><?php if($order['order_type']==1):?>桌号:<?php if($siteType){echo $siteType['name'];}?><?php echo $site['serial'];?><?php else:?>订单状态<?php endif;?></div>
	<div class="rt"><?php if($order['order_status'] < 3) echo '<button class="payOrder specialbttn bttn_orange" status="'.$order['order_status'].'">待支付</button>';elseif($order['order_status'] == 3) echo '<span style="color:#ff9933;font-size:18px;">已支付</span>';elseif ($order['order_status']==7) echo '<span style="color:#999999;font-size:18px;">已取消</span>';else echo '<span style="color:#ff9933;font-size:18px;">已完成</span>';?></div>
	<div class="clear"></div>
</div>
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
<div class="order-site">
	<?php if($order['order_type']==6):?>
	<span>类型: <?php if($order['takeout_typeid']){ echo '打包';}else{ echo '堂食';}?></span>
	<span>取餐时间: <?php echo $order['appointment_time'];?></span>
	<?php endif;?>
	<span>交易序号: <?php echo $order['account_no'];?></span>
	<span>下单时间: <?php echo $order['create_at'];?></span>
</div>
<div class="order-info">
	<?php foreach($orderProducts as $product):?>
	<div class="item">
		<div class="lt"><?php echo $product['product_name'];?><?php if($product['is_retreat']):?><span style="color:red">(已退)</span><?php endif;?></div><div class="rt">X<?php echo $product['amount'];?> ￥<?php echo number_format($product['price'],2);?></div>
		<div class="clear"></div>
	</div>
		<?php if(isset($product['taste'])&&!empty($product['taste'])):?>
		<div class="taste">口味:
		<?php foreach ($product['taste'] as $taste):?>
		<span> <?php echo $taste['name'].'('.$taste['price'].')';?> </span>
		<?php endforeach;?>
		</div>
		<?php endif;?>
		
		<?php if(isset($product['detail'])&&!empty($product['detail'])):?>
		<div class="taste">
		<?php foreach ($product['detail'] as $detail):?>
		<span> <?php echo $detail['product_name'];?> </span>
		<?php endforeach;?>
		</div>
		<?php endif;?>
		
	<?php endforeach;?>
	<div class="ht1"></div>
		<?php if(!empty($order['taste'])):?>
		<div class="taste">整单口味:
		<?php foreach ($order['taste'] as $otaste): $orderTatsePrice +=$otaste['price'];?>
		<span> <?php echo $otaste['name'].'('.$otaste['price'].')';?> </span>
		<?php endforeach;?>
		</div>
	<?php endif;?>
		<!-- 其他费用 -->
	<?php if($order['order_type']==1||$order['order_type']==3):?>
	<div class="item">
		<div class="lt">餐位费:</div>
		<div class="rt">X1 ￥<?php echo $seatingFee?number_format($seatingFee,2):'0.00';?></div>
		<div class="clear"></div>
	</div>
	<?php elseif($order['order_type']==2):?>
	<div class="item">
		<div class="lt">包装费:</div>
		<div class="rt">X1 ￥<?php echo $packingFee?number_format($packingFee,2):'0.00';?></div>
		<div class="clear"></div>
	</div>
	<div class="item">
		<div class="lt">配送费:</div>
		<div class="rt">X1 ￥<?php echo $freightFee?number_format($freightFee,2):'0.00';?></div>
		<div class="clear"></div>
	</div>
	<?php endif;?>
	<?php if($orderTatsePrice>0):?>
		<div class="item">
			<div class="lt">口味加价:</div><div class="rt">￥<?php echo number_format($orderTatsePrice,2);?></div>
			<div class="clear"></div>
		</div>
	<?php endif;?>
	<div class="item">
		<div class="lt">总计:</div><div class="rt">￥<?php echo $order['reality_total'];?></div>
		<div class="clear"></div>
	</div>
	<?php if($order['reality_total'] > $order['should_total']):?>
	<div class="item">
		<div class="lt">优惠:</div><div class="rt">-￥<?php echo $order['reality_total'] - $order['should_total'];?></div>
		<div class="clear"></div>
	</div>
	<?php endif;?>
	<div class="item">
		<div class="lt">实付:</div><div class="rt">￥<?php echo $order['should_total'];?></div>
		<div class="clear"></div>
	</div>
	<?php if(!empty($orderPays)):?>
	<?php foreach ($orderPays as $pay):?>
		<?php if($pay['paytype']==0):?>
		<div class="item">
			<div class="lt">现金支付:</div><div class="rt">￥<?php echo $pay['pay_amount'];?></div>
			<div class="clear"></div>
		</div>
		<?php elseif($pay['paytype']==1||$pay['paytype']==12||$pay['paytype']==13):?>
		<div class="item">
			<div class="lt">微信支付:</div><div class="rt">￥<?php echo $pay['pay_amount'];?></div>
			<div class="clear"></div>
		</div>
		<?php elseif($pay['paytype']==2):?>
		<div class="item">
			<div class="lt">支付宝支付:</div><div class="rt">￥<?php echo $pay['pay_amount'];?></div>
			<div class="clear"></div>
		</div>
		<?php elseif($pay['paytype']==9):?>
		<div class="item">
			<div class="lt">现金券:</div><div class="rt">￥<?php echo $pay['pay_amount'];?></div>
			<div class="clear"></div>
		</div>
		<?php elseif($pay['paytype']==10):?>
		<div class="item">
			<div class="lt">储值支付:</div><div class="rt">￥<?php echo $pay['pay_amount'];?></div>
			<div class="clear"></div>
		</div>
		<?php endif;?>
	<?php endforeach;?>
	<?php endif;?>
</div>

<?php if($order['order_status']< 3):?>
<div class="close_window specialbttn bttn_orange" order-id="<?php echo $order['lid'];?>" order-dpid="<?php echo $order['dpid'];?>" style="font-size:1.2em;">取消订单</div>
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
    $desc = '点单优惠，尽在物易我要点单';
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
	var orderId = 0;
	var orderDpid = 0;
	$('.payOrder').click(function(){
		var status = $(this).attr('status');
		if(parseInt(status) < 2){
			location.href = '<?php echo $this->createUrl('/mall/order',array('companyId'=>$order['dpid'],'orderId'=>$order['lid']));?>';
		}else{
			location.href = '<?php echo $this->createUrl('/mall/payOrder',array('companyId'=>$order['dpid'],'orderId'=>$order['lid']));?>';
		}
	});
	$('.close_window').click(function(){
		orderId = $(this).attr('order-id');
		orderDpid = $(this).attr('order-dpid');
		$('#dialog1').show();
	});
	$('#dialog1 .primary').click(function(){
		layer.load(2);
		$('#dialog1').hide();
		$.ajax({
			url:'<?php echo $this->createUrl('/user/ajaxCancelOrder',array('companyId'=>$this->companyId));?>',
			data:{orderId:orderId,orderDpid:orderDpid},
			success:function(data){
				layer.closeAll('loading');
				if(parseInt(data)){
					history.go(0);
				}else{
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