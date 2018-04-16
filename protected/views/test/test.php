<?php 
	$key = 'redis_key_arr';
	$size = Yii::app()->redis->lSize($key);
	var_dump($size);
	$res = Yii::app()->redis->lPush($key,'aaa');
	var_dump($res);
?>

