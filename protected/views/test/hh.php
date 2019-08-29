<?php 
	$companyId = '0000000027';
	$key = 'productList-'.$companyId.'-*';
	$keys = Yii::app()->redis->keys($key);
	var_dump($keys);
?>