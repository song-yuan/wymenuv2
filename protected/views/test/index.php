<?php
	$orderSize = Yii::app()->redis->lLen('redis-order-data-0');
	var_dump($orderSize);
	echo "<br/>";
// 	$ckey = 'order_online_total_operation_0';
// 	$cSize = Yii::app()->redis->set($ckey,0);
// 	var_dump($cSize);
// 	echo '<br>';
	$sql = 'select dpid from nb_company where type=1 and delete_flag = 0';
	$dpids = Yii::app()->db->createCommand($sql)->queryColumn();
	foreach ($dpids as $dpid){
		$orderSize = Yii::app()->redis->lLen('redis-third-platform-'.(int)$dpid);
		var_dump($dpid.'-'.$orderSize);
		echo "<br/>";
	}
?>

