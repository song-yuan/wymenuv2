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
						Yii::app()->session['qrcode-'.$userId] = $scaned['scene_id'];
					}
					if(!isset(Yii::app()->session['qrcode-'.$userId])){
						Yii::app()->session['qrcode-'.$userId] = -1;//通过扫描二维码 添加session
					}
				}else{
					Yii::app()->session['qrcode-'.$userId] = -1;
				}
			}else{
				//pc 浏览
				$userId =-1;
				Yii::app()->session['userId'] = $userId;
				Yii::app()->session['qrcode-'.$userId] = -1;
			}
		}
		return true;
	}
	public function actionIndex()
	{
		$userId = Yii::app()->session['userId'];
		$siteId = Yii::app()->session['qrcode-'.$userId];
		//特价菜
		$promotion = new WxPromotion($this->companyId,$userId);
		var_dump($promotion);exit;
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
		
		$cartObj = new WxCart($this->companyId,$userId,$productArr = array(),$siteId);
		$carts = $cartObj->getCart();
		$this->render('cart',array('companyId'=>$this->companyId,'models'=>$carts));
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
		$orderId = Yii::app()->request->getParam('orderId');
		$order = WxOrder::getOrder($orderId,$this->companyId);
		$orderProducts = WxOrder::getOrderProduct($orderId,$this->companyId);
		$this->render('order',array('companyId'=>$this->companyId,'order'=>$order,'orderProducts'=>$orderProducts));
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
		
		$order = WxOrder::getOrder($orderId,$this->companyId);
		$orderProducts = WxOrder::getOrderProduct($orderId,$this->companyId);
		$this->render('payorder',array('companyId'=>$this->companyId,'userId'=>$userId,'order'=>$order,'orderProducts'=>$orderProducts));
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
	private function weixinServiceAccount() {	
		$this->weixinServiceAccount = WxAccount::get($this->companyId);
	}
	private function brandUser($openId) {	
		$this->brandUser = WxBrandUser::getFromOpenId($openId);
	}
}