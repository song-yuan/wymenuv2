<?php 
function confirm($orderId){
	$appid = 4190;
	$apppoicode = 't_ADV1kPfiDx';
	$res = MtOpenOrder::confirm($appid, $apppoicode, $orderId);
	var_dump($res);
}
function cancel($orderId){
	$appid = 4190;
	$apppoicode = 't_ADV1kPfiDx';
	$reason = '售完';
	$reasonCode = 1001;
	$res = MtOpenOrder::cancel($appid, $apppoicode, $orderId, $reason, $reasonCode);
	var_dump($res);
}
function agree($orderId){
	$appid = 4190;
	$apppoicode = 't_ADV1kPfiDx';
	$reason = '售完';
	$reasonCode = 1001;
	$res = MtOpenOrder::agree($appid, $apppoicode, $orderId, $reason);
	var_dump($res);
}
function reject($orderId){
	$appid = 4190;
	$apppoicode = 't_ADV1kPfiDx';
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
$t = $_GET['t'];
$orderId = $_GET['id'];
if($t=='confirm'){
	confirm($orderId);
}elseif($t=='cancel'){
	cancel($orderId);
}elseif($t=='agree'){
	agree($orderId);
}elseif($t=='reject'){
	reject($orderId);
}elseif($t=='pull'){
	pullPhoneNumber();
}
exit;
?>