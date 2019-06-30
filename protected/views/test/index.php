<?php 
$appid = 4073;
$apppoicode = 't_9uCwcElNGj';
$orderId = '';
$reason = '售完';
$reasonCode = 1001;
$res = MtOpenOrder::cancel($appid, $apppoicode, $orderId, $reason, $reasonCode);
?>