<?php 
/**
 * 
 * 
 * 微信端个人优惠类
 * 
 */
class WxPromotion
{
	public $dpid;
	public $userId;
	public $siteId;
	
	public function __construct($dpid,$userId){
		$this->dpid = $dpid;
		$this->userId = $userId;
		$this->getUser();
		$this->getUserPromotion();
	}
	public function getUser(){
		$sql = 'select * from nb_brand_user where lid=:lid and dpid=:dpid';
		$this->user = Yii::app()->db->createCommand($sql)->bindValue(':dpid',$this->dpid)->bindValue(':lid',$this->userId)->queryRow();
	}
	public function getUserPromotion(){
		$now = date('Y-m-d H:i:s',time());
		$sql = 'select t.private_promotion_id,t.to_group,t1.promotion_title,t1.promotion_type,t1.order_num from nb_private_branduser t,nb_private_promotion t1 where t.private_promotion_id=t1.lid and t.dpid=t1.dpid and t.brand_user_lid=:userId and t.dpid=:dpid and t1.begin_time <= :now and :now <= t1.end_time and t.is_used=1 and t.to_group=3 and t.delete_flag=0 and t1.is_available=0 and t1.delete_flag=0' .
			   ' union select t.private_promotion_id,t.to_group,t1.promotion_title,t1.promotion_type,t1.order_num from nb_private_branduser t,nb_private_promotion t1 where t.private_promotion_id=t1.lid and t.dpid=t1.dpid and t.brand_user_lid=:userLevelId and t.dpid=:dpid and t1.begin_time <= :now and :now <= t1.end_time and t.is_used=1 and t.to_group=2 and t.delete_flag=0 and t1.is_available=0 and t1.delete_flag=0';
		$results = Yii::app()->db->createCommand($sql)->bindValue(':dpid',$this->dpid)->bindValue(':userId',$this->user['lid'])->bindValue(':userLevelId',$this->user['user_level_lid'])->bindValue(':now',$now)->queryAll();
		foreach($results as $k=>$result){
			$sql = 'select m.*,n.num from (select t.product_id,t.is_set,t.is_discount,t.promotion_money,t.promotion_discount,t.order_num,t1.product_name,t1.main_picture,t1.original_price,t1.store_number from nb_private_promotion_detail t,nb_product t1 where t.product_id=t1.lid and t.dpid=t1.dpid and t.dpid=:dpid and t.private_promotion_id=:promotionId and t.delete_flag=0 and t1.status=0 and t1.is_show=1)m left join nb_cart n on m.product_id=n.product_id and n.user_id=:userId and privation_promotion_id=:promotionId';
			$products = Yii::app()->db->createCommand($sql)->bindValue(':dpid',$this->dpid)->bindValue(':userId',$this->user['lid'])->bindValue(':promotionId',$result['private_promotion_id'])->queryAll();
			foreach($products as $j=>$product){
//				$products[$j]['price'] = self::getPromotionPrice($this->dpid,$this->userId,$product['product_id'],$result['private_promotion_id'],$result['to_group']);
				if($product['is_discount']==0){
					$products[$j]['price'] = ($product['original_price'] - $product['promotion_money']) > 0 ? number_format($product['original_price'] - $product['promotion_money'],2) : number_format(0,2);
				}else{
					$products[$j]['price'] = ($product['original_price']*$product['promotion_discount']) > 0 ? number_format($product['original_price']*$product['promotion_discount'],2) : number_format(0,2);
				}
			}
			$results[$k]['productList'] = $products;
		}
		$this->promotionProductList = $results;
	}
	/**
	 * 获取活动信息
	 * 
	 */
	 public static function getPromotion($dpid,$promotionId){
	 	$sql = 'select * from  nb_private_promotion where dpid=:dpid and lid=:lid and delete_flag=0';
	 	$result = Yii::app()->db->createCommand($sql)->bindValue(':dpid',$dpid)->bindValue(':lid',$promotionId)->queryRow();
	 	return $result;
	 }
	/**
	 * 
	 * 产品特价活动价格
	 * 
	 */
	 public static function getPromotionPrice($dpid,$userId,$productId,$promotionId,$toGroup){
	 	$now = date('Y-m-d H:i:s',time());
	 	$user = WxBrandUser::get($userId,$dpid);
	 	$product = WxProduct::getProduct($productId,$dpid);
	 	if($toGroup == 2){
	 		$sql = 'select t.private_promotion_id,t1.promotion_title,t1.promotion_type,t1.order_num from nb_private_branduser t,nb_private_promotion t1 where t.private_promotion_id=t1.lid and t.dpid=t1.dpid and t.private_promotion_id=:privatePromotionId and t.brand_user_lid=:userLevelId and t.dpid=:dpid and t1.begin_time <= :now and :now <= t1.end_time and t.is_used=1 and t.to_group=2 and t.delete_flag=0 and t1.is_available=0 and t1.delete_flag=0';
	 		$result = Yii::app()->db->createCommand($sql)->bindValue(':dpid',$dpid)->bindValue(':privatePromotionId',$promotionId)->bindValue(':userLevelId',$user['user_level_lid'])->bindValue(':now',$now)->queryRow();
	 	}elseif($toGroup == 3){
	 		$sql = 'select t.private_promotion_id,t.to_group,t1.promotion_title,t1.promotion_type,t1.order_num from nb_private_branduser t,nb_private_promotion t1 where t.private_promotion_id=t1.lid and t.dpid=t1.dpid and t.brand_user_lid=:userId and t.dpid=:dpid and t1.begin_time <= :now and :now <= t1.end_time and t.is_used=1 and t.to_group=3 and t.delete_flag=0 and t1.is_available=0 and t1.delete_flag=0';
	 		$result = Yii::app()->db->createCommand($sql)->bindValue(':dpid',$dpid)->bindValue(':privatePromotionId',$promotionId)->bindValue(':userId',$user['lid'])->bindValue(':now',$now)->queryRow();
	 	}
	 	if($result){
	 		$sql = 'select * from nb_private_promotion_detail where dpid=:dpid and private_promotion_id=:promotionId and product_id=:productId and delete_flag=0';
	 		$promotion = Yii::app()->db->createCommand($sql)->bindValue(':dpid',$dpid)->bindValue(':promotionId',$result['private_promotion_id'])->bindValue(':productId',$productId)->queryRow();
	 		if($promotion){
	 			if($promotion['is_discount']==0){
	 				$price = ($product['original_price'] - $promotion['promotion_money']) > 0 ? number_format($product['original_price'] - $promotion['promotion_money'],2) : number_format(0,2);
	 				$promotion_money = $price ? $promotion['promotion_money'] : $price;
	 				return array('promotion_type'=>1,'price'=>$price,'promotion_info'=>array(array('is_discount'=>0,'promotion_money'=>$promotion_money,'poromtion_id'=>$promotion['private_promotion_id'])));
	 			}else{
	 				$price = number_format($product['original_price']*$promotion['promotion_discount'],2);
	 				$promotion_money = $product['original_price'] -$price;
	 				return array('promotion_type'=>1,'price'=>$price,'promotion_info'=>array(array('is_discount'=>0,'promotion_money'=>$promotion_money,'poromtion_id'=>$promotion['private_promotion_id'])));
	 			}
	 		}else{
	 			return array('promotion_type'=>-1,'price'=>$product['original_price'],'promotion_info'=>array());
	 		}
	 	}else{
	 		return array('promotion_type'=>-1,'price'=>$product['original_price'],'promotion_info'=>array());
	 	}
	 }
}