<?php
class OrderList
{
	/**
	 * 
	 * $orderList = array{$key=>array(array(),array())} $key 为商品分类的lid
	 * 
	 */
	public $orderId = 0;
	public $siteNoId = 0;
	public $orderList = array();
	
	public function __construct($siteNoId = 0){
		$this->siteNoId = $siteNoId;
		$this->db = Yii::app()->db;
	}
	public function OrderProductList(){
		$sql = 'select * from nb_order where lid=:lid and dpid=:dpid';
		$conn = $this->db->createCommand($sql);
		$conn->bindValue(':lid',$this->orderId);
		$conn->bindValue(':dpid',$this->companyId);
		$this->order = $conn->queryRow();
		if($this->order){
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
}