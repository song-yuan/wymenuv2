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
	 * 
	 */
	public $productId;
	public $dpid;
	public $isSet;
	public $price;
	
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
			$this->price = $product['member_price'];
		}else{
			$sql = 'select * from nb_product where lid=:lid and dpid=:dpid';
			$product = Yii::app()->db->createCommand($sql)->bindValue(':lid',$this->productId)->bindValue(':dpid',$this->dpid)->queryRow();
			$this->price = $product['member_price'];
		}
	}
}