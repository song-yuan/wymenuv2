<?php
	$baseUrl = Yii::app()->baseUrl;
	$this->setPageTitle('支付订单');
    
    $notifyUrl = 'http://'.$_SERVER['HTTP_HOST'].$this->createUrl('/weixin/notify');
	$orderId = $order['lid'].'-'.$order['dpid'];
	//①、获取用户openid
	$canpWxpay = true;
	try{
		$tools = new JsApiPay();
		$openId = WxBrandUser::openId($userId,$this->companyId);
		//②、统一下单
		$input = new WxPayUnifiedOrder();
		$input->SetBody("扫码订单");
		$input->SetAttach("0");
		$input->SetOut_trade_no($orderId);
		$input->SetTotal_fee($order['should_total']*100);
		$input->SetTime_start(date("YmdHis"));
		$input->SetTime_expire(date("YmdHis", time() + 600));
		$input->SetGoods_tag("扫码订单");
		$input->SetNotify_url($notifyUrl);
		$input->SetTrade_type("JSAPI");
		if(WxPayConfig::ISSUBMCH){
			$input->SetSubOpenid($openId);
		}else{
			$input->SetOpenid($openId);
		}
        var_dump($input);
		$orderInfo = WxPayApi::unifiedOrder($input);
        var_dump($orderInfo);exit;
		$jsApiParameters = $tools->GetJsApiParameters($orderInfo);
		
	}catch(Exception $e){
		$canpWxpay = false;
		$jsApiParameters = $e->getMessage();
	}
?>

<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl;?>/css/weui.min.css">

<script type="text/javascript" src="<?php echo $baseUrl;?>/js/mall/jquery-1.9.1.min.js"></script>
		
<div class="weui_cells">
    <div class="weui_cell"><div class="weui_cell_bd weui_cell_primary"><p>订单号:</p></div><div class="weui_cell_ft"><?php echo $order['lid'].'-'.$order['dpid'];?></div></div>
    <div class="weui_cell"><div class="weui_cell_bd weui_cell_primary"><p>共计金额:</p></div><div class="weui_cell_ft">￥<?php echo $order['should_total'];?></div></div>
    <div class="weui_cell"><div class="weui_cell_bd weui_cell_primary"><p>下单时间:</p></div><div class="weui_cell_ft"><?php echo $order['create_at'];?></div></div>
</div>
<div class='weui_btn_area'><a id="payOrder" class='weui_btn weui_btn_primary submit' href='javascript:'>确认付款</a></div>
<script>
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
			}<strong></strong>
		);
	}
	<?php endif;?>
    function callpay()
	{
		<?php if(!$canpWxpay):?>
		alert('<?php echo $jsApiParameters;?>');
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
		