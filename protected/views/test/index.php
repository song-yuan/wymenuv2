<?php
$dpid = 28;
$keyOrder = 'redis-third-platform-'.(int)$dpid;
$orderStr = Yii::app()->redis->rPop($keyOrder);
var_dump($orderStr);
?>

