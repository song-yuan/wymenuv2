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
	public static function refundOrderPay($orderPay){
		$now = date('Y-m-d H:i:s',time());
		$isSync = DataSync::getInitSync();
		
		$se = new Sequence("order_pay");
		$orderPayId = $se->nextval();
		$insertOrderPayArr = array(
				'lid'=>$orderPayId,
				'dpid'=>$orderPay['dpid'],
				'create_at'=>$now,
				'update_at'=>$now,
				'order_id'=>$orderPay['order_id'],
				'account_no'=>$orderPay['account_no'],
				'pay_amount'=>-$orderPay['pay_amount'],
				'paytype'=>$orderPay['paytype'],
				'paytype_id'=>$orderPay['paytype_id'],
				'remark'=>$orderPay['remark'],
				'is_sync'=>$isSync,
		);
		$orderPay = Yii::app()->db->createCommand()->insert('nb_order_pay', $insertOrderPayArr);
		if(!$orderPay){
			throw new Exception('支付处理失败!');
		}
	}
}