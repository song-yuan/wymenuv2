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
	public $promotion = array('is_discount'=>-1,'price'=>0);
	
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
				$sql = 'select t.product_id,t.is_set,t.is_discount,t.promotion_money,t.promotion_discount,t.order_num from nb_normal_promotion_detail t,nb_normal_promotion t1 where t.normal_promotion_id=t1.lid and t.dpid=t1.dpid and t.product_id=:productId and t.dpid=:dpid and t.is_set=0 and t.delete_flag=0 and t1.delete_flag=0 and t1.is_available=0 and t1.begin_time <= :now and t1.end_time >=:now and t1.promotion_type=0 order by create_at desc';
				$promotion = Yii::app()->db->createCommand($sql)->bindValue(':productId',$this->productId)->bindValue(':dpid',$this->dpid)->bindValue(':now',$formatTime)->queryRow();
				if(!empty($promotion)){
					if($promotion['is_discount']){
						//优惠
						if($this->price > ($this->product['original_price'] - $promotion['promotion_money'])){
							$this->price = $this->product['original_price'] - $promotion['promotion_money'];
							$this->promotion['is_discount'] = $promotion['is_discount'];
						}
					}else{
					   //打折
					   if($this->price > $this->product['original_price']*$promotion['promotion_discount']/100){
					   	 $this->price = $this->product['original_price']*$promotion['promotion_discount']/100;
					   	 $this->promotion['is_discount'] = $promotion['is_discount'];
					   }
					}
					$this->price = $this->price >0 ? $this->price : 0;
					$this->promotion['price'] = $this->price;
				}
				
				
				
				//查出万能优惠
				$sql = 'select t.product_id,t.is_set,t.is_discount,t.promotion_money,t.promotion_discount,t.order_num from nb_normal_promotion_detail t,nb_normal_promotion t1 where t.normal_promotion_id=t1.lid and t.dpid=t1.dpid and t.product_id=:productId and t.dpid=:dpid and t.is_set=0 and t.delete_flag=0 and t1.delete_flag=0 and t1.is_available=0 and t1.begin_time <= :now and t1.end_time >=:now and t1.promotion_type=1';
				$allNarmanPromotions = Yii::app()->db->createCommand($sql)->bindValue(':productId',$this->productId)->bindValue(':dpid',$this->dpid)->bindValue(':now',$formatTime)->queryAll();
				foreach($allNarmanPromotions as $allPromotion){
					if($promotion['is_discount']){
						//优惠
						$this->price = $this->price - $allPromotion['promotion_money'];				
					}else{
					   //打折
					   $this->price = $this->price*$allPromotion['promotion_discount']/100;
					}
					$this->price = $this->price >0 ? $this->price : 0;
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