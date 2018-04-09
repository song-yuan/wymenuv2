<?php 
	$key = 'redis_key_arr';
	$size = Yii::app()->redis->lSize($key);
	var_dump($size);
?>


