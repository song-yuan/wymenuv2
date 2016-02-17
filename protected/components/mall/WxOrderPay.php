<?php 
/**
 * 
 * 
 * 微信支付方式
 * 
 * 
 */
class WxOrderPay
{

	public static function get($dpid,$orderId){
		$sql = 'select * from nb_order_pay where order_id=:orderId and dpid=:dpid';
		$orderPays = Yii::app()->db->createCommand($sql)
		 		  ->bindValue(':orderId',$orderId)
				  ->bindValue(':dpid',$dpid)
				  ->queryAll();
	    return $orderPays;
	}
	
}