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
	
	public function __construct($dpid,$userId,$productArr = array(),$siteId = null){
		$this->dpid = $dpid;
		$this->userId = $userId;
		$this->siteId = $siteId;
		$this->productArr = $productArr;
		if(!empty($this->productArr)){
			$this->isCart();
		}
	}
	public function isCart(){
		$sql = 'select * from nb_cart where dpid=:dpid and user_id=:userId and product_id=:productId and site_id=:siteId and privation_promotion_id=:privationPromotionId';
		$this->cart = Yii::app()->db->createCommand($sql)
					  ->bindValue(':dpid',$this->dpid)
					  ->bindValue(':userId',$this->userId)
					  ->bindValue(':productId',$this->productArr['product_id'])
					  ->bindValue(':siteId',$this->siteId)
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
					  
			$sql = 'select t.order_num as product_num,t1.order_num from nb_private_promotion_detail t,nb_private_promotion t1 where t.private_promotion_id=t1.lid and t.dpid=t1.dpid and t.private_promotion_id=:privationPromotionId and t.dpid=:dpid and t.product_id=:productId and t.is_set=0';
			$result = Yii::app()->db->createCommand($sql)
						  ->bindValue(':dpid',$this->dpid)
						  ->bindValue(':productId',$this->productArr['product_id'])
						  ->bindValue(':privationPromotionId',$this->productArr['privation_promotion_id'])
						  ->queryRow();
			if($resulta['count']>=$result['order_num'] ||$result['product_num'] >= $this->cart['num']){
				return array('status'=>false,'msg'=>'超过活动商品数量!');
			}
				return array('status'=>true,'msg'=>'OK');
		}
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
	        );
			$result = Yii::app()->db->createCommand()->insert('nb_cart', $insertCartArr);
	        if($result){
	        	$success = true;
	        }
		}else{
			$sql = 'update nb_cart set num=num+1 where lid=:lid and dpid=:dpid';
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
			$sql = 'update nb_cart set num=num-1 where lid=:lid and dpid=:dpid';
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
	public static function updateSiteId($userId,$dpid,$siteId){
		$sql = 'update nb_cart set site_id='.$siteId.' where dpid='.$dpid.' and user_id='.$userId.' and site_id!='.$siteId;
		Yii::app()->db->createCommand($sql)->execute();
	}
}