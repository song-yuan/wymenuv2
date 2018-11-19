<?php
	$baseUrl = Yii::app()->baseUrl;
	$this->setPageTitle('订单详情');
	$orderTatsePrice = 0.00;
	$payYue = 0.00;
	$payCupon = 0.00;
	$payPoints = 0.00;
	if(!empty($orderPays)){
		foreach($orderPays as $orderPay){
			if($orderPay['paytype']==10){
				$payYue = $orderPay['pay_amount']; 
			}elseif($orderPay['paytype']==9){
				$payCupon = $orderPay['pay_amount']; 
			}elseif($orderPay['paytype']==8){
				$payPoints = $orderPay['pay_amount']; 
			}
		}
	}
	$randNum = Helper::randNum(3);
	$payPrice = number_format($order['should_total'] - $payYue - $payCupon - $payPoints,2); // 最终支付价格
	$orderId = $order['lid'].'-'.$order['dpid'].'-'.$randNum;
	
	$canpWxpay = true;
	$compaychannel = WxCompany::getpaychannel($this->companyId);
	$payChannel = $compaychannel?$compaychannel['pay_channel']:0;
	$jsApiParameters = '';
	if($payChannel==1){
		$notifyUrl = 'http://'.$_SERVER['HTTP_HOST'].$this->createUrl('/weixin/notify');
		$returnUrl = 'http://'.$_SERVER['HTTP_HOST'].$this->createUrl('/user/orderInfo',array('companyId'=>$this->companyId,'orderId'=>$order['lid'],'orderDpid'=>$order['dpid']));
		//①、获取用户openid
		try{
				$tools = new JsApiPay();
				$openId = WxBrandUser::openId($userId,$this->companyId);
				$account = WxAccount::get($this->companyId);
				//②、统一下单
				$input = new WxPayUnifiedOrder();
				$input->SetBody($company['company_name']."-微信点餐订单");
				$input->SetAttach("0");
				$input->SetOut_trade_no($orderId);
				$input->SetTotal_fee($payPrice*100);
				$input->SetTime_start(date("YmdHis"));
				$input->SetTime_expire(date("YmdHis", time() + 600));
				$input->SetGoods_tag($company['company_name']."-微信点餐订单");
				$input->SetNotify_url($notifyUrl);
				$input->SetTrade_type("JSAPI");
				if($account['multi_customer_service_status'] == 1){
					$input->SetSubOpenid($openId);
				}else{
					$input->SetOpenid($openId);
				}
				$orderInfo = WxPayApi::unifiedOrder($input);
				
				$jsApiParameters = $tools->GetJsApiParameters($orderInfo);
		}catch(Exception $e){
			$canpWxpay = false;
			$jsApiParameters = $e->getMessage();
		}
	}elseif($payChannel==2){
		$notifyUrl = 'http://'.$_SERVER['HTTP_HOST'].$this->createUrl('/sqbpay/wappayresult');
		$returnUrl = 'http://'.$_SERVER['HTTP_HOST'].$this->createUrl('/sqbpay/wappayreturn');
		$reflect = json_encode(array('companyId'=>$this->companyId,'dpid'=>$order['dpid']));
		$data = array(
				'companyId'=>$this->companyId,
				'dpid'=>$order['dpid'],
				'client_sn'=>$orderId,
				'total_amount'=>$payPrice,
				'subject'=>$company['company_name']."-微信点餐订单",
				'payway'=>3,
				'pay_channel'=>$payChannel,
				'operator'=>'微信会员-'.$user['lid'],
				'reflect'=>$reflect,
				'notify_url'=>$notifyUrl,
				'return_url'=>$returnUrl,
		);
		$sqbpayUrl = $this->createUrl('/mall/sqbPayOrder',$data);
	}elseif($payChannel==3){
		// 美团线上支付 新接口
		$notifyUrl = 'http://'.$_SERVER['HTTP_HOST'].$this->createUrl('/mtpay/mtwappayresult');
		$returnUrl = 'http://'.$_SERVER['HTTP_HOST'].$this->createUrl('/mtpay/mtpayreturn',array('companyId'=>$this->companyId,'orderId'=>$order['lid'],'orderDpid'=>$order['dpid']));
		$data = array(
				'companyId'=>$this->companyId,
				'dpid'=>$order['dpid'],
				'outTradeNo'=>$orderId,
				'totalFee'=>$payPrice*100,
				'subject'=>'goods',
				'body'=>'goods',
				'channel'=>'wx_scan_pay',
				'expireMinutes'=>'5',
				'notifyUrl'=>$notifyUrl,
				'return_url'=>$returnUrl
		);
		$mtpayUrl = $this->createUrl('/mall/mtPayOrder',$data);
	}
