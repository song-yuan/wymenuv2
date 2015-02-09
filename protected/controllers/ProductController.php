<?php

class ProductController extends Controller
{
	public $companyId = 0;
	public $wifMac = 0;
	public $moMac = 0;
	public $siteNoId = 0;
	
	public $layout = '/layouts/productmain';
	public function init(){
		session_start();
		$this->companyId = 1;
		$moMac = Yii::app()->request->getParam('momac',0);
		if($moMac){
			$_SESSION['momac'] = $moMac;
		}
		$mac = Yii::app()->request->getParam('wuyimenusysosyoyhmac',0);
		if($mac){
			$companyWifi = CompanyWifi::model()->find('macid=:macId',array(':macId'=>$mac));
			$this->companyId = $companyWifi?$companyWifi->company_id:0;
			$_SESSION['companyId'] = $this->companyId;
		}
		if(!$this->companyId){
			$this->companyId = isset($_SESSION['companyId'])?$_SESSION['companyId']:0;
		}
		$checkCode = Yii::app()->request->getParam('checkcode',0);
		if($checkCode){
			$siteNo = SiteNo::model()->find('dpid=:companyId and code=:code',array(':companyId'=>$this->companyId,':code'=>$checkCode));
			$_SESSION['siteNoId'] = $siteNo['lid'];
		}
		if(!$this->siteNoId){
			$this->siteNoId = isset($_SESSION['siteNoId'])?$_SESSION['siteNoId']:1;
		}
		$this->moMac = isset($_SESSION['momac'])?$_SESSION['momac']:0;
	}
	/**
	 * //----n---
	 * 获取一级分类 没启用
	 */
	public function actionProductCategory(){
		$totalCatgorys = array();
		$command = Yii::app()->db;
		$sql = 'select lid,category_name from nb_product_category where dpid=:companyId and pid=0 and delete_flag=0';
		$parentCategorys = $command->createCommand($sql)->bindValue(':companyId',$this->companyId)->queryAll();
		foreach($parentCategorys as $category){
			$csql = 'select lid,category_name from nb_product_category where dpid=:companyId and pid=:pid and delete_flag=0';
			$categorys = $command->createCommand($csql)->bindValue(':companyId',$this->companyId)->bindValue(':pid',$category['lid'])->queryAll();
			$category['children'] = $categorys;
			array_push($totalCatgorys,$category);
		}
		$this->renderPartial('parentcategory',array('parentCategorys'=>$totalCatgorys));
	}
	/**
	 * 
	 * 获取分类商品
	 */
	public function actionIndex()
	{
		$pid = Yii::app()->request->getParam('pid',0);
		$categoryId = Yii::app()->request->getParam('category',0);
		
		$command = Yii::app()->db;
		if(!$categoryId){
			$sql = 'select lid, pid,category_name from nb_product_category where dpid=:companyId and pid=0 and delete_flag=0';
			$parentCategorys = $command->createCommand($sql)->bindValue(':companyId',$this->companyId)->queryRow();
			$csql = 'select lid, pid, category_name from nb_product_category where dpid=:companyId and pid=:pid and delete_flag=0';
			$categorys = $command->createCommand($csql)->bindValue(':companyId',$this->companyId)->bindValue(':pid',$parentCategorys['lid'])->queryRow();
			$pid = $categorys['pid'];
			$categoryId = $categorys['lid'];
		}
		$this->render('product',array('pid'=>$pid,'categoryId'=>$categoryId));
	}
	/**
	 * 
	 * 商品详情
	 */
	 public function actionProductInfo(){
	 	$id = Yii::app()->request->getParam('id',0);
	 	$product = Product::model()->findByPk($id);
	 	$this->render('productinfo',array('product'=>$product));
	 }
	 /**
	  * 
	  * 推荐商品
	  */
	 public function actionRecommend(){
	 	$this->render('recommend');
	 }
	public function actionGetJson()
	{
//		$page = Yii::app()->request->getParam('page',1);
		$rec = Yii::app()->request->getParam('rec',0);
		if($rec){
			$sql = 'select * from nb_product where dpid=:companyId and recommend=1 and status=0 and delete_flag=0 and is_show = 1';
			$connect = Yii::app()->db->createCommand($sql);
			$connect->bindValue(':companyId',$this->companyId);
			$product = $connect->queryAll();
		}else{
			$categoryId = Yii::app()->request->getParam('cat',0);
			$product = Product::getCategoryProducts($this->companyId,$this->siteNoId);
		}
		Yii::app()->end(json_encode($product));
	}
	
