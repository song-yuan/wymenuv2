<?php
	$baseUrl = Yii::app()->baseUrl;
	$this->setPageTitle('支付订单');
	$payYue = 0.00;
	if(!empty($orderPays)){
		foreach($orderPays as $orderPay){
			if($orderPay['paytype']==10){
				$payYue = $orderPay['pay_amount']; 
			}
		}
	}
	
	//子订单号
	$se = new Sequence("order_subno");
	$orderSubNo = $se->nextval();
	
	$notifyUrl = 'http://'.$_SERVER['HTTP_HOST'].$this->createUrl('/weixin/notify');
	$orderId = $order['lid'].'-'.$order['dpid'].'-'.$orderSubNo;
	//①、获取用户openid
	$canpWxpay = true;
	try{
		$tools = new JsApiPay();
		$openId = WxBrandUser::openId($userId,$this->companyId);
		//②、统一下单
		$input = new WxPayUnifiedOrder();
		$input->SetBody("点餐订单");
		$input->SetAttach("0");
		$input->SetOut_trade_no($orderId);
		$input->SetTotal_fee($order['should_total']*100);
		$input->SetTime_start(date("YmdHis"));
		$input->SetTime_expire(date("YmdHis", time() + 600));
		$input->SetGoods_tag("点餐订单");
		$input->SetNotify_url($notifyUrl);
		$input->SetTrade_type("JSAPI");
		$input->SetOpenid($openId);
		
		$orderInfo = WxPayApi::unifiedOrder($input);
		
		$jsApiParameters = $tools->GetJsApiParameters($orderInfo);
	}catch(Exception $e){
		$canpWxpay = false;
		$jsApiParameters = '';
	}
	var_dump($jsApiParameters);
	
?>

<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl;?>/css/mall/style.css">
<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl;?>/css/mall/order.css">
<script type="text/javascript" src="<?php echo $baseUrl;?>/js/mall/Adaptive.js"></script>
<script type="text/javascript" src="<?php echo $baseUrl;?>/js/mall/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="<?php echo $baseUrl.'/js/layer/layer.js';?>"></script>

<div class="order-title">支付订单</div>
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
	<?php if($order['order_type']==1||$order['order_type']==3):?>
	<div class="item">
		<div class="lt">餐位费:</div><div class="rt">￥<?php echo $seatingFee?number_format($seatingFee,2):'免费';?></div>
		<div class="clear"></div>
	</div>
	<?php else:?>
	<div class="item">
		<div class="lt">包装费:</div><div class="rt">￥<?php echo $packingFee?number_format($packingFee,2):'免费';?></div>
		<div class="clear"></div>
	</div>
	<div class="item">
		<div class="lt">配送费:</div><div class="rt">￥<?php echo $freightFee?number_format($freightFee):'免费';?></div>
		<div class="clear"></div>
	</div>
	<?php endif;?>
	<div class="item">
		<div class="lt">总计:</div><div class="rt">￥<?php echo $order['reality_total'];?></div>
		<div class="clear"></div>
	</div>
	
	<?php if($order['reality_total'] - $order['should_total'] - $payYue):?>
	<?php if($order['cupon_branduser_lid'] > 0):?>
	<?php if(($order['reality_total'] - $order['should_total'] - $payYue - $order['cupon_money'])>0):?>
	<div class="item">
		<div class="lt">会员减免</div><div class="rt">￥<?php echo number_format($order['reality_total'] - $order['should_total'] - $payYue - $order['cupon_money'],2);?></div>
		<div class="clear"></div>
	</div>
	<?php endif;?>
	<div class="item">
		<div class="lt">现金券减免</div><div class="rt">￥<?php echo $order['cupon_money'];?></div>
		<div class="clear"></div>
	</div>
	<?php else:?>
	<div class="item">
		<div class="lt">会员减免</div><div class="rt">￥<?php echo number_format($order['reality_total'] - $order['should_total'] - $payYue,2);?></div>
		<div class="clear"></div>
	</div>
	<?php endif;?>
	<?php endif;?>
	
	<?php if($payYue > 0):?>
	<div class="item" >
		<div class="lt">余额支付:</div><div class="rt">￥<span style="color:#FF5151"><?php echo $payYue;?></span></div>
		<div class="clear"></div>
	</div>
	<?php endif;?>
	<div class="item">
		<div class="lt">合计</div><div class="rt">￥<span style="color:#FF5151"><?php echo number_format($order['should_total'],2);?></span></div>
		<div class="clear"></div>
	</div>
</div>


<footer>
    <div class="ft-lt">
        <p>￥<span id="total" class="total" should-total="<?php echo $order['should_total'];?>"><?php echo number_format($order['should_total'],2);?></span></p>
    </div>
    <div class="ft-rt">
        <p><a href="javascript:;" id="payOrder">付款</a></p>
    </div>
    <div class="clear"></div>
</footer>

<script type="text/javascript">
	<?php if($canpWxpay):?>
	//调用微信JS api 支付
	function jsApiCall()
	{
		WeixinJSBridge.invoke(
			'getBrandWCPayRequest',
			<?php echo $jsApiParameters; ?>,
			function(res){
				 if(res.err_msg == "get_brand_wcpay_request:ok" ) {
				 	// 使用以上方式判断前端返回,微信团队郑重提示：res.err_msg将在用户支付成功后返回    ok，但并不保证它绝对可靠。 
				 	layer.msg('支付成功!');
				 	location.href = '<?php echo $this->createUrl('/user/orderInfo',array('companyId'=>$this->companyId,'orderId'=>$order['lid']));?>';
				 }else{
				 	//支付失败或取消支付
				 	
				 }     
			}
		);
	}
	<?php endif;?>
	function callpay()
	{
		<?php if(!$canpWxpay):?>
		layer.msg('使用其他方式付款');
		return;
		<?php endif;?>
		if (typeof WeixinJSBridge == "undefined"){
		    if( document.addEventListener ){
		        document.addEventListener('WeixinJSBridgeReady', jsApiCall, false);
		    }else if (document.attachEvent){
		        document.attachEvent('WeixinJSBridgeReady', jsApiCall); 
		        document.attachEvent('onWeixinJSBridgeReady', jsApiCall);
		    }
		}else{
		    jsApiCall();
		}
	}
	$(document).ready(function(){
		$('#payOrder').click(function(){
			callpay();
		});
	})
</script>
