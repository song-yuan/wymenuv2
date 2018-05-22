<?php
// $dpid = 438;
// // $size = Yii::app()->redis->lSize('redis-order-data-'.(int)$dpid);
// // var_dump($size);
// $ckey = 'order_online_total_operation_'.(int)$dpid;
// // $isActive = Yii::app()->redis->get($ckey);
// // var_dump($isActive);
// $res = Yii::app()->redis->set($ckey,false);
// var_dump($res);
// exit;
$sql = 'select dpid from nb_company where type=1 and delete_flag = 0';
$dpids = Yii::app()->db->createCommand($sql)->queryColumn();
foreach ($dpids as $dpid){
	$orderSize = Yii::app()->redis->lSize('redis-third-platform-'.(int)$dpid);
	$size = Yii::app()->redis->lSize('redis-order-data-'.(int)$dpid);
	$ckey = 'order_online_total_operation_'.(int)$dpid;
	$isActive = Yii::app()->redis->get($ckey);
	var_dump($isActive);
	var_dump($dpid.'-'.$size.'-'.$orderSize);
	echo "<br/>";

}
?>

