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
	public $cartNumber = 0;
	public $isTemp = 0;
	public $seatingFee = 0;
	public $packingFee = 0;
	public $freightFee = 0;
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
		if($this->type==1||$this->type==3){
			$this->isTemp = 0;
			$this->getSite();
			$this->getSeatingFee();
		}else{
			$this->isTemp = 1;
			$this->orderOpenSite();
			$this->getPackingFee();
			$this->getFreightFee();
		}
	}
	//获取购物车信息
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
			$this->cartNumber +=$result['num'];
		}
		$this->cart = $results;
	}
	//处理订单口味
	public function dealTastes(){
		if(!empty($this->tastes)){
			foreach($this->tastes as $taste){
				$tasteArr = explode('-',$taste);
				if(count($tasteArr)>1){
					$this->productTastes[$tasteArr[0]][] = $tasteArr[1];
				}
			}
		}
	}
	//获取座位状态
	public function getSite(){
		$site = WxSite::get($this->siteId,$this->dpid);
		if(!in_array($site['status'],array(1,2,3))){
			if(empty($this->number)){
				 throw new Exception('开台餐位数不能为0，请添加餐位数！');
			}
			$this->orderOpenSite();
		}elseif($site['status'] == 1){
			$this->order = self::getOrderBySiteId($this->siteId,$this->dpid);
		}
	}
	//获取餐位费
	public function getSeatingFee(){
		$isSeatingFee = WxCompanyFee::get(1,$this->dpid);
		if($isSeatingFee){
			$this->seatingFee = $isSeatingFee['fee_price'];
		}else{
			$this->seatingFee = 0;
		}
	}
	//获取打包费
	public function getPackingFee(){
		$isPackingFee = WxCompanyFee::get(2,$this->dpid);
		if($isPackingFee){
			$this->packingFee = $isPackingFee['fee_price'];
		}else{
			$this->packingFee = 0;
		}
	}
	//获取运费
	public function getFreightFee(){
		$isFreightFee = WxCompanyFee::get(3,$this->dpid);
		if($isFreightFee){
			$this->freightFee = $isFreightFee['fee_price'];
		}else{
			$this->freightFee = 0;
		}
	}
	//座位开台
	public function orderOpenSite(){
		$result = SiteClass::openSite($this->dpid,$this->number,$this->isTemp,$this->siteId);
		if($this->isTemp==1){
			$this->getSiteNo($result['siteid']);
		}
	}
	public function getSiteNo($siteId){
		$sql = 'select * from nb_site_no where site_id=:siteId and dpid=:dpid and is_temp=1 and status=1';
		$siteNo = Yii::app()->db->createCommand($sql)
				  ->bindValue(':siteId',$siteId)
				  ->bindValue(':dpid',$this->dpid)
				  ->queryRow();
	    if($siteNo){
	    	$this->siteId = $siteNo['lid'];
	    }
	}
	//生成订单
	public function createOrder(){
		$time = time();
		$orderPrice = 0;
		$realityPrice = 0;
		$accountNo = 0;
		$transaction = Yii::app()->db->beginTransaction();
 		try {
			$se = new Sequence("order");
		    $orderId = $se->nextval();
		    
		    if($this->type==1 && $this->order){
 				$accountNo = $this->order['account_no'];
 			}else{
 				$accountNo = self::getAccountNo($this->dpid,$this->siteId,0,$orderId);
 			}
 			
		    $insertOrderArr = array(
		        	'lid'=>$orderId,
		        	'dpid'=>$this->dpid,
		        	'create_at'=>date('Y-m-d H:i:s',$time),
		        	'update_at'=>date('Y-m-d H:i:s',$time), 
		        	'account_no'=>$accountNo,
		        	'user_id'=>$this->userId,
		        	'site_id'=>$this->siteId,
		        	'is_temp'=>$this->isTemp,
		        	'number'=>$this->number,
		        	'order_status'=>1,
		        	'order_type'=>$this->type,
		        	'is_sync'=>DataSync::getInitSync(),
		        );
			$result = Yii::app()->db->createCommand()->insert('nb_order', $insertOrderArr);
 			
 			//外卖订单地址
 			if($this->type==2){
 				$address = WxAddress::getDefault($this->userId,$this->dpid);
 				if($address){
 					WxOrderAddress::addOrderAddress($orderId,$address);
 				}
 			}
			//整单口味
			if(isset($this->productTastes[0]) && !empty($this->productTastes[0])){
				foreach($this->productTastes[0] as $ordertaste){
					$se = new Sequence("order_taste");
	    			$orderTasteId = $se->nextval();
			 		$orderTasteData = array(
			 								'lid'=>$orderTasteId,
											'dpid'=>$this->dpid,
											'create_at'=>date('Y-m-d H:i:s',$time),
				        					'update_at'=>date('Y-m-d H:i:s',$time),
				        					'taste_id'=>$ordertaste,
				        					'order_id'=>$orderId,
				        					'is_order'=>1,
				        					'is_sync'=>DataSync::getInitSync(),
			 								);
			 		$result = Yii::app()->db->createCommand()->insert('nb_order_taste',$orderTasteData);
				}
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
								'product_name'=>$cart['product_name'],
								'product_pic'=>$cart['main_picture'],
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
				 		$se = new Sequence("order_product_promotion");
		    			$orderproductpromotionId = $se->nextval();
				 		$orderProductPromotionData =array(
			 										'lid'=>$orderproductpromotionId,
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
			 if(($this->type==1||$this->type==3) && $this->seatingFee > 0){
				 	$se = new Sequence("order_product");
			    	$orderProductId = $se->nextval();
		         	$orderProductData = array(
									'lid'=>$orderProductId,
									'dpid'=>$this->dpid,
									'create_at'=>date('Y-m-d H:i:s',$time),
		        					'update_at'=>date('Y-m-d H:i:s',$time), 
									'order_id'=>$orderId,
									'set_id'=>0,
									'product_id'=>0,
									'product_name'=>'餐位费',
									'product_pic'=>'',
									'product_type'=>1,
									'price'=>$this->seatingFee,
									'original_price'=>$this->seatingFee,
									'amount'=>$this->number,
									'product_order_status'=>9,
									'is_sync'=>DataSync::getInitSync(),
									);
					 Yii::app()->db->createCommand()->insert('nb_order_product',$orderProductData);
					$orderPrice +=  $this->seatingFee*$this->number;
				 	$realityPrice += $this->seatingFee*$this->number;
			  }elseif($this->type==2){
			  		if($this->packingFee > 0){
			  			$se = new Sequence("order_product");
				    	$orderProductId = $se->nextval();
			         	$orderProductData = array(
										'lid'=>$orderProductId,
										'dpid'=>$this->dpid,
										'create_at'=>date('Y-m-d H:i:s',$time),
			        					'update_at'=>date('Y-m-d H:i:s',$time), 
										'order_id'=>$orderId,
										'set_id'=>0,
										'product_id'=>0,
										'product_name'=>'包装费',
										'product_pic'=>'',
										'product_type'=>2,
										'price'=>$this->packingFee,
										'original_price'=>$this->packingFee,
										'amount'=>$this->cartNumber,
										'product_order_status'=>9,
										'is_sync'=>DataSync::getInitSync(),
										);
						 Yii::app()->db->createCommand()->insert('nb_order_product',$orderProductData);
						$orderPrice +=  $this->packingFee*$this->cartNumber;
					 	$realityPrice += $this->packingFee*$this->cartNumber;
			  		}
				 	if($this->freightFee > 0){
				 		$se = new Sequence("order_product");
				    	$orderProductId = $se->nextval();
			         	$orderProductData = array(
										'lid'=>$orderProductId,
										'dpid'=>$this->dpid,
										'create_at'=>date('Y-m-d H:i:s',$time),
			        					'update_at'=>date('Y-m-d H:i:s',$time), 
										'order_id'=>$orderId,
										'set_id'=>0,
										'product_id'=>0,
										'product_name'=>'配送费',
										'product_pic'=>'',
										'product_type'=>3,
										'price'=>$this->freightFee,
										'original_price'=>$this->freightFee,
										'amount'=>1,
										'product_order_status'=>9,
										'is_sync'=>DataSync::getInitSync(),
										);
						 Yii::app()->db->createCommand()->insert('nb_order_product',$orderProductData);
						$orderPrice +=  $this->freightFee;
					 	$realityPrice += $this->freightFee;
				 	}
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
		$total = self::updateOrderTotal($order);
		$order['should_total'] = $total['total'];
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
		$sql = 'select t.price,t.amount,t.is_retreat,t1.product_name,t1.main_picture,t.original_price from nb_order_product t,nb_product t1 where t.product_id=t1.lid and t.dpid=t1.dpid and t.order_id = :orderId and t.dpid = :dpid and t.product_type=0 and t.delete_flag=0';
		$orderProduct = Yii::app()->db->createCommand($sql)
				  ->bindValue(':orderId',$orderId)
				  ->bindValue(':dpid',$dpid)
				  ->queryAll();
	    return $orderProduct;
	}
	public static function getOrderProductByType($orderId,$dpid,$type){
		$sql = 'select t.price,t.amount,t.is_retreat from nb_order_product t where t.order_id = :orderId and t.dpid = :dpid and t.product_type=:type and t.delete_flag=0';
		$orderProduct = Yii::app()->db->createCommand($sql)
				  ->bindValue(':orderId',$orderId)
				  ->bindValue(':dpid',$dpid)
				  ->bindValue(':type',$type)
				  ->queryAll();
	    return $orderProduct;
	}
	public static function getUserOrderList($userId,$dpid,$type){
		if($type==1){
			$sql = 'select * from nb_order where dpid=:dpid and user_id=:userId and order_status in (1,2) order by lid desc limit 0,20';
		}elseif($type==2){
			$sql = 'select * from nb_order where dpid=:dpid and user_id=:userId and order_status in (3,4)  order by lid desc limit 0,20';
		}else{
			$sql = 'select * from nb_order where dpid=:dpid and user_id=:userId order by lid desc limit 0,20';
		}
		$orderList = Yii::app()->db->createCommand($sql)
				  ->bindValue(':userId',$userId)
				  ->bindValue(':dpid',$dpid)
				  ->queryAll();
		foreach($orderList as $k=>$order){
			$total = self::updateOrderTotal($order);
			$orderList[$k]['should_total'] = $total['total'];
			$orderList[$k]['order_num'] = $total['count'];
		}
	    return $orderList;
	}
	public static function getOrderAddress($orderId,$dpid){
		$sql = 'select * from nb_order_address where order_lid=:orderId and dpid=:dpid and delete_flag=0';
		$address = Yii::app()->db->createCommand($sql)
				  ->bindValue(':orderId',$orderId)
				  ->bindValue(':dpid',$dpid)
				  ->queryRow();
	    return $address;
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
		$seatingFee = 0;
		$packingFee = 0;
		$freightFee = 0;
		$orderId = $order['lid'];
		$dpid = $order['dpid'];
		$orderProducts = self::getOrderProduct($orderId,$dpid);
		foreach($orderProducts as $product){
			if($product['is_retreat']==0){
				$total += $product['price']*$product['amount'];
				$oTotal += $product['original_price']*$product['amount'];
			}
		}
		$seatingProducts = WxOrder::getOrderProductByType($orderId,$dpid,1);
		foreach($seatingProducts as $seatingProduct){
			$seatingFee += $seatingProduct['price']*$seatingProduct['amount'];
		}
		$packingProducts = WxOrder::getOrderProductByType($orderId,$dpid,2);
		foreach($packingProducts as $packingProduct){
			$packingFee += $packingProduct['price']*$packingProduct['amount'];
		}
		$freightProducts = WxOrder::getOrderProductByType($orderId,$dpid,3);
		foreach($freightProducts as $freightProduct){
			$freightFee += $freightProduct['price']*$freightProduct['amount'];
		}
			
		$total = $total + $seatingFee + $packingFee + $freightFee;
		$oTotal = $oTotal + $seatingFee + $packingFee + $freightFee;
		
		if($order['cupon_branduser_lid']==0 && $total!=$order['should_total']){
			$orderPay = WxOrderPay::get($dpid,$orderId);
			if(empty($orderPay)){
				$isSync = DataSync::getInitSync();
				$sql = 'update nb_order set should_total='.$total.',reality_total='.$oTotal.',is_sync='.$isSync.' where lid='.$orderId.' and dpid='.$dpid;
				Yii::app()->db->createCommand($sql)->execute();
			}else{
				$total = $order['should_total'];
			}
		}else{
			$total = $order['should_total'];
		}
		return array('total'=>$total,'count'=>count($orderProducts));
	}
	public static function updateOrderStatus($orderId,$dpid){
		$now = date('Y-m-d H:i:s',time());
		$isSync = DataSync::getInitSync();
		$sql = 'update nb_order set order_status=3,paytype=1,pay_time="'.$now.'",is_sync='.$isSync.' where lid='.$orderId.' and dpid='.$dpid;
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
	 * 更改订单备注
	 * 
	 * 
	 */
	 public static function updateRemark($orderId,$dpid,$remark){
	 	$isSync = DataSync::getInitSync();
		$sql = 'update nb_order set remark="'.$remark.'",is_sync='.$isSync.' where lid='.$orderId.' and dpid='.$dpid;
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
		$sql = 'select t1.cupon_money,t1.min_consumer from nb_cupon_branduser t,nb_cupon t1 where t.cupon_id=t1.lid and t.dpid=t1.dpid and  t.lid='.$cuponBranduserLid.
				' and t.dpid='.$dpid.' and t1.begin_time <= "'.$now.'" and "'.$now.'" <= t1.end_time and t1.delete_flag=0 and t1.is_available=0';
		$result = Yii::app()->db->createCommand($sql)->queryRow();
		if($result && $order['should_total'] > $result['min_consumer']){
			$isSync = DataSync::getInitSync();
			$money = ($order['should_total'] - $result['cupon_money']) >0 ? $order['should_total'] - $result['cupon_money'] : 0;
			$cuponMoney = $result['cupon_money'];
			$sql = 'update nb_order set cupon_branduser_lid='.$cuponBranduserLid.',cupon_money='.$cuponMoney.',should_total='.$money.',is_sync='.$isSync.' where lid='.$orderId.' and dpid='.$dpid;
			$res = Yii::app()->db->createCommand($sql)->execute();
			if($res){
				return true;
			}else{
				return false;
			}
		}
		return false;
	}
	/**
	 * 
	 * 更改订单信息
	 * 
	 * 
	 */
	 public static function update($orderId,$dpid,$contion){
	 	$isSync = DataSync::getInitSync();
		$sql = 'update nb_order set '.$contion.'is_sync='.$isSync.' where lid='.$orderId.' and dpid='.$dpid;
		Yii::app()->db->createCommand($sql)->execute();
	}
	/**
	 * 
	 * 取消订单
	 * 
	 */
	 public static function cancelOrder($orderId,$dpid){
	 	$isSync = DataSync::getInitSync();
		$sql = 'update nb_order set order_status=7,is_sync='.$isSync.' where lid='.$orderId.' and dpid='.$dpid;
		$result = Yii::app()->db->createCommand($sql)->execute();
		return $result;
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
	 		$payMoney = self::reduceYue($user,$order);
	 		
	 		$se = new Sequence("order_pay");
		    $orderPayId = $se->nextval();
		    $insertOrderPayArr = array(
		        	'lid'=>$orderPayId,
		        	'dpid'=>$order['dpid'],
		        	'create_at'=>date('Y-m-d H:i:s',$time),
		        	'update_at'=>date('Y-m-d H:i:s',$time), 
		        	'order_id'=>$order['lid'],
		        	'account_no'=>$order['account_no'],
		        	'pay_amount'=>$payMoney,
		        	'paytype'=>$paytype,
		        	'is_sync'=>DataSync::getInitSync(),
		        );
			$result = Yii::app()->db->createCommand()->insert('nb_order_pay', $insertOrderPayArr);
	 		
	 	}else{
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
	 	}
	 	
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
		if($paytype != 10){
			//返现或者积分
			$back = new WxCashBack($order['dpid'],$order['user_id'],$order['should_total']);
			$back->inRecord($order['lid']);
		}
	 }
	/**
	 * 
	 * 扣除会员余额
	 * 
	 */
	 public static function reduceYue($user,$order){
	 	$payMoney = 0;
	 	$userId = $user['lid'];
	 	$dpid = $order['dpid'];
	 	$total = $order['should_total'];
	 	$isSync = DataSync::getInitSync();
	 	
	 	$yue = WxBrandUser::getYue($userId,$dpid);//余额
	 	$cashback = WxBrandUser::getCashBackYue($userId,$dpid);//返现余额
	 	
	 	if($cashback > 0){
	 		//返现余额大于等于支付
	 		if($cashback >= $total){
	 			$sql = 'update nb_order set should_total = 0,is_sync='.$isSync.' where lid='.$order['lid'].' and dpid='.$dpid;
				$result = Yii::app()->db->createCommand($sql)->execute();
					
	 			WxCashBack::userCashBack($total,$userId,$dpid,0);
	 			//修改订单状态
				WxOrder::updateOrderStatus($order['lid'],$order['dpid']);
				//修改订单产品状态
				WxOrder::updateOrderProductStatus($order['lid'],$order['dpid']);
				//修改座位状态
				if($order['order_type']==1){
					WxSite::updateSiteStatus($order['site_id'],$order['dpid'],3);
				}
				$payMoney = $total;
	 		}else{
	 			WxCashBack::userCashBack($total,$userId,$dpid,1);
	 			if($yue > $total){//剩余充值大于支付
 					$sql = 'update nb_brand_user set remain_money = remain_money-'.($total - $cashback).',is_sync='.$isSync.' where lid='.$user['lid'].' and dpid='.$dpid;
					$result = Yii::app()->db->createCommand($sql)->execute();
					
					$sql = 'update nb_order set should_total = 0,is_sync='.$isSync.' where lid='.$order['lid'].' and dpid='.$dpid;
					$result = Yii::app()->db->createCommand($sql)->execute();
					
					//修改订单状态
					WxOrder::updateOrderStatus($order['lid'],$order['dpid']);
					//修改订单产品状态
					WxOrder::updateOrderProductStatus($order['lid'],$order['dpid']);
					//修改座位状态
					if($order['order_type']==1){
						WxSite::updateSiteStatus($order['site_id'],$order['dpid'],3);
					}
					
					//返现或者积分
					$back = new WxCashBack($order['dpid'],$order['user_id'],$total - $cashback);
					$back->inRecord($order['lid']);
					$payMoney = $total;
	 			}else{
	 				$sql = 'update nb_brand_user set remain_money = 0,is_sync='.$isSync.' where lid='.$user['lid'].' and dpid='.$dpid;
					$result = Yii::app()->db->createCommand($sql)->execute();
					
					$sql = 'update nb_order set should_total = '.($total - $yue).',is_sync='.$isSync.' where lid='.$order['lid'].' and dpid='.$dpid;
					$result = Yii::app()->db->createCommand($sql)->execute();
					$payMoney = $yue;
	 			}
	 		}
	 	}else{
	 		if($yue > $total){
				$sql = 'update nb_brand_user set remain_money = remain_money-'.$total.',is_sync='.$isSync.' where lid='.$user['lid'].' and dpid='.$dpid;
				$result = Yii::app()->db->createCommand($sql)->execute();
				
				$sql = 'update nb_order set should_total = 0,is_sync='.$isSync.' where lid='.$order['lid'].' and dpid='.$dpid;
				$result = Yii::app()->db->createCommand($sql)->execute();
				//修改订单状态
				WxOrder::updateOrderStatus($order['lid'],$order['dpid']);
				//修改订单产品状态
				WxOrder::updateOrderProductStatus($order['lid'],$order['dpid']);
				//修改座位状态
				if($order['order_type']==1){
					WxSite::updateSiteStatus($order['site_id'],$order['dpid'],3);
				}
				
				//返现或者积分
				$back = new WxCashBack($order['dpid'],$order['user_id'],$total - $cashback);
				$back->inRecord($order['lid']);
				$payMoney = $total;
 			}else{
 				$sql = 'update nb_brand_user set remain_money = 0,is_sync='.$isSync.' where lid='.$user['lid'].' and dpid='.$dpid;
				$result = Yii::app()->db->createCommand($sql)->execute();
				
				$sql = 'update nb_order set should_total = '.($total - $yue).',is_sync='.$isSync.' where lid='.$order['lid'].' and dpid='.$dpid;
				$result = Yii::app()->db->createCommand($sql)->execute();
				$payMoney = $yue;
 			}
	 	}
	 	
	 	return $payMoney;
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