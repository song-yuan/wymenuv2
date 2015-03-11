<?php
class ProductClass
{
	public static function getFirstCategoryId($dpid = 0){
		$command = Yii::app()->db;
		$sql = 'select lid, pid,category_name from nb_product_category where dpid=:companyId and pid=0 and delete_flag=0';
		$parentCategorys = $command->createCommand($sql)->bindValue(':companyId',$dpid)->queryRow();
		$csql = 'select lid, pid, category_name from nb_product_category where dpid=:companyId and pid=:pid and delete_flag=0';
		$categorys = $command->createCommand($csql)->bindValue(':companyId',$dpid)->bindValue(':pid',$parentCategorys['lid'])->queryRow();
		return $categorys;
	}
	
	public static function getCategoryProducts($dpid=0,$siteNoId=0)
	{
		$siteId = 0;
		$siteNo = SiteNo::model()->find('lid=:lid',array(':lid'=>$siteNoId));
		if($siteNo){
			$siteId = $siteNo['site_id'];
		}
		//type 0 普通商品 1 套餐
		$sql = 'select t.*,t1.order_id from ' .
			   '(select lid,product_name, original_price, main_picture, rank, order_number, favourite_number,0 as type from nb_product where dpid=:companyId and status=0 and delete_flag=0 and is_show = 1 union select  lid,set_name as product_name, 0 as original_price, main_picture, rank, order_number, favourite_number, 1 as type from nb_product_set where delete_flag=0)t ' .
			   'LEFT JOIN (select order_id,product_id from nb_order_product t2 LEFT JOIN nb_order t3 on t2.order_id=t3.lid where t3.site_id=:siteId )t1 on t.lid=t1.product_id';
		$connect = Yii::app()->db->createCommand($sql);
		$connect->bindValue(':companyId',$dpid);
		$connect->bindValue(':siteId',$siteId);
		$product = $connect->queryAll();
		return $product;
	}
	public static function getCartInfo($siteNoId = 0){
		$command = Yii::app()->db;
		
		$sql = 'select * from nb_site_no where lid=:lid';
		$conn = $command->createCommand($sql);
		$conn->bindValue(':lid',$siteNoId);
		$siteNo = $conn->queryRow();
		
		$sql = 'select * from nb_order where dpid=:dpid and site_id=:siteId and is_temp=:isTemp order by lid desc';
		$conn = $command->createCommand($sql);
		$conn->bindValue(':dpid',$siteNo['dpid']);
		$conn->bindValue(':siteId',$siteNo['site_id']);
		$conn->bindValue(':isTemp',$siteNo['is_temp']);
		$order = $conn->queryRow();
		
		$sql = 'select * from nb_order_product where order_id=:orderId and delete_flag = 0 and product_order_status = 0';
		$conn = $command->createCommand($sql);
		$conn->bindValue(':orderId',$order['lid']);
		$orderProdcuts = $conn->queryAll();
		
		$nums = count($orderProdcuts);
		$price = 0.00;
		foreach($orderProdcuts as $orderProdcut){
			$price += $orderProdcut['price']*$orderProdcut['amount'];
		}
		
		$result = join(':',array($price,$nums));
		return $result;
	}
}