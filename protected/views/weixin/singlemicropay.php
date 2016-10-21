<?php
$now = time();
$rand = rand(100,999);
$orderId = $now.'-'.$dpid.'-'.$rand;

$company = WxCompany::get($dpid);
if(isset($auth_code) && $auth_code != ""){
	$input = new WxPayMicroPay();
	$input->SetAuth_code($auth_code);
	$input->SetBody($company['company_name']);
	$input->SetTotal_fee($should_total*100);
	$input->SetOut_trade_no($orderId);
	
	$microPay = new MicroPay();
	$result = $microPay->pay($input);
	if($result){
		$msg = array('status'=>true);
	}else{
		$msg = array('status'=>false);
	}
}else{
	$msg = array('status'=>false);
}
echo json_encode($msg);
exit;
?>

