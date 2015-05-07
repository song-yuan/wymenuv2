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
	
	public static function getCategoryProducts($dpid=0,$categoryId,$siteNoId=0)
	{
		$siteId = 0;
		$siteNo = SiteNo::model()->find('lid=:lid',array(':lid'=>$siteNoId));
		if($siteNo){
			$siteId = $siteNo['site_id'];
		}
		//type 0 普通商品 1 套餐
		$sql = 'select t.*,t1.order_id from(select lid,product_name, original_price, main_picture, rank, order_number, favourite_number,0 as type from nb_product where dpid=:companyId and category_id=:categoryId and status=0 and delete_flag=0 and is_show = 1)t LEFT JOIN (select order_id,product_id from nb_order_product t2 LEFT JOIN nb_order t3 on t2.order_id=t3.lid where t2.dpid = :companyId and t3.site_id=:siteId and t2.delete_flag=0 and t2.product_order_status=0 and t2.set_id=0 )t1 on t.lid=t1.product_id ' .
			   'union select r.*,s.order_id from(' .
			   'select lid , product_name, sum(price) as original_price, main_picture, rank, order_number, favourite_number, type from(' .
			   'select n.lid,n.set_name as product_name, n.main_picture, n.rank, n.order_number, n.favourite_number, n1.price,1 as type from nb_product_set n LEFT JOIN nb_product_set_detail n1 on n.lid=n1.set_id  where n.dpid=n1.dpid and n.dpid=:companyId  and n.delete_flag=0 and n1.delete_flag=0 and n1.is_select=1)r1 group by lid )r LEFT JOIN (select order_id,set_id from nb_order_product t4 LEFT JOIN nb_order t5 on t4.order_id=t5.lid where t4.dpid = :companyId and t5.site_id=:siteId and t4.delete_flag=0 and t4.product_order_status=0  and t4.set_id > 0)s on r.lid=s.set_id';
		$connect = Yii::app()->db->createCommand($sql);
		$connect->bindValue(':companyId',$dpid);
		$connect->bindValue(':categoryId',$categoryId);
		$connect->bindValue(':siteId',$siteId);
		$product = $connect->queryAll();
		return $product;
	}
	public static function getCartInfo($dpid = 0,$siteNoId = 0){
		$command = Yii::app()->db;
		
		$sql = 'select * from nb_site_no where lid=:lid and dpid=:dpid';
		$conn = $command->createCommand($sql);
		$conn->bindValue(':lid',$siteNoId);
		$conn->bindValue(':dpid',$dpid);
		$siteNo = $conn->queryRow();
		
		$sql = 'select * from nb_order where dpid=:dpid and site_id=:siteId and is_temp=:isTemp order by lid desc';
		$conn = $command->createCommand($sql);
		$conn->bindValue(':dpid',$siteNo['dpid']);
		$conn->bindValue(':siteId',$siteNo['site_id']);
		$conn->bindValue(':isTemp',$siteNo['is_temp']);
		$order = $conn->queryRow();
		
		$sql = 'select * from nb_order_product where order_id=:orderId and dpid=:dpid and delete_flag = 0 and product_order_status = 0 and set_id = 0 ' .
			   ' union select lid,dpid,create_at,update_at,order_id,set_id,main_id,product_id,is_retreat,sum(price) as price,amount,zhiamount,is_waiting,weight,taste_memo,is_giving,is_print,delete_flag,product_order_status from nb_order_product where order_id=:orderId and dpid=:dpid and delete_flag = 0 and product_order_status = 0 and set_id > 0 group by set_id';
		$conn = $command->createCommand($sql);
		$conn->bindValue(':orderId',$order['lid']);
		$conn->bindValue(':dpid',$siteNo['dpid']);
		$orderProdcuts = $conn->queryAll();
		$nums = count($orderProdcuts);
		$price = 0.00;
		foreach($orderProdcuts as $orderProdcut){
			$price += $orderProdcut['price']*$orderProdcut['amount'];
		}
		
		$result = join(':',array($price,$nums));
		return $result;
	}
	public static function getCategoryName($categoryId = 0){
		$command = Yii::app()->db;
		
		$sql = 'select * from nb_product_category where lid=:lid';
		$conn = $command->createCommand($sql);
		$conn->bindValue(':lid',$categoryId);
		$result = $conn->queryRow();
		
		return $result['category_name'];
	}
	public static function getProductPic($dpid = 0,$productId = 0){
		$picArr =array();
		$command = Yii::app()->db;
		
		$sql = 'select pic_path from nb_product_picture where dpid=:dpid and product_id=:productId and delete_flag=0';
		$conn = $command->createCommand($sql);
		$conn->bindValue(':dpid',$dpid);
		$conn->bindValue(':productId',$productId);
		$result = $conn->queryAll();
		if($result){
			$picArr =$result;
		}
		return $picArr;
	}
	public static function getHotsProduct($dpid = 0,$type = 1,$siteNoId = 0){
		$command = Yii::app()->db;
		$siteId = 0;
		$siteNo = SiteNo::model()->find('lid=:lid',array(':lid'=>$siteNoId));
		if($siteNo){
			$siteId = $siteNo['site_id'];
		}
		switch($type){
			case 1:
			$sql = 'select t8.*,t9.order_id from (select t.price_discount,t.is_discount,t1.lid,t1.product_name, t1.original_price, t1.main_picture, t1.rank, t1.order_number, t1.favourite_number,0 as type from nb_product_discount t,nb_product t1 where t.product_id=t1.lid and t.dpid=:companyId and t1.status=0 and t1.delete_flag=0 and t1.is_show = 1 and t.is_set=0 and t.begin_time<:time and t.end_time>:time ' .
				   ' union select  t2.price_discount,t2.is_discount,t3.lid,t3.set_name as product_name, 0 as original_price, t3.main_picture, t3.rank, t3.order_number, t3.favourite_number,1 as type from nb_product_discount t2,nb_product_set t3 where t2.product_id=t3.lid and t2.dpid=:companyId and t3.status=0 and t3.delete_flag=0 and t2.is_set=1 and t2.begin_time<:time and t2.end_time>:time ' .
				   ' union select t4.price,2 as is_discount,t5.lid,t5.product_name, t5.original_price, t5.main_picture, t5.rank, t5.order_number, t5.favourite_number,0 as type from nb_product_special t4,nb_product t5 where t4.product_id=t5.lid and t4.dpid=:companyId and t5.status=0 and t5.delete_flag=0 and t5.is_show = 1 and t4.is_set=0 and t4.begin_time<:time and t4.end_time>:time ' .
				   ' union select t6.price,2 as is_discount,t7.lid,t7.product_name, t7.original_price, t7.main_picture, t7.rank, t7.order_number, t7.favourite_number,1 as type from nb_product_special t6,nb_product t7 where t6.product_id=t7.lid and t6.dpid=:companyId and t7.status=0 and t7.delete_flag=0 and t7.is_show = 1 and t6.is_set=1 and t6.begin_time<:time and t6.end_time>:time )t8 ' .
				   ' LEFT JOIN (select order_id,product_id from nb_order_product t10 LEFT JOIN nb_order t11 on t10.order_id=t11.lid where t11.site_id=:siteId and  t10.delete_flag=0 and t10.product_order_status=0 )t9 on t8.lid=t9.product_id ';
				   break;
			case 2:
			$sql = 'select t.*,t1.order_id from (select lid , product_name, sum(price) as original_price, main_picture, rank, order_number, favourite_number, type from(select  n.lid,n.set_name as product_name, n.main_picture, n.rank, n.order_number, n.favourite_number, n1.price,1 as type from nb_product_set n LEFT JOIN nb_product_set_detail n1 on n.lid=n1.set_id  where n.dpid=:companyId and n1.dpid=:companyId and n.delete_flag=0 and n1.delete_flag=0 and n1.is_select=1)m group by lid)t' .
					' LEFT JOIN (select order_id,product_id from nb_order_product t2 LEFT JOIN nb_order t3 on t2.order_id=t3.lid where t3.site_id=:siteId and t2.delete_flag=0 and t2.product_order_status=0 )t1 on t.lid=t1.product_id';
				   break;
			case 3:
			$sql = 'select t.*,t1.order_id from ' .
			   	   ' (select lid,product_name, original_price, main_picture, rank, order_number, favourite_number,0 as type from nb_product where dpid=:companyId and status=0 and delete_flag=0 and is_show = 1 ' .
			   	   'union select lid , product_name, sum(price) as original_price, main_picture, rank, order_number, favourite_number, type from(select  n.lid,n.set_name as product_name, n.main_picture, n.rank, n.order_number, n.favourite_number, n1.price,1 as type from nb_product_set n LEFT JOIN nb_product_set_detail n1 on n.lid=n1.set_id  where n.dpid=:companyId and n1.dpid=:companyId and n.delete_flag=0 and n1.delete_flag=0 and n1.is_select=1)m group by lid)t ' .
			   	   ' LEFT JOIN (select order_id,product_id from nb_order_product t2 LEFT JOIN nb_order t3 on t2.order_id=t3.lid where t3.site_id=:siteId and t2.delete_flag=0 and t2.product_order_status=0 )t1 on t.lid=t1.product_id order by favourite_number desc limit 10';
				   break;
			case 4:
			$sql = 'select t.*,t1.order_id from ' .
			   	   ' (select lid,product_name, original_price, main_picture, rank, order_number, favourite_number,0 as type from nb_product where dpid=:companyId and status=0 and delete_flag=0 and is_show = 1 ' .
			   	   'union select lid , product_name, sum(price) as original_price, main_picture, rank, order_number, favourite_number, type from(select  n.lid,n.set_name as product_name, n.main_picture, n.rank, n.order_number, n.favourite_number, n1.price,1 as type from nb_product_set n LEFT JOIN nb_product_set_detail n1 on n.lid=n1.set_id  where n.dpid=:companyId and n1.dpid=:companyId and n.delete_flag=0 and n1.delete_flag=0 and n1.is_select=1)m group by lid)t ' .
			   	   'LEFT JOIN (select order_id,product_id from nb_order_product t2 LEFT JOIN nb_order t3 on t2.order_id=t3.lid where t3.site_id=:siteId and t2.delete_flag=0 and t2.product_order_status=0 )t1 on t.lid=t1.product_id order by order_number desc limit 10';
				   break;
		}
		$conn = $command->createCommand($sql);
		$conn->bindValue(':companyId',$dpid);
		$conn->bindValue(':siteId',$siteId);
		if($type==1){
			$conn->bindValue(':time',date('Y-m-d H:i:s',time()));
		}
		$result = $conn->queryAll();
		return $result;
	}
        
        public static function getProductName($productId,$companyId){
		$sql = 'SELECT product_name from nb_product where lid='.$productId.' and dpid='.$companyId;
		$product = Yii::app()->db->createCommand($sql)->queryRow();
		return $product['product_name'];
	}
        
        public static function getCategories($companyId){
		$criteria = new CDbCriteria;
		$criteria->with = 'company';
		$criteria->condition =  't.delete_flag=0 and t.dpid='.$companyId ;
		$criteria->order = ' tree,t.lid asc ';
		
		$models = ProductCategory::model()->findAll($criteria);
                
		//return CHtml::listData($models, 'lid', 'category_name','pid');
		$options = array();
		$optionsReturn = array('--请选择分类--');
		if($models) {
			foreach ($models as $model) {
				if($model->pid == '0') {
					$options[$model->lid] = array();
				} else {
					$options[$model->pid][$model->lid] = $model->category_name;
				}
			}
                        //var_dump($options);exit;
		}
		foreach ($options as $k=>$v) {
                    //var_dump($k,$v);exit;
			$model = ProductCategory::model()->find('t.lid = :lid and dpid=:dpid',array(':lid'=>$k,':dpid'=>  $companyId));
			$optionsReturn[$model->category_name] = $v;
		}
		return $optionsReturn;
	}
        
        public static function getProducts($categoryId,$companyId){
                if($categoryId==0)
                {
                    //var_dump ('2',$categoryId);exit;
                    $products = Product::model()->findAll('dpid=:companyId and delete_flag=0' , array(':companyId' => $companyId));
                }else{
                    //var_dump ('3',$categoryId);exit;
                    $products = Product::model()->findAll('dpid=:companyId and category_id=:categoryId and delete_flag=0' , array(':companyId' => $companyId,':categoryId'=>$categoryId)) ;
                }
                $products = $products ? $products : array();
                //var_dump($products);exit;
                return $products;
		//return CHtml::listData($products, 'lid', 'product_name');
	}
        
        public static function getAdditionProducts($productId,$companyId){
                $products = ProductAddition::model()->with('sproduct')->findAll('t.dpid=:companyId and t.mproduct_id=:mproductId and t.delete_flag=0' , array(':companyId' => $companyId,':mproductId'=>$productId)) ;
                
                $products = $products ? $products : array();
                //var_dump($products);exit;
                return $products;
		//return CHtml::listData($products, 'lid', 'product_name');
	}
        
        public static function getCategoryList($companyId){
		$categories = ProductCategory::model()->findAll('delete_flag=0 and dpid=:companyId' , array(':companyId' => $companyId)) ;
		//var_dump($categories);exit;
		return CHtml::listData($categories, 'lid', 'category_name');
	}
        
}