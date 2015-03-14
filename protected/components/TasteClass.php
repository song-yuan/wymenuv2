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
	
	//全订单口味列表 1 整单 0 非整单
	public static function getAllOrderTaste($dpid,$type){
		$sql = 'select lid,name from nb_taste where dpid=:dpid and allflae=:allflae and delete_flag=0';
		$conn = Yii::app()->db->createCommand($sql);
		$conn->bindValue(':dpid',$dpid);
		$conn->bindValue(':allflae',$type);
		$result = $conn->queryAll();
		return $result;
	}
	
	//订单口味 type = 1 全单口味 2 订单产品口味
	public static function getOrderTaste($orderId,$type){
		if($type==1){
			$sql = 'select t.taste_id from nb_order_taste t where t.order_id=:orderId and t.is_order=1';
			$conn = Yii::app()->db->createCommand($sql);
			$conn->bindValue(':orderId',$orderId);
		}elseif($type==2){
			$sql = 'select t.taste_id from nb_order_taste t where t.order_id=:orderId and t.is_order=0';
			$conn = Yii::app()->db->createCommand($sql);
			$conn->bindValue(':orderId',$orderId);
		}
		$result = $conn->queryAll();
		return $result;
	}
	
	public static function save($dpid,$productId,$tastesIds = array()){
		$sql = 'delete from nb_product_taste where dpid=:dpid and product_id=:productId';
		$conn = Yii::app()->db->createCommand($sql);
		$conn->bindValue(':dpid',$dpid);
		$conn->bindValue(':productId',$productId);
		$conn->execute();
		
		foreach($tastesIds as $taste){
			$sql = 'SELECT NEXTVAL("product_taste") AS id';
			$maxId = Yii::app()->db->createCommand($sql)->queryRow();
			$data = array(
			 'lid'=>$maxId['id'],
			 'dpid'=>$dpid,
			 'create_at'=>date('Y-m-d H:i:s',time()),
			 'taste_id'=>$taste,
			 'product_id'=>$productId,
			);
			Yii::app()->db->createCommand()->insert('nb_product_taste',$data);
		}
		return true;
	}
	public static function getTasteName($tasteId){
		$sql = 'SELECT name from nb_taste where lid=:lid';
		$taste = Yii::app()->db->createCommand($sql)->bindValue(':lid',$tasteId)->queryRow();
		return $taste['name'];
	}
}