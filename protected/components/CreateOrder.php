<?php
class CreateOrder
{
	public $siteId = 0;
	public $companyId = 0;
	public $product = array();
	public function __construct($siteNoId = 0,$product = array()){
		$this->siteNo = SiteNo::model()->find('lid=:lid',array(':lid'=>$siteNoId));
		$this->companyId = $this->siteNo->dpid;
		$this->siteId = $this->siteNo->site_id;
		$this->product = $product;
		$this->db = Yii::app()->db;
	}
	public function createOrder(){
		$time = date('Y-m-d H:i:s',time());
		$transaction = $this->db->beginTransaction();
		try {
			if(!$this->siteNo->status){
				$order = new Order;
				$data = array(
							'lid'=>$this->getMaxOrderId(),
							'dpid'=>$this->companyId,
							'site_id'=>$this->siteId,
							'create_at'=>$time,
							'is_temp'=>$this->siteNo->is_temp,
							'number'=>$this->siteNo->number,
							'update_at'=>$time,
							'remark'=>'无',
							'taste_memo'=>'无',
							);
				$order->attributes = $data;
				$order->save();
			}
			$orderProduct = new OrderProduct;
			$setId = 0;
			if($this->product['type']){
				$setId = $this->product['lid'];
			}
			$orderProductData = array(
									'lid'=>$this->getMaxOrderProductId(),
									'dpid'=>$this->companyId,
									'create_at'=>$time,
									'order_id'=>$order->lid,
									'set_id'=>$setId,
									'product_id'=>$this->product['lid'],
									'price'=>$this->getProductPrice($this->companyId,$this->product['lid'],$this->product['type']),
									'update_at'=>$time,
									'amount'=>1,
									'taste_memo'=>'无',
									'retreat_memo'=>'无',
									);
			$orderProduct->attributes = $orderProductData;
			$orderProduct->save();
			$this->siteNo->status = 1;
			$this->siteNo->save();
			$transaction->commit(); //提交事务会真正的执行数据库操作
			return true;
		} catch (Exception $e) {
			$transaction->rollback(); //如果操作失败, 数据回滚
			return false;
		}
	}
	public function getMaxOrderId(){
		$maxOrderId = 1;
		$sql = 'SELECT NEXTVAL("nb_order") AS id';
		$order = $this->db->createCommand($sql)->queryRow();
		if($order){
			$maxOrderId = $order['id']?$order['id']:1;
		}
		return $maxOrderId;
	}
	public function getMaxOrderProductId(){
		$maxOrderId = 1;
		$sql = 'SELECT NEXTVAL("nb_order_product") AS id';
		$order = $this->db->createCommand($sql)->queryRow();
		if($order){
			$maxOrderId = $order['id']?$order['id']:1;
		}
		return $maxOrderId;
	}
	public static function getProductPrice($dpid = 0,$productId = 0,$type = 0){
		$price = 0;
		$time = date('Y-m-d H:i:s',time());
		$db = Yii::app()->db;
		if(!$type){
			// 非套餐
			$sql = 'select * from nb_product where lid=:lid and dpid=:dpid';
			$connect = $db->createCommand($sql);
			$connect->bindValue(':lid',$productId);
			$connect->bindValue(':dpid',$dpid);
			$product = $connect->queryRow();
			
			$price = $product['original_price'];
			if($product['is_temp_price']){
				$sql = 'select * from nb_product_tempprice where product_id=:productId and dpid=:dpid and begain_time < :time and end_time > :time';
				$connect = $db->createCommand($sql);
				$connect->bindValue(':productId',$product['lid']);
				$connect->bindValue(':dpid',$this->companyId);
				$connect->bindValue(':time',$time);
				$productTempPrice = $connect->queryRow();
				
				$price = $productTempPrice['price'];
			}
			if($product['is_special']){
				$sql = 'select * from nb_product_special where product_id=:productId and dpid=:dpid and begain_time < :time and end_time > :time';
				$connect = $db->createCommand($sql);
				$connect->bindValue(':productId',$product['lid']);
				$connect->bindValue(':dpid',$this->companyId);
				$connect->bindValue(':time',$time);
				$productTempPrice = $connect->queryRow();
				
				$price = $productTempPrice['price'];
			}
			if($product['is_discount']){
				$sql = 'select * from nb_product_discount where product_id=:productId and dpid=:dpid';
				$connect = $db->createCommand($sql);
				$connect->bindValue(':productId',$product['lid']);
				$connect->bindValue(':dpid',$this->companyId);
				$productDiscount = $connect->queryRow();
				if($productDiscount['is_discount']){
					$price = $product['original_price']*$productDiscount['price_discount'];
				}else{
					$price = $productDiscount['price_discount'];
				}
			}
		}else{
			//套餐
			$sql = 'select * from nb_product_set where lid=:lid and dpid=:dpid';
			$connect = $db->createCommand($sql);
			$connect->bindValue(':lid',$productId);
			$connect->bindValue(':dpid',$this->companyId);
			$product = $connect->queryRow();
			
			$price = $product['original_price'];
			if($product['is_special']){
				$sql = 'select * from nb_product_special where product_id=:productId and dpid=:dpid and begain_time < :time and end_time > :time';
				$connect = $db->createCommand($sql);
				$connect->bindValue(':productId',$product['lid']);
				$connect->bindValue(':dpid',$this->companyId);
				$connect->bindValue(':time',$time);
				$productTempPrice = $connect->queryRow();
				
				$price = $productTempPrice['price'];
			}
			if($product['is_discount']){
				$sql = 'select * from nb_product_discount where product_id=:productId and dpid=:dpid';
				$connect = $db->createCommand($sql);
				$connect->bindValue(':productId',$product['lid']);
				$connect->bindValue(':dpid',$this->companyId);
				$productDiscount = $connect->queryRow();
				if($productDiscount['is_discount']){
					$price = $product['original_price']*$productDiscount['price_discount'];
				}else{
					$price = $product['original_price']-$productDiscount['price_discount'];
				}
			}
		}
		
		return $price?$price:0;
	}
	public static function deleteOrderProduct($dpid = 0,$productId = 0){
		$sql = 'delete from nb_order_product where dpid=:dpid and product_id=:productId';
		$connect = Yii::app()->db->createCommand($sql);
		$connect->bindValue(':productId',$productId);
		$connect->bindValue(':dpid',$dpid);
		$result = $connect->execute();
		if($result){
			return true;
		}else{
			return false;
		}
	}
}