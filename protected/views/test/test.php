<?php 
$key = 'order_platform_total_operation_28';
$orderSize = Yii::app()->redis->set($key,false);
var_dump($orderSize);
?>


