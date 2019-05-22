<?php 
// 获取云端失败数据
$orderData = Yii::app()->redis->get('order-183-2019-05-22 14:42:09-20190522144209003');
$orderDataArr = json_decode($orderData,true);
$result = DataSyncOperation::operateOrder($orderDataArr);
var_dump($result);
?>
