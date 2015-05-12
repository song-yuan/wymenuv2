<?php

class ProductController extends Controller
{
	public $companyId = 0;
	public $wifMac = 0;
	public $moMac = 0;
	public $siteNoId = 0;
	public $isPad = 0;
	
	public $layout = '/layouts/productmain';
	public function init(){
		session_start();
		$moMac = Yii::app()->request->getParam('momac',0);
		if($moMac){
			$_SESSION['momac'] = $moMac;
		}
		$mac = Yii::app()->request->getParam('wuyimenusysosyoyhmac',0);
		$padId = Yii::app()->request->getParam('padid',0);
		if($padId){
			$campanyId = Yii::app()->request->getParam('companyid',0);
			$this->companyId = $campanyId;
			$_SESSION['companyId'] = $this->companyId;
			while(true){
				$code = SiteClass::openTempSite($campanyId);
				if($code){
					$siteNo = SiteNo::model()->find('dpid=:companyId and code=:code',array(':companyId'=>$this->companyId,':code'=>$code));
					$_SESSION['siteNoId'] = $siteNo['lid'];
					break;
				}
			}
			Yii::app()->theme = 'pad';
		}
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
		$type = Yii::app()->request->getParam('type',0);
		$categoryId = Yii::app()->request->getParam('categoryId',0);
		
		if(!$categoryId){
			$categorys = ProductClass::getFirstCategoryId($this->companyId);
			$pid = $categorys['pid'];
			$categoryId = $categorys['lid'];
		}
		$this->render('product',array('pid'=>$pid,'categoryId'=>$categoryId,'siteNoId'=>$this->siteNoId,'type'=>$type,'isPad'=>$this->isPad));
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
		$type = Yii::app()->request->getParam('type',0);// 0 普通产品  1推荐品 2套餐 3点赞 4点单
		if($type){
			$product = ProductClass::getHotsProduct($this->companyId,$type,$this->siteNoId);
		}else{
			$categoryId = Yii::app()->request->getParam('cat',0);
			$pad = Yii::app()->request->getParam('pad',0);
			$product = ProductClass::getCategoryProducts($this->companyId,$categoryId,$this->siteNoId,$pad);
		}
		Yii::app()->end(json_encode($product));
	}
	public function actionGetOrderListJson()
	{
		$orderProductList = array();
		$orderList = new OrderList($this->companyId,$this->siteNoId);
		if($orderList->order){
			$orderProductList = $orderList->OrderProductList($orderList->order['lid'],0,1);
			foreach($orderProductList as $key=>$val){
				$orderProductList[$key]['category_name'] = OrderList::GetCatoryName($key);
				if(!$key){
					foreach($val as $k=>$v){
						$orderProductList[$key][$k]['product_id'] = ProductSetClass::GetProductSetProductIds($this->companyId,$v['set_id']);
					}
				}	
			}
		}
		Yii::app()->end(json_encode($orderProductList));
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
			var_dump($this->companyId);var_dump($this->siteNoId);exit;
			$createOrder = new CreateOrder($this->companyId,$this->siteNoId,$product);
			if($createOrder->createOrder()){
				echo 1;
			}else{
				echo 0;
			}
		}else{
			//删除
			$createOrder = new CreateOrder($this->companyId,$this->siteNoId,$product);
			if($createOrder->deleteOrderProduct()){
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
			//确认订单
			$orderId = Yii::app()->request->getParam('orderId',0);
			$orderlist = new OrderList($this->companyId,$this->siteNoId);
			if(!$orderlist->ConfirmOrder($orderId,$goodsIds)){
			   $this->redirect(array('/product/order','orderId'=>$orderId));
			}
		}
		
	 	$this->render('orderlist');
	}
	//确认订单
	public function actionConfirmPadOrder(){
		$goodsIds = isset($_POST) ?$_POST :array();
	 	if(!empty($goodIds)){
	 		$orderList = new OrderList($this->companyId,$this->siteNoId);
	 		if($orderList->order){
	 			$result = OrderList::UpdatePadOrder($this->companyId,$orderList->order['lid'],$goodsIds);
	 			if($result){
	 				echo 1;
	 			}else{
	 				echo 0;
	 			}
	 		}
	 	}
	 	exit;
	}
	//确认订单
	public function actionOrder(){
		$orderId = Yii::app()->request->getParam('orderId');
		$goodsIds = isset($_POST) ?$_POST :array();
		if(!($goodsIds && OrderList::UpdateOrder($this->companyId,$orderId,$goodsIds))){
			$this->redirect(array('/product/orderList'));
		}
		
	 	$this->render('order');
	}
	//获取商品口味
	public function actionGetProductPicJson()
	{
		$id = Yii::app()->request->getParam('id');
		$pic = ProductClass::getProductPic($this->companyId,$id);
		$this->renderPartial('_productImg',array('pics'=>$pic));
	}
	//获取商品口味
	public function actionGetTasteJson()
	{
		$tasteArr = array();
		$type = Yii::app()->request->getParam('type');
		$id = Yii::app()->request->getParam('id');
		if($type==1){ //全单口味
			$allOrderTastes = TasteClass::getAllOrderTaste($this->companyId,$type,$this->companyId);
		}elseif($type==2){ //产品口味
			$productId = Yii::app()->request->getParam('productId');
			$allOrderTastes = TasteClass::getProductTaste($productId,$this->companyId);
		}
		$tasteMemo = TasteClass::getOrderTasteMemo($id,$type,$this->companyId);
		$orderTastes = TasteClass::getOrderTaste($id,$type,$this->companyId);
		$tasteArr['taste'] = $allOrderTastes;
		foreach($allOrderTastes as $key=>$val){
			if(in_array($val['lid'],$orderTastes)){
				$tasteArr['taste'][$key]['has'] = 1;
			}else{
				$tasteArr['taste'][$key]['has'] = 0;
			}
		}
		$tasteArr['taste_memo'] = $tasteMemo;
		Yii::app()->end(json_encode($tasteArr));
	}
	public function actionSetOrderTaste(){
		$type = Yii::app()->request->getPost('type');
		$id = Yii::app()->request->getPost('id');
		$tasteMemo = Yii::app()->request->getPost('tasteMemo');
		$tasteIds = Yii::app()->request->getPost('tasteIds');
		if($type==1){ //全单口味
			$result = TasteClass::save($this->companyId, 1, $id, $tasteIds, $tasteMemo);
		}elseif($type==2){ //产品口味
			$result = TasteClass::save($this->companyId, 0, $id, $tasteIds, $tasteMemo);
		}elseif($type==3){
			$result = FeedBackClass::save($this->companyId, $this->siteNoId, 1, $id, $tasteIds, $tasteMemo);
		}
		if($result){
			echo 1;
		}else{
			echo 0;
		}
		exit;
	}
	//获取商品口味 array('feeback_id'=>feeback_mome,)
	public function actionGetFeebackJson()
	{
		$type = Yii::app()->request->getParam('type');
		$id = Yii::app()->request->getParam('id');
		if($type==3){ //全单口味
			$orderFeeback = FeedBackClass::getOrderFeeBack($id,1);
			$allOrderFeeback = FeedBackClass::getAllFeeBack($this->companyId,1);
			if(!empty($orderFeeback)){
				foreach($allOrderFeeback as $key=>$feeback){
					foreach($orderFeeback as $ofeeback){
						if($feeback['lid']==$ofeeback['feedback_id']){
							$allOrderFeeback[$key]['feedback_memo'] = $ofeeback['feedback_memo'];
						}
					}
				}
			}
		}
		Yii::app()->end(json_encode($allOrderFeeback));
	}
	public function actionAddProductAddition(){
		$orderId = Yii::app()->request->getParam('orderId');
		$lid = Yii::app()->request->getParam('id');
		$data = array('status'=>false,'msg'=>'加菜失败!');
		$productAddition = new ProductAdditionClass($this->companyId,$orderId,$lid);
		if($productAddition->save()){
			$data = array('status'=>true,'msg'=>'加菜成功!','data'=>$productAddition->productAddition,'lastLid'=>sprintf("%010d",$productAddition->lastLid));
			Yii::app()->end(json_encode($data));
		}else{
			Yii::app()->end(json_encode($data));
		}
	}
}