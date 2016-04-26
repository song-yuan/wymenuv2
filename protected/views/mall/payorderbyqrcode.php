<?php

	$notifyUrl = 'http://'.$_SERVER['HTTP_HOST'].$this->createUrl('/weixin/notify');
	$orderId = $order['lid'].'-'.$order['dpid'];
	//①、获取用户openid
	$canpWxpay = true;
	try{
		//模式二扫码支付
		$notify = new WxPayNativePay();
		$input = new WxPayUnifiedOrder();
		$input->SetBody("点餐订单");
		$input->SetAttach("0");
		$input->SetOut_trade_no($orderId);
		$input->SetTotal_fee($order['should_total']*100);
		$input->SetTime_start(date("YmdHis"));
		$input->SetTime_expire(date("YmdHis", time() + 600));
		$input->SetGoods_tag("点餐订单");
		$input->SetNotify_url($notifyUrl);
		$input->SetTrade_type("NATIVE");
		$input->SetProduct_id("123456789");

		$result = $notify->GetPayUrl($input);
		$url2 = $result["code_url"];
	}catch(Exception $e){
		$canpWxpay = false;
		$jsApiParameters = $e->getMessage();
	}
	echo $url2;
	if($canpWxpay){
		$code=new QRCode($url2);
		$code->create();
	}else{
		echo '生成失败';
	}
	exit;
?>

