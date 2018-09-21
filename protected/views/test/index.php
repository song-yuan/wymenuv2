<?php
$keys = Yii::app()->redis->keys('*');
var_dump($keys);
?>

