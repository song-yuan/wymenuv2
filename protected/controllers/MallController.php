<?php

class MallController extends Controller
{
	/**
	 * 
	 * type点单类型 1 堂吃 2 外卖
	 * 
	 */
	public $companyId;
	public $type = 1;
	public $weixinServiceAccount;
	public $brandUser;
	public $layout = '/layouts/mallmain';
	
	
	public function init() 
	{
		$companyId = Yii::app()->request->getParam('companyId');
		$type = Yii::app()->request->getParam('type',1);
		$this->companyId = $companyId;
		$this->type = $type;
	}
	
	public function beforeAction($actin){
		if(in_array($actin->id,array('index','cart','order','payOrder'))){
			//如果微信浏览器
			if(Helper::isMicroMessenger()){
				$this->weixinServiceAccount();
				$baseInfo = new WxUserBase($this->weixinServiceAccount['appid'],$this->weixinServiceAccount['appsecret']);
				$userInfo = $baseInfo->getSnsapiBase();
				$openid = $userInfo['openid'];
				$this->brandUser($openid);
				if(!$this->brandUser){
					$newBrandUser = new NewBrandUser($openid, $this->companyId);
		    		$this->brandUser = $newBrandUser->brandUser;
				}
				$userId = $this->brandUser['lid'];
				Yii::app()->session['userId'] = $userId;
				if($this->type==1){
					//堂吃
					$scaned = WxScanLog::get($this->companyId,$userId);
					if(!empty($scaned)){
						$scene = WxScanLog::getScene($this->companyId,$scaned['scene_id']);
						Yii::app()->session['qrcode-'.$userId] = $scene['id'];
					}
					if(!isset(Yii::app()->session['qrcode-'.$userId])){
						Yii::app()->session['qrcode-'.$userId] = -1;//通过扫描二维码 添加session
					}
				}else{
					Yii::app()->session['qrcode-'.$userId] = -1;
				}
			}else{
				//pc 浏览
				$userId = 2;
				Yii::app()->session['userId'] = $userId;
				Yii::app()->session['qrcode-'.$userId] = 24;
			}
		}
		return true;
	}
	public function actionIndex()
	{
		$userId = Yii::app()->session['userId'];
		$siteId = Yii::app()->session['qrcode-'.$userId];
		//特价菜
		$promotion = new WxPromotion($this->companyId,$userId,$siteId);
		//普通优惠
		$product = new WxProduct($this->companyId,$userId,$siteId);
		$categorys = $product->categorys;
		$products = $product->categoryProductLists;
		$this->render('index',array('companyId'=>$this->companyId,'categorys'=>$categorys,'models'=>$products,'promotions'=>$promotion->promotionProductList));
	}
	/**
	 * 
	 * 购物车
	 * 
	 */
	public function actionCart()
	{
		$userId = Yii::app()->session['userId'];
		$siteId = Yii::app()->session['qrcode-'.$userId];
		
		$site = WxSite::get($siteId,$this->companyId);
		$cartObj = new WxCart($this->companyId,$userId,$productArr = array(),$siteId);
		$carts = $cartObj->getCart();
		if(empty($carts)){
			$this->redirect(array('/mall/index','companyId'=>$this->companyId));
		}
		$this->render('cart',array('companyId'=>$this->companyId,'models'=>$carts,'site'=>$site));
	}
	/**
	 * 
	 * 生成订单
	 * 
	 */
	public function actionGeneralOrder()
	{
		$userId = Yii::app()->session['userId'];
		$siteId = Yii::app()->session['qrcode-'.$userId];
		
		$serial = Yii::app()->request->getParam('serial');
		$site = WxSite::getBySerial($serial,$this->companyId);
		if(!$site){
			$this->redirect(array('/mall/cart','companyId'=>$this->companyId));
		}else{
			WxCart::updateSiteId($userId,$this->companyId,$site['lid']);
		}
		
		$orderObj = new WxOrder($this->companyId,$userId,$siteId,$this->type);
		$orderId = $orderObj->createOrder();
		$this->redirect(array('/mall/order','companyId'=>$this->companyId,'orderId'=>$orderId));
	}
	/**
	 * 
	 * 
	 * 订单
	 * 
	 */
	 public function actionOrder()
	 {
	 	$userId = Yii::app()->session['userId'];
	 	$siteId = Yii::app()->session['qrcode-'.$userId];
		$orderId = Yii::app()->request->getParam('orderId');
		
		$site = WxSite::get($siteId,$this->companyId);
		$order = WxOrder::getOrder($orderId,$this->companyId);
		$orderProducts = WxOrder::getOrderProduct($orderId,$this->companyId);
		$this->render('order',array('companyId'=>$this->companyId,'order'=>$order,'orderProducts'=>$orderProducts,'site'=>$site));
	 }
	 /**
	 * 
	 * 
	 * 支付订单
	 * 
	 */
	 public function actionPayOrder()
	 {
	 	$userId = Yii::app()->session['userId'];
		$orderId = Yii::app()->request->getParam('orderId');
		$paytype = Yii::app()->request->getParam('paytype');
		
		if($paytype == 1){
			WxOrder::updatePayType($orderId,$this->companyId,0);
			$this->redirect(array('/user/orderInfo','companyId'=>$this->companyId,'orderId'=>$orderId));
		}
		WxOrder::updatePayType($orderId,$this->companyId);
		
		$order = WxOrder::getOrder($orderId,$this->companyId);
		$orderProducts = WxOrder::getOrderProduct($orderId,$this->companyId);
		$this->render('payorder',array('companyId'=>$this->companyId,'userId'=>$userId,'order'=>$order,'orderProducts'=>$orderProducts,'user'=>$this->brandUser));
	 }
	 /**
	 * 
	 * 
	 * 余额支付订单
	 * 
	 */
	 public function actionPayOrderByYue()
	 {
		$orderId = Yii::app()->request->getParam('orderId');
		$order = WxOrder::getOrder($orderId,$this->companyId);
		if($order['order_status'] < 3){
			WxOrder::insertOrderPay($order,10);
			WxOrder::updateOrderStatus($order['lid'],$order['dpid']);
		}
		
		$this->redirect(array('/user/orderInfo','companyId'=>$this->companyId,'orderId'=>$orderId));
	 }
	/**
	 * 
	 * 添加购物车
	 * 
	 */
	public function actionAddCart()
	{
		$userId = Yii::app()->session['userId'];
		$siteId = Yii::app()->session['qrcode-'.$userId];
		
		if($userId < 0){
			Yii::app()->end(json_encode(array('status'=>false,'msg'=>'请关注微信公众号我要点单进行点餐')));
		}
		
		if($this->type==1){
			if($siteId < 0){
				Yii::app()->end(json_encode(array('status'=>false,'msg'=>'请先扫描餐桌二维码,然后再进行点单')));
			}
		}
		
		$productId = Yii::app()->request->getParam('productId');
		$promoteId = Yii::app()->request->getParam('promoteId');
		
		$productArr = array('product_id'=>$productId,'num'=>1,'privation_promotion_id'=>$promoteId);
		$cart = new WxCart($this->companyId,$userId,$productArr,$siteId);
		
		//检查活动商品数量
		if($promoteId > 0){
			$chek = $cart->checkPromotion();
			if(!$chek['status']){
				Yii::app()->end(json_encode($chek));
			}	
		}
		
		if($cart->addCart()){
			Yii::app()->end(json_encode(array('status'=>true,'msg'=>'ok')));
		}else{
			Yii::app()->end(json_encode(array('status'=>false,'msg'=>'点单失败,请重新点单')));
		}
	}
	/**
	 * 
	 * 删除购物车
	 * 
	 */
	public function actionDeleteCart()
	{
		$userId = Yii::app()->session['userId'];
		$siteId = Yii::app()->session['qrcode-'.$userId];
		
		if($userId < 0){
			Yii::app()->end(json_encode(array('status'=>false,'msg'=>'请关注微信公众号我要点单进行点餐')));
		}
		
		if($this->type==1){
			if($siteId < 0){
				Yii::app()->end(json_encode(array('status'=>false,'msg'=>'请先扫描餐桌二维码,然后再进行点单')));
			}
		}
		
		$productId = Yii::app()->request->getParam('productId');
		$promoteId = Yii::app()->request->getParam('promoteId');
		$productArr = array('product_id'=>$productId,'num'=>1,'privation_promotion_id'=>$promoteId);
		
		$cart = new WxCart($this->companyId,$userId,$productArr,$siteId);
		if($cart->deleteCart()){
			Yii::app()->end(json_encode(array('status'=>true,'msg'=>'ok')));
		}else{
			Yii::app()->end(json_encode(array('status'=>false,'msg'=>'请重新操作')));
		}
	}
	/**
	 * 
	 * 获取订单状态
	 * 
	 */
	public function actionGetOrderStatus()
	{
		$orderId = Yii::app()->request->getParam('orderId');
		$order = WxOrder::getOrder($orderId,$this->companyId);
		Yii::app()->end($order['order_status']);
	}
	private function weixinServiceAccount() {	
		$this->weixinServiceAccount = WxAccount::get($this->companyId);
	}
	private function brandUser($openId) {	
		$this->brandUser = WxBrandUser::getFromOpenId($openId);
	}
}