<?php
	$key = 'olist';
	Yii::app()->redis->lPush($key,'abc');
	$str = Yii::app()->redis->rPop($key);
	var_dump($str);
	$len = Yii::app()->redis->lLen($key);
	var_dump($len);
?>