<?php
	$baseUrl = Yii::app()->baseUrl;
	$this->setPageTitle('订单详情');
	
	$orderTatsePrice = 0.00;
?>

<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl;?>/css/mall/reset.css">
<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl;?>/css/mall/user.css">
<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl;?>/css/mall/order.css">


<?php if($order['order_type']==1):?>
<div class="order-title">桌号: <?php if($siteType){echo $siteType['name'];}?><?php echo $site['serial'];?></div>
<?php else:?>
<div class="order-title">取餐号: <?php echo $order['callno'];?></div>
<?php endif;?>
<div class="order-site">
	<div class="lt">订单状态</div>
	<div class="rt">
	<?php 
		if($order['order_status']==1){
			echo '<span style="color:#ff9933;font-size:18px;">已下单</span>';
		}elseif(1 < $order['order_status']&&$order['order_status'] < 3){
			echo '<span style="color:#f5342f;font-size:18px;">待付款</span>';
		}elseif($order['order_status'] == 3){
			echo '<span style="color:#ff9933;font-size:18px;">已支付</span>';
		}elseif ($order['order_status']==7){
			echo '<span style="color:#999999;font-size:18px;">已取消</span>';
		}else{
			echo '<span style="color:#ff9933;font-size:18px;">已完成</span>';
		}
	?>
	</div>
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
	
	<?php if($order['appointment_time']!=$order['create_at']):?>
	<span>取餐时间: <?php echo $order['appointment_time'];?></span>
	<?php endif;?>
	
	<?php elseif($order['order_type']==2):?>
	<span>类型: 外卖</span>
	
	<?php if($order['appointment_time']!=$order['create_at']):?>
	<span>期望时间: <?php echo $order['appointment_time'];?></span>
	<?php endif;?>
	<?php elseif($order['order_type']==1):?>
	<span>类型: 餐桌</span>
	<?php endif;?>
	<span>交易序号: <?php echo $order['account_no'];?></span>
	<span>下单时间: <?php echo $order['create_at'];?></span>
</div>
<div class="order-info">
	<?php foreach($orderProducts as $product):?>
	<div class="item">
		<div class="lt"><?php echo $product['product_name'];?><?php if($product['is_retreat']):?><span style="color:red">(已退)</span><?php endif;?></div><div class="rt">x<?php echo $product['amount'];?> ￥<?php echo number_format($product['price'],2);?></div>
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
		<div class="rt">x1 ￥<?php echo $seatingFee?number_format($seatingFee,2):'0.00';?></div>
		<div class="clear"></div>
	</div>
	<?php elseif($order['order_type']==2):?>
	<div class="item">
		<div class="lt">包装费:</div>
		<div class="rt">x1 ￥<?php echo $packingFee?number_format($packingFee,2):'0.00';?></div>
		<div class="clear"></div>
	</div>
	<div class="item">
		<div class="lt">配送费:</div>
		<div class="rt">x1 ￥<?php echo $freightFee?number_format($freightFee,2):'0.00';?></div>
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
		<?php elseif($pay['paytype']==7):?>
		<div class="item">
			<div class="lt">储值支付:</div><div class="rt">￥<?php echo $pay['pay_amount'];?></div>
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

<?php if($order['remark']):?>
<div class="order-site">
	<span>备注:<?php echo $order['remark'];?></span>
</div>
<?php endif;?>

<div style="height:4em;"></div>
<?php if($order['order_status']<3 && $order['order_type']!=1):?>
<div class="bttnbar">
	<button class="cancelOrder bttn_large bttn_black2" order-id="<?php echo $order['lid'];?>" order-dpid="<?php echo $order['dpid'];?>" style="margin-right:1.2em;">取消订单</button>
	<button class="payOrder bttn_large bttn_red">去支付</button>
</div>
<?php endif;?>
<div id="dialogs">
	<!--BEGIN dialog1-->
	<div class="js_dialog" id="dialog1" style="display: none;">
	    <div class="weui-mask"></div>
	    <div class="weui-dialog">
	         <div class="weui-dialog__hd"><strong class="weui-dialog__title">提示</strong></div>
	         <div class="weui-dialog__bd">是否要取消订单？</div>
	         <div class="weui-dialog__ft">
	         	<a href="javascript:;" class="weui-dialog__btn weui-dialog__btn_default">取消</a>
	         	<a href="javascript:;" class="weui-dialog__btn weui-dialog__btn_primary">确定</a>
	         </div>
	     </div>
	</div>
	<!--END dialog1-->
	<!--BEGIN dialog2-->
	<div class="js_dialog" id="dialog2" style="display: none;">
     	<div class="weui-mask"></div>
        <div class="weui-dialog">
                <div class="weui-dialog__bd">订单取消失败,请重新操作</div>
                <div class="weui-dialog__ft">
                    <a href="javascript:;" class="weui-dialog__btn weui-dialog__btn_primary">确定</a>
                </div>
         </div>
    </div>       
	<!--END dialog2-->
	<!--BEGIN dialog3-->
	<div class="js_dialog" id="dialog3" style="display: none;">
	    <div class="weui-mask"></div>
	    <div class="weui-dialog">
	         <div class="weui-dialog__hd"><strong class="weui-dialog__title">提示</strong></div>
	         <div class="weui-dialog__bd">如果该笔订单已经支付成功,订单未及时更新,请不要重复支付!!!!!!</div>
	         <div class="weui-dialog__ft">
	         	<a href="javascript:;" class="weui-dialog__btn weui-dialog__btn_default">刷新</a>
	         	<a href="javascript:;" class="weui-dialog__btn weui-dialog__btn_primary">去支付</a>
	         </div>
	     </div>
	</div>
	<!--END dialog3-->
</div>
<!-- loading toast -->
<div id="loadingToast" style="display:none;">
	<div class="weui-mask_transparent"></div>
 	<div class="weui-toast">
    	<i class="weui-loading weui-icon_toast"></i>
    	<p class="weui-toast__content">订单取消中</p>
    </div>
</div>                   
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
		$('#dialog3').fadeIn(200);
	});
	$('.cancelOrder').click(function(){
		orderId = $(this).attr('order-id');
		orderDpid = $(this).attr('order-dpid');
		$('#dialog1').fadeIn(200);
	});
	$('#dialog1 .weui-dialog__btn_primary').click(function(){
		$('#dialog1').fadeOut(200);
		$('#loadingToast').fadeIn(200);
		$.ajax({
			url:'<?php echo $this->createUrl('/user/ajaxCancelOrder',array('companyId'=>$this->companyId));?>',
			data:{orderId:orderId,orderDpid:orderDpid},
			success:function(data){
				layer.closeAll('loading');
				if(parseInt(data)){
					history.go(0);
				}else{
					$('#dialog2').fadeIn(200);
				}
			}
		});
	});
	$('#dialog3 .weui-dialog__btn_primary').click(function(){
		location.href = '<?php echo $this->createUrl('/mall/payOrder',array('companyId'=>$order['dpid'],'orderId'=>$order['lid']));?>';
	});				
	$('#dialogs').on('click', '.weui-dialog__btn_default', function(){
        $(this).parents('.js_dialog').fadeOut(200);
    });	
	$('#dialog3 .weui-dialog__btn_default').click(function(){
		history.go(0);
	});	
	$('.share').click(function(){
		$('.popshare').show();
	});
	$('.popshare').click(function(){
		$(this).hide();
	});
})
</script>