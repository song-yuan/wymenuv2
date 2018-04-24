<?php 
$key = 'redis-order-data-27';
$size = Yii::app()->redis->lSize($key);
var_dump($size);
?>


