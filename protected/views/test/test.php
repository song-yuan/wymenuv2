<?php 
function getProduct($dpid){
	$product = array();
	$sql = 'select category_id,phs_code,product_name from nb_product where dpid='.$dpid.' and delete_flag=0';
	$sql .= ' union select category_id,pshs_code as phs_code,set_name as product_name from nb_product_set where dpid='.$dpid.' and delete_flag=0';
	$products = Yii::app()->db->createCommand($sql)->queryAll();
	foreach ($products as $p){
		$product[$p['phs_code']] = $p;
	}
	return $product;
}

WxCupon::getWxSentMoneyCupon(27, $user['lid'], $user['openid'], $user['weixin_group'], $totalFee/100);
?>
