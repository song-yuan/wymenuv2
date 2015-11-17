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
	public $productList = array();
	
	public function __construct($companyId){
		$this->companyId = $companyId;
		$this->productList();
	}
	public function productList(){
		$sql = 'select * from nb_product where status=0 and is_show=1 and delete_flag=0 and dpid=:dpid';
		$this->productList = Yii::app()->db->createCommand($sql)->bindValue(':dpid',$this->companyId)->queryAll();
	}
}