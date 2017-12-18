<?php
$keys = Yii::app()->cache->get();
var_dump($keys);exit;
Yii::app()->cache->delete();
?>

