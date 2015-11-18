<?php 
/**
 * 
 * 
 * 微信端产品类
 * 
 */
class WxProductPrice
{
	/**
	 * 
	 * only 独享优惠id
	 * all 万能优惠id
	 * 
	 */
	public $productId;
	public $dpid;
	public $userId;
	public $set;
	public $price;
	public $promotion = array('is_discount'=>-1,'price'=>0,'limit_num'=>-1,'private_brandser_id'=>array('only'=>-1,'all'=>array()));
	
	public function __construct($lid,$dpid,$userId = null){
		$this->productId = $lid;
		$this->dpid = $dpid;
		$this->userId = $userId;
		$this->checkSet();
		$this->product();
		$this->price();
	}
	public function checkSet(){
		$this->set = WxTotalPromotion::get($this->dpid);
	}
	public function product(){
		$sql = 'select * from nb_product where lid=:lid and dpid=:dpid';
		$this->product = Yii::app()->db->createCommand($sql)->bindValue(':lid',$this->productId)->bindValue(':dpid',$this->dpid)->queryRow();
	}
	public function price(){
		$this->price = $this->product['original_price'];
		$formatTime = date('Y-m-d H:i:s',time());
		
		$this->promotion['price'] = $this->price;
		if(!empty($this->set)){
			//只有普通优惠
			if(!$this->set['is_narmal_promotion']&&$this->set['is_private_promotion']){
				//查出排他活动
				$sql = 'select t.product_id,t.is_set,t.is_discount,t.promotion_money,t.promotion_discount,t.order_num from nb_normal_promotion_detail t,nb_normal_promotion t1 where t.normal_promotion_id=t1.lid and t.dpid=t1.dpid and t.product_id=:productId and t.dpid=:dpid and t.is_set=0 and t.delete_flag=0 and t1.delete_flag=0 and t1.is_available=0 and t1.begin_time <= :now and t1.end_time >=:now and t1.promotion_type=0';
				$narmanPromotions = Yii::app()->db->createCommand($sql)->bindValue(':productId',$this->productId)->bindValue(':dpid',$this->dpid)->bindValue(':now',$formatTime)->queryAll();
				foreach($narmanPromotions as $promotion){
					if($promotion['is_discount']){
						//优惠
						if($this->price > ($this->product['original_price'] - $promotion['promotion_money'])){
							$this->price = $this->product['original_price'] - $promotion['promotion_money'];
							$this->promotion['limit_num'] = $promotion['order_num'];
							$this->promotion['is_discount'] = $promotion['is_discount'];
						}
					}else{
					   //打折
					   if($this->price > $this->product['original_price']*$promotion['promotion_discount']/100){
					   	 $this->price = $this->product['original_price']*$promotion['promotion_discount']/100;
					   	 $this->promotion['limit_num'] = $promotion['order_num'];
					   	 $this->promotion['is_discount'] = $promotion['is_discount'];
					   }
					}
					$this->price = $this->price >0 ? $this->price : 0;
					$this->promotion['price'] = $this->price;
					if(!$this->price){
						break;
					}
				}
				
				
				
				//查出万能优惠
				$sql = 'select t.product_id,t.is_set,t.is_discount,t.promotion_money,t.promotion_discount,t.order_num from nb_normal_promotion_detail t,nb_normal_promotion t1 where t.normal_promotion_id=t1.lid and t.dpid=t1.dpid and t.product_id=:productId and t.dpid=:dpid and t.is_set=0 and t.delete_flag=0 and t1.delete_flag=0 and t1.is_available=0 and t1.begin_time <= :now and t1.end_time >=:now and t1.promotion_type=1 order by t.order_num asc';
				$allNarmanPromotions = Yii::app()->db->createCommand($sql)->bindValue(':productId',$this->productId)->bindValue(':dpid',$this->dpid)->bindValue(':now',$formatTime)->queryAll();
				foreach($narmanPromotions as $allPromotion){
					if($promotion['is_discount']){
						//优惠
						$this->price = $this->price - $allPromotion['promotion_money'];				
					}else{
					   //打折
					   $this->price = $this->price*$allPromotion['promotion_discount']/100;
					}
					$this->price = $this->price >0 ? $this->price : 0;
					$this->promotion['price'] = $this->price;
					$this->promotion['limit_num'] = $allPromotion['order_num'];
					if(!$this->price){
						break;
					}
				}
				
			}
			//只有专享优惠
			if(!$this->set['is_private_promotion']&&$this->set['is_narmal_promotion']){
				
				//专享独立优惠
				$sql = 'select m.* from (select t.lid,t.dpid,t.private_promotion_id,t1.product_id,t1.is_set,t1.is_discount,t1.promotion_money,t1.promotion_discount,t1.order_num from nb_private_branduser t,nb_private_promotion_detail t1 where t.private_promotion_id=t1.private_promotion_id and t.dpid=t1.dpid and t.dpid=:dpid and t.brand_user_lid=:userId and t1.product_id=:productId and t1.is_set=0 and t.delete_flag=0 and t1.delete_flag=0 and t.is_used=1)m ,nb_normal_promotion n where m.private_promotion_id=n.lid and m.dpid=n.dpid and n.delete_flag=0 and n.is_available=0 and n.begin_time <= :now and n.end_time >=:now and n.promotion_type=0';
				$privatePromotions = Yii::app()->db->createCommand($sql)->bindValue(':productId',$this->productId)->bindValue(':dpid',$this->dpid)->bindValue(':userId',$this->userId)->bindValue(':now',$formatTime)->queryAll();
				foreach($privatePromotions as $privatePromotion){
					if($privatePromotion['is_discount']){
						//优惠
						if($this->price > ($this->product['original_price'] - $privatePromotion['promotion_money'])){
							$this->price = $this->product['original_price'] - $privatePromotion['promotion_money'];
							$this->promotion['private_branduser_lid']['only'] = $privatePromotion['lid'];
							$this->promotion['limit_num'] = $privatePromotion['order_num'];
							$this->promotion['is_discount'] = $privatePromotion['is_discount'];	
						}
					}else{
					   //打折
					   if($this->price > $this->product['original_price']*$privatePromotion['promotion_discount']/100){
					   	   $this->price = $this->product['original_price']*$privatePromotion['promotion_discount']/100;
					   	   $this->promotion['private_branduser_lid']['only'] = $privatePromotion['lid'];
						   $this->promotion['limit_num'] = $privatePromotion['order_num'];
						   $this->promotion['is_discount'] = $privatePromotion['is_discount'];
					   }
					}
					$this->price = $this->price >0 ? $this->price : 0;
					$this->promotion['price'] = $this->price;
					if(!$this->price){
						break;
					}
				}
				
				//专享万能优惠
				$sql = 'select m.* from (select t.lid,t.dpid,t.private_promotion_id,t1.product_id,t1.is_set,t1.is_discount,t1.promotion_money,t1.promotion_discount,t1.order_num from nb_private_branduser t,nb_private_promotion_detail t1 where t.private_promotion_id=t1.private_promotion_id and t.dpid=t1.dpid and t.dpid=:dpid and t.brand_user_lid=:userId and t1.product_id=:productId and t1.is_set=0 and t.delete_flag=0 and t1.delete_flag=0 and t.is_used=1)m ,nb_normal_promotion n where m.private_promotion_id=n.lid and m.dpid=n.dpid and n.delete_flag=0 and n.is_available=0 and n.begin_time <= :now and n.end_time >=:now and n.promotion_type=1 order by m.order_num asc';
				$allprivatePromotions = Yii::app()->db->createCommand($sql)->bindValue(':productId',$this->productId)->bindValue(':dpid',$this->dpid)->bindValue(':userId',$this->userId)->bindValue(':now',$formatTime)->queryAll();
				foreach($privatePromotions as $allprivatePromotion){
					if($allprivatePromotion['is_discount']){
						//优惠
						$this->price = $this->price - $allprivatePromotion['promotion_money'];
						$this->promotion['is_discount'] = $allprivatePromotion['is_discount'];			
					}else{
					   //打折
					   $this->price = $this->price *$allprivatePromotion['promotion_discount']/100;
					   $this->promotion['is_discount'] = $allprivatePromotion['is_discount'];
					}
					$this->price = $this->price >0 ? $this->price : 0;
					$this->promotion['price'] = $this->price;
					$this->promotion['limit_num'] = $allprivatePromotion['order_num'];
					array_push($this->promotion['private_branduser_lid']['all'],$allprivatePromotion['lid']);
					if(!$this->price){
						break;
					}
				}
				
			} 
			
			
			//普通、专享优惠
			if(!$this->set['is_narmal_promotion']&&!$this->set['is_private_promotion']){
				//普通优惠 独立
				$nomalPrice = $this->product['original_price'];
				$nomalIsDiscount = -1;
				$nomalLimitNum = 0;
				
				$sql = 'select t.product_id,t.is_set,t.is_discount,t.promotion_money,t.promotion_discount,t.order_num from nb_normal_promotion_detail t,nb_normal_promotion t1 where t.normal_promotion_id=t1.lid and t.dpid=t1.dpid and t.product_id=:productId and t.dpid=:dpid and t.is_set=0 and t.delete_flag=0 and t1.delete_flag=0 and t1.is_available=0 and t1.begin_time <= :now and t1.end_time >=:now and t1.promotion_type=0';
				$narmanPromotions = Yii::app()->db->createCommand($sql)->bindValue(':productId',$this->productId)->bindValue(':dpid',$this->dpid)->bindValue(':now',$formatTime)->queryAll();
				foreach($narmanPromotions as $promotion){
					if($promotion['is_discount']){
						//优惠
						if($nomalPrice > ($this->product['original_price'] - $promotion['promotion_money'])){
							$nomalPrice = $this->product['original_price'] - $promotion['promotion_money'];
							$nomalLimitNum = $promotion['order_num'];
							$nomalIsDiscount = $promotion['is_discount'];		
						}
					}else{
					   //打折
					   if($nomalPrice > $this->product['original_price']*$promotion['promotion_discount']/100){
					   	   $nomalPrice = $this->product['original_price']*$promotion['promotion_discount']/100;
					   	   $nomalLimitNum = $promotion['order_num'];
					   	   $nomalIsDiscount= $promotion['is_discount'];
					   }
					  
					}
					$nomalPrice = $nomalPrice >0 ? $nomalPrice : 0;
				}
				
			   //专享优惠 独立
			    $privatePrice = $this->product['original_price'];
				$privateIsDiscount = -1;
				$privateLimitNum = 0;
				$privateBranduserLid = -1;
				
			    $sql = 'select m.* from (select t.lid,t.dpid,t.private_promotion_id,t1.product_id,t1.is_set,t1.is_discount,t1.promotion_money,t1.promotion_discount,t1.order_num from nb_private_branduser t,nb_private_promotion_detail t1 where t.private_promotion_id=t1.private_promotion_id and t.dpid=t1.dpid and t.dpid=:dpid and t.brand_user_lid=:userId and t1.product_id=:productId and t1.is_set=0 and t.delete_flag=0 and t1.delete_flag=0 and t.is_used=1)m ,nb_normal_promotion n where m.private_promotion_id=n.lid and m.dpid=n.dpid and n.delete_flag=0 and n.is_available=0 and n.begin_time <= :now and n.end_time >=:now and n.promotion_type=0';
				$privatePromotions = Yii::app()->db->createCommand($sql)->bindValue(':productId',$this->productId)->bindValue(':dpid',$this->dpid)->bindValue(':userId',$this->userId)->bindValue(':now',$formatTime)->queryAll();
				foreach($privatePromotions as $privatePromotion){
					if($privatePromotion['is_discount']){
						//优惠
						if($privatePrice > ($this->product['original_price'] - $privatePromotion['promotion_money'])){
							$privatePrice = $this->product['original_price'] - $privatePromotion['promotion_money'];
							$privateBranduserLid = $privatePromotion['lid'];
							$privateLimitNum = $privatePromotion['order_num'];
							$privateIsDiscount = $privatePromotion['is_discount'];	
						}
					}else{
					   //打折
					   if($privatePrice > $this->product['original_price']*$privatePromotion['promotion_discount']/100){
					   	   $privatePrice = $this->product['original_price']*$privatePromotion['promotion_discount']/100;
					   	   $privateBranduserLid = $privatePromotion['lid'];
						   $privateLimitNum = $privatePromotion['order_num'];
						   $privateIsDiscount = $privatePromotion['is_discount'];
					   }
					}
					$privatePrice = $privatePrice >0 ? $privatePrice : 0;
				}
				
				if($nomalPrice >= $privatePrice){
					$this->price = $privatePrice;
					$this->promotion['price'] = $this->price;
					$this->promotion['private_branduser_lid']['only'] = $privateBranduserLid;
				    $this->promotion['limit_num'] = $privateLimitNum;
				    $this->promotion['is_discount'] = $privateIsDiscount;
				}else{
					$this->price = $nomalPrice;
					$this->promotion['price'] = $this->price;
					$this->promotion['private_branduser_lid']['only'] = $privateBranduserLid;
				    $this->promotion['limit_num'] = $nomalLimitNum;
				    $this->promotion['is_discount'] = $nomalIsDiscount;
				}
				
				//普通万能优惠
				$sql = 'select t.product_id,t.is_set,t.is_discount,t.promotion_money,t.promotion_discount,t.order_num from nb_normal_promotion_detail t,nb_normal_promotion t1 where t.normal_promotion_id=t1.lid and t.dpid=t1.dpid and t.product_id=:productId and t.dpid=:dpid and t.is_set=0 and t.delete_flag=0 and t1.delete_flag=0 and t1.is_available=0 and t1.begin_time <= :now and t1.end_time >=:now and t1.promotion_type=1 order by t.order_num asc';
				$allNarmanPromotions = Yii::app()->db->createCommand($sql)->bindValue(':productId',$this->productId)->bindValue(':dpid',$this->dpid)->bindValue(':now',$formatTime)->queryAll();
				foreach($narmanPromotions as $allPromotion){
					if($promotion['is_discount']){
						//优惠
						$this->price = $this->price - $allPromotion['promotion_money'];				
					}else{
					   //打折
					   $this->price = $this->price*$allPromotion['promotion_discount']/100;
					}
					$this->price = $this->price >0 ? $this->price : 0;
					$this->promotion['price'] = $this->price;
					$this->promotion['limit_num'] = $allPromotion['order_num'];
					if(!$this->price){
						break;
					}
				}
				
				//专享万能优惠
				$sql = 'select m.* from (select t.lid,t.dpid,t.private_promotion_id,t1.product_id,t1.is_set,t1.is_discount,t1.promotion_money,t1.promotion_discount,t1.order_num from nb_private_branduser t,nb_private_promotion_detail t1 where t.private_promotion_id=t1.private_promotion_id and t.dpid=t1.dpid and t.dpid=:dpid and t.brand_user_lid=:userId and t1.product_id=:productId and t1.is_set=0 and t.delete_flag=0 and t1.delete_flag=0 and t.is_used=1)m ,nb_normal_promotion n where m.private_promotion_id=n.lid and m.dpid=n.dpid and n.delete_flag=0 and n.is_available=0 and n.begin_time <= :now and n.end_time >=:now and n.promotion_type=1 order by m.order_num asc';
				$allprivatePromotions = Yii::app()->db->createCommand($sql)->bindValue(':productId',$this->productId)->bindValue(':dpid',$this->dpid)->bindValue(':userId',$this->userId)->bindValue(':now',$formatTime)->queryAll();
				foreach($privatePromotions as $allprivatePromotion){
					if($allprivatePromotion['is_discount']){
						//优惠
						$this->price = $this->price - $allprivatePromotion['promotion_money'];
						$this->promotion['is_discount'] = $allprivatePromotion['is_discount'];			
					}else{
					   //打折
					   $this->price = $this->price *$allprivatePromotion['promotion_discount']/100;
					   $this->promotion['is_discount'] = $allprivatePromotion['is_discount'];
					}
					$this->price = $this->price >0 ? $this->price : 0;
					$this->promotion['price'] = $this->price;
					$this->promotion['limit_num'] = $allprivatePromotion['order_num'];
					array_push($this->promotion['private_branduser_lid']['all'],$allprivatePromotion['lid']);
					if(!$this->price){
						break;
					}
				}
			}
		}
	}
}