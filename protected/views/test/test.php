<?php 
	$key = 'redis_key';
	$cache = Yii::app()->redis->get($key);
	if($cache===false){
		$cache = Yii::app()->redis->set($key,'6666');
	}
	$cache = Yii::app()->redis->get($key);
	var_dump($cache);
?>


