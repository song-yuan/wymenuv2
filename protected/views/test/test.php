<?php 
$order = '{"id": "a4a3f8c9-b8f1-458e-924b-91e45781414b","result": null,"error": {"code": "BIZ_FAILED_ORDER_STATE","message": "操作失败，订单已确认"}}';
$obj = json_decode($order);
$errmessage = $obj->error->message;
var_dump($errmessage);
var_dump(strpos($errmessage,'订单已确认'));
?>