?>

<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl;?>/css/mall/style.css">
<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl;?>/css/mall/order.css">
<script type="text/javascript" src="<?php echo $baseUrl;?>/js/mall/Adaptive.js"></script>

<div class="order-title">等待付款</div>
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
	<?php if($order['order_type']==1||$order['order_type']==3):?>
	<div class="item">
		<div class="lt">餐位费:</div><div class="rt">￥<?php echo $seatingFee?number_format($seatingFee,2):'0.00';?></div>
		<div class="clear"></div>
	</div>
	<?php elseif($order['order_type']==2):?>
	<div class="item">
		<div class="lt">包装费:</div><div class="rt">￥<?php echo $packingFee?number_format($packingFee,2):'0.00';?></div>
		<div class="clear"></div>
	</div>
	<div class="item">
		<div class="lt">配送费:</div><div class="rt">￥<?php echo $freightFee?number_format($freightFee):'0.00';?></div>
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
		<div class="lt">优惠</div><div class="rt">-￥<?php echo number_format($order['reality_total'] - $order['should_total'],2);?></div>
		<div class="clear"></div>
	</div>
	<?php endif;?>
	<?php if($payCupon>0):?>
	<div class="item">
		<div class="lt">现金券支付</div><div class="rt">-￥<?php echo $payCupon;?></div>
		<div class="clear"></div>
	</div>
	<?php endif;?>
	<?php if($payYue > 0):?>
	<div class="item" >
		<div class="lt">储值支付:</div><div class="rt">-￥<span style="color:#FF5151"><?php echo $payYue;?></span></div>
		<div class="clear"></div>
	</div>
	<?php endif;?>
	
	<div class="item">
		<div class="lt">实付:</div><div class="rt">￥<span style="color:#FF5151"><?php echo $payPrice;?></span></div>
		<div class="clear"></div>
	</div>
</div>


<footer>
    <div class="ft-lt">
        <p>￥<span id="total" class="total"><?php echo $payPrice;?></span></p>
    </div>
    <div class="ft-rt" id="payOrder">
        <p><a href="javascript:;">付款</a></p>
    </div>
    <div class="clear"></div>
</footer>
<script type="text/javascript">
	//调用微信JS api 支付
	function jsApiCall()
	{
		<?php if($canpWxpay&&$payChannel==1):?>
		WeixinJSBridge.invoke(
			'getBrandWCPayRequest',
			<?php echo $jsApiParameters; ?>,
			function(res){
				 if(res.err_msg == "get_brand_wcpay_request:ok" ) {
				 	// 使用以上方式判断前端返回,微信团队郑重提示：res.err_msg将在用户支付成功后返回    ok，但并不保证它绝对可靠。 
				 	layer.msg('支付成功!');
				 	location.href = '<?php echo $returnUrl;?>';
				 }else{
				 	//支付失败或取消支付
				 	 layer.closeAll('loading');
					 layer.msg('支付失败,请重新支付!');
				 }     
			}
		);
		<?php endif;?>
	}
	function callpay()
	{
		<?php if(!$canpWxpay):?>
		layer.msg('<?php echo $jsApiParameters;?>');
		return;
		<?php endif;?>
		<?php if ($payChannel==1):?>
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
		<?php elseif($payChannel==2):?>
		// 收钱吧线上支付
		location.href = '<?php echo $sqbpayUrl;?>';
		<?php elseif($payChannel==3):?>
		// 美团线上支付
		location.href = '<?php echo $mtpayUrl;?>';
		<?php else:?>
		layer.msg('无支付信息,请联系客服!');
		<?php endif;?>
	}
	$(document).ready(function(){
		$('#payOrder').click(function(){
			layer.load(2);
			callpay();
		});
	})
</script>