	/**
	 * 点单
	 * 
	 */
	public function actionCreateCart(){
		$isAddOrder = Yii::app()->request->getPost('isAddOrder');
		$productId = Yii::app()->request->getPost('productId');
		$type = Yii::app()->request->getPost('type');//是否是套餐
		$product = array('lid'=>$productId,'type'=>$type);
		echo 1;exit;
		if($isAddOrder){
			//增加
			$createOrder = new CreateOrder($this->siteNoId,$product);
			if($createOrder->createOrder()){
				echo 1;
			}else{
				echo 0;
			}
		}else{
			//删除
			if(CreateOrder::deleteOrderProduct($this->companyId,$productId)){
				echo 1;
			}else{
				echo 0;
			}
		}
		
		exit;
	}
	/**
	 * 商品点赞
	 */
	public function actionFavorite(){
		$productId = Yii::app()->request->getParam('id');
		$model = Product::model()->find('lid=:lid',array(':lid'=>$productId));
		$model->favourite_number = $model->favourite_number + 1; 
		if($model->update()){
			echo 1;
		}else{
			echo 0;
		}
		exit;
	}
	/**
	 * 
	 * 购物车列表
	 */
	public function actionCartList(){
		$isCode = 0;//判断是否是服务生成的开台号 是1 否0
		$cartLists = array();
		$seatnum = Yii::app()->request->getParam('code',0);
		if(!$seatnum){
			$seatnum = $this->seatNum;//如果没有开台号 设置为临时座次号
		}
		$model = SiteNo::model()->find('code=:code and delete_flag=0',array(':code'=>$seatnum));
		if($model){
			$isCode = 1;
			if($this->seatNum > 1000000){
				Cart::model()->updateAll(array('code'=>$seatnum),'code=:code',array(':code'=>$this->seatNum));
			}
			$cartLists = Cart::model()->with('product')->findAll('t.company_id=:companyId and t.code=:code',array(':companyId'=>$this->companyId,':code'=>$seatnum));
			
			$_SESSION['seatnum'] = $seatnum;//正式座次号放session
			$this->seatNum = $seatnum;
		}
		$this->render('cartlist',array('cartLists'=>$cartLists,'seatnum'=>$this->seatNum,'isCode'=>$isCode));
	}
	/**
	 * 
	 * 点击减号减少购物车商品
	 */
	public function actionDeleteCartProduct(){
		$id = Yii::app()->request->getParam('id');
		$cartproduct= Cart::model()->find('company_id=:companyId and product_id=:productId and code=:code',array(':companyId'=>$this->companyId,':productId'=>$id,':code'=>$this->seatNum));
		if($cartproduct->delete()){
			echo 1;
		}else{
			echo 0;
		}
		Yii::app()->end();
	}
	public function actionDeleteCart(){
		$id = Yii::app()->request->getParam('id');
		$cart= Cart::model()->findByPk($id);
		$cart->delete();
		$this->redirect(array('/product/cartList','code'=>$this->seatNum));
	}
	/**
	 * 生成订单
	 * $products = array(array(2,1,18),array(3,1,29)) ==>array(product_id,product_num,price)
	 */
	 
	 public function actionCreateOrder(){
	 	$seatnum = Yii::app()->request->getParam('code');
	 	
	 	$siteNo = SiteNo::model()->find('company_id=:companyId and code=:code and delete_flag=0',array(':companyId'=>$this->companyId,':code'=>$seatnum));
	 	if(!$siteNo){
	 		echo 0;exit;
	 	}
	 
	 	if(Yii::app()->request->isPostRequest){
	 		$now = time();
	 		$site_no_id = $siteNo->id;
	 		$waiter_id = $siteNo->waiter_id;
	 		$number = $siteNo->number;
	 		$products = Yii::app()->request->getPost('products');
	 		
	 		$transaction=Yii::app()->db->beginTransaction();
	 		try{
	 			$order = Order::model()->with('siteNo')->find('t.company_id=:companyId and siteNo.code=:code and delete_flag=0',array(':companyId'=>$this->companyId,':code'=>$seatnum));
		 		if(!$order){
		 			$order = new Order;
			 		$orderData = array(
			 							'company_id'=>$this->companyId,
			 							'site_no_id'=>$site_no_id,
			 							'waiter_id'=>$waiter_id,
			 							'number'=>$number,
			 							'create_time'=>$now,
			 							);
			 		$order->attributes = $orderData;
			 		$order->save();
		 		}
		 		$orderId = $order->order_id;
		 		foreach($products as $product){
		 			$orderProduct = new OrderProduct;
		 				$productData = array(
		 									'order_id'=>$orderId,
		 									'product_id'=>$product[0],
		 									'price'=>$product[2],
		 									'amount'=>$product[1],
		 									);
		 			$orderProduct->attributes = $productData;
		 			$orderProduct->save();
		 		}
		 		$transaction->commit();
		 		$res = Helper::printCartGoods($this->companyId,$this->seatNum);
		 		
		 		//setcookie('orderId',$orderId);
	 		}catch (Exception $e) {
            	$transaction->rollback();//回滚函数
        	}
	 	}
	 	$this->redirect(array('/product/cartList','code'=>$seatnum));
	 }
	public function actionOrderList(){
		
	 	$this->render('orderlist');
	}
}