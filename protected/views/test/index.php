<?php
$keys = Yii::app()->redis->keys('order*');
var_dump($keys);
?>

