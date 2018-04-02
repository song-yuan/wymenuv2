<?php 
	$key = 'redis_key';
	$cache = Yii::app()->redis->get($key);
	var_dump($cache);
?>


