<?php 
/**
 * 
 * 
 * 微信端产品类
 * 
 */
class WxProduct
{
	public $dpid;
	public $userId;
	public $siteId;
	public $categorys = array();
	public $categoryProductLists = array();
	public $productSetLists = array();
	
	public function __construct($dpid,$userId){
		$this->dpid = $dpid;
		$this->userId = $userId;
		$this->getCategory();
		$this->productList();
		$this->productSetList();
	}
	public function getCategory(){
		$this->categorys = WxCategory::get($this->dpid);
		$this->categoryProductLists = $this->categorys;
	}
	public function productList(){
		foreach($this->categoryProductLists as $k=>$category){
			$childrenCategorys = WxCategory::getChrildrenIds($this->dpid,$category['lid']);
			if(!empty($childrenCategorys)){
				$categoryIds = join(',',$childrenCategorys);
			}else{
				$categoryIds = $category['lid'];
			}
			$sql = 'select m.*,n.num from (select * from nb_product where status=0 and is_show=1 and delete_flag=0 and dpid=:dpid and category_id in ('.$categoryIds.'))m left join nb_cart n on m.lid=n.product_id and n.user_id=:userId and is_set=0 and promotion_id=-1 order by m.sort asc,m.lid desc';
		    $categoryProducts = Yii::app()->db->createCommand($sql)->bindValue(':dpid',$this->dpid)->bindValue(':userId',$this->userId)->queryAll();
		    $this->categoryProductLists[$k]['product_list'] = $categoryProducts;
		}
	}
	public function productSetList(){
		$sql = 'select m.*,n.num from (select * from nb_product_set where status=0 and is_show=1 and delete_flag=0 and dpid=:dpid)m left join nb_cart n on m.lid=n.product_id and n.user_id=:userId and is_set=1 and promotion_id=-1 order by m.lid desc';
		$this->productSetLists = Yii::app()->db->createCommand($sql)->bindValue(':dpid',$this->dpid)->bindValue(':userId',$this->userId)->queryAll();
		foreach ($this->productSetLists as $k=>$set){
			$setDetail = self::getProductSetDetail($set['lid'], $set['dpid']);
			$this->productSetLists[$k]['detail'] = $setDetail;
		}
	}
	/**
	 * 
	 * 获取产品
	 * 
	 */
	 public static function getProduct($productId,$dpid){
	 	$sql = 'select * from nb_product where lid=:lid and dpid=:dpid';
	 	$product = Yii::app()->db->createCommand($sql)->bindValue(':dpid',$dpid)->bindValue(':lid',$productId)->queryRow();
	 	return $product;
	 }
	 public static function getProductSet($productSetId,$dpid){
	 	$sql = 'select * from nb_product_set where lid=:lid and dpid=:dpid';
	 	$productSet = Yii::app()->db->createCommand($sql)->bindValue(':dpid',$dpid)->bindValue(':lid',$productSetId)->queryRow();
	 	return $productSet;
	 }
	 public static function getProductSetDetail($productSetId,$dpid){
	 	$setDetails = array(); // 按分组号
 		$sql = 'select t.set_id,t.product_id,t.price,t.group_no,t.number,t.is_select,t1.* from nb_product_set_detail t, nb_product t1 where t.product_id=t1.lid and t.dpid=t1.dpid and t.set_id=:setId and t.dpid=:dpid and t.delete_flag=0 and t1.delete_flag=0';
 		$setDetail = Yii::app()->db->createCommand($sql)->bindValue(':dpid',$dpid)->bindValue(':setId',$productSetId)->queryAll();
	 	foreach ($setDetail as $detail){
	 		$setDetails[$detail['group_no']][] = $detail;
	 	}
 		return $setDetails;
	 }
}