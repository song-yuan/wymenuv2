<?php
class CreateOrder
{
	public $siteId = 0;
	public $companyId = 0;
	public $product = array();
	public function __construct($dpid = 0,$siteNoId = 0,$product = array()){
		$this->siteNo = SiteNo::model()->find('lid=:lid and dpid=:dpid',array(':lid'=>$siteNoId,':dpid'=>$dpid));
		$this->companyId = $this->siteNo->dpid;
		$this->siteId = $this->siteNo->site_id;
		$this->product = $product;
		$this->db = Yii::app()->db;
	}
	public function createOrder(){
		$time = date('Y-m-d H:i:s',time());
		$transaction = $this->db->beginTransaction();
		try {
			$criteria = new CDbCriteria;
			$criteria->addCondition('dpid=:dpid and site_id=:siteId and is_temp=:isTemp');
			$criteria->params = array(':dpid'=>$this->siteNo->dpid,':siteId'=>$this->siteNo->site_id,':isTemp'=>$this->siteNo->is_temp); 
			$criteria->order =  'lid desc';
			$order = Order::model()->find($criteria);
			if(!$order){
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
			$setId = 0;
			if($this->product['type']){
				$setId = $this->product['lid'];
				$orderProduct = OrderProduct::model()->find('order_id=:orderId and dpid=:dpid and set_id=:setId and product_order_status=0',array(':orderId'=>$order->lid,'dpid'=>$this->companyId,':setId'=>$setId));
			}else{
				$orderProduct = OrderProduct::model()->find('order_id=:orderId and dpid=:dpid and product_id=:productId and product_order_status=0',array(':orderId'=>$order->lid,'dpid'=>$this->companyId,':productId'=>$this->product['lid']));
			}
			
			if($orderProduct){
				$orderProduct->price = $this->getProductPrice($this->companyId,$this->product['lid'],$this->product['type']);
				$orderProduct->delete_flag = 0;
				$orderProduct->update();
			}else{
				$orderProduct = new OrderProduct;
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
										);
				$orderProduct->attributes = $orderProductData;
				$orderProduct->save();
			}
			$transaction->commit(); //提交事务会真正的执行数据库操作
			return true;
		} catch (Exception $e) {
			$transaction->rollback(); //如果操作失败, 数据回滚
			return false;
		}
	}
	public function getMaxOrderId(){
		$maxOrderId = 1;
		$sql = 'SELECT NEXTVAL("order") AS id';
		$order = $this->db->createCommand($sql)->queryRow();
		if($order){
			$maxOrderId = $order['id']?$order['id']:1;
		}
		return $maxOrderId;
	}
	public function getMaxOrderProductId(){
		$maxOrderId = 1;
		$sql = 'SELECT NEXTVAL("order_product") AS id';
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
				$sql = 'select * from nb_product_tempprice where product_id=:productId and dpid=:dpid and begin_time < :time and end_time > :time';
				$connect = $db->createCommand($sql);
				$connect->bindValue(':productId',$product['lid']);
				$connect->bindValue(':dpid',$dpid);
				$connect->bindValue(':time',$time);
				$productTempPrice = $connect->queryRow();
				if($productTempPrice){
					$price = $productTempPrice['price'];
				}
			}
			if($product['is_special']){
				$sql = 'select * from nb_product_special where product_id=:productId and dpid=:dpid and begin_time < :time and end_time > :time';
				$connect = $db->createCommand($sql);
				$connect->bindValue(':productId',$product['lid']);
				$connect->bindValue(':dpid',$dpid);
				$connect->bindValue(':time',$time);
				$productTempPrice = $connect->queryRow();
				if($productTempPrice){
					$price = $productTempPrice['price'];
				}
			}
			if($product['is_discount']){
				$sql = 'select * from nb_product_discount where product_id=:productId and dpid=:dpid';
				$connect = $db->createCommand($sql);
				$connect->bindValue(':productId',$product['lid']);
				$connect->bindValue(':dpid',$dpid);
				$productDiscount = $connect->queryRow();
				if($productDiscount){
					if($productDiscount['is_discount']){
						$price = $product['original_price']*$productDiscount['price_discount']/100;
					}else{
						$price = $product['original_price'] - $productDiscount['price_discount'];
					}
				}
			}
		}else{
			//套餐
			$sql = 'select sum(price) as price from nb_product_set_detail where set_id=:setId and dpid=:dpid and delete_flag=0 and is_select=1';
			$connect = $db->createCommand($sql);
			$connect->bindValue(':setId',$productId);
			$connect->bindValue(':dpid',$dpid);
			$product = $connect->queryRow();
			$price = $product['price'];
		}
		
		return $price?$price:0;
	}
	public function deleteOrderProduct(){
		$criteria = new CDbCriteria;
		$criteria->addCondition('dpid=:dpid and site_id=:siteId and is_temp=:isTemp');
		$criteria->params = array(':dpid'=>$this->siteNo->dpid,':siteId'=>$this->siteNo->site_id,':isTemp'=>$this->siteNo->is_temp); 
		$criteria->order =  'lid desc';
		$order = Order::model()->find($criteria);
			
		$orderProduct = OrderProduct::model()->find('order_id=:orderId and product_id=:productId and product_order_status=0',array(':orderId'=>$order->lid,':productId'=>$this->product['lid']));
		$orderProduct->delete_flag = 1;
		if($orderProduct->update()){
			return true;
		}else{
			return false;
		}
	}
}