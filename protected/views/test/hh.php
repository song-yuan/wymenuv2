<?php 
	$companyId = '0000000027';
	$key = array('productList-'.$companyId.'-2','productList-'.$companyId.'-6');
	Yii::app()->redis->delete($key);
	$key = 'productList-'.$companyId.'-*';
	$keys = Yii::app()->redis->keys($key);
	var_dump($keys);
?>