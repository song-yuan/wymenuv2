<?php 
	$key = 'redis_key';
	$cache = Yii::app()->redis->delete($key);
	var_dump($cache);
	$key = 'redis_key_arr';
	Yii::app()->redis->lPush($key,'6666');
	Yii::app()->redis->lPush($key,'7777');
	Yii::app()->redis->lPush($key,'8888');
	$cache = Yii::app()->redis->get($key);
	var_dump($cache);
?>


