<?php 
$data = array('dpid'=>27,'card_id'=>'10027000000837','pro_ids'=>'0000001810,0000001812');
$result = DataSyncOperation::getUserInfo($data);
echo $result;exit;
?>