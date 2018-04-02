<?php
function eleme_order($data,$dpid){
	$data = urldecode($data);
	$obj = json_decode($data);
	$type = $obj->type;
	$shopId = $obj->shopId;
	$message = $obj->message;
	$order = json_decode($message);
	$orderId = $order->id;
	if($order!=false){
		$res = Elm::dealOrder($order,$dpid,4);
		return $res;
	}else{
		return true;
	}
}


function meituan_order($data){
	$resArr = MtUnit::dealData($data);
	$ePoiId = $resArr['ePoiId'];
	$order = $resArr['order'];
	$order = urldecode($order);
	$result = MtOrder::dealOrder($order,$ePoiId,2);
	$reobj = json_decode($result);
	var_dump($reobj);
}

?>
