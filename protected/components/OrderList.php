<?php
class OrderList
{
	/**
	 * 
	 * $orderList = array{$key=>array(array(),array())} $key 为商品分类的lid
	 * 
	 */
	public $siteNoId = 0;
	public $orderId = 0;
	public $orderStatus = 0;
	public $orderLockStatus = 0;
	public $orderList = array();
	
	public function __construct($siteNoId = 0){
		$this->siteNoId = $siteNoId;
		$this->db = Yii::app()->db;
		$this->SiteNo();
		$this->OrderProductList();
		$this->OrderStatus();
	}
	public function SiteNo(){
		$sql = 'select * from nb_site_no where lid=:lid';
		$conn = $this->db->createCommand($sql);
		$conn->bindValue(':lid',$this->siteNoId);
		$this->siteNo = $conn->queryRow();
	}
	//订单商品类别
	public function OrderProductList(){
		$sql = 'select * from nb_order where dpid=:dpid and site_id=:siteId and is_temp=:isTemp order by lid desc';
		$conn = $this->db->createCommand($sql);
		$conn->bindValue(':dpid',$this->siteNo['dpid']);
		$conn->bindValue(':siteId',$this->siteNo['site_id']);
		$conn->bindValue(':isTemp',$this->siteNo['is_temp']);
		$this->order = $conn->queryRow();
		if($this->order){
			$this->orderId = $this->order['lid'];
			$sql = 'select t.*, t1.category_id, t1.product_name, t1.main_picture, t1.original_price, t1.product_unit, t1.weight_unit, t1.is_weight_confirm, t1.printer_way_id from nb_order_product t,nb_product t1  where t.product_id=t1.lid and order_id=:orderId and t.delete_flag=0 and set_id=0 group by t1.category_id ' .
					'union select t.*, 0 as category_id, t1.set_name as product_name, t1.main_picture,0 as original_price,0 as product_unit, 0 as weight_unit, 0 as is_weight_confirm, 0 as printer_way_id  from nb_order_product t,nb_product_set t1  where t.product_id=t1.lid and order_id=:orderId and t.delete_flag=0 and set_id > 0';
			$conn = $this->db->createCommand($sql);
			$conn->bindValue(':orderId',$this->order['lid']);
			$orderlist = $conn->queryAll();
			foreach($orderlist as $key=>$val){
				$result[$val['category_id']][] = $val;
			}
			$this->orderList = $result;
		}
	}
	
	//订单状态
	public function OrderStatus(){
		if($this->order){
			$this->orderStatus = $this->order['order_status'];
			$this->orderLockStatus = $this->order['lock_status'];
		}
	}
	
	//下单更新数量 锁定订单 $goodsIds = array('goods_id'=>'num','goods_id'=>'num') 如 array('102'=>2) goods_id =102 num = 2
	public static function UpdateOrder($orderId,$goodsIds){
		if($goodsIds){
			foreach($goodsIds as $key=>$val){
				$sql = 'update nb_order_product set amount = :amount where order_id = :orderId and product_id = :productId';
				$conn = $this->db->createCommand($sql);
				$conn->bindValue(':amount',$val);
				$conn->bindValue(':orderId',$orderId);
				$conn->bindValue(':productId',$key);
				$conn->execute();
			}
			return true;
		}else{
			return false;
		}
	}
	//获取种类的名称
	public static function GetCatoryName($catoryId){
		$sql = 'select category_name from  nb_product_category where lid = :lid';
		$conn = $this->db->createCommand($sql);
		$conn->bindValue(':lid',$catoryId);
		$catoryName = $conn->queryScalar();
		return $catoryName;
	}
}