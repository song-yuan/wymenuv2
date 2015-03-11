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
			$this->companyId = $companyWifi?$companyWifi->dpid:0;
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
		
		if(!$categoryId){
			$categorys = ProductClass::getFirstCategoryId($this->companyId);
			$pid = $categorys['pid'];
			$categoryId = $categorys['lid'];
		}
		$this->render('product',array('pid'=>$pid,'categoryId'=>$categoryId,'siteNoId'=>$this->siteNoId));
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
			$product = ProductClass::getCategoryProducts($this->companyId,$this->siteNoId);
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
	 //订单列表
	public function actionOrderList(){
		$confirm = Yii::app()->request->getParam('confirm',0);
		$goodsIds = isset($_POST) ?$_POST :array();
		
		if($confirm){
			$orderId = Yii::app()->request->getParam('orderId',0);
			if(!OrderList::UpdateOrder($orderId,$goodsIds)){
			   $this->redirect(array('/product/order','orderId'=>$orderId));
			}
		}
		
	 	$this->render('orderlist');
	}
	//确认订单
	public function actionConfirmOrder(){
		
	 	$this->render('confirmOrder');
	}
	//订单
	public function actionOrder(){
		$orderId = Yii::app()->request->getParam('orderId');
		$goodsIds = isset($_POST) ?$_POST :array();
		if(!$goodsIds && !OrderList::UpdateOrder($orderId,$goodsIds)){
			$this->redirect(array('/product/orderList'));
		}
		$orderList = new OrderList($this->siteNoId);
		
	 	$this->render('order',array('orderList'=>$orderList));
	}
}