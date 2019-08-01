<?php 
function cancel(){
	$appid = 4190;
	$apppoicode = 't_ADV1kPfiDx';
	$orderId = '26964220373755333';
	$reason = '售完';
	$reasonCode = 1001;
	$res = MtOpenOrder::cancel($appid, $apppoicode, $orderId, $reason, $reasonCode);
	var_dump($res);
}
function agree(){
	$appid = 4190;
	$apppoicode = 't_ADV1kPfiDx';
	$orderId = '26959143173839162';
	$reason = '售完';
	$reasonCode = 1001;
	$res = MtOpenOrder::agree($appid, $apppoicode, $orderId, $reason);
	var_dump($res);
}
function reject(){
	$appid = 4190;
	$apppoicode = 't_ADV1kPfiDx';
	$orderId = '26959140812816829';
	$reason = '售完';
	$reasonCode = 1001;
	$res = MtOpenOrder::reject($appid, $apppoicode, $orderId, $reason);
	var_dump($res);
}
function pullPhoneNumber(){
	$appid = 4190;
	$apppoicode = 't_ADV1kPfiDx';
	$res = MtOpenOrder::pullPhoneNumber($appid, $apppoicode);
	var_dump($res);
}
cancel();
exit;
?>