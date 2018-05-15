<?php 
$dpid = 28;
$size = Yii::app()->redis->lSize('redis-order-data-'.(int)$dpid);
var_dump($size);
$ckey = 'order_online_total_operation_'.(int)$dpid;
$isActive = Yii::app()->redis->get($ckey);
var_dump($isActive);
?>


