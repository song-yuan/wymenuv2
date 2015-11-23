<?php 
/**
 * 
 * 
 * 微信端订单类
 * //堂吃必须有siteId
 *$type 1 堂吃 2 外卖
 * 
 */
class WxOrder
{
	public $dpid;
	public $userId;
	public $siteId;
	public $type;
	public $cart = array();
	
	public function __construct($dpid,$userId,$siteId = null,$type = 1){
		$this->dpid = $dpid;
		$this->userId = $userId;
		$this->siteId = $siteId;
		$this->type = $type;
		$this->getCart();
	}
	public function getCart(){
		$sql = 'select t.dpid,t.product_id,t.num,t.privation_promotion_id,t1.product_name,t1.main_picture,t1.original_price from nb_cart t,nb_product t1 where t.product_id=t1.lid and t.dpid=t1.dpid and t.dpid=:dpid and t.user_id=:userId and t.site_id=:siteId';
		$results = Yii::app()->db->createCommand($sql)
				  ->bindValue(':dpid',$this->dpid)
				  ->bindValue(':userId',$this->userId)
				  ->bindValue(':siteId',$this->siteId)
				  ->queryAll();
		foreach($results as $k=>$result){
			$productPrice = new WxProductPrice($result['product_id'],$result['dpid']);
			$results[$k]['price'] = $productPrice->price;
			$results[$k]['promotion'] = $productPrice->promotion;
		}
		$this->cart = $results;
	}
	public function createOrder(){
		$time = time();
		$orderPrice = 0;
		$realityPrice = 0;
		$transaction = Yii::app()->db->beginTransaction();
 		try {
			$se = new Sequence("order");
		    $orderId = $se->nextval();
		    $insertOrderArr = array(
		        	'lid'=>$orderId,
		        	'dpid'=>$this->dpid,
		        	'create_at'=>date('Y-m-d H:i:s',$time),
		        	'update_at'=>date('Y-m-d H:i:s',$time), 
		        	'user_id'=>$this->userId,
		        	'site_id'=>$this->siteId,
		        	'order_status'=>2,
		        	'order_type'=>$this->type
		        );
			$result = Yii::app()->db->createCommand()->insert('nb_order', $insertOrderArr);
			
			foreach($this->cart as $cart){
				$se = new Sequence("order_product");
		    	$orderProductId = $se->nextval();
	         	$orderProductData = array(
								'lid'=>$orderProductId,
								'dpid'=>$this->dpid,
								'create_at'=>date('Y-m-d H:i:s',$time),
	        					'update_at'=>date('Y-m-d H:i:s',$time), 
								'order_id'=>$orderId,
								'set_id'=>0,
								'product_id'=>$cart['product_id'],
								'price'=>$cart['price'],
								'amount'=>$cart['num'],
								'product_order_status'=>1,
								);
				 Yii::app()->db->createCommand()->insert('nb_order_product',$orderProductData);
				 $orderPrice +=  $cart['price']*$cart['num'];
				 $realityPrice += $cart['original_price']*$cart['num'];
			}
			$sql = 'update nb_order set should_total='.$orderPrice.',reality_total='.$realityPrice.' where lid='.$orderId.' and dpid='.$this->dpid;
			Yii::app()->db->createCommand($sql)->execute();
			
			//清空购物车
			$sql = 'delete from nb_cart where user_id='.$this->userId.' and dpid='.$this->dpid;
			Yii::app()->db->createCommand($sql)->execute();
			
			$transaction->commit();	
		 } catch (Exception $e) {
            $transaction->rollback(); //如果操作失败, 数据回滚
            throw new Exception($e->getMessage());
        } 
        return $orderId;
	}
	public static function getOrder($orderId,$dpid){
		$sql = 'select * from nb_order where lid=:lid and dpid=:dpid';
		$order = Yii::app()->db->createCommand($sql)
				  ->bindValue(':lid',$orderId)
				  ->bindValue(':dpid',$dpid)
				  ->queryRow();
	    return $order;
	}
	public static function getOrderProduct($orderId,$dpid){
		$sql = 'select t.price,t.amount,t1.product_name,t1.main_picture from nb_order_product t,nb_product t1 where t.product_id=t1.lid and t.dpid=t1.dpid and t.order_id = :orderId and t.dpid = :dpid';
		$orderProduct = Yii::app()->db->createCommand($sql)
				  ->bindValue(':orderId',$orderId)
				  ->bindValue(':dpid',$dpid)
				  ->queryAll();
	    return $orderProduct;
	}
	
}