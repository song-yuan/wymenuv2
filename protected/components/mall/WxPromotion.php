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
	public $type;
	public $promotionProductList;
	public function __construct($dpid,$userId,$type){
		$this->dpid = $dpid;
		$this->userId = $userId;
		$this->type = $type;
		$this->getPromotionDetail();
	}
	public function getPromotionDetail(){
		$now = date('Y-m-d H:i:s',time());
		if($this->type == '6'){
			$sql = 'select t.*,t1.promotion_title,t1.main_picture,t1.to_group,t1.can_cupon,t1.group_id,t1.begin_time,t1.end_time,t1.weekday,t1.day_begin,t1.day_end,t1.order_num as all_order_num from nb_normal_promotion_detail t,nb_normal_promotion t1 where t.normal_promotion_id=t1.lid and t.dpid=t1.dpid and t.dpid=:dpid and t1.begin_time <= :now and t1.end_time >= :now and (t1.is_available=2 or t1.is_available=3 or t1.is_available=4) and t.delete_flag=0 and t1.delete_flag=0';
		}elseif($this->type == '2'){
			$sql = 'select t.*,t1.promotion_title,t1.main_picture,t1.to_group,t1.can_cupon,t1.group_id,t1.begin_time,t1.end_time,t1.weekday,t1.day_begin,t1.day_end,t1.order_num as all_order_num from nb_normal_promotion_detail t,nb_normal_promotion t1 where t.normal_promotion_id=t1.lid and t.dpid=t1.dpid and t.dpid=:dpid and t1.begin_time <= :now and t1.end_time >= :now and (t1.is_available=2 or t1.is_available=3 or t1.is_available=5) and t.delete_flag=0 and t1.delete_flag=0';
		}else{
			$sql = 'select t.*,t1.promotion_title,t1.main_picture,t1.to_group,t1.can_cupon,t1.group_id,t1.begin_time,t1.end_time,t1.weekday,t1.day_begin,t1.day_end,t1.order_num as all_order_num from nb_normal_promotion_detail t,nb_normal_promotion t1 where t.normal_promotion_id=t1.lid and t.dpid=t1.dpid and t.dpid=:dpid and t1.begin_time <= :now and t1.end_time >= :now and (t1.is_available=2 or t1.is_available=3) and t.delete_flag=0 and t1.delete_flag=0';
		}
		$results = Yii::app()->db->createCommand($sql)->bindValue(':dpid',$this->dpid)->bindValue(':now',$now)->queryAll();
		$promotionArr = array();
		foreach($results as $k=>$result){
			if($result['to_group']==2){
				// 会员等级活动
				$user = WxBrandUser::get($this->userId, $this->dpid);
				$promotionUser = self::getPromotionUser($this->dpid, $user['user_level_lid'], $result['normal_promotion_id']);
				if(empty($promotionUser)){
					continue;
				}
			}
			if($result['is_set'] > 0){
				//套餐	
				$sql = 'select * from nb_product_set where lid=:lid and dpid=:dpid and delete_flag=0 and status=0 and is_show=1 and is_show_wx=1';
				$product = Yii::app()->db->createCommand($sql)->bindValue(':lid',$result['product_id'])->bindValue(':dpid',$this->dpid)->queryRow();
				if($product){
					if($result['is_discount']==0){
						$product['price'] = ($product['set_price'] - $result['promotion_money']) > 0 ? number_format($product['set_price'] - $result['promotion_money'],2) : number_format(0,2);
					}else{
						$product['price'] = ($product['set_price']*$result['promotion_discount']) > 0 ? number_format($product['set_price']*$result['promotion_discount'],2) : number_format(0,2);
					}
					$product['original_price'] = $product['set_price'];
					$product['product_name'] = $product['set_name'];
					$results[$k]['product'] = $product;
				}else{
					unset($results[$k]);
					continue;
				}
			}else{
				//单品
				$sql = 'select * from nb_product where lid=:lid and dpid=:dpid and delete_flag=0 and status=0 and is_show=1 and is_show_wx=1';
				$product = Yii::app()->db->createCommand($sql)->bindValue(':lid',$result['product_id'])->bindValue(':dpid',$this->dpid)->queryRow();
				if($product){
					if($result['is_discount']==0){
						$product['price'] = ($product['original_price'] - $result['promotion_money']) > 0 ? number_format($product['original_price'] - $result['promotion_money'],2) : number_format(0,2);
					}else{
						$product['price'] = ($product['original_price']*$result['promotion_discount']) > 0 ? number_format($product['original_price']*$result['promotion_discount'],2) : number_format(0,2);
					}
					$results[$k]['product'] = $product;
				}else{
					unset($results[$k]);
					continue;
				}
			}
			if(!isset($promotionArr['lid'.$result['normal_promotion_id']])){
				$promotionArr['lid'.$result['normal_promotion_id']] = array();
			}
			array_push($promotionArr['lid'.$result['normal_promotion_id']],$results[$k]);
		}
		$this->promotionProductList = $promotionArr;
	}
	/**
	 * 获取活动信息
	 * 
	 */
	 public static function getPromotion($dpid,$promotionId){
	 	$sql = 'select * from  nb_normal_promotion where dpid=:dpid and lid=:lid and delete_flag=0';
	 	$result = Yii::app()->db->createCommand($sql)->bindValue(':dpid',$dpid)->bindValue(':lid',$promotionId)->queryRow();
	 	return $result;
	 }
	 /**
	  * 获取单品活动详情
	  *
	  */
	 public static function getProductPromotion($dpid,$promotionId,$productId,$isSet){
	 	$sql = 'select * from  nb_normal_promotion_detail where dpid=:dpid and normal_promotion_id=:promotionId and product_id=:productId and is_set=:isSet and delete_flag=0';
	 	$result = Yii::app()->db->createCommand($sql)->bindValue(':dpid',$dpid)->bindValue(':promotionId',$promotionId)->bindValue(':productId',$productId)->bindValue(':isSet',$isSet)->queryRow();
	 	return $result;
	 }
	/**
	 * 
	 * 产品特价活动价格
	 * 
	 */
	 public static function getPromotionPrice($dpid,$userId,$productId,$isSet,$promotionId,$toGroup){
	 	$now = date('Y-m-d H:i:s',time());
	 	if($isSet){
	 		$product = WxProduct::getProductSet($productId,$dpid);
	 		$sql = 'select t.*,t1.can_cupon,t1.to_group,t1.begin_time,t1.end_time,t1.weekday,t1.day_begin,t1.day_end,t1.order_num as all_order_num from nb_normal_promotion_detail t,nb_normal_promotion t1 where t.normal_promotion_id=t1.lid and t.dpid=t1.dpid and t.dpid=:dpid and t.normal_promotion_id=:promotionId and t.product_id=:productId and t1.begin_time <= :now and t1.end_time >= :now and t.is_set=1 and t.delete_flag=0 and t1.delete_flag=0';
	 		$promotion = Yii::app()->db->createCommand($sql)->bindValue(':dpid',$dpid)->bindValue(':promotionId',$promotionId)->bindValue(':productId',$productId)->bindValue(':now',$now)->queryRow();
	 		if($promotion){
	 			if($promotion['is_discount']==0){
	 				$price = ($product['set_price'] - $promotion['promotion_money']) > 0 ? number_format($product['set_price'] - $promotion['promotion_money'],2) : number_format(0,2);
	 				$promotion_money = $price ? $promotion['promotion_money'] : $price;
	 				return array('promotion_type'=>1,'price'=>$price,'promotion_info'=>array(array('is_discount'=>0,'promotion_money'=>$promotion_money,'poromtion_id'=>$promotion['normal_promotion_id'],'can_cupon'=>$promotion['can_cupon'])));
	 			}else{
	 				$price = number_format($product['set_price']*$promotion['promotion_discount'],2);
	 				$promotion_money = $product['set_price'] - $price;
	 				return array('promotion_type'=>1,'price'=>$price,'promotion_info'=>array(array('is_discount'=>1,'promotion_money'=>$promotion_money,'poromtion_id'=>$promotion['normal_promotion_id'],'can_cupon'=>$promotion['can_cupon'])));
	 			}
	 		}else{
	 			return array('promotion_type'=>-1,'price'=>$product['original_price'],'promotion_info'=>array());
	 		}
	 	}else{
	 		$product = WxProduct::getProduct($productId,$dpid);
	 		$sql = 'select t.*,t1.can_cupon,t1.to_group,t1.begin_time,t1.end_time,t1.weekday,t1.day_begin,t1.day_end,t1.order_num as all_order_num from nb_normal_promotion_detail t,nb_normal_promotion t1 where t.normal_promotion_id=t1.lid and t.dpid=t1.dpid and t.dpid=:dpid and t.normal_promotion_id=:promotionId and t.product_id=:productId and t1.begin_time <= :now and t1.end_time >= :now and t.is_set=0 and t.delete_flag=0 and t1.delete_flag=0';
	 		$promotion = Yii::app()->db->createCommand($sql)->bindValue(':dpid',$dpid)->bindValue(':promotionId',$promotionId)->bindValue(':productId',$productId)->bindValue(':now',$now)->queryRow();
	 		if($promotion){
	 			if($promotion['is_discount']==0){
	 				$price = ($product['original_price'] - $promotion['promotion_money']) > 0 ? number_format($product['original_price'] - $promotion['promotion_money'],2) : number_format(0,2);
	 				$promotion_money = $price ? $promotion['promotion_money'] : $price;
	 				return array('promotion_type'=>1,'price'=>$price,'promotion_info'=>array(array('is_discount'=>0,'promotion_money'=>$promotion_money,'poromtion_id'=>$promotion['normal_promotion_id'],'can_cupon'=>$promotion['can_cupon'])));
	 			}else{
	 				$price = number_format($product['original_price']*$promotion['promotion_discount'],2);
	 				$promotion_money = $product['original_price'] - $price;
	 				return array('promotion_type'=>1,'price'=>$price,'promotion_info'=>array(array('is_discount'=>1,'promotion_money'=>$promotion_money,'poromtion_id'=>$promotion['normal_promotion_id'],'can_cupon'=>$promotion['can_cupon'])));
	 			}
	 		}else{
	 			return array('promotion_type'=>-1,'price'=>$product['original_price'],'promotion_info'=>array());
	 		}
	 	}
	 }
	 public static function getPromotionUser($dpid,$userLevelId,$promotionId){
	 	$sql = 'select * from nb_normal_branduser where dpid=:dpid and normal_promotion_id=:promotionId and brand_user_lid=:userLevelId and to_group=2 and delete_flag=0';
	 	$result = Yii::app()->db->createCommand($sql)->bindValue(':dpid',$dpid)->bindValue(':userLevelId',$userLevelId)->bindValue(':promotionId',$promotionId)->queryRow();
	 	return $result;
	 }
	 // 活动是否有效
	 public static function isPromotionValid($dpid,$promotionId,$type){
	 	$now = date('Y-m-d H:i:s',time());
	 	$promotion = self::getPromotion($dpid, $promotionId);
	 	if($promotion){
	 		if($type==2){
	 			if(!in_array($promotion['is_available'], array(2,3,5))){
	 				return false;
	 			}
	 		}elseif($type==6){
	 			if(!in_array($promotion['is_available'], array(2,3,4))){
	 				return false;
	 			}
	 		}else{
	 			if(!in_array($promotion['is_available'], array(2,3))){
	 				return false;
	 			}
	 		}
	 		if($promotion['end_time'] >= $now&&$now >= $promotion['begin_time']){
	 			$week = date('w');
	 			if($week==0){
	 				$week = 7;
	 			}
	 			$weekday = explode(',',$promotion['weekday']);
	 			if(in_array($week, $weekday)){
		 			$time = date('H:i');
		 			$promotionBegin = date('H:i',strtotime($promotion['day_begin']));
		 			$promotionEnd = date('H:i',strtotime($promotion['day_end']));
		 			if($promotionEnd >= $time&&$time >= $promotionBegin){
		 				return true;
		 			}
	 			}
	 		}
	 	}
	 	return false;
	 }
}