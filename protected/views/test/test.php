<?php 
$key = 'redis-third-platform-28';
$orderSize = Yii::app()->redis->lSize($key);
var_dump($orderSize);
?>


