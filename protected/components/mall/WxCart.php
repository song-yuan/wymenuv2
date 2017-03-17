<?php 
/**
 * 
 * 
 * 微信端购物车类
 * //堂吃必须有siteId
 * productArr = array('product_id'=>1,'num'=>1,'promotion_id'=>-1)
 * 
 */
class WxCart
{
	
	
	public $dpid;
	public $userId;
	public $siteId;
	public $productArr = array();
	public $cart = array();
	
	public function __construct($dpid,$userId,$productArr = array(),$siteId){
		$this->dpid = $dpid;
		$this->userId = $userId;
		$this->siteId = $siteId;
		$this->productArr = $productArr;
		if(!empty($this->productArr)){
			$this->isCart();
		}
	}
	//加入购物车 判断
	public function isCart(){
		$sql = 'select * from nb_cart where dpid=:dpid and user_id=:userId and product_id=:productId and is_set=:isSet and promotion_id=:privationPromotionId';
		$this->cart = Yii::app()->db->createCommand($sql)
					  ->bindValue(':dpid',$this->dpid)
					  ->bindValue(':userId',$this->userId)
					  ->bindValue(':productId',$this->productArr['product_id'])
					  ->bindValue(':isSet',$this->productArr['is_set'])
					  ->bindValue(':privationPromotionId',$this->productArr['promotion_id'])
					  ->queryRow();
	}
	//判断产品库存
	public function checkStoreNumber(){
		if($this->productArr['is_set']){
			$sql = 'select * from nb_product_set where lid=:productId and dpid=:dpid and delete_flag=0';
		}else{
			$sql = 'select * from nb_product where lid=:productId and dpid=:dpid and delete_flag=0';
		}
		$product = Yii::app()->db->createCommand($sql)
					  ->bindValue(':dpid',$this->dpid)
					  ->bindValue(':productId',$this->productArr['product_id'])
					  ->queryRow();
		if($product['store_number']==0){
			return array('status'=>false,'msg'=>'该产品已售罄!');
		}
		if($product['store_number'] > 0){
			if($this->cart && $this->cart['num'] >= $product['store_number']){
				return array('status'=>false,'msg'=>'超出产品库存!');
			}
		}
		return array('status'=>true,'msg'=>'');
	}
	// 如果产品有特价活动
	public function checkPromotion(){
		if($this->productArr['promotion_id'] > 0){
			$sqla = 'select count(*) as count from nb_cart where dpid=:dpid and user_id=:userId and promotion_id=:privationPromotionId';
			$resulta = Yii::app()->db->createCommand($sqla)
					  ->bindValue(':dpid',$this->dpid)
					  ->bindValue(':userId',$this->userId)
					  ->bindValue(':privationPromotionId',$this->productArr['promotion_id'])
					  ->queryRow();
					  
			$sql = 'select t.order_num as product_num,t1.order_num,t1.promotion_type from nb_normal_promotion_detail t,nb_normal_promotion t1 where t.normal_promotion_id=t1.lid and t.dpid=t1.dpid and t.normal_promotion_id=:privationPromotionId and t.dpid=:dpid and t.product_id=:productId and t.is_set=0 and t.delete_flag=0';
			$result = Yii::app()->db->createCommand($sql)
						  ->bindValue(':dpid',$this->dpid)
						  ->bindValue(':productId',$this->productArr['product_id'])
						  ->bindValue(':privationPromotionId',$this->productArr['promotion_id'])
						  ->queryRow();
			if($result['promotion_type']==0){
				$cartPromotions = $this->getCartPromotion();
				if(!empty($cartPromotions)){
					foreach($cartPromotions as $promotion){
						$privatePromotion = WxPromotion::getPromotion($this->dpid,$promotion['promotion_id']);
						if($privatePromotion['promotion_type']==0){
							return array('status'=>false,'msg'=>'本活动不与其他活动同时使用!');
						}
					}
				}
			}
			if($result['order_num']==0){
				if($result['product_num']==0){
					return array('status'=>true,'msg'=>'OK');
				}else{
					if((isset($this->cart['num'])?$this->cart['num']:0) >= $result['product_num']){
						return array('status'=>false,'msg'=>'超过活动商品数量!');
					}
				}
			}else{
				if($result['product_num']==0){
					if(!$this->cart && $resulta['count'] >= $result['order_num']){
						return array('status'=>false,'msg'=>'超过活动商品数量!');
					}
				}else{
					if((!$this->cart &&$resulta['count'] >= $result['order_num'])||(isset($this->cart['num'])?$this->cart['num']:0) >= $result['product_num']){
						return array('status'=>false,'msg'=>'超过活动商品数量!');
					}
				}
			}
			return array('status'=>true,'msg'=>'OK');
		}
	}
	public function getCart(){
		$sql = 'select t.dpid,t.product_id,t.is_set,t.num,t.promotion_id,t.to_group,t1.product_name,t1.main_picture,t1.original_price from nb_cart t,nb_product t1 where t.product_id=t1.lid and t.dpid=t1.dpid and t.dpid=:dpid and t.user_id=:userId and t.is_set=0';
		$sql .= ' union select t.dpid,t.product_id,t.is_set,t.num,t.promotion_id,t.to_group,t1.set_name as product_name,t1.main_picture,t1.set_price as original_price from nb_cart t,nb_product_set t1 where t.product_id=t1.lid and t.dpid=t1.dpid and t.dpid=:dpid and t.user_id=:userId and t.is_set=1';
		$results = Yii::app()->db->createCommand($sql)
				  ->bindValue(':dpid',$this->dpid)
				  ->bindValue(':userId',$this->userId)
				  ->queryAll();
		foreach($results as $k=>$result){
			if($result['promotion_id'] > 0){
				$productPrice = WxPromotion::getPromotionPrice($result['dpid'],$this->userId,$result['product_id'],$result['is_set'],$result['promotion_id'],$result['to_group']);
				$results[$k]['price'] = $productPrice['price'];
				$results[$k]['promotion'] = $productPrice;
			}else{
				$productPrice = new WxProductPrice($result['product_id'],$result['dpid'],$result['is_set']);
				$results[$k]['price'] = $productPrice->price;
				$results[$k]['promotion'] = $productPrice->promotion;
			}
			$results[$k]['taste_groups'] = WxTaste::getProductTastes($result['product_id'],$this->dpid);
		}
		return $results;
	}
	public function getCartPromotion(){
		$sql = 'select * from nb_cart where dpid=:dpid and user_id=:userId and promotion_id > 0 and promotion_id!=:privationPromotionId';
		$results = Yii::app()->db->createCommand($sql)
				  ->bindValue(':dpid',$this->dpid)
				  ->bindValue(':userId',$this->userId)
				  ->bindValue(':privationPromotionId',$this->productArr['promotion_id'])
				  ->queryAll();
		return $results;
	}
	/**
	 * @return boolean
	 * 
	 * 增加菜品
	 * 
	 */
	public function addCart(){
		$success = false;
		$time = time();
		if(empty($this->cart)){
			$se = new Sequence("cart");
	        $lid = $se->nextval();
	        $insertCartArr = array(
	        	'lid'=>$lid,
	        	'dpid'=>$this->dpid,
	        	'create_at'=>date('Y-m-d H:i:s',$time),
	        	'update_at'=>date('Y-m-d H:i:s',$time), 
	        	'user_id'=>$this->userId,
	        	'product_id'=>$this->productArr['product_id'],
        		'is_set'=>$this->productArr['is_set'],
	        	'num'=>$this->productArr['num'],
	        	'site_id'=>$this->siteId,
	        	'promotion_id'=>$this->productArr['promotion_id'],
	        	'to_group'=>$this->productArr['to_group'],
	        	'is_sync'=>DataSync::getInitSync(),	
	        );
			$result = Yii::app()->db->createCommand()->insert('nb_cart', $insertCartArr);
	        if($result){
	        	$success = true;
	        }
		}else{
			$isSync = DataSync::getInitSync();
			$sql = 'update nb_cart set num=num+1,is_sync='.$isSync.' where lid=:lid and dpid=:dpid';
			$result = Yii::app()->db->createCommand($sql)
					  ->bindValue(':dpid',$this->dpid)
					  ->bindValue(':lid',$this->cart['lid'])->execute();
			if($result){
	        	$success = true;
	        }
		}
		return $success;
	}
	/**
	 * 
	 * @return boolean
	 * 
	 * 减少菜品
	 */
	public function deleteCart(){
		$success = false;
		$time = time();
		if($this->cart['num'] > 1){
			$isSync = DataSync::getInitSync();
			$sql = 'update nb_cart set num=num-1,is_sync='.$isSync.' where lid=:lid and dpid=:dpid';
			$result = Yii::app()->db->createCommand($sql)
					  ->bindValue(':dpid',$this->dpid)
					  ->bindValue(':lid',$this->cart['lid'])->execute();
	        if($result){
	        	$success = true;
	        }
		}else{
			$sql = 'delete from nb_cart where lid=:lid and dpid=:dpid';
			$result = Yii::app()->db->createCommand($sql) 
					  ->bindValue(':dpid',$this->dpid)
					  ->bindValue(':lid',$this->cart['lid'])->execute();
			if($result){
	        	$success = true;
	        }
		}
		return $success;
	}
	public static function getCartPrice($cartArrs){
		$price = 0;
		foreach($cartArrs as $cart){
			$price += $cart['price']*$cart['num'];
		}
		return number_format($price,2);
	}
	public static function clearCart($userId,$dpid){
		$sql = 'delete from nb_cart where dpid='.$dpid.' and user_id='.$userId;
		$result = Yii::app()->db->createCommand($sql)->execute();
		return $result;
	}
	public static function updateSiteId($userId,$dpid,$siteId){
		$isSync = DataSync::getInitSync();
		$sql = 'update nb_cart set site_id='.$siteId.',is_sync='.$isSync.' where dpid='.$dpid.' and user_id='.$userId.' and site_id!='.$siteId;
		Yii::app()->db->createCommand($sql)->execute();
	}
	public static function isEmptyCart($userId,$dpid){
		$sql = 'select * from nb_cart where dpid=:dpid and user_id=:userId';
		$results = Yii::app()->db->createCommand($sql)
				  ->bindValue(':dpid',$dpid)
				  ->bindValue(':userId',$userId)
				  ->queryAll();
		return $results;
	}
}