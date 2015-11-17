<?php 
/**
 * 
 * 
 * 微信端产品类
 * 
 */
class WxProductPrice
{
	public $productId;
	public $dpid;
	public $userId;
	public $set;
	public $price;
	
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
		if(!$this->set['is_narmal_promotion']){
			
		}
		if(!$this->set['is_private_promotion']){
			
		}
	}
}