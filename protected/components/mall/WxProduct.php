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
	public $siteId;
	public $categorys = array();
	public $categoryProductLists = array();
	
	public function __construct($companyId,$userId){
		$this->companyId = $companyId;
		$this->userId = $userId;
		$this->getCategory();
		$this->productList();
	}
	public function getCategory(){
		$this->categorys = WxCategory::get($this->companyId);
		$this->categoryProductLists = $this->categorys;
	}
	public function productList(){
		foreach($this->categoryProductLists as $k=>$category){
			$childrenCategorys = WxCategory::getChrildrenIds($this->companyId,$category['lid']);
			if(!empty($childrenCategorys)){
				$categoryIds = join(',',$childrenCategorys);
			}else{
				$categoryIds = $category['lid'];
			}
			$sql = 'select m.*,n.num from (select * from nb_product where status=0 and is_show=1 and delete_flag=0 and dpid=:dpid and category_id in ('.$categoryIds.'))m left join nb_cart n on m.lid=n.product_id and n.user_id=:userId and promotion_id=-1 order by m.lid desc';
		    $categoryProducts = Yii::app()->db->createCommand($sql)->bindValue(':dpid',$this->companyId)->bindValue(':userId',$this->userId)->queryAll();
		    foreach($categoryProducts as $j=>$product){
		    	$productPrice = new WxProductPrice($product['lid'],$product['dpid']);
				$categoryProducts[$j]['price'] = $productPrice->price;
				$categoryProducts[$j]['promotion'] = $productPrice->promotion;
		    }
		    $this->categoryProductLists[$k]['product_list'] = $categoryProducts;
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
}