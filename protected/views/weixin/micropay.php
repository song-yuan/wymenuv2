<?php
$orderId = $order['lid'].'-'.$order['dpid'];
if(isset($auth_code) && $auth_code != ""){
	$input = new WxPayMicroPay();
	$input->SetAuth_code($auth_code);
	$input->SetBody("刷卡支付");
	$input->SetTotal_fee($order['should_total']*100);
	$input->SetOut_trade_no($orderId);
	$microPay = new MicroPay();
	$result = $microPay->pay($input);
	if($result){
		$msg = array('status'=>true);
	}else{
		$msg = array('status'=>false);
	}
}
$msg = array('status'=>false);
echo json_encode($msg);
exit;
?>

