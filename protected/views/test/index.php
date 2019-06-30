<?php 
function cancel(){
	$appid = 4073;
	$apppoicode = 't_9uCwcElNGj';
	$orderId = '26959140812816829';
	$reason = '售完';
	$reasonCode = 1001;
	$res = MtOpenOrder::cancel($appid, $apppoicode, $orderId, $reason, $reasonCode);
	var_dump($res);
}
function agree(){
	$appid = 4073;
	$apppoicode = 't_9uCwcElNGj';
	$orderId = '26959143173839162';
	$reason = '售完';
	$reasonCode = 1001;
	$res = MtOpenOrder::agree($appid, $apppoicode, $orderId, $reason);
	var_dump($res);
}
function reject(){
	$appid = 4073;
	$apppoicode = 't_9uCwcElNGj';
	$orderId = '26959140812816829';
	$reason = '售完';
	$reasonCode = 1001;
	$res = MtOpenOrder::reject($appid, $apppoicode, $orderId, $reason);
	var_dump($res);
}
cancel();
exit;
?>