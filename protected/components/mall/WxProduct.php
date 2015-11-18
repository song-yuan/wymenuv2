<?php 
/**
 * 
 * 
 * 微信端产品类
 * 
 */
class WxProduct
{
	public $companyId;
	public $userId;
	public $productList = array();
	
	public function __construct($companyId,$userId = null){
		$this->companyId = $companyId;
		$this->userId = $userId;
		$this->productList();
		$this->productPrice();
	}
	public function productList(){
		$sql = 'select * from nb_product where status=0 and is_show=1 and delete_flag=0 and dpid=:dpid';
		$this->productList = Yii::app()->db->createCommand($sql)->bindValue(':dpid',$this->companyId)->queryAll();
	}
	public function productPrice(){
		foreach($this->productList as $k=>$product){
			$productPrice = new WxProductPrice($product['lid'],$product['dpid'],$this->userId);
			$this->productList[$k]['original_price'] = $productPrice->price;
			$this->productList[$k]['promotion'] = $productPrice->promotion;
		}
	}
}