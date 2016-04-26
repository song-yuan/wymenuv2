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
//	$se = new Sequence("order_subno");
//	$orderSubNo = $se->nextval();
	
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
		echo $url2;exit;
		$code=new QRCode($url2);
		$code->create();
	}catch(Exception $e){
		$canpWxpay = false;
		$jsApiParameters = $e->getMessage();
	}
	
?>

