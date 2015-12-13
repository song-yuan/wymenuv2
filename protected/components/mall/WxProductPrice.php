<?php 
/**
 * 
 * 
 * 微信端产品类
 * 优惠活动是按照最新添加的活动价格来计算
 * 
 */
class WxProductPrice
{
	/**
	 * 
	 * only 独享优惠id
	 * all 万能优惠id
	 * 
	 * promotion_type 0普通优惠 1特价优惠
	 * 
	 */
	public $productId;
	public $dpid;
	public $userId;
	public $set;
	public $price;
	public $promotion = array('promotion_type'=>0,'price'=>0,'promotion_info'=>array());
	
	public function __construct($lid,$dpid){
		$this->productId = $lid;
		$this->dpid = $dpid;
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
			//begain 普通优惠
			if(!$this->set['is_normal_promotion']){
				//查出排他活动
				$sql = 'select t.normal_promotion_id,t.product_id,t.is_set,t.is_discount,t.promotion_money,t.promotion_discount,t.order_num from nb_normal_promotion_detail t,nb_normal_promotion t1 where t.normal_promotion_id=t1.lid and t.dpid=t1.dpid and t.product_id=:productId and t.dpid=:dpid and t.is_set=0 and t.delete_flag=0 and t1.delete_flag=0 and t1.is_available=0 and t1.begin_time <= :now and t1.end_time >=:now and t1.promotion_type=0 order by t1.create_at desc';
				$promotion = Yii::app()->db->createCommand($sql)->bindValue(':productId',$this->productId)->bindValue(':dpid',$this->dpid)->bindValue(':now',$formatTime)->queryRow();
				if(!empty($promotion)){
					if($promotion['is_discount']){
						//优惠
						if($this->price > ($this->product['original_price'] - $promotion['promotion_money'])){
							$this->price = number_format($this->product['original_price'] - $promotion['promotion_money'],2);
							$promotion_money = $this->price ? $promotion['promotion_money'] : $this->price;
							$this->promotion['promotion_info'][] = array('is_discount'=>$promotion['is_discount'],'promotion_money'=>$promotion_money,'poromtion_id'=>$promotion['normal_promotion_id']);
						}
					}else{
					   //打折
					   if($this->price > $this->product['original_price']*$promotion['promotion_discount']){
					   	 $this->price = number_format($this->product['original_price']*$promotion['promotion_discount'],2);
					   	 $promotion_money = $this->product['original_price'] - number_format($this->product['original_price']*$promotion['promotion_discount'],2);
					   	$this->promotion['promotion_info'][] = array('is_discount'=>$promotion['is_discount'],'promotion_money'=>$promotion_money,'poromtion_id'=>$promotion['normal_promotion_id']);
					   }
					}
					$this->price = $this->price >0 ? $this->price : number_format(0,2);
					$this->promotion['price'] = $this->price;
				}

				//查出万能优惠
				$sql = 'select t.normal_promotion_id,t.product_id,t.is_set,t.is_discount,t.promotion_money,t.promotion_discount,t.order_num from nb_normal_promotion_detail t,nb_normal_promotion t1 where t.normal_promotion_id=t1.lid and t.dpid=t1.dpid and t.product_id=:productId and t.dpid=:dpid and t.is_set=0 and t.delete_flag=0 and t1.delete_flag=0 and t1.is_available=0 and t1.begin_time <= :now and t1.end_time >=:now and t1.promotion_type=1';
				$allNarmanPromotions = Yii::app()->db->createCommand($sql)->bindValue(':productId',$this->productId)->bindValue(':dpid',$this->dpid)->bindValue(':now',$formatTime)->queryAll();
				foreach($allNarmanPromotions as $allPromotion){
					$oPrice = $this->price;
					
					if($allPromotion['is_discount']==0){
						//优惠
						$this->price = number_format($this->price - $allPromotion['promotion_money'],2);
						$promotion_money = $this->price ? $allPromotion['promotion_money'] : $oPrice;
						
						$this->promotion['promotion_info'][] = array('is_discount'=>$allPromotion['is_discount'],'promotion_money'=>$promotion_money,'poromtion_id'=>$allPromotion['normal_promotion_id']);
					}else{
					   //打折
					   $this->price = number_format($this->price*$allPromotion['promotion_discount'],2);
					   $promotion_money = $oPrice - number_format($oPrice*$allPromotion['promotion_discount'],2);
					   
					   $this->promotion['promotion_info'][] = array('is_discount'=>$allPromotion['is_discount'],'promotion_money'=>$promotion_money,'poromtion_id'=>$allPromotion['normal_promotion_id']);
					}
					$this->price = $this->price >0 ? $this->price : number_format(0,2);
					$this->promotion['price'] = $this->price;
					if(!$this->price){
						break;
					}
				}
			}
			//end 优惠
		}
	}
}