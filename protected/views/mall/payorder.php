<?php
	$baseUrl = Yii::app()->baseUrl;
	$this->setPageTitle('订单');
	
	$notifyUrl = 'http://'.$_SERVER['HTTP_HOST'].$this->createUrl('/weixin/notify');
	$orderId = $order['lid'].'-'.$order['dpid'];
	//①、获取用户openid
	try{
		$tools = new JsApiPay();
		$openId = WxBrandUser::openId($userId,$this->companyId);
		//②、统一下单
		$input = new WxPayUnifiedOrder();
		$input->SetBody("点餐订单");
		$input->SetAttach("微信支付");
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
		$jsApiParameters = array();
	}
	
?>

<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl;?>/css/mall/style.css">
<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl;?>/css/mall/order.css">
<script type="text/javascript" src="<?php echo $baseUrl;?>/js/mall/Adaptive.js"></script>
<script type="text/javascript" src="<?php echo $baseUrl;?>/js/mall/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="<?php echo $baseUrl.'/js/layer/layer.js';?>"></script>

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
		<div class="lt">优惠金额</div><div class="rt">￥<?php echo number_format($order['reality_total'] - $order['should_total'],2);?></div>
		<div class="clear"></div>
	</div>
	<?php endif;?>
</div>

<div class="order-paytype">
<div class="select-type">选择支付方式</div>
<div class="paytype">
	<div class="item on" paytype="1">微信支付</div>
	<div class="item" paytype="2" remain-money="<?php echo $user['remain_money'];?>" style="border:none;">余额支付<span style="color:#FF5151"><?php echo $user['remain_money'];?></span></div>
</div>
</div>

<footer>
    <div class="ft-lt">
        <p>￥<span id="total" class="total" should-total="<?php echo $order['should_total'];?>"><?php echo $order['should_total'];?></span></p>
    </div>
    <div class="ft-rt">
        <p><a href="javascript:;" id="payOrder">付款</a></p>
    </div>
    <div class="clear"></div>
</footer>

<script type="text/javascript">
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

	function callpay()
	{
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
		$('.paytype .item').click(function(){
			var paytype = $(this).attr('paytype');
			if(parseInt(paytype)==2){
				var remainMoney = $(this).attr('remain-money');
				var shouldTotal = $('#total').attr('should-total');
				if(remainMoney < shouldTotal){
					layer.msg('余额不足!');
					return;
				}
			}
			$('.item').removeClass('on');
			$(this).addClass('on');
		});
		$('#payOrder').click(function(){
			var paytype = $('.paytype .on').attr('paytype');
			if(parseInt(paytype)==1){
				callpay();
			}else{
				location.href = '<?php echo $this->createUrl('/mall/payOrderByYue',array('companyId'=>$this->companyId,'orderId'=>$order['lid']));?>';
			}
		});
	})
</script>
