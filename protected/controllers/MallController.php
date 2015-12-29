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
		if(in_array($actin->id,array('index','cart','order','payOrder','cupon','cuponinfo','reCharge','share'))){
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
					}else{
						Yii::app()->session['qrcode-'.$userId] = -1;//通过扫描二维码 添加session
					}
				}else{
					Yii::app()->session['qrcode-'.$userId] = -1;
				}
			}else{
//				pc 浏览
				$userId = -1;
//				Yii::app()->session['userId'] = $userId;
//				Yii::app()->session['qrcode-'.$userId] = -1;
//				pc 测试
				$userId = 2;
				Yii::app()->session['userId'] = $userId;
				Yii::app()->session['qrcode-'.$userId] = 40;
			}
		}
		return true;
	}
	public function actionIndex()
	{
		$userId = Yii::app()->session['userId'];
		//特价菜
		$promotion = new WxPromotion($this->companyId,$userId);
		//普通优惠
		$product = new WxProduct($this->companyId,$userId);
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
		$siteType = false;
		$siteNum = false;
		
		$site = WxSite::get($siteId,$this->companyId);
		if($site){
			$siteType = WxSite::getSiteType($site['type_id'],$this->companyId);
			$siteNum = WxSite::getSiteNumber($site['splid'],$this->companyId);
		}
		
		$cartObj = new WxCart($this->companyId,$userId,$productArr = array(),$siteId);
		$carts = $cartObj->getCart();
		$orderTastes = WxTaste::getOrderTastes($this->companyId);
//		var_dump($carts);exit;
		if(empty($carts)){
			$this->redirect(array('/mall/index','companyId'=>$this->companyId,'type'=>$this->type));
		}
		$this->render('cart',array('companyId'=>$this->companyId,'models'=>$carts,'orderTastes'=>$orderTastes,'site'=>$site,'siteType'=>$siteType,'siteNum'=>$siteNum));
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
		$msg = '';
		$number = 1;
		if($this->type==1){
			$serial = Yii::app()->request->getPost('serial');
			$number = Yii::app()->request->getPost('number');
			$serialArr = explode('>',$serial);
			if(count($serialArr)==1){
				$serial = $serialArr[0];
			}else{
				$serial = $serialArr[1];
			}
			$site = WxSite::getBySerial($serial,$this->companyId);
			if(!$site){
				$this->redirect(array('/mall/cart','companyId'=>$this->companyId,'type'=>$this->type));
			}else{
				WxCart::updateSiteId($userId,$this->companyId,$site['lid']);
				$siteId = $site['lid'];
			}
		}
		$tastes = Yii::app()->request->getPost('taste',array());
		
		$orderObj = new WxOrder($this->companyId,$userId,$siteId,$this->type,$number,$tastes);
		if(!$orderObj->cart){
			$msg = '下单失败,请重新下单';
			$this->redirect(array('/mall/cart','companyId'=>$this->companyId,'type'=>$this->type));
		}
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
		$orderId = Yii::app()->request->getParam('orderId');
		$siteType = false;
		$address = false;
		
		$order = WxOrder::getOrder($orderId,$this->companyId);
		$site = WxSite::get($order['site_id'],$this->companyId);
		
		if($site){
			$siteType = WxSite::getSiteType($site['type_id'],$this->companyId);
		}
		if($order['order_type']==2){
			$this->type = 2;
			$address = WxAddress::getDefault($userId,$this->companyId);
		}
		$cupons = WxCupon::getUserAvaliableCupon($order['should_total'],$userId,$this->companyId);
		$orderProducts = WxOrder::getOrderProduct($orderId,$this->companyId);
		$this->render('order',array('companyId'=>$this->companyId,'order'=>$order,'orderProducts'=>$orderProducts,'site'=>$site,'cupons'=>$cupons,'siteType'=>$siteType,'address'=>$address));
	 }
	 /**
	  * 
	  * 处理 现金券
	  * 
	  */
	  public function actionOrderCupon(){
		  	$userId = Yii::app()->session['userId'];
			$orderId = Yii::app()->request->getParam('orderId');
			$paytype = Yii::app()->request->getPost('paytype');
			$addressId = Yii::app()->request->getPost('address',-1);
			$cuponId = Yii::app()->request->getPost('cupon');
			$remark = Yii::app()->request->getPost('remark');
			
			$order = WxOrder::getOrder($orderId,$this->companyId);
			
			if($order['cupon_branduser_lid'] > 0){
				$this->redirect(array('/mall/payOrder','companyId'=>$this->companyId,'orderId'=>$orderId));
			}
			if($order['order_type']==2){
				if($addressId > 0){
					$address = WxAddress::getAddress($addressId,$this->companyId);
					$result = WxOrderAddress::addOrderAddress($orderId,$address);
					if(!$result){
						$this->redirect(array('/mall/order','companyId'=>$this->companyId,'orderId'=>$orderId));
					}
				}else{
					$this->redirect(array('/mall/order','companyId'=>$this->companyId,'orderId'=>$orderId));
				}
			}
			if($cuponId){
				$result = WxOrder::updateOrderCupon($orderId,$this->companyId,$cuponId);
				if(!$result){
					$this->redirect(array('/mall/order','companyId'=>$this->companyId,'orderId'=>$orderId));
				}
			}
			
			if($remark){
				WxOrder::updateRemark($orderId,$this->companyId,$remark);
			}
			
			if($paytype == 1){
				WxOrder::updatePayType($orderId,$this->companyId,0);
				$this->redirect(array('/user/orderInfo','companyId'=>$this->companyId,'orderId'=>$orderId));
			}
			WxOrder::updatePayType($orderId,$this->companyId);
			
			$this->redirect(array('/mall/payOrder','companyId'=>$this->companyId,'orderId'=>$orderId));
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
		$address = false;
		
		$order = WxOrder::getOrder($orderId,$this->companyId);
		if($order['order_status'] > 2){
			$this->redirect(array('/user/orderInfo','companyId'=>$this->companyId,'orderId'=>$orderId));
		}
		$orderProducts = WxOrder::getOrderProduct($orderId,$this->companyId);
		if($order['order_type']==2){
			$address =  WxOrder::getOrderAddress($orderId,$this->companyId);
		}
		$this->render('payorder',array('companyId'=>$this->companyId,'userId'=>$userId,'order'=>$order,'address'=>$address,'orderProducts'=>$orderProducts,'user'=>$this->brandUser));
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
		$msg = '';
		
		$order = WxOrder::getOrder($orderId,$this->companyId);
		if($order['order_status'] < 3){
			$transaction=Yii::app()->db->beginTransaction();
			try{
				WxOrder::insertOrderPay($order,10);
				//修改订单状态
				WxOrder::updateOrderStatus($order['lid'],$order['dpid']);
				//修改订单产品状态
				WxOrder::updateOrderProductStatus($order['lid'],$order['dpid']);
				//修改座位状态
				if($order['order_type']==1){
					WxSite::updateSiteStatus($order['site_id'],$order['dpid'],3);
				}
				$transaction->commit();
			}catch (Exception $e) {
				$transaction->rollback();
				$msg = $e->getMessage();
			}
		}
		$this->redirect(array('/user/orderInfo','companyId'=>$this->companyId,'orderId'=>$orderId));
	 }
	 /**
	 * 
	 * 营销活动的明细列表
	 * 不只是现金券
	 * 
	 */
	 public function actionCupon()
	{
		$userId = Yii::app()->session['userId'];
		$activeId = Yii::app()->request->getParam('activeId');//promotion_activity的lid
		$active = WxPromotionActivity::getActivity($this->companyId,$activeId);
		if($active){
			$activeDetails = WxPromotionActivity::getDetail($this->companyId,$activeId);
		}else{
			$activeDetails = array();
		}
		$this->render('cupon',array('companyId'=>$this->companyId,'cupons'=>$activeDetails,'userId'=>$userId));
	}
	/**
	 * 
	 * 营销活动
	 * 领取页面
	 * 
	 */
	 public function actionCuponInfo()
	{
		$userId = Yii::app()->session['userId'];
		$activeDetailId = Yii::app()->request->getParam('detailid');//promotion_activity的lid
		$deatil = WxPromotionActivity::getDetailItem($this->companyId,$activeDetailId);
		$lid = WxPromotionActivity::sent($this->companyId,$userId,$deatil['promotion_type'],$deatil['promotion_lid'],$deatil['activity_lid']);
		$this->render('cuponinfo',array('companyId'=>$this->companyId,'ptype'=>$deatil['promotion_type'],'lid'=>$lid));
	}
	/**
	 * 
	 * 领取分享现金券红包
	 * 
	 */
	public function actionShare(){
	 	$userId = Yii::app()->session['userId'];
		$redPacketId = Yii::app()->request->getParam('redptId');//红包id
		$redPacketDetails = array();
		$redPacket = WxRedPacket::getRedPacket($this->companyId,$redPacketId);
		if($redPacket){
			$redPacketDetails = WxRedPacket::getRedPacketDetail($this->companyId,$redPacketId);
			WxRedPacket::sent($userId,$this->companyId,$redPacket,$redPacketDetails);
		}
		$this->render('redpacket',array('companyId'=>$this->companyId,'redPacket'=>$redPacket,'redPacketDetails'=>$redPacketDetails));
	}
	/**
	 * 
	 * 卡券领取页面
	 * 
	 */
	 public function actionGetWxCard()
	{
		$this->render('getwxcard',array('companyId'=>$this->companyId));
	}
	/**
	 * 
	 * 
	 * 充值
	 * 
	 */
	 public function actionReCharge(){
	 	$userId = Yii::app()->session['userId'];
	 	$recharges = WxRecharge::getWxRecharge($this->companyId);
	 	$this->render('recharge',array('companyId'=>$this->companyId,'recharges'=>$recharges,'userId'=>$userId));
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
		
//		if($this->type==1){
//			if($siteId < 0){
//				Yii::app()->end(json_encode(array('status'=>false,'msg'=>'请先扫描餐桌二维码,然后再进行点单')));
//			}
//		}
		
		$productId = Yii::app()->request->getParam('productId');
		$promoteId = Yii::app()->request->getParam('promoteId');
		$toGroup = Yii::app()->request->getParam('toGroup');
		
		$productArr = array('product_id'=>$productId,'num'=>1,'privation_promotion_id'=>$promoteId,'to_group'=>$toGroup);
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
		
//		if($this->type==1){
//			if($siteId < 0){
//				Yii::app()->end(json_encode(array('status'=>false,'msg'=>'请先扫描餐桌二维码,然后再进行点单')));
//			}
//		}
		$all = Yii::app()->request->getParam('all',0);
		if($all){
			$result = WxCart::clearCart($userId,$this->companyId);
			if($result){
				Yii::app()->end(json_encode(array('status'=>true,'msg'=>'清空成功!')));
			}else{
				Yii::app()->end(json_encode(array('status'=>false,'msg'=>'清空失败,请重新操作!')));
			}
		}
		
		$productId = Yii::app()->request->getParam('productId');
		$promoteId = Yii::app()->request->getParam('promoteId');
		$toGroup = Yii::app()->request->getParam('toGroup');
		
		$productArr = array('product_id'=>$productId,'num'=>1,'privation_promotion_id'=>$promoteId,'to_group'=>$toGroup);
		
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
		$status = 0;
		$orderId = Yii::app()->request->getParam('orderId');
		$order = WxOrder::getOrder($orderId,$this->companyId);
		$orderProduct = WxOrder::getNoPayOrderProduct($orderId,$this->companyId);
		$status = $order['order_status'];
		if(!empty($orderProduct)){
			$status = 0;
		}
		echo $status;exit;
	}
	/**
	 * 
	 * 领券
	 * 
	 */
	public function actionGetCupon()
	{
		$lid = Yii::app()->request->getParam('lid');
		$type = Yii::app()->request->getParam('type');
		
		$result = WxPromotionActivity::getPromotionActivity($this->companyId,$lid,$type);
		if($result){
			$msg = '领取成功';
		}else{
			$msg = '已领取';
		}
		Yii::app()->end($msg);
	}
	private function weixinServiceAccount() {	
		$this->weixinServiceAccount = WxAccount::get($this->companyId);
	}
	private function brandUser($openId) {	
		$this->brandUser = WxBrandUser::getFromOpenId($openId);
	}
}