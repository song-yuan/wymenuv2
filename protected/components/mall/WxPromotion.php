<?php 
/**
 * 
 * 
 * 微信端 普通优惠活动
 * 
 */
class WxPromotion
{
	public $dpid;
	public $userId;
	public $promotionProductList;
	public function __construct($dpid,$userId){
		$this->dpid = $dpid;
		$this->userId = $userId;
		$this->getPromotionDetail();
	}
	public function getPromotionDetail(){
		$now = date('Y-m-d H:i:s',time());
		$sql = 'select t.*,t1.begin_time,t1.end_time,t1.weekday,t1.day_begin,t1.day_end,t1.order_num as all_order_num from nb_normal_promotion_detail t,nb_normal_promotion t1 where t.normal_promotion_id=t1.lid and t.dpid=t1.dpid and t.dpid=:dpid and t1.begin_time <= :now and t1.end_time >= :now and t.delete_flag=0 and t1.delete_flag=0';
		$results = Yii::app()->db->createCommand($sql)->bindValue(':dpid',$this->dpid)->bindValue(':now',$now)->queryAll();
		
		foreach($results as $k=>$result){
			if($result['is_set']){
				//套餐	
				$sql = 'select t.* from nb_product_set t left join nb_cart t1 on t.lid=t1.product_id and t1.user_id=:userId and t1.promotion_id=:promotionId and t1.is_set=1 where t.lid=:lid and t.dpid=:dpid and t.delete_flag=0 and t.status=0 and t.is_show=1';
				$product = Yii::app()->db->createCommand($sql)->bindValue(':lid',$result['product_id'])->bindValue(':dpid',$this->dpid)->bindValue(':userId',$this->userId)->bindValue(':promotionId',$result['normal_promotion_id'])->queryRow();
				if($product){
					if($result['is_discount']==0){
						$product['price'] = ($product['set_price'] - $result['promotion_money']) > 0 ? number_format($product['set_price'] - $result['promotion_money'],2) : number_format(0,2);
					}else{
						$product['price'] = ($product['set_price']*$result['promotion_discount']) > 0 ? number_format($product['set_price']*$result['promotion_discount'],2) : number_format(0,2);
					}
					$results[$k]['product'] = $product;
				}else{
					unset($results[$k]);
				}
			}else{
				//单品
				$sql = 'select t.* from nb_product t left join nb_cart t1 on t.lid=t1.product_id and t1.user_id=:userId and t1.promotion_id=:promotionId and t1.is_set=0 where t.lid=:lid and t.dpid=:dpid and t.delete_flag=0 and t.status=0 and t.is_show=1';
				$product = Yii::app()->db->createCommand($sql)->bindValue(':lid',$result['product_id'])->bindValue(':dpid',$this->dpid)->bindValue(':userId',$this->userId)->bindValue(':promotionId',$result['normal_promotion_id'])->queryRow();
				if($product){
					if($result['is_discount']==0){
						$product['price'] = ($product['original_price'] - $result['promotion_money']) > 0 ? number_format($product['original_price'] - $result['promotion_money'],2) : number_format(0,2);
					}else{
						$product['price'] = ($product['original_price']*$result['promotion_discount']) > 0 ? number_format($product['original_price']*$result['promotion_discount'],2) : number_format(0,2);
					}
					$results[$k]['product'] = $product;
				}else{
					unset($results[$k]);
				}
			}
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