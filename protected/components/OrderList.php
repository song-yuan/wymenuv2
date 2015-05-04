<?php
class OrderList
{
	/**
	 * 
	 * $orderList = array{$key=>array(array(),array())} $key 为商品分类的lid
	 * 
	 */
	public $siteNoId = 0;
	public $dpid = 0;
	public $orderId = 0;
	public $orderStatus = 0;
	public $orderLockStatus = 0;
	public $orderList = array();
	
	public function __construct($dpid = 0,$siteNoId = 0){
		$this->dpid = $dpid;
		$this->siteNoId = $siteNoId;
		$this->db = Yii::app()->db;
		$this->SiteNo();
		$this->Order();
		$this->OrderStatus();
	}
	public function SiteNo(){
		$sql = 'select * from nb_site_no where lid=:lid and dpid=:dpid';
		$conn = $this->db->createCommand($sql);
		$conn->bindValue(':lid',$this->siteNoId);
		$conn->bindValue(':dpid',$this->dpid);
		$this->siteNo = $conn->queryRow();
	}
	//获取订单信息
	public function Order(){
		$sql = 'select * from nb_order where dpid=:dpid and site_id=:siteId and is_temp=:isTemp order by lid desc';
		$conn = $this->db->createCommand($sql);
		$conn->bindValue(':dpid',$this->siteNo['dpid']);
		$conn->bindValue(':siteId',$this->siteNo['site_id']);
		$conn->bindValue(':isTemp',$this->siteNo['is_temp']);
		$this->order = $conn->queryRow();
	}
	
	//订单商品类别 type 是否下单 0 未下单 1 已下单
	public function OrderProductList($orderId,$type,$groupby = 0,$isOrder = 0){
			$result = array();
			$sql = 'select t.*, t1.category_id, t1.product_name, t1.main_picture, t1.original_price, t1.product_unit, t1.weight_unit, t1.is_weight_confirm, t1.printer_way_id from nb_order_product t,nb_product t1  where t.product_id=t1.lid and order_id=:orderId and t.delete_flag=0 and main_id=0 and set_id=0 and product_order_status='.$type.
				   ' union select t.*, 0 as category_id, t1.set_name as product_name, t1.main_picture,t2.product_name as original_price,0 as product_unit, 0 as weight_unit, 0 as is_weight_confirm, 0 as printer_way_id  from nb_order_product t left join nb_product_set t1 on t.set_id=t1.lid left join nb_product t2 on t.product_id=t2.lid where order_id=:orderId and t.delete_flag=0 and main_id=0 and t.set_id > 0 and t.product_order_status='.$type;
			if($groupby){
				$sql .= ' group by t.set_id';
			}
			$conn = $this->db->createCommand($sql);
			$conn->bindValue(':orderId',$orderId);
			$orderlist = $conn->queryAll();
			foreach($orderlist as $key=>$val){
				if($isOrder){
					$val['addition'] = self::GetOrderAddProduct($val['dpid'],$orderId,$val['product_id'],$type);
				}else{
					$val['hasAddition'] = self::GetOrderAddProduct($val['dpid'],$orderId,$val['product_id'],$type);
					$val['addition'] = self::GetAddProduct($val['dpid'],$val['product_id']);
				}
				$result[$val['category_id']][] = $val;
			}
			return $result;
	}
	//该产品加菜
	public static function GetAddProduct($dpid,$productId){
		$sql = 'select t.*,t1.main_picture,t1.product_name from nb_product_addition t,nb_product t1 where t.sproduct_id=t1.lid and t.dpid=t1.dpid and t.dpid=:dpid and t.mproduct_id=:productId';
		$conn = Yii::app()->db->createCommand($sql);
		$conn->bindValue(':dpid',$dpid);
		$conn->bindValue(':productId',$productId);
		$addProducts = $conn->queryAll();
		return $addProducts;
	}
	//订单中加菜
	public static function GetOrderAddProduct($dpid,$orderId,$mainId,$type){
		$sql = 'select t.*,t1.main_picture,t1.product_name,t1.product_unit from nb_order_product t,nb_product t1 where t.product_id=t1.lid and t.dpid=t1.dpid and t.dpid=:dpid and t.order_id=:orderId and t.main_id=:mainId and t.product_order_status=:status';
		$conn = Yii::app()->db->createCommand($sql);
		$conn->bindValue(':dpid',$dpid);
		$conn->bindValue(':orderId',$orderId);
		$conn->bindValue(':mainId',$mainId);
		$conn->bindValue(':status',$type);
		$addProducts = $conn->queryAll();
		return $addProducts;
	}
	//获取该套餐的产品 array(1=>array(,),2=>array(,)) 1,2表示group_no
	public function GetSetProduct($setId){
		$result = array();
		$sql = 'select t.*, t1.product_name, t1.main_picture from nb_product_set_detail t left join nb_product t1 on t.product_id=t1.lid where t.set_id=:setId and t.dpid=:dpid and t1.dpid=:dpid and t.delete_flag=0 order by is_select desc';
		$conn = $this->db->createCommand($sql);
		$conn->bindValue(':setId',$setId);
		$conn->bindValue(':dpid',$this->siteNo['dpid']);
		$results = $conn->queryAll();
		foreach($results as $key=>$val){
			$result[$val['group_no']][] = $val;
		}
		return $result;
	}
	//订单状态
	public function OrderStatus(){
		if($this->order){
			$this->orderStatus = $this->order['order_status'];
			$this->orderLockStatus = $this->order['lock_status'];
		}
	}
	//订单总额和总数量 type 已下单和为下单 isOrder 是否已加菜
	public function OrderPrice($type,$isOrder = 0){
		$price = 0;
		$num = 0;
		if($this->order){
			$products = $this->OrderProductList($this->order['lid'],$type,0,$isOrder);
			foreach($products as $product){
				foreach($product as $val){
					if($isOrder&&!empty($val['addition'])){
						foreach($val['addition'] as $v){
							$price += $v['price']*$v['amount'];
							$num += $v['amount'];
						}
					}
					$price += $val['price']*$val['amount'];
					$num += $val['amount'];
				}
			}
		}
		return $price.':'.$num;
	}
	//下单更新数量 锁定订单 $goodsIds = array('goods_id'=>'num','goods_id'=>'num','set_id,goods_id1-goods_id2-goods_id3'=>num) 如 array('102'=>2) goods_id =102 num = 2
	public static function UpdateOrder($dpid,$orderId,$goodsIds){
		if($goodsIds){
			foreach($goodsIds as $key=>$val){
				if(!strpos($key,'group')){//去除套餐中的 checkbox
					$goodsArr = explode(',',$key);//如果数组元素个数是2 证明书套餐
					if(count($goodsArr)==2){
						$setId = $goodsArr[0];
						$sql = 'delete from nb_order_product where order_id=:orderId and set_id=:setId';
						$connect = Yii::app()->db->createCommand($sql);
						$connect->bindValue(':setId',$setId);
						$connect->bindValue(':orderId',$orderId);
						$connect->execute();
						
						$goodsData = explode('-',$goodsArr[1]);
						foreach($goodsData as $goods){
							$se=new Sequence("product");
                        	$lid = $se->nextval();
							$insertData = array(
												'lid'=>$lid,
												'dpid'=>$dpid,
												'create_at'=>time(),
												'order_id'=>$orderId,
												'set_id'=>$setId,
												'product_id'=>$goods,
												'price'=>ProductSetClass::GetProductSetPrice($dpid,$setId,$goods),
												'update_at'=>time(),
												'amount'=>$val,
												'taste_memo'=>'无',
												);
							Yii::app()->db->createCommand()->insert('nb_order_product',$insertData);
						}
							
					}else{
						$sql = 'update nb_order_product set amount = :amount where order_id = :orderId and product_id = :productId';
						$conn = Yii::app()->db->createCommand($sql);
						$conn->bindValue(':amount',$val);
						$conn->bindValue(':orderId',$orderId);
						$conn->bindValue(':productId',$key);
						$conn->execute();
					}
				}
			}
				$sql = 'update nb_order set lock_status=1 where lid = :orderId';
				$conn = Yii::app()->db->createCommand($sql);
				$conn->bindValue(':orderId',$orderId);
				$conn->execute();
			return true;
		}else{
			return false;
		}
	}
	//下单更新数量 解除锁定订单 $goodsIds = array('goods_id'=>'num','goods_id'=>'num') 如 array('102'=>2) goods_id =102 num = 2
	public function ConfirmOrder($orderId,$goodsIds){
		if($goodsIds){
			foreach($goodsIds as $key=>$val){
				$goodsArr = explode('-',$key);//如果数组元素个数是2 证明书套餐
				if(count($goodsArr)==2){
					$sql = 'update nb_order_product set amount = :amount , product_order_status=1 where order_id = :orderId and set_id=:setId and product_id = :productId';
					$conn = Yii::app()->db->createCommand($sql);
					$conn->bindValue(':amount',$val);
					$conn->bindValue(':orderId',$orderId);
					$conn->bindValue(':setId',$goodsArr[0]);
					$conn->bindValue(':productId',$goodsArr[1]);
					$conn->execute();
				}else{
					$sql = 'update nb_order_product set amount = :amount , product_order_status=1 where order_id = :orderId and product_id = :productId';
					$conn = Yii::app()->db->createCommand($sql);
					$conn->bindValue(':amount',$val);
					$conn->bindValue(':orderId',$orderId);
					$conn->bindValue(':productId',$key);
					$conn->execute();
				}
			}
			$sql = 'update nb_order set lock_status=0, order_status=2 where lid = :orderId';
			$conn = Yii::app()->db->createCommand($sql);
			$conn->bindValue(':orderId',$orderId);
			$conn->execute();
			//siteNo 表
			$sql = 'update nb_site_no set status=2 where lid = :siteNoId and dpid=:dpid';
			$conn = Yii::app()->db->createCommand($sql);
			$conn->bindValue(':siteNoId',$this->siteNo['lid']);
			$conn->bindValue(':dpid',$this->dpid);
			$conn->execute();
			//site 表
			$sql = 'update nb_site set status=2 where lid = :siteId and dpid=:dpid';
			$conn = Yii::app()->db->createCommand($sql);
			$conn->bindValue(':siteId',$this->siteNo['site_id']);
			$conn->bindValue(':dpid',$this->dpid);
			$conn->execute();
			return true;
		}else{
			return false;
		}
	}
	//获取种类的名称
	public static function GetCatoryName($catoryId){
		$sql = 'select category_name from  nb_product_category where lid = :lid';
		$conn = Yii::app()->db->createCommand($sql);
		$conn->bindValue(':lid',$catoryId);
		$catoryName = $conn->queryScalar();
		return $catoryName;
	}
}