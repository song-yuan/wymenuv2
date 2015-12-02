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
		if($this->type==1){
			$this->orderOpenSite();
		}
	}
	public function getCart(){
		$sql = 'select t.dpid,t.product_id,t.num,t.privation_promotion_id,t.to_group,t1.product_name,t1.main_picture,t1.original_price from nb_cart t,nb_product t1 where t.product_id=t1.lid and t.dpid=t1.dpid and t.dpid=:dpid and t.user_id=:userId and t.site_id=:siteId';
		$results = Yii::app()->db->createCommand($sql)
				  ->bindValue(':dpid',$this->dpid)
				  ->bindValue(':userId',$this->userId)
				  ->bindValue(':siteId',$this->siteId)
				  ->queryAll();
		foreach($results as $k=>$result){
			if($result['privation_promotion_id'] > 0){
				$productPrice = WxPromotion::getPromotionPrice($result['dpid'],$this->userId,$result['product_id'],$result['privation_promotion_id'],$result['to_group']);
				$results[$k]['price'] = $productPrice['price'];
				$results[$k]['promotion'] = $productPrice;
			}else{
				$productPrice = new WxProductPrice($result['product_id'],$result['dpid']);
				$results[$k]['price'] = $productPrice->price;
				$results[$k]['promotion'] = $productPrice->promotion;
			}
		}
		$this->cart = $results;
	}
	public function orderOpenSite(){
		SiteClass::openSite($this->dpid,1,0,$this->siteId);
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
		        	'order_status'=>1,
		        	'order_type'=>$this->type,
		        	'is_sync'=>DataSync::getInitSync(),
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
								'product_order_status'=>0,
								'is_sync'=>DataSync::getInitSync(),
								);
				 Yii::app()->db->createCommand()->insert('nb_order_product',$orderProductData);
				 $orderPrice +=  $cart['price']*$cart['num'];
				 $realityPrice += $cart['original_price']*$cart['num'];
			}
			$isSync = DataSync::getInitSync();
			$sql = 'update nb_order set should_total='.$orderPrice.',reality_total='.$realityPrice.',is_sync='.$isSync.' where lid='.$orderId.' and dpid='.$this->dpid;
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
	public static function getUserOrderList($userId,$dpid){
		$sql = 'select * from nb_order where dpid=:dpid and user_id=:userId order by lid desc';
		$orderList = Yii::app()->db->createCommand($sql)
				  ->bindValue(':userId',$userId)
				  ->bindValue(':dpid',$dpid)
				  ->queryAll();
	    return $orderList;
	}
	public static function updateOrderStatus($orderId,$dpid){
		$isSync = DataSync::getInitSync();
		$sql = 'update nb_order set order_status=3,paytype=1,is_sync='.$isSync.' where lid='.$orderId.' and dpid='.$dpid;
		Yii::app()->db->createCommand($sql)->execute();
	}
	/**
	 * 
	 * 更改订单支付方式
	 * 
	 * 
	 */
	 public static function updatePayType($orderId,$dpid,$paytype = 1){
	 	$isSync = DataSync::getInitSync();
		$sql = 'update nb_order set paytype='.$paytype.',is_sync='.$isSync.' where lid='.$orderId.' and dpid='.$dpid;
		Yii::app()->db->createCommand($sql)->execute();
	}
	/**
	 * 
	 * 插入订单代金券表
	 * 并减少订单相应的金额
	 * 
	 */
	public static function updateOrderCupon($orderId,$dpid,$cuponBranduserLid){
		$now = date('Y-m-d H:i:s',time());
		$order = self::getOrder($orderId,$dpid);
		$sql = 'select t1.cupon_money from nb_cupon_branduser t,nb_cupon t1 where t.cupon_id=t1.lid and t.dpid=t1.dpid and  t.lid='.$cuponBranduserLid.
				' and t1.begin_time <= '.$now.' and '.$now.' <= t1.end_time and t1.delete_flag=0 and t1.is_available=0';
		$result = Yii::app()->db->createCommand($sql)->queryRow();
		if($result){
			$isSync = DataSync::getInitSync();
			$money = ($order['should_total'] - $result['cupon_money']) >0 ? $order['should_total'] - $result['cupon_money']:0;
			$sql = 'update nb_order set cupon_branduser_lid='.$cuponBranduserLid.',should_total='.$money.',is_sync='.$isSync.' where lid='.$orderId.' and dpid='.$dpid;
			Yii::app()->db->createCommand($sql)->execute();
		}
	}
	/**
	 * 
	 * 微信支付 通知时 使用该方法
	 * order——pay表记录支付数据
	 * 
	 */
	 public static function insertOrderPay($order,$paytype = 1){
	 	$time = time();
	 	if($paytype==10){
	 		$user = WxBrandUser::get($order['user_id'],$order['dpid']);
	 		if(!$user){
	 			throw new Exception('不存在该会员!');
	 		}
	 		if($user['remain_money'] < $order['should_total']){
	 			throw new Exception('余额不足!');
	 		}
	 		self::reduceYue($order['user_id'],$order['dpid'],$order['should_total']);
	 		
	 	}
	 	$se = new Sequence("order_pay");
	    $orderPayId = $se->nextval();
	    $insertOrderPayArr = array(
	        	'lid'=>$orderPayId,
	        	'dpid'=>$order['dpid'],
	        	'create_at'=>date('Y-m-d H:i:s',$time),
	        	'update_at'=>date('Y-m-d H:i:s',$time), 
	        	'order_id'=>$order['lid'],
	        	'pay_amount'=>$order['should_total'],
	        	'paytype'=>$paytype,
	        	'is_sync'=>DataSync::getInitSync(),
	        );
		$result = Yii::app()->db->createCommand()->insert('nb_order_pay', $insertOrderPayArr);
		if($order['cupon_branduser_lid']){
			$se = new Sequence("order_pay");
		    $orderPayId = $se->nextval();
		    $insertOrderPayArr = array(
		        	'lid'=>$orderPayId,
		        	'dpid'=>$order['dpid'],
		        	'create_at'=>date('Y-m-d H:i:s',$time),
		        	'update_at'=>date('Y-m-d H:i:s',$time), 
		        	'order_id'=>$order['lid'],
		        	'pay_amount'=>$order['should_total'],
		        	'paytype'=>9,
		        	'paytype_id'=>$order['cupon_branduser_lid'],
		        	'is_sync'=>DataSync::getInitSync(),
		     );
			$result = Yii::app()->db->createCommand()->insert('nb_order_pay', $insertOrderPayArr);
			
			$isSync = DataSync::getInitSync();
			$sql = 'update nb_cupon_branduser set is_used=1,is_sync='.$isSync.' where lid='.$order['cupon_branduser_lid'].' and dpid='.$order['dpid'].' and to_group=3';
			Yii::app()->db->createCommand($sql)->execute();
		}
	 }
	/**
	 * 
	 * 扣除会员余额
	 * 
	 */
	 public static function reduceYue($userId,$dpid,$total){
	 	$isSync = DataSync::getInitSync();
	 	$sql = 'update from nb_brand_user set remain_money = remain_money-'.$total.',is_sync='.$isSync.' where lid='.$userId.' and dpid='.$dpid;
	 	$result = Yii::app()->db->createCommand($sql)->execute();
	 	if(!$result){
	 		throw new Exception('支付失败');
	 	}
	 }
}