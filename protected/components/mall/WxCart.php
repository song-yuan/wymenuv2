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
	
	public function __construct($dpid,$userId,$productArr,$siteId = null){
		$this->dpid = $dpid;
		$this->userId = $userId;
		$this->siteId = $siteId;
		$this->productArr = $productArr;
		$this->isCart();
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
}