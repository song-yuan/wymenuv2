<?php 
$appid = 4073;
$apppoicode = 't_9uCwcElNGj';
$orderId = '26959143364425973';
$reason = '售完';
$reasonCode = 1001;
$res = MtOpenOrder::cancel($appid, $apppoicode, $orderId, $reason, $reasonCode);
var_dump($res);
exit;
?>