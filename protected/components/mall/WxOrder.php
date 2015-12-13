<?php 
/**
 * 
 * 
 * 微信端订单类
 * //堂吃必须有siteId
 *$type 1 堂吃 2 外卖
 *$normalPromotionIds 菜品普通优惠id
 *
 * 
 */
class WxOrder
{
	public $dpid;
	public $userId;
	public $siteId;
	public $type;
	public $number;
	public $cart = array();
	public $normalPromotionIds = array();
	public $tastes = array();//原始产品口味
	public $productTastes = array();//处理后的产品口味
	public $order = false;
	
	public function __construct($dpid,$userId,$siteId = null,$type = 1,$number = 1,$tastes = array()){
		$this->dpid = $dpid;
		$this->userId = $userId;
		$this->siteId = $siteId;
		$this->type = $type;
		$this->number = $number;
		$this->tastes = $tastes;
		$this->getCart();
		$this->dealTastes();
		if($this->type==1){
			$this->getSite();
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
	public function dealTastes(){
		if(!empty($this->tastes)){
			foreach($this->tastes as $taste){
				$tasteArr = explode('-',$taste);
				$this->productTastes[$tasteArr[0]][] = $tasteArr[1];
			}
		}
	}
	public function getSite(){
		$site = WxSite::get($this->siteId,$this->dpid);
		if(!in_array($site['status'],array(1,2,3))){
			$this->orderOpenSite();
		}elseif($site['status'] == 1){
			$this->order = self::getOrderBySiteId($this->siteId,$this->dpid);
		}
	}
	public function orderOpenSite(){
		SiteClass::openSite($this->dpid,$this->number,0,$this->siteId);
	}
	public function createOrder(){
		$time = time();
		$orderPrice = 0;
		$realityPrice = 0;
		$transaction = Yii::app()->db->beginTransaction();
 		try {
 			if($this->type==1 && $this->order){
 				$orderId = $this->order['lid'];
 				$orderPrice = $this->order['should_total'];
 				$realityPrice = $this->order['reality_total'];
 				$accountNo = $this->order['account_no'];
 			}else{
 				$se = new Sequence("order");
			    $orderId = $se->nextval();
			    
			    $accountNo = self::getAccountNo($this->dpid,$this->siteId,0,$orderId);
			    
			    $insertOrderArr = array(
			        	'lid'=>$orderId,
			        	'dpid'=>$this->dpid,
			        	'create_at'=>date('Y-m-d H:i:s',$time),
			        	'update_at'=>date('Y-m-d H:i:s',$time), 
			        	'account_no'=>$accountNo,
			        	'user_id'=>$this->userId,
			        	'site_id'=>$this->siteId,
			        	'order_status'=>1,
			        	'order_type'=>$this->type,
			        	'is_sync'=>DataSync::getInitSync(),
			        );
				$result = Yii::app()->db->createCommand()->insert('nb_order', $insertOrderArr);
 			}
			
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
								'original_price'=>$cart['original_price'],
								'amount'=>$cart['num'],
								'product_order_status'=>9,
								'is_sync'=>DataSync::getInitSync(),
								);
				 Yii::app()->db->createCommand()->insert('nb_order_product',$orderProductData);
				
				 //插入产品口味
				 if(isset($this->productTastes[$cart['product_id']]) && !empty($this->productTastes[$cart['product_id']])){
				 	foreach($this->productTastes[$cart['product_id']] as $taste){
				 		$se = new Sequence("order_taste");
		    			$orderTasteId = $se->nextval();
				 		$orderTasteData = array(
				 								'lid'=>$orderTasteId,
												'dpid'=>$this->dpid,
												'create_at'=>date('Y-m-d H:i:s',$time),
					        					'update_at'=>date('Y-m-d H:i:s',$time),
					        					'taste_id'=>$taste,
					        					'order_id'=>$orderProductId,
					        					'is_order'=>0,
					        					'is_sync'=>DataSync::getInitSync(),
				 								);
				 		Yii::app()->db->createCommand()->insert('nb_order_taste',$orderTasteData);								
				 	}
				 }
				 
				 //插入订单优惠
				 if(!empty($cart['promotion'])){
				 	foreach($cart['promotion']['promotion_info'] as $promotion){
				 		$orderProductPromotionData =array(
			 										'lid'=>$orderProductId,
													'dpid'=>$this->dpid,
													'create_at'=>date('Y-m-d H:i:s',$time),
						        					'update_at'=>date('Y-m-d H:i:s',$time), 
													'order_id'=>$orderId,
													'order_product_id'=>$orderProductId,
													'account_no'=>$accountNo,
													'promotion_type'=>$cart['promotion']['promotion_type'],
													'promotion_id'=>$promotion['poromtion_id'],
													'promotion_money'=>$promotion['promotion_money'],
													'delete_flag'=>0,
													'is_sync'=>DataSync::getInitSync(),
			 										);
			 			Yii::app()->db->createCommand()->insert('nb_order_product_promotion',$orderProductPromotionData);								
				 	}
				 }
				 $orderPrice +=  $cart['price']*$cart['num'];
				 $realityPrice += $cart['original_price']*$cart['num'];
			}
			if($orderPrice==0){
				$orderPrice = 0.01;
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
		self::updateOrderTotal($order);
	    return $order;
	}
	/**
	 * 
	 * 获取未付款的 订单产品
	 * 
	 */
	 public static function getNoPayOrderProduct($orderId,$dpid){
		$sql = 'select * from nb_order_product where order_id=:lid and dpid=:dpid and delete_flag=0 and product_order_status=9';
		$order = Yii::app()->db->createCommand($sql)
				  ->bindValue(':lid',$orderId)
				  ->bindValue(':dpid',$dpid)
				  ->queryAll();
	    return $order;
	}
	/**
	 * 
	 * 通过siteid获取订单未支付
	 * 
	 */
	public static function getOrderBySiteId($siteId,$dpid){
		$sql = 'select * from nb_order where site_id=:siteId and dpid=:dpid and order_status=1 and order_type=1';
		$order = Yii::app()->db->createCommand($sql)
				  ->bindValue(':siteId',$siteId)
				  ->bindValue(':dpid',$dpid)
				  ->queryRow();
	    return $order;
	}
	public static function getOrderProduct($orderId,$dpid){
		$sql = 'select t.price,t.amount,t1.product_name,t1.main_picture,t.original_price from nb_order_product t,nb_product t1 where t.product_id=t1.lid and t.dpid=t1.dpid and t.order_id = :orderId and t.dpid = :dpid and t.delete_flag=0';
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
		foreach($orderList as $k=>$order){
			$total = self::updateOrderTotal($order);
			$orderList[$k]['should_total'] = $total;
		}
	    return $orderList;
	}
	/**
	 * 
	 * 获取当天改会员使用现金券支付的订单
	 * 
	 */
	 public static function getOrderUseCupon($userId,$dpid){
	 	$now = date('Y-m-d',time());
	 	$sql = 'select * from nb_order where dpid=:dpid and user_id=:userId and cupon_branduser_lid > 0 and create_at >= :now';
		$order = Yii::app()->db->createCommand($sql)
				  ->bindValue(':userId',$userId)
				  ->bindValue(':dpid',$dpid)
				  ->bindValue(':now',$now)
				  ->queryAll();
		return $order;
	 }
	/**
	 * 
	 * 查询订单总价是否与订单产品总价
	 * 
	 */
	public static function updateOrderTotal($order){
		$total = 0;
		$oTotal = 0;
		$orderId = $order['lid'];
		$dpid = $order['dpid'];
		$orderProducts = self::getOrderProduct($orderId,$dpid);
		foreach($orderProducts as $product){
			$total += $product['price']*$product['amount'];
			$oTotal += $product['original_price']*$product['amount'];
		}
		if($order['cupon_branduser_lid']==0&&$total!=$order['should_total']){
			if($total==0){
				$total = 0.01;
			}
			$isSync = DataSync::getInitSync();
			$sql = 'update nb_order set should_total='.$total.',reality_total='.$oTotal.',is_sync='.$isSync.' where lid='.$orderId.' and dpid='.$dpid;
			Yii::app()->db->createCommand($sql)->execute();
		}else{
			$total = $order['should_total'];
		}
		return $total;
	}
	public static function updateOrderStatus($orderId,$dpid){
		$isSync = DataSync::getInitSync();
		$sql = 'update nb_order set order_status=3,paytype=1,is_sync='.$isSync.' where lid='.$orderId.' and dpid='.$dpid;
		Yii::app()->db->createCommand($sql)->execute();
	}
	/**
	 * 
	 * 更改订单产品表状态
	 * 
	 */
	public static function updateOrderProductStatus($orderId,$dpid){
		$isSync = DataSync::getInitSync();
		$sql = 'update nb_order_product set product_order_status=8,is_sync='.$isSync.' where order_id='.$orderId.' and dpid='.$dpid.' and delete_flag=0';
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
				' and t.dpid='.$dpid.' and t1.begin_time <= "'.$now.'" and "'.$now.'" <= t1.end_time and t1.delete_flag=0 and t1.is_available=0';
		$result = Yii::app()->db->createCommand($sql)->queryRow();
		if($result){
			$isSync = DataSync::getInitSync();
			$money = ($order['should_total'] - $result['cupon_money']) >0 ? $order['should_total'] - $result['cupon_money']:0;
			$cuponMoney = $result['cupon_money'];
			$sql = 'update nb_order set cupon_branduser_lid='.$cuponBranduserLid.',cupon_money='.$cuponMoney.',should_total='.$money.',is_sync='.$isSync.' where lid='.$orderId.' and dpid='.$dpid;
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
	        	'account_no'=>$order['account_no'],
	        	'pay_amount'=>$order['should_total'],
	        	'paytype'=>$paytype,
	        	'is_sync'=>DataSync::getInitSync(),
	        );
		$result = Yii::app()->db->createCommand()->insert('nb_order_pay', $insertOrderPayArr);
		if($order['cupon_branduser_lid'] > 0){
			$sql = 'select t1.cupon_money from nb_cupon_branduser t,nb_cupon t1 where t.cupon_id=t1.lid and t.dpid=t1.dpid and  t.lid='.$order['cupon_branduser_lid'].' and t.dpid='.$order['dpid'];
			$result = Yii::app()->db->createCommand($sql)->queryRow();
			
			$se = new Sequence("order_pay");
		    $orderPayId = $se->nextval();
		    $insertOrderPayArr = array(
		        	'lid'=>$orderPayId,
		        	'dpid'=>$order['dpid'],
		        	'create_at'=>date('Y-m-d H:i:s',$time),
		        	'update_at'=>date('Y-m-d H:i:s',$time), 
		        	'order_id'=>$order['lid'],
		        	'account_no'=>$order['account_no'],
		        	'pay_amount'=>$result['cupon_money'],
		        	'paytype'=>9,
		        	'paytype_id'=>$order['cupon_branduser_lid'],
		        	'is_sync'=>DataSync::getInitSync(),
		     );
			$result = Yii::app()->db->createCommand()->insert('nb_order_pay', $insertOrderPayArr);
			
			$isSync = DataSync::getInitSync();
			$sql = 'update nb_cupon_branduser set is_used=2,is_sync='.$isSync.' where lid='.$order['cupon_branduser_lid'].' and dpid='.$order['dpid'].' and to_group=3';
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
	 /**
	  * 
	  * 订单流水单号
	  * 
	  */
	  public static function getAccountNo($dpid,$siteId,$isTemp,$orderId){
            $sql="select ifnull(min(account_no),'000000000000') as account_no from nb_order where dpid="
                    .$dpid." and site_id=".$siteId." and is_temp=".$isTemp
                    ." and order_status in ('1','2','3')";
            $ret=Yii::app()->db->createCommand($sql)->queryScalar();      
            if(empty($ret) || $ret=="0000000000")
            {
                $ret=substr(date('Ymd',time()),-6).substr("0000000000".$orderId, -6);
            }
            return $ret;
        }
}