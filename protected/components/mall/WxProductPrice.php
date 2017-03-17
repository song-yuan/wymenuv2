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
	public $isSet;
	public $price;
	public $promotion = array('promotion_type'=>0,'price'=>0,'promotion_info'=>array());
	
	public function __construct($lid,$dpid,$isSet){
		$this->productId = $lid;
		$this->dpid = $dpid;
		$this->isSet = $isSet;
		$this->price();
	}
	public function price(){
		if($this->isSet){
			$sql = 'select * from nb_product_set where lid=:lid and dpid=:dpid';
			$product = Yii::app()->db->createCommand($sql)->bindValue(':lid',$this->productId)->bindValue(':dpid',$this->dpid)->queryRow();
			$this->price = $product['set_price'];
		}else{
			$sql = 'select * from nb_product where lid=:lid and dpid=:dpid';
			$product = Yii::app()->db->createCommand($sql)->bindValue(':lid',$this->productId)->bindValue(':dpid',$this->dpid)->queryRow();
			$this->price = $product['original_price'];
		}
		$this->promotion['price'] = $this->price;
	}
}