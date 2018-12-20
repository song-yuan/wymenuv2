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
			if(($this->type=='6'&&$category['show_type']=='3')||($this->type=='2'&&$category['show_type']=='2')||$category['show_type']=='4'||$category['show_type']=='6'){
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
				$sql = 'select * from nb_product where status=0 and is_show=1 and delete_flag=0 and dpid=:dpid and category_id in ('.$categoryIds.') order by sort asc,lid desc';
				$categoryProducts = Yii::app()->db->createCommand($sql)->bindValue(':dpid',$this->dpid)->queryAll();
				foreach ($categoryProducts as $key=>$product){
					if(($this->type=='6'&&$product['is_show_wx']=='4')||($this->type=='2'&&$product['is_show_wx']=='3')||$product['is_show_wx']=='2'){
						unset($categoryProducts[$key]);
						continue;
					}
					$categoryProducts[$key]['taste_groups'] = WxTaste::getProductTastes($product['lid'],$product['dpid']);
				}
			}else{
				$sql = 'select * from nb_product_set where status=0 and is_show=1 and is_show_wx!=2 and delete_flag=0 and dpid=:dpid and category_id in ('.$categoryIds.') order by sort asc,lid desc';
				$categoryProducts = Yii::app()->db->createCommand($sql)->bindValue(':dpid',$this->dpid)->queryAll();
				foreach ($categoryProducts as $sk=>$set){
					if(($this->type=='6'&&$product['is_show_wx']=='4')||($this->type=='2'&&$product['is_show_wx']=='3')||$product['is_show_wx']=='2'){
						unset($categoryProducts[$key]);
						continue;
					}
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
	 /**
	  * 获取套餐详情
	  */
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