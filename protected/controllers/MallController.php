<?php

class MallController extends Controller
{
	/**
	 * 
	 * type点单类型 无餐桌 6 微信临时座 
	 * 带餐桌 1微信 堂吃 （需要扫餐桌二维码） 3 预约
	 * 2 微信外卖
	 * $companyId 子店铺的id
	 */
	public $companyId;
	public $type = 0;
	public $brandUser;
	public $company;
	public $layout = '/layouts/mallmain';
	
	
	public function init() 
	{
		$companyId = Yii::app()->request->getParam('companyId');
		$type = Yii::app()->request->getParam('type',6);
		$this->companyId = $companyId;
		$this->type = $type;
		$this->company = WxCompany::get($this->companyId);
	}
	
	public function beforeAction($actin){
		if($this->company['type']=='0'&&!in_array($actin->id,array('reCharge'))){
			$this->redirect(array('/shop/index','companyId'=>$this->companyId,'type'=>$this->type));
			exit;
		}
		$dpidSelf = Yii::app()->session['dpid_self_'.$this->companyId];
		if($dpidSelf !== null){
			if($dpidSelf == 1){
				$comdpid = $this->company['dpid'];
			}else{
				$comdpid = $this->company['comp_dpid'];
			}
			$userId = Yii::app()->session['userId-'.$comdpid];
		}else{
			$userId = null;
		}
		//如果微信浏览器
		if(Helper::isMicroMessenger()){
			if(empty($userId)){
				$url = Yii::app()->request->url;
				$this->redirect(array('/weixin/redirect','companyId'=>$this->companyId,'url'=>urlencode($url)));
				exit;
			}
			$this->brandUser = WxBrandUser::get($userId, $this->companyId);
			if(empty($this->brandUser)){
				$url = Yii::app()->request->url;
				$this->redirect(array('/weixin/redirect','companyId'=>$this->companyId,'url'=>urlencode($url)));
				exit;
			}
			if($this->type==1){
				//堂吃
				$scaned = WxScanLog::get($comdpid,$userId);
				if(!empty($scaned)){
					$scene = WxScanLog::getScene($comdpid,$scaned['scene_id']);
					Yii::app()->session['qrcode-'.$userId] = $scene['scene_lid'];
				}else{
					Yii::app()->session['qrcode-'.$userId] = -1;//通过扫描二维码 添加session
				}
			}else{
				Yii::app()->session['qrcode-'.$userId] = -1;
			}
		}else{
			//pc 浏览
			$userId = 2204;
			$this->brandUser = WxBrandUser::get($userId, $this->companyId);
			$userId = $this->brandUser['lid'];
			$userDpid = $this->brandUser['dpid'];
			Yii::app()->session['userId-'.$userDpid] = $userId;
			Yii::app()->session['qrcode-'.$userId] = 504;
		}
		return true;
	}
	public function actionIndex()
	{
		$key = 'productList-'.$this->companyId.'-'.$this->type;
		$expire = 10*60; // 过期时间
		$user = $this->brandUser;
        $userId = $user['lid'];
        $cartList = array();
        $siteId = Yii::app()->session['qrcode-'.$userId];
        
        $promotion = new WxPromotion($this->companyId,$userId,$this->type);
        $promotions = $promotion->promotionProductList;
        $buySentPromotions = $promotion->buySentProductList;
        $fullSents = $promotion->fullSentList;
        $cache = Yii::app()->redis->get($key);
        if($cache!=false){
        	$products = json_decode($cache,true);
        }else{
        	$product = new WxProduct($this->companyId,$userId,$this->type);
        	$products = $product->categoryProductLists;
        	Yii::app()->redis->set($key,json_encode($products),$expire);
        }
        $cartObj = new WxCart($this->companyId,$userId,$productArr = array(),$siteId,$this->type);
        $carts = $cartObj->getCart();
        $disables = $carts['disable'];
        $avalibles = $carts['available'];
        foreach ($avalibles as $cart){
        	$productId = (int)$cart['product_id'];
        	$isSte = $cart['is_set'];
        	$promotionType = $cart['promotion_type'];
        	$promotionId = (int)$cart['promotion_id'];
        	$toGroup = $cart['to_group'];
        	$canCupon = $cart['can_cupon'];
        	$cartKey = $promotionType.'-'.$productId.'-'.$isSte.'-'.$promotionId.'-'.$toGroup.'-'.$canCupon;
        	$cartList[$cartKey] = $cart;
        }	
		$start = WxCompanyFee::get(4,$this->companyId);
		$notices = WxNotice::getNotice($this->company['comp_dpid'], 2, 1);
		$this->render('index',array('companyId'=>$this->companyId,'userId'=>$userId,'promotions'=>$promotions,'buySentPromotions'=>$buySentPromotions,'fullsents'=>$fullSents,'products'=>$products,'cartList'=>$cartList,'disables'=>$disables,'start'=>$start,'notices'=>$notices));
	}
	/**
	 * 
	 * 购物车
	 * 
	 */
	public function actionCart()
	{
		$user = $this->brandUser;
        $userId = $user['lid'];
		$siteId = Yii::app()->session['qrcode-'.$userId];
		$siteType = false;
		$siteNum = false;
		
		$site = WxSite::get($siteId,$this->companyId);
		if($site){
			$siteType = WxSite::getSiteType($site['type_id'],$this->companyId);
			$siteNum = WxSite::getSiteNumber($site['splid'],$this->companyId);
		}
		
		$cartObj = new WxCart($this->companyId,$userId,$productArr = array(),$siteId,$this->type);
		$carts = $cartObj->getCart();
		$orderTastes = WxTaste::getOrderTastes($this->companyId);
		if(empty($carts)){
			$this->redirect(array('/mall/index','companyId'=>$this->companyId,'type'=>$this->type));
		}
		$this->render('cart',array('companyId'=>$this->companyId,'models'=>$carts,'orderTastes'=>$orderTastes,'site'=>$site,'siteType'=>$siteType,'siteNum'=>$siteNum));
	}
	/**
	 * 
	 * 
	 * 订单确认
	 * 
	 */
	public function actionCheckOrder()
	{
		$user = $this->brandUser;
        $userId = $user['lid'];
		$siteId = Yii::app()->session['qrcode-'.$userId];
		$msg = Yii::app()->request->getParam('msg',null);
		$siteType = false;
		$siteNum = false;
		$siteOpen = false;
		$isMustYue = false; // 是否必须储值来支付
		
		$site = WxSite::get($siteId,$this->companyId);
		if($this->type==1){
			if($site){
				$siteNo = WxSite::getSiteNo($siteId,$this->companyId);
				$siteType = WxSite::getSiteType($site['type_id'],$this->companyId);
				$siteNum = WxSite::getSiteNumber($site['splid'],$this->companyId);
				if(in_array($siteNo['status'],array(1,2,3))){
					$siteOpen = true;
					$siteNum = $siteNo['number'];
				}
			}
		}
		
		$cartObj = new WxCart($this->companyId,$userId,$productArr = array(),$siteId,$this->type);
		$carts = $cartObj->getCart();
		if(empty($carts['disable'])&&empty($carts['available'])){
			$this->redirect(array('/mall/index','companyId'=>$this->companyId,'type'=>$this->type));
		}
		$isMustYue = $cartObj->pormotionYue;
		
		$levelDiscount = WxBrandUser::getUserDiscount($user,$this->type);
		
		$disables = $carts['disable'];
		$availables = $carts['available'];
		$original = WxCart::getCartOrigianPrice($availables); // 购物车原价
		$price = WxCart::getCartPrice($availables,$levelDiscount);// 购物车价格 会员折扣后价格
		$canuseCuponPrice = WxCart::getCartUnDiscountPrice($availables,$levelDiscount);// 购物车可使用优惠券的价格
		$orderTastes = WxTaste::getOrderTastes($this->companyId);//全单口味
		$memdisprice = $original - $price;
		$productCodeArr = WxCart::getCartCanCuponProductCode($availables);
		$remainMoney = WxBrandUser::getYue($userId,$user['dpid']);
		
		// 如果没普通优惠活动  可满减满送
		$fullsent = array();
		if(!$cartObj->haspormotion){
			$fullsent = WxFullSent::canUseFullsent($this->companyId, $price, $this->type);
			if(!empty($fullsent)){
				if($fullsent['full_type']){
					$minusprice = $price - $fullsent['extra_cost'];
					$canuseCuponPrice = $canuseCuponPrice - $fullsent['extra_cost'];
					if($minusprice > 0){
						$price = $minusprice;
					}else{
						$price = 0;
					}
				}
			}
		}
		$cupons = WxCupon::getUserAvaliableCupon($productCodeArr,$canuseCuponPrice,$userId,$this->companyId,$this->type);
		if($this->type!=6){
			$isSeatingFee = WxCompanyFee::get(1,$this->companyId);
			$isPackingFee = WxCompanyFee::get(2,$this->companyId);
			$isFreightFee = WxCompanyFee::get(3,$this->companyId);
			
			$address = WxAddress::getDefault($userId,$user['dpid']);
		}else{
			$isSeatingFee = 0;
			$isPackingFee = 0;
			$isFreightFee = 0;
			$address = array();
		}
		$this->render('checkorder',array('company'=>$this->company,'models'=>$availables,'disables'=>$disables,'orderTastes'=>$orderTastes,'site'=>$site,'siteType'=>$siteType,'siteNum'=>$siteNum,'siteOpen'=>$siteOpen,'price'=>$price,'original'=>$original,'memdisprice'=>$memdisprice,'remainMoney'=>$remainMoney,'cupons'=>$cupons,'user'=>$user,'levelDiscount'=>$levelDiscount,'address'=>$address,'isSeatingFee'=>$isSeatingFee,'isPackingFee'=>$isPackingFee,'isFreightFee'=>$isFreightFee,'isMustYue'=>$isMustYue,'fullsent'=>$fullsent,'msg'=>$msg));
	}
	/**
	 * 
	 * 生成订单
	 * 
	 */
	public function actionGeneralOrder()
	{
		$user = $this->brandUser;
        $userId = $user['lid'];
		$siteId = Yii::app()->session['qrcode-'.$userId];
		$paytype = Yii::app()->request->getPost('paytype');
		$cuponId = Yii::app()->request->getPost('cupon',0);
		$takeoutTypeId = Yii::app()->request->getPost('takeout_typeid',0);
		$fullsentId = Yii::app()->request->getPost('fullsent','0-0-0');
		$yue = Yii::app()->request->getPost('yue',0);
		$addressId = Yii::app()->request->getPost('address',-1);
		$orderTime = Yii::app()->request->getPost('order_time',null);
		$remark = Yii::app()->request->getPost('taste_memo',null);
		
		$contion = null;
		$number = 1;
		$setDetails = Yii::app()->request->getPost('set-detail',array());
		$tastes = Yii::app()->request->getPost('taste',array());
		$others = array('takeout'=>$takeoutTypeId,'fullsent'=>$fullsentId);
		try{
			$orderObj = new WxOrder($this->companyId,$user,$siteId,$this->type,$number,$setDetails,$tastes,$others);
			if(empty($orderObj->cart)){
				$this->redirect(array('/mall/index','companyId'=>$this->companyId,'type'=>$this->type));
			}
			//订单地址
			if(in_array($this->type,array(2,3,7,8))){
				if($addressId > 0){
					$address = WxAddress::getAddress($addressId,$user['dpid']);
					if(!$address){
						throw new Exception('订单地址信息,有误请重新添加！');
					}
				}else{
					throw new Exception('请添加订单地址信息！');
				}
			}
		}catch (Exception $e) {
			$msg = $e->getMessage();
			$this->redirect(array('/mall/checkOrder','companyId'=>$this->companyId,'type'=>$this->type,'msg'=>$msg));
		}
		
		$transaction = Yii::app()->db->beginTransaction();
		try{
			//生成订单
			$orderId = $orderObj->createOrder();
		
			//使用现金券
			if($cuponId){
			   WxOrder::updateOrderCupon($orderId,$this->companyId,$cuponId,$user);
			}
			//预订时间
			if($this->type==6||$this->type==2){
				$orderTime = date('Y-m-d H:i:s',strtotime('+'. $orderTime*60 .' seconds'));
			}
			if($orderTime){
				$contion = $contion.' appointment_time="'.$orderTime.'",';
			}
			//备注
			if($remark){
				$remark = Helper::dealString($remark);
				$contion = $contion.' remark="'.$remark.'",';
			}
			
			if($contion){
				WxOrder::update($orderId,$this->companyId,$contion);
			}
		
			//使用余额
			if($yue){
				$order = WxOrder::getOrder($orderId,$this->companyId);
				if($order['order_status'] < 3){
					$remainMoney = WxBrandUser::getYue($userId,$user['dpid']);
					if($remainMoney > 0){
						WxOrder::insertOrderPay($order,10,'');
					}
				}
			}
			if($paytype == 1){
				WxOrder::updatePayType($orderId,$this->companyId,2);
			}else{
				WxOrder::updatePayType($orderId,$this->companyId,1);
			}
		   $transaction->commit();
		}catch (Exception $e) {
			$transaction->rollback();
			$msg = $e->getMessage();
			$this->redirect(array('/mall/checkOrder','companyId'=>$this->companyId,'type'=>$this->type,'msg'=>$msg));
		}
		if($this->type==1){
			// 餐桌模式 数据放入缓存中
			$orderArr = array();
			$order = WxOrder::getOrder($orderId, $this->companyId);
			$orderProduct = WxOrder::getOrderProductData($orderId, $this->companyId);
			$orderDiscount = WxOrder::getOrderAccountDiscount($orderId, $this->companyId);
			$orderArr['nb_site_no'] = $orderObj->siteNo;
			$orderArr['nb_order_platform'] = array();
			$orderArr['nb_order'] = $order;
			$orderArr['nb_order_product'] = $orderProduct;
			$orderArr['nb_order_pay'] = array();
			$orderArr['nb_order_address'] = array();
			$orderArr['nb_order_taste'] = $order['taste'];
			$orderArr['nb_order_account_discount'] = array();
			$orderStr = json_encode($orderArr);
			WxRedis::pushPlatform($this->dpid, $orderStr);
			$this->redirect(array('/mall/siteOrder','companyId'=>$this->companyId,'type'=>$this->type));
		}
		if($paytype == 1){
			//支付宝支付
			$order = WxOrder::getOrder($orderId,$this->companyId);
			if($order['order_status'] > 2){
				$this->redirect(array('/user/orderInfo','companyId'=>$this->companyId,'orderId'=>$orderId,'orderDpid'=>$this->companyId));
			}else{
				$orderPays = WxOrderPay::get($this->companyId,$orderId);
				$this->redirect(array('/alipay/mobileWeb','companyId'=>$this->companyId,'order'=>$order,'orderPays'=>$orderPays));
			}
		}else{
			$this->redirect(array('/mall/payOrder','companyId'=>$this->companyId,'orderId'=>$orderId));
		}
	}
	 /**
	 * 支付订单
	 */
	 public function actionPayOrder()
	 {
	 	$user = $this->brandUser;
        $userId = $user['lid'];
		$orderId = Yii::app()->request->getParam('orderId');
		$address = false;
		$seatingFee = 0;
		$packingFee = 0;
		$freightFee = 0;
		
		$order = WxOrder::getOrder($orderId,$this->companyId);
		if($order['order_status'] > 2){
			$this->redirect(array('/user/orderInfo','companyId'=>$this->companyId,'orderId'=>$orderId,'orderDpid'=>$this->companyId));
		}
		$orderProducts = WxOrder::getOrderProduct($orderId,$this->companyId);
		if(in_array($order['order_type'],array(2,3))){
			$address =  WxOrder::getOrderAddress($orderId,$this->companyId);
		}
		if($order['order_type']==1){
			$seatingProducts = WxOrder::getOrderProductByType($orderId,$this->companyId,1);
			foreach($seatingProducts as $seatingProduct){
				$seatingFee += $seatingProduct['price']*$seatingProduct['amount'];
			}
		}elseif($order['order_type']==3){
			$packingProducts = WxOrder::getOrderProductByType($orderId,$this->companyId,2);
			foreach($packingProducts as $packingProduct){
				$packingFee += $packingProduct['price']*$packingProduct['amount'];
			}
		}else{
			$packingProducts = WxOrder::getOrderProductByType($orderId,$this->companyId,2);
			foreach($packingProducts as $packingProduct){
				$packingFee += $packingProduct['price']*$packingProduct['amount'];
			}
			$freightProducts = WxOrder::getOrderProductByType($orderId,$this->companyId,3);
			foreach($freightProducts as $freightProduct){
				$freightFee += $freightProduct['price']*$freightProduct['amount'];
			}
		}

		$orderPays = WxOrderPay::get($this->companyId,$orderId);
	    $this->render('payorder',array('companyId'=>$this->companyId,'company'=>$this->company,'userId'=>$userId,'order'=>$order,'address'=>$address,'orderProducts'=>$orderProducts,'user'=>$user,'orderPays'=>$orderPays,'seatingFee'=>$seatingFee,'packingFee'=>$packingFee,'freightFee'=>$freightFee));
	 }
	 /**
	  * 收钱吧支付
	  */
	 public function actionSqbPayOrder()
	 {
	 	$data = $_GET;
	 	unset($data['companyId']);
	 	SqbPay::preOrder($data);
	 	exit;
	 }
	 /**
	  * 美团支付
	  */
	 public function actionMtPayOrder()
	 {
	 	$data = $_GET;
	 	$dpid = $data['companyId'];
	 	$mtr = MtpConfig::MTPAppKeyMid($dpid);
	 	if($mtr){
	 		$mts = explode(',',$mtr);
	 		$merchantId = $mts[0];
	 		$appId = $mts[1];
	 		$key = $mts[2];
	 	}
	 	$ods = array(
	 			'merchantid'=>$merchantId,
	 			'appid'=>$appId,
	 	);
	 	$baseUrl = urlencode('http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
	 	if(!isset($_GET['openId'])){
	 		MtpPay::getOpenId($ods,$baseUrl);
	 	}
	 	$data['merchantId'] = $merchantId;
	 	$data['appId'] = $appId;
	 	$data['key'] = $key;
	 	$data['return_url'] .= '&orderId='.$data['orderId'].'&orderDpid='.$data['orderDpid'];
	 	unset($data['companyId']);
	 	unset($data['orderId']);
	 	unset($data['orderDpid']);
	 	MtpPay::preOrder($data);
	 	exit;
	 }
	 public function actionSiteOrder()
	 {
	 	$user = $this->brandUser;
	 	$userId = $user['lid'];
	 	$siteId = Yii::app()->session['qrcode-'.$userId];//餐桌id
	 	$msg = '';
	 	$site = WxSite::get($siteId, $this->companyId);
	 	if($site){
	 		$siteNo = WxSite::getSiteNo($siteId, $this->companyId);
	 		$siteType = WxSite::getSiteType($site['type_id'],$this->companyId);
	 		$siteNoLid = $siteNo['lid'];
	 		$orders = WxOrder::getOrderBySiteId($siteNoLid, $this->companyId);
	 		if(empty($orders)){
	 			$msg = '该餐桌还未下单';
	 			$this->redirect(array('/mall/index','companyId'=>$this->companyId,'type'=>$this->type,'msg'=>$msg));
	 		}
	 	}else{
	 		$msg = '该餐桌不存在';
	 		$this->redirect(array('/mall/index','companyId'=>$this->companyId,'type'=>$this->type,'msg'=>$msg));
	 	}
	 	$this->render('siteorder',array('companyId'=>$this->companyId,'company'=>$this->company,'userId'=>$userId,'orders'=>$orders,'user'=>$user,'site'=>$site,'siteType'=>$siteType,'siteNo'=>$siteNo));
	 }
	/**
	 * 处理餐桌订单
	 */
	 public function actionCheckSiteOrder()
	 {
	 	$user = $this->brandUser;
        $userId = $user['lid'];
		$siteId = Yii::app()->request->getParam('siteNoId');//订单里的site_id
		$proCodeArr = array();
		$productArr = array();
		$haspormotion = false;
		$siteType = false;
		$seatingFee = 0;
		$price = 0;
		$memdisprice = 0;
		
		$levelDiscount = WxBrandUser::getUserDiscount($user,'1');
		$siteNo = WxSite::getSiteNoByLid($siteId,$this->companyId);
		$site = WxSite::get($siteNo['site_id'], $this->companyId);
		$siteType = WxSite::getSiteType($site['type_id'],$this->companyId);
		
		$orders = WxOrder::getOrderBySiteId($siteId, $this->companyId);
		if(empty($orders)){
			$msg = '该餐桌还未下单';
			$this->redirect(array('/mall/index','companyId'=>$this->companyId,'type'=>1,'msg'=>$msg));
		}
		foreach ($orders as $order){
			$orderProducts = $order['product_list'];
			foreach ($orderProducts as $product){
				array_push($proCodeArr, $product['phs_code']);
				if($product['set_id'] > 0){
					$amount = $product['zhiamount'];
				}else{
					$amount = $product['amount'];
				}
				if($product['private_promotion_lid'] > 0){
					$orderPromotion = WxOrder::getOrderProductPromotion($product['lid'],$this->companyId);
					$haspormotion = true;
					$isdiscount = 0;
					array_push($productArr, array('promotion_id'=>$orderPromotion['	promotion_id'],'num'=>$amount,'price'=>$product['price'],'can_cupon'=>$orderPromotion['can_cupon'],'is_member_discount'=>'0'));
				}else{
					$isdiscount = $product['is_member_discount'];
					array_push($productArr, array('promotion_id'=>-1,'num'=>$amount,'price'=>$product['price'],'can_cupon'=>1,'is_member_discount'=>$isdiscount));
				}
				if($isdiscount){
					$memdisprice += $amount*$product['price']*(1-$levelDiscount);
					$price +=  $amount*$product['price']*$levelDiscount;
				}else{
					$price +=  $amount*$product['price'];
				}
			}
		}
		
		$canuseCuponPrice = WxCart::getCartUnDiscountPrice($productArr,$levelDiscount);// 购物车优惠原价
		
		$remainMoney = WxBrandUser::getYue($userId,$user['dpid']);
		// 如果没普通优惠活动  可满减满送
		$fullsent = array();
		if(!$haspormotion){
			$fullsent = WxFullSent::canUseFullsent($this->companyId, $price, $order['order_type']);
			if(!empty($fullsent)){
				if($fullsent['full_type']){
					$minusprice = $price - $fullsent['extra_cost'];
					$canuseCuponPrice = $canuseCuponPrice - $fullsent['extra_cost'];
					if($minusprice > 0){
						$price = $minusprice;
					}else{
						$price = 0;
					}
				}
			}
		}
		$cupons = WxCupon::getUserAvaliableCupon($proCodeArr,$canuseCuponPrice,$userId,$this->companyId,$order['order_type']);
		
		$this->render('order',array('companyId'=>$this->companyId,'orders'=>$orders,'site'=>$site,'cupons'=>$cupons,'siteType'=>$siteType,'user'=>$user,'siteId'=>$siteId,'price'=>$price,'remainMoney'=>$remainMoney,'seatingFee'=>$seatingFee,'memdisprice'=>$memdisprice,'fullsent'=>$fullsent));
	 }
	 /**
	  * 
	  * 处理餐桌订单
	  * 餐桌有多个订单的合并到最新订单里
	  * 
	  */
	  public function actionGeneralSiteOrder(){
	  		$contion = null;
		  	$user = $this->brandUser;
        	$userId = $user['lid'];
			$siteId = Yii::app()->request->getParam('siteNoId');
			$paytype = Yii::app()->request->getPost('paytype');
			$fullsent = Yii::app()->request->getPost('fullsent');
			$cuponId = Yii::app()->request->getPost('cupon');
			$remark = Yii::app()->request->getPost('remark',null);
			$yue = Yii::app()->request->getPost('yue',0);
			$others = array('fullsent'=>$fullsent);
			try {
				$sorderObj = new WxSiteOrder($this->companyId, $siteId, $user, $others);
				if(empty($sorderObj->orders)){
					throw new Exception('没有订单不能支付！');
				}
			}catch (Exception $e){
				$this->redirect(array('/mall/siteOrder','companyId'=>$this->companyId,'type'=>1));
			}
			
			$transaction = Yii::app()->db->beginTransaction();
			try{
				$orderId = $sorderObj->createOrder();
				if($cuponId){
					WxOrder::updateOrderCupon($orderId,$this->companyId,$cuponId,$user);
				}
					
				if($remark){
					$remark = Helper::dealString($remark);
					$contion = $contion.' remark="'.$remark.'",';
				}
					
				if($contion){
					WxOrder::update($orderId,$this->companyId,$contion);
				}
				
				//使用余额
				if($yue){
					$order = WxOrder::getOrder($orderId,$this->companyId);
					if($order['order_status'] < 3){
						$remainMoney = WxBrandUser::getYue($userId,$user['dpid']);
						if($remainMoney > 0){
							WxOrder::insertOrderPay($order,10,'');
						}
					}
				}	
				if($paytype == 1){
					//支付宝支付
					WxOrder::updatePayType($orderId,$this->companyId,2);
				}
				WxOrder::updatePayType($orderId,$this->companyId);
				$transaction->commit();
			}catch (Exception $e){
				$transaction->rollback();
				$msg = $e->getMessage();
			}
			if($paytype == 1){
				$showUrl = Yii::app()->request->hostInfo."/wymenuv2/user/orderInfo?companyId=".$this->companyId.'&orderId='.$orderId;
				//支付宝支付
				$order = WxOrder::getOrder($orderId,$this->companyId);
				if($order['order_status'] > 2){
					$this->redirect(array('/user/orderInfo','companyId'=>$this->companyId,'orderId'=>$orderId));
				}
				$this->redirect(array('/alipay/mobileWeb','companyId'=>$this->companyId,'out_trade_no'=>$order['lid'].'-'.$order['dpid'],'subject'=>'点餐买单','total_fee'=>$order['should_total'],'show_url'=>$showUrl));
			}
			$this->redirect(array('/mall/payOrder','companyId'=>$this->companyId,'orderId'=>$orderId));
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
				WxOrder::insertOrderPay($order,10,'');
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
		$user = $this->brandUser;
        $userId = $user['lid'];
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
		$user = $this->brandUser;
        $userId = $user['lid'];
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
	 	$user = $this->brandUser;
        $userId = $user['lid'];
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
	 * 输入金额
	 * 买单
	 * 
	 */
	 public function actionBill(){
	    $user = $this->brandUser;
        $userId = $user['lid'];
	 	$this->render('bill',array('userId'=>$userId));
	 }
     /**
	 * 
	 * 生成扫码订单
	 * 
	 */
	 public function actionCreateBillOrder(){
	 	$userId = Yii::app()->request->getPost('userId');
        $orderPrice = Yii::app()->request->getPost('orderPrice');
        $offprice = Yii::app()->request->getPost('offprice');
        $result = WxOrder::createBillOrder($this->companyId,$userId,$orderPrice,$offprice);
        echo $result;
        exit;
	 }
     /**
	 * 
	 * 支付扫码订单
	 * 
	 */
     public function actionPayBillOrder(){
        $type = Yii::app()->request->getParam('type');
        $orderId = Yii::app()->request->getParam('oid');
        $userId = Yii::app()->request->getParam('uid');
        
        $order = WxOrder::getOrder($orderId,$this->companyId);
        if($type==1){
            $showUrl = Yii::app()->request->hostInfo."/wymenuv2/user/orderInfo?companyId=".$this->companyId.'&orderId='.$orderId;
		
    		if($order['order_status'] > 2){
    			$this->redirect(array('/user/orderInfo','companyId'=>$this->companyId,'orderId'=>$orderId));
    		}
    		$this->redirect(array('/alipay/mobileWeb','companyId'=>$this->companyId,'out_trade_no'=>$order['lid'].'-'.$order['dpid'],'subject'=>'扫码买单','total_fee'=>$order['should_total'],'show_url'=>$showUrl));
        }
        $this->render('paybill',array('order'=>$order,'userId'=>$userId));
	 }
	/**
	 * 
	 * 卡券领取页面
	 * 
	 */
	 public function actionGetWxCard()
	{
		$coupons = WxPromotionActivity::getNoPush($this->companyId);
		$this->render('getwxcard',array('companyId'=>$this->companyId,'coupons'=>$coupons));
	}
	/**
	 * 
	 * 
	 * 充值
	 * 
	 */
	 public function actionReCharge(){
	 	$user = $this->brandUser;
        $userId = $user['lid'];
	 	$backUrl = Yii::app()->request->getParam('url',null);
	 	$recharges = WxRecharge::getWxRecharge($this->companyId);
	 	$this->render('recharge',array('companyId'=>$this->companyId,'recharges'=>$recharges,'userId'=>$userId,'backUrl'=>urldecode($backUrl)));
	 }
	/**
	 * 
	 * 获取商品
	 * 
	 * 
	 */
	public function actionGetProduct()
	{
		$userId = Yii::app()->request->getParam('userId');
		//普通优惠
		$promotion = new WxPromotion($this->companyId,$userId,$this->type);
		$promotions = $promotion->promotionProductList;
		
		$product = new WxProduct($this->companyId,$userId,$this->type);
		$categorys = $product->categorys;
		$products = $product->categoryProductLists;
		echo json_encode(array('categorys'=>$categorys,'promotions'=>$promotions,'products'=>$products));
		exit;
	}
	/**
	 * 
	 * 添加购物车
	 * 
	 */
	public function actionAddCart()
	{
		$userId = Yii::app()->request->getParam('userId');
		$siteId = Yii::app()->session['qrcode-'.$userId];
		$type = Yii::app()->request->getParam('type');
		
		if($userId < 0){
			Yii::app()->end(json_encode(array('status'=>false,'msg'=>'请关注微信公众进行点餐')));
		}
		
		if($type==1){
			if($siteId < 0){
				Yii::app()->end(json_encode(array('status'=>false,'msg'=>'请先扫描餐桌二维码,然后再进行点单')));
			}
		}
		
		$promoteType = Yii::app()->request->getParam('promoteType');
		$productId = Yii::app()->request->getParam('productId');
		$promoteId = Yii::app()->request->getParam('promoteId');
		$toGroup = Yii::app()->request->getParam('toGroup');
		$canCupon = Yii::app()->request->getParam('canCupon');
		$isSet =  Yii::app()->request->getParam('isSet');
		
		$productArr = array('product_id'=>$productId,'is_set'=>$isSet,'num'=>1,'promotion_type'=>$promoteType,'promotion_id'=>$promoteId,'to_group'=>$toGroup,'can_cupon'=>$canCupon);
		$cart = new WxCart($this->companyId,$userId,$productArr,$siteId,$type);
		
		//检查活动商品数量
		if($promoteId > 0){
			$chek = $cart->checkPromotion();
			if(!$chek['status']){
				Yii::app()->end(json_encode($chek));
			}	
		}
		
		$result = $cart->checkStoreNumber();
		if(!$result['status']){
			Yii::app()->end(json_encode($result));
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
		$userId = Yii::app()->request->getParam('userId');
		$siteId = Yii::app()->session['qrcode-'.$userId];
		$type = Yii::app()->request->getParam('type');
		
		if($userId < 0){
			Yii::app()->end(json_encode(array('status'=>false,'msg'=>'请关注微信公众号我要点单进行点餐')));
		}
		
		$all = Yii::app()->request->getParam('all',0);
		if($all){
			$result = WxCart::clearCart($userId,$this->companyId);
			if($result){
				Yii::app()->end(json_encode(array('status'=>true,'msg'=>'清空成功!')));
			}else{
				Yii::app()->end(json_encode(array('status'=>false,'msg'=>'清空失败,请重新操作!')));
			}
		}
		
		$promoteType = Yii::app()->request->getParam('promoteType');
		$productId = Yii::app()->request->getParam('productId');
		$promoteId = Yii::app()->request->getParam('promoteId');
		$toGroup = Yii::app()->request->getParam('toGroup');
		$canCupon = Yii::app()->request->getParam('canCupon');
		$isSet =  Yii::app()->request->getParam('isSet');
		
		$productArr = array('product_id'=>$productId,'is_set'=>$isSet,'num'=>1,'promotion_type'=>$promoteType,'promotion_id'=>$promoteId,'to_group'=>$toGroup,'can_cupon'=>$canCupon);
		
		$cart = new WxCart($this->companyId,$userId,$productArr,$siteId,$type);
		if($cart->deleteCart()){
			Yii::app()->end(json_encode(array('status'=>true,'msg'=>'ok')));
		}else{
			Yii::app()->end(json_encode(array('status'=>false,'msg'=>'请重新操作')));
		}
	}
	/**
	 *
	 * 删除购物车单条记录
	 *
	 */
	public function actionDeleteCartItem(){
		$dpid = $this->companyId;
		$lid = Yii::app()->request->getParam('lid');
		$result = WxCart::deleteCartItem($lid, $dpid);
		if($result){
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
	/**
	 * 
	 * 判断购物车是否为空
	 * 
	 */
	public function actionEmptyCart()
	{
		$userId = Yii::app()->request->getParam('userId');
		$companyId = Yii::app()->request->getParam('companyId');
		$carts = WxCart::isEmptyCart($userId,$companyId);
		if(empty($carts)){
			echo 1;
		}else{
			echo 0;
		}
		exit;
	}
}