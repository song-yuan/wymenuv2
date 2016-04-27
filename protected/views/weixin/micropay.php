<?php
$orderId = $order['lid'].'-'.$order['dpid'];
if(isset($auth_code) && $auth_code != ""){
	$input = new WxPayMicroPay();
	$input->SetAuth_code($auth_code);
	$input->SetBody("刷卡支付");
	$input->SetTotal_fee($order['should_total']*100);
	$input->SetOut_trade_no($orderId);
	$microPay = new MicroPay();
	printf_info($microPay->pay($input));
}
?>

