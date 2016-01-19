<?php 
/**
 * 
 * 
 * 微信端购物车类
 * //堂吃必须有siteId
 * productArr = array('product_id'=>1,'num'=>1,'privation_promotion_id'=>-1)
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
	public function isCart(){
		$sql = 'select * from nb_cart where dpid=:dpid and user_id=:userId and product_id=:productId and privation_promotion_id=:privationPromotionId';
		$this->cart = Yii::app()->db->createCommand($sql)
					  ->bindValue(':dpid',$this->dpid)
					  ->bindValue(':userId',$this->userId)
					  ->bindValue(':productId',$this->productArr['product_id'])
					  ->bindValue(':privationPromotionId',$this->productArr['privation_promotion_id'])
					  ->queryRow();
	}
	public function checkPromotion(){
		if($this->productArr['privation_promotion_id'] > 0){
			$sqla = 'select count(*) as count from nb_cart where dpid=:dpid and user_id=:userId and privation_promotion_id=:privationPromotionId';
			$resulta = Yii::app()->db->createCommand($sqla)
					  ->bindValue(':dpid',$this->dpid)
					  ->bindValue(':userId',$this->userId)
					  ->bindValue(':privationPromotionId',$this->productArr['privation_promotion_id'])
					  ->queryRow();
					  
			$sql = 'select t.order_num as product_num,t1.order_num,t1.promotion_type from nb_private_promotion_detail t,nb_private_promotion t1 where t.private_promotion_id=t1.lid and t.dpid=t1.dpid and t.private_promotion_id=:privationPromotionId and t.dpid=:dpid and t.product_id=:productId and t.is_set=0 and t.delete_flag=0';
			$result = Yii::app()->db->createCommand($sql)
						  ->bindValue(':dpid',$this->dpid)
						  ->bindValue(':productId',$this->productArr['product_id'])
						  ->bindValue(':privationPromotionId',$this->productArr['privation_promotion_id'])
						  ->queryRow();
			if($result['promotion_type']==0){
				$cartPromotions = $this->getCartPromotion();
				if(!empty($cartPromotions)){
					foreach($cartPromotions as $promotion){
						$privatePromotion = WxPromotion::getPromotion($this->dpid,$promotion['privation_promotion_id']);
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
		$sql = 'select t.dpid,t.product_id,t.num,t.privation_promotion_id,t.to_group,t1.product_name,t1.main_picture,t1.original_price from nb_cart t,nb_product t1 where t.product_id=t1.lid and t.dpid=t1.dpid and t.dpid=:dpid and t.user_id=:userId';
		$results = Yii::app()->db->createCommand($sql)
				  ->bindValue(':dpid',$this->dpid)
				  ->bindValue(':userId',$this->userId)
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
			$results[$k]['taste_groups'] = WxTaste::getProductTastes($result['product_id'],$this->dpid);
		}
		return $results;
	}
	public function getCartPromotion(){
		$sql = 'select * from nb_cart where dpid=:dpid and user_id=:userId and privation_promotion_id > 0 and privation_promotion_id!=:privationPromotionId';
		$results = Yii::app()->db->createCommand($sql)
				  ->bindValue(':dpid',$this->dpid)
				  ->bindValue(':userId',$this->userId)
				  ->bindValue(':privationPromotionId',$this->productArr['privation_promotion_id'])
				  ->queryAll();
		return $results;
	}
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
	        	'num'=>$this->productArr['num'],
	        	'site_id'=>$this->siteId,
	        	'privation_promotion_id'=>$this->productArr['privation_promotion_id'],
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
			$price += $cart['price'];
		}
		return $price;
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
}