<?php 
/**
 * 
 * 
 * 微信端个人优惠类
 * 
 */
class WxPromotion
{
	public $dpid;
	public $userId;
	public $siteId;
	
	public function __construct($dpid,$userId,$siteId){
		$this->dpid = $dpid;
		$this->userId = $userId;
		$this->siteId = $siteId;
		$this->getUser();
		$this->getUserPromotion();
	}
	public function getUser(){
		$sql = 'select * from nb_brand_user where lid=:lid and dpid=:dpid';
		$this->user = Yii::app()->db->createCommand($sql)->bindValue(':dpid',$this->dpid)->bindValue(':lid',$this->userId)->queryRow();
	}
	public function getUserPromotion(){
		$now = date('Y-m-d H:i:s',time());
		$sql = 'select t.private_promotion_id,t1.promotion_title,t1.promotion_type,t1.order_num from nb_private_branduser t,nb_private_promotion t1 where t.private_promotion_id=t1.lid and t.dpid=t1.dpid and t.brand_user_lid=:userId and t.dpid=:dpid and t1.begin_time <= :now and :now <= t1.end_time and t.is_used=1 and t.to_group=3 and t.delete_flag=0' .
			   ' union select t.private_promotion_id,t1.promotion_title,t1.promotion_type,t1.order_num from nb_private_branduser t,nb_private_promotion t1 where t.private_promotion_id=t1.lid and t.dpid=t1.dpid and t.brand_user_lid=:userLevelId and t.dpid=:dpid and t1.begin_time <= :now and :now <= t1.end_time and t.is_used=1 and t.to_group=2 and t.delete_flag=0';
		$results = Yii::app()->db->createCommand($sql)->bindValue(':dpid',$this->dpid)->bindValue(':userId',$this->user['lid'])->bindValue(':userLevelId',$this->user['user_level_lid'])->bindValue(':now',$now)->queryAll();
		foreach($results as $k=>$result){
			$sql = 'select m.*,n.num from (select t.product_id,t.is_set,t.is_discount,t.promotion_money,t.promotion_discount,t.order_num,t1.product_name,t1.main_picture,t1.original_price from nb_private_promotion_detail t,nb_product t1 where t.product_id=t1.lid and t.dpid=t1.dpid and t.dpid=:dpid and t.private_promotion_id=:promotionId and t.delete_flag=0)m left join nb_cart n on m.product_id=n.product_id and n.user_id=:userId and n.site_id=:siteId and privation_promotion_id=:promotionId';
			$products = Yii::app()->db->createCommand($sql)->bindValue(':dpid',$this->dpid)->bindValue(':userId',$this->user['lid'])->bindValue(':siteId',$this->siteId)->bindValue(':promotionId',$result['private_promotion_id'])->queryAll();
			foreach($products as $j=>$product){
				if($product['is_discount']==0){
					$products[$j]['price'] = ($product['original_price'] - $product['promotion_money']) > 0 ? $product['original_price'] - $product['promotion_money'] : 0;
				}else{
					$products[$j]['price'] = ($product['original_price']*$product['promotion_discount']) > 0 ? $product['original_price']*$product['promotion_discount'] : 0;
				}
			}
			$results[$k]['productList'] = $products;
		}
		$this->promotionProductList = $results;
	}
	
}