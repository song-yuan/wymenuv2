<?php
	$baseUrl = Yii::app()->baseUrl;
	$this->setPageTitle('充值中心');
	$notifyUrl = 'http://'.$_SERVER['HTTP_HOST'].$this->createUrl('/weixin/notify');
?>

<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl;?>/css/mall/style.css">
<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl;?>/css/mall/recharge.css">
<div class="section">
	<div class="title">充值金额</div>
	<?php foreach($recharges as $k=>$recharge):?>
	<?php 
		$se = new Sequence("order_subno");
		$orderSubNo = $se->nextval();
		$rechargeId = $recharge['lid'].'-'.$recharge['dpid'].'-'.$orderSubNo;
		//①、获取用户openid
		$canpWxpay = true;
		try{
			$tools = new JsApiPay();
			$openId = WxBrandUser::openId($userId,$this->companyId);
			$account = WxAccount::get($this->companyId);
			//②、统一下单
			$input = new WxPayUnifiedOrder();
			$input->SetBody("点餐订单");
			$input->SetAttach("1");
			$input->SetOut_trade_no($rechargeId);
			$input->SetTotal_fee((int)$recharge['recharge_money']*100);
			$input->SetTime_start(date("YmdHis"));
			$input->SetTime_expire(date("YmdHis", time() + 600));
			$input->SetGoods_tag("充值订单");
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
			$jsApiParameters = '';
		}
	?>
	<div class="item" onclick="callpay<?php echo $k;?>()"><div class="top"><?php echo $recharge['recharge_money'];?>元</div><div class="down"><?php if($recharge['recharge_cashback']):?>返<?php echo $recharge['recharge_cashback'];?>元<?php endif;?> <?php if($recharge['recharge_pointback']):?>返<?php echo $recharge['recharge_pointback'];?>积分<?php endif;?></div></div>
	<script type="text/javascript">
	<?php if($canpWxpay):?>
		//调用微信JS api 支付
		function jsApiCall<?php echo $k;?>()
		{
			WeixinJSBridge.invoke(
				'getBrandWCPayRequest',
				<?php echo $jsApiParameters; ?>,
				function(res){
					 if(res.err_msg == "get_brand_wcpay_request:ok" ) {
					 	// 使用以上方式判断前端返回,微信团队郑重提示：res.err_msg将在用户支付成功后返回    ok，但并不保证它绝对可靠。 
					 	layer.msg('支付成功!');
					 	history.go(-1);
					 }else{
					 	//支付失败或取消支付
					 	layer.msg('取消支付!');
					 }     
				}
			);
		}
	<?php endif;?>
		function callpay<?php echo $k;?>()
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
			    jsApiCall<?php echo $k;?>();
			}
		}
	</script>
	<?php endforeach;?>
	<div class="clear"></div>
	</div>