<?php
class TasteClass
{
	//产品口味 列表
	public static function getProductTaste($productId){
		$sql = 'select t.taste_id as lid,t1.name from nb_product_taste t,nb_taste t1 where t.taste_id=t1.lid and t.product_id=:productId and t.delete_flag=0';
		$conn = Yii::app()->db->createCommand($sql);
		$conn->bindValue(':productId',$productId);
		$result = $conn->queryAll();
		return $result;
	}
	
	//全订单口味列表
	public static function getAllOrderTaste($dpid){
		$sql = 'select lid,name from nb_taste where dpid=:dpid and delete_flag=0';
		$conn = Yii::app()->db->createCommand($sql);
		$conn->bindValue(':dpid',$dpid);
		$result = $conn->queryAll();
		return $result;
	}
	
	//订单口味 type = 1 全单口味 2 订单产品口味
	public static function getOrderTaste($orderId,$type){
		if($type==1){
			$sql = 'select t.taste_id from nb_order_taste t where t.order_id=:orderId and t.is_order=1';
			$conn = Yii::app()->db->createCommand($sql);
			$conn->bindValue(':productId',$orderId);
		}elseif($type==2){
			$sql = 'select t.taste_id from nb_order_taste t where t.order_id=:orderId and t.is_order=0';
			$conn = Yii::app()->db->createCommand($sql);
			$conn->bindValue(':productId',$orderId);
		}
		$result = $conn->queryAll();
		return $result;
	}
}