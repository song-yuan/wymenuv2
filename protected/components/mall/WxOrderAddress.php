<?php 
/**
 * 
 * 
 * 订单地址
 * 
 *
 * 
 */
class WxOrderAddress
{
	 /**
	  * 
	  * 添加订单地址
	  * 
	  */
	  public static function addOrderAddress($orderId,$orderDpid,$address){
	  		self::checkOrderAddress($orderId,$address['dpid']);
            $time = time();
			$se = new Sequence("order_address");
		    $lid = $se->nextval();
			$insertData = array(
								'lid'=>$lid,
					        	'dpid'=>$orderDpid,
					        	'create_at'=>date('Y-m-d H:i:s',$time),
					        	'update_at'=>date('Y-m-d H:i:s',$time), 
					        	'order_lid'=>$orderId,
					        	'consignee'=>$address['name'],
					        	'province'=>$address['province'],
					        	'city'=>$address['city'],
					        	'area'=>$address['area'],
					        	'street'=>Helper::dealString($address['street']),
					        	'postcode'=>$address['postcode'],
					        	'mobile'=>$address['mobile'],
					        	'tel'=>$address['tel'],
					        	'is_sync'=>DataSync::getInitSync(),
								);
			$result = Yii::app()->db->createCommand()->insert('nb_order_address', $insertData);
			return $result;
      }
      public static function checkOrderAddress($orderId,$dpid){
           $sql = 'delete from nb_order_address where order_lid='.$orderId.' and dpid='.$dpid;
           Yii::app()->db->createCommand($sql)->execute();
      }
}