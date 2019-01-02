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
	public $proProIdList = array();
	public $promotionProductList;
	public $buySentProductList;
	public $fullSentList;
	public function __construct($dpid,$userId,$type){
		$this->dpid = $dpid;
		$this->userId = $userId;
		$this->type = $type;
		$this->getPromotionDetail();
		$this->getBuySentDetail();
		$this->getFullSentDetail();
	}
	public function getPromotionDetail(){
		$now = date('Y-m-d H:i:s',time());
		$orderType = -1;
		if($this->type == '6'){
			$orderType = 2;
		}elseif($this->type == '2'){
			$orderType = 3;
		}else{
			$orderType = 2;
		}
		$sql = 'select t.*,t1.promotion_title,t1.promotion_abstract,t1.main_picture,t1.to_group,t1.can_cupon,t1.group_id,t1.begin_time,t1.end_time,t1.weekday,t1.day_begin,t1.day_end,t1.order_num as all_order_num from nb_normal_promotion_detail t,nb_normal_promotion t1 where t.normal_promotion_id=t1.lid and t.dpid=t1.dpid and t.dpid=:dpid and t1.begin_time <= :now and t1.end_time >= :now and t1.is_available like "%'.$orderType.'%" and t.delete_flag=0 and t1.delete_flag=0';
		$results = Yii::app()->db->createCommand($sql)->bindValue(':dpid',$this->dpid)->bindValue(':now',$now)->queryAll();
		$promotionArr = array();
		$proproArr = array();
		foreach($results as $k=>$result){
			if($result['to_group']==2){
				// 会员等级活动
				$user = WxBrandUser::get($this->userId, $this->dpid);
				$promotionUser = self::getPromotionUser($this->dpid, $user['user_level_lid'], $result['normal_promotion_id']);
				if(empty($promotionUser)){
					continue;
				}
			}
			if(!self::checkDayTime($result['weekday'],$result['day_begin'],$result['day_end'])){
				continue;
			}
			
			if($result['is_set'] > 0){
				//套餐	
				$sql = 'select * from nb_product_set where lid=:lid and dpid=:dpid and status=0 and is_show=1 and delete_flag=0';
				$product = Yii::app()->db->createCommand($sql)->bindValue(':lid',$result['product_id'])->bindValue(':dpid',$this->dpid)->queryRow();
				if($product){
					$isShow = WxProduct::isProductWxShow($this->type, $product['is_show_wx']);
					if(!$isShow){
						unset($results[$k]);
						continue;
					}
					if($result['is_discount']==0){
						$product['price'] = ($product['set_price'] - $result['promotion_money']) > 0 ? number_format($product['set_price'] - $result['promotion_money'],2) : number_format(0,2);
					}else{
						$product['price'] = ($product['set_price']*$result['promotion_discount']) > 0 ? number_format($product['set_price']*$result['promotion_discount'],2) : number_format(0,2);
					}
					$product['original_price'] = $product['set_price'];
					$product['product_name'] = $product['set_name'];
					$setDetail = WxProduct::getProductSetDetail($product['lid'], $product['dpid']);
					if(empty($setDetail)){
						unset($results[$k]);
						continue;
					}
					$product['detail'] = $setDetail;
					array_push($this->proProIdList, '1-'.$product['lid']);
				}else{
					unset($results[$k]);
					continue;
				}
			}else{
				//单品
				$sql = 'select * from nb_product where lid=:lid and dpid=:dpid and status=0 and is_show=1 and delete_flag=0';
				$product = Yii::app()->db->createCommand($sql)->bindValue(':lid',$result['product_id'])->bindValue(':dpid',$this->dpid)->queryRow();
				if($product){
					$isShow = WxProduct::isProductWxShow($this->type, $product['is_show_wx']);
					if(!$isShow){
						unset($results[$k]);
						continue;
					}
					if($result['is_discount']==0){
						$product['price'] = ($product['original_price'] - $result['promotion_money']) > 0 ? number_format($product['original_price'] - $result['promotion_money'],2) : number_format(0,2);
					}else{
						$product['price'] = ($product['original_price']*$result['promotion_discount']) > 0 ? number_format($product['original_price']*$result['promotion_discount'],2) : number_format(0,2);
					}
					$product['taste_groups'] = WxTaste::getProductTastes($product['lid'],$product['dpid']);
					array_push($this->proProIdList, '0-'.$product['lid']);
				}else{
					unset($results[$k]);
					continue;
				}
			}
			$promotionkey = 'lid'.$result['normal_promotion_id'];
			if(!isset($promotionArr[$promotionkey])){
				$promotionArr[$promotionkey] = array();
				$promotionArr[$promotionkey]['promotion_title'] = $result['promotion_title'];
				$promotionArr[$promotionkey]['promotion_abstract'] = $result['promotion_abstract'];
				$promotionArr[$promotionkey]['main_picture'] = $result['main_picture'];
				$promotionArr[$promotionkey]['to_group'] = $result['to_group'];
				$promotionArr[$promotionkey]['can_cupon'] = $result['can_cupon'];
				$promotionArr[$promotionkey]['group_id'] = $result['group_id'];
				$promotionArr[$promotionkey]['begin_time'] = $result['begin_time'];
				$promotionArr[$promotionkey]['end_time'] = $result['end_time'];
				$promotionArr[$promotionkey]['weekday'] = $result['weekday'];
				$promotionArr[$promotionkey]['day_begin'] = $result['day_begin'];
				$promotionArr[$promotionkey]['day_end'] = $result['day_end'];
				$promotionArr[$promotionkey]['all_order_num'] = $result['all_order_num'];
			}
			$procatekey = $result['is_set'].'-'.$product['category_id'];
			if(!isset($promotionArr[$promotionkey]['product'][$procatekey])){
				$promotionArr[$promotionkey]['product'][$procatekey] = array();
			}
			$result['product'] = $product;
			array_push($promotionArr[$promotionkey]['product'][$procatekey],$result);
		}
		$this->promotionProductList = $promotionArr;
	}
	public function getBuySentDetail(){
		$now = date('Y-m-d H:i:s',time());
		$orderType = -1;
		if($this->type == '6'){
			$orderType = 2;
		}elseif($this->type == '2'){
			$orderType = 3;
		}else{
			$orderType = 2;
		}
		$sql = 'select t.*,t1.promotion_title,t1.promotion_abstract,t1.main_picture,t1.to_group,t1.can_cupon,t1.group_id,t1.begin_time,t1.end_time,t1.weekday,t1.day_begin,t1.day_end,t1.order_num as all_order_num from nb_buysent_promotion_detail t,nb_buysent_promotion t1 where t.buysent_pro_id=t1.lid and t.dpid=t1.dpid and t.dpid=:dpid and t1.begin_time <= :now and t1.end_time >= :now and t1.is_available like "%'.$orderType.'%" and t.delete_flag=0 and t1.delete_flag=0';
		$results = Yii::app()->db->createCommand($sql)->bindValue(':dpid',$this->dpid)->bindValue(':now',$now)->queryAll();
		$promotionArr = array();
		foreach($results as $k=>$result){
			if($result['to_group']==2){
				// 会员等级活动
				$user = WxBrandUser::get($this->userId, $this->dpid);
				$promotionUser = self::getPromotionUser($this->dpid, $user['user_level_lid'], $result['buysent_pro_id']);
				if(empty($promotionUser)){
					continue;
				}
			}
			if(!self::checkDayTime($result['weekday'],$result['day_begin'],$result['day_end'])){
				continue;
			}
			if($result['is_set'] > 0){
				//套餐
				$sql = 'select * from nb_product_set where lid=:lid and dpid=:dpid and status=0 and is_show=1 and and delete_flag=0';
				$product = Yii::app()->db->createCommand($sql)->bindValue(':lid',$result['product_id'])->bindValue(':dpid',$this->dpid)->queryRow();
				if($product){
					$isShow = WxProduct::isProductWxShow($this->type, $product['is_show_wx']);
					if(!$isShow){
						unset($results[$k]);
						continue;
					}
					$product['original_price'] = $product['set_price'];
					$product['product_name'] = $product['set_name'];
					$product['price'] = $product['set_price'];
					$setDetail = WxProduct::getProductSetDetail($product['lid'], $product['dpid']);
					if(empty($setDetail)){
						unset($results[$k]);
						continue;
					}
					$product['detail'] = $setDetail;
					array_push($this->proProIdList, '1-'.$product['lid']);
				}else{
					unset($results[$k]);
					continue;
				}
			}else{
				//单品
				$sql = 'select * from nb_product where lid=:lid and dpid=:dpid and status=0 and is_show=1 and delete_flag=0';
				$product = Yii::app()->db->createCommand($sql)->bindValue(':lid',$result['product_id'])->bindValue(':dpid',$this->dpid)->queryRow();
				if($product){
					$isShow = WxProduct::isProductWxShow($this->type, $product['is_show_wx']);
					if(!$isShow){
						unset($results[$k]);
						continue;
					}
					$product['price'] = $product['original_price'];
					$product['taste_groups'] = WxTaste::getProductTastes($product['lid'],$product['dpid']);
					array_push($this->proProIdList, '0-'.$product['lid']);
				}else{
					unset($results[$k]);
					continue;
				}
			}
			$promotionkey = 'lid'.$result['buysent_pro_id'];
			if(!isset($promotionArr[$promotionkey])){
				$promotionArr[$promotionkey] = array();
				$promotionArr[$promotionkey]['promotion_title'] = $result['promotion_title'];
				$promotionArr[$promotionkey]['promotion_abstract'] = $result['promotion_abstract'];
				$promotionArr[$promotionkey]['main_picture'] = $result['main_picture'];
				$promotionArr[$promotionkey]['to_group'] = $result['to_group'];
				$promotionArr[$promotionkey]['can_cupon'] = $result['can_cupon'];
				$promotionArr[$promotionkey]['group_id'] = $result['group_id'];
				$promotionArr[$promotionkey]['begin_time'] = $result['begin_time'];
				$promotionArr[$promotionkey]['end_time'] = $result['end_time'];
				$promotionArr[$promotionkey]['weekday'] = $result['weekday'];
				$promotionArr[$promotionkey]['day_begin'] = $result['day_begin'];
				$promotionArr[$promotionkey]['day_end'] = $result['day_end'];
				$promotionArr[$promotionkey]['all_order_num'] = $result['all_order_num'];
			}
			$procatekey = $result['is_set'].'-'.$product['category_id'];
			if(!isset($promotionArr[$promotionkey]['product'][$procatekey])){
				$promotionArr[$promotionkey]['product'][$procatekey] = array();
			}
			$result['product'] = $product;
			array_push($promotionArr[$promotionkey]['product'][$procatekey],$result);
		}
		$this->buySentProductList = $promotionArr;
	}
	/**
	 * 
	 * 满送 满减
	 * 
	 */
	public function getFullSentDetail(){
		$fullsentArr = array();
		$fullSent = WxFullSent::getAllFullsent($this->dpid, $this->type, 0);
		array_push($fullsentArr, $fullSent);
		$fullMinus = WxFullSent::getAllFullsent($this->dpid, $this->type, 1);
		array_push($fullsentArr, $fullMinus);
		$this->fullSentList = $fullsentArr;
	}
	/**
	 * 获取活动信息
	 * promotion 普通优惠 buysent 买送优惠 fullsent0 满送 fullsent1 满减
	 * 
	 */
	 public static function getPromotion($dpid,$promotionType,$promotionId){
	 	if($promotionType=='promotion'){
	 		$sql = 'select * from nb_normal_promotion where dpid=:dpid and lid=:lid and delete_flag=0';
	 	}elseif($promotionType=='buysent'||$promotionType=='sent'){
	 		$sql = 'select * from nb_buysent_promotion where dpid=:dpid and lid=:lid and delete_flag=0';
	 	}else{
	 		return false;
	 	}
	 	$result = Yii::app()->db->createCommand($sql)->bindValue(':dpid',$dpid)->bindValue(':lid',$promotionId)->queryRow();
	 	return $result;
	 }
	 /**
	  * 获取单品活动详情
	  *
	  */
	 public static function getProductPromotion($dpid,$promotionType,$promotionId,$productId,$isSet){
	 	if($promotionType=='promotion'){
	 		$sql = 'select * from  nb_normal_promotion_detail where dpid=:dpid and normal_promotion_id=:promotionId and product_id=:productId and is_set=:isSet and delete_flag=0';
	 	}elseif($promotionType=='buysent'){
	 		$sql = 'select * from  nb_buysent_promotion_detail where dpid=:dpid and buysent_pro_id=:promotionId and product_id=:productId and is_set=:isSet and delete_flag=0';
	 	}elseif($promotionType=='sent'){
	 		$sql = 'select * from  nb_buysent_promotion_detail where dpid=:dpid and buysent_pro_id=:promotionId and s_product_id=:productId and is_set=:isSet and delete_flag=0';
	 	}else{
	 		return false;
	 	}
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
	 public static function isPromotionValid($dpid,$promotionType,$promotionId,$type){
	 	$now = date('Y-m-d H:i:s',time());
	 	$promotion = self::getPromotion($dpid,$promotionType, $promotionId);
	 	if($promotion){
	 		if($type==2){
	 			if(strpos($promotion['is_available'],'3')===FALSE){
	 				return false;
	 			}
	 		}elseif($type==6){
	 			if(strpos($promotion['is_available'],'2')===FALSE){
	 				return false;
	 			}
	 		}else{
	 			if(strpos($promotion['is_available'],'2')===FALSE){
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
	 /**
	  * 检查活动当天时间
	  * 是否有效
	  */
	 public static function checkDayTime($weekday,$daybegin,$dayend){
	 	$week = date('w');
	 	if($week==0){
	 		$week = 7;
	 	}
	 	$weekdayArr = explode(',',$weekday);
	 	if(in_array($week, $weekdayArr)){
	 		$time = date('H:i');
	 		$proBegin = date('H:i',strtotime($daybegin));
	 		$proEnd = date('H:i',strtotime($dayend));
	 		if($proEnd >= $time&&$time >= $proBegin){
	 			return true;
	 		}
	 	}
	 	return false;
	 }
}