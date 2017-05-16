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
	public $type;
	public $siteId;
	public $categorys = array();
	public $categoryProductLists = array();
	
	
	public function __construct($dpid,$userId,$type){
		$this->dpid = $dpid;
		$this->userId = $userId;
		$this->type = $type;
		$this->getCategory();
		$this->productList();
	}
	public function getCategory(){
		$this->categoryProductLists = WxCategory::get($this->dpid);
	}
	public function productList(){
		foreach($this->categoryProductLists as $k=>$category){
			if(($this->type=='6'&&($category['show_type']=='2'||$category['show_type']=='3'))||$category['show_type']=='4'){
				unset($this->categoryProductLists[$k]);
				continue;
			}
			
			$childrenCategorys = WxCategory::getChrildrenIds($this->dpid,$category['lid']);
			if(!empty($childrenCategorys)){
				$categoryIds = join(',',$childrenCategorys);
			}else{
				$categoryIds = $category['lid'];
			}
			
			if($category['cate_type']!=2){
				$sql = 'select m.*,n.num from (select * from nb_product where status=0 and is_show=1 and is_show_wx=1 and delete_flag=0 and dpid=:dpid and category_id in ('.$categoryIds.'))m left join nb_cart n on m.lid=n.product_id and n.user_id=:userId and is_set=0 and promotion_id=-1 order by m.sort asc,m.lid desc';
				$categoryProducts = Yii::app()->db->createCommand($sql)->bindValue(':dpid',$this->dpid)->bindValue(':userId',$this->userId)->queryAll();
			}else{
				$sql = 'select m.*,n.num from (select * from nb_product_set where status=0 and is_show=1 and is_show_wx=1 and delete_flag=0 and dpid=:dpid and category_id in ('.$categoryIds.'))m left join nb_cart n on m.lid=n.product_id and n.user_id=:userId and is_set=1 and promotion_id=-1 order by m.lid desc';
				$categoryProducts = Yii::app()->db->createCommand($sql)->bindValue(':dpid',$this->dpid)->bindValue(':userId',$this->userId)->queryAll();
				foreach ($categoryProducts as $sk=>$set){
					$setDetail = self::getProductSetDetail($set['lid'], $set['dpid']);
					if(!empty($setDetail)){
						$categoryProducts[$sk]['detail'] = $setDetail;
					}else{
						unset($categoryProducts[$sk]);
						continue;
					}
				}
			}
			if(empty($categoryProducts)){
				unset($this->categoryProductLists[$k]);
			}else{
				array_push($this->categorys,$category);
				$this->categoryProductLists[$k]['product_list'] = $categoryProducts;
			}
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
	 		$taste = WxTaste::getProductTastes($detail['product_id'], $dpid);
	 		$detail['taste_groups'] = $taste;
	 		$setDetails[$detail['group_no']][] = $detail;
	 	}
 		return array_merge($setDetails);
	 }
}