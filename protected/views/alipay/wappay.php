<?php 
if(!empty($orderId)){
	
	$outTradeNo = $orderId;
	$subject = "网页支付";
	$totalAmount = 0.01;
	
	$sellerId = $this->alipay_config['seller_id'];
	$body = "购买商品共花费".$totalAmount."元";
	
	// 支付超时，线下扫码交易定义为5分钟
	$timeExpress = "5m";
	
	// 创建请求builder，设置请求参数
	$wapPayRequestBuilder = new AlipayTradeWapPayContentBuilder();
	$wapPayRequestBuilder->setOutTradeNo($outTradeNo);
	$wapPayRequestBuilder->setTotalAmount($totalAmount);
	$wapPayRequestBuilder->setTimeExpress($timeExpress);
	$wapPayRequestBuilder->setSubject($subject);
	$wapPayRequestBuilder->setBody($body);
	$wapPayRequestBuilder->setSellerId($sellerId);
	
	// 调用wapPay方法获取当面付应答
	$wapPay = new AlipayTradeWapService($this->f2fpay_config);
	$wapResult = $wapPay->wapPay($wapPayRequestBuilder,$this->companyId);
// 	var_dump($wapResult);exit;
	echo $wapResult;
	exit;
}
?>