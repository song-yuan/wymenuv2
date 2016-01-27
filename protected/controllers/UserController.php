<?php

class UserController extends Controller
{

	public $companyId;
	public $brandUser;
	public $weixinServiceAccount;
	public $layout = '/layouts/mallmain';
	
	
	public function init() 
	{
		$companyId = Yii::app()->request->getParam('companyId');
		$this->companyId = $companyId;
	}
	
	public function beforeAction($actin){
		if(in_array($actin->id,array('index','orderList','orderinfo','address','addAddress','setAddress','gift','usedGift','cupon','expireGift','giftInfo'))){
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
			}else{
				//pc 浏览
				$userId = 2;
				Yii::app()->session['userId'] = $userId;
			}
		}
		return true;
	}
	/**
	 * 
	 * 个人中心
	 * 
	 */
	public function actionIndex()
	{
		$userId = Yii::app()->session['userId'];
		$user = WxBrandUser::get($userId,$this->companyId);
		$userLevel =  WxBrandUser::getUserLevel($user['user_level_lid'],$this->companyId);
		$remainMoney =  WxBrandUser::getYue($userId,$this->companyId);
		$this->render('index',array('companyId'=>$this->companyId,'user'=>$user,'userLevel'=>$userLevel,'remainMoney'=>$remainMoney));
	}
	/**
	 * 
	 * 订单列表
	 * 
	 */
	public function actionOrderList()
	{
		$userId = Yii::app()->session['userId'];
		$type = Yii::app()->request->getParam('t',0);
		
		$orderLists = WxOrder::getUserOrderList($userId,$this->companyId,$type);
		$this->render('orderlist',array('companyId'=>$this->companyId,'models'=>$orderLists,'type'=>$type));
	}
	/**
	 * 
	 * 订单详情
	 * 
	 */
	public function actionOrderInfo()
	{
		$userId = Yii::app()->session['userId'];
		$siteType = false;
		$address = false;
		
		$orderId = Yii::app()->request->getParam('orderId');
		$order = WxOrder::getOrder($orderId,$this->companyId);
		$site = $site = WxSite::get($order['site_id'],$this->companyId);
		if($site){
			$siteType = WxSite::getSiteType($site['type_id'],$this->companyId);
		}
		
		$orderProducts = WxOrder::getOrderProduct($orderId,$this->companyId);
		
		if(in_array($order['order_type'],array(2,3))){
			$address =  WxOrder::getOrderAddress($orderId,$this->companyId);
		}
		$orderPays = WxOrderPay::get($this->companyId,$orderId);
		//查找分享红包
		$redPack = WxRedPacket::getOrderShareRedPacket($this->companyId,$order['should_total']);
		
		$this->render('orderinfo',array('companyId'=>$this->companyId,'order'=>$order,'orderProducts'=>$orderProducts,'site'=>$site,'address'=>$address,'siteType'=>$siteType,'orderPays'=>$orderPays,'redPack'=>$redPack));
	}
	public function actionAddress()
	{
		$userId = Yii::app()->session['userId'];
		$addresss = WxAddress::get($userId,$this->companyId);
		$this->render('address',array('companyId'=>$this->companyId,'addresss'=>$addresss,'userId'=>$userId));
	}
	public function actionSetAddress()
	{
		$userId = Yii::app()->session['userId'];
		$url = Yii::app()->request->getParam('url');
		$addresss = WxAddress::get($userId,$this->companyId);
		$this->render('setaddress',array('companyId'=>$this->companyId,'addresss'=>$addresss,'userId'=>$userId,'url'=>$url));
	}
	public function actionAddAddress()
	{
		$userId = Yii::app()->session['userId'];
		$lid = Yii::app()->request->getParam('lid',0);
		$url = Yii::app()->request->getParam('url',0);
		$address = false;
		
		if($lid){
			$address = WxAddress::getAddress($lid,$this->companyId);
		}
		$this->render('addaddress',array('companyId'=>$this->companyId,'userId'=>$userId,'address'=>$address,'url'=>$url));
	}
	public function actionGenerateAddress() {
		$goBack = Yii::app()->request->getParam('url');
		if(Yii::app()->request->isPostRequest) {
			$post = Yii::app()->request->getPost('address');
			$post['dpid'] = $this->companyId;
			if($post['lid']){
				 $generateAddress = WxAddress::update($post);
			}else{
				 $generateAddress = WxAddress::insert($post);
			}
            if($goBack){
				$this->redirect(urldecode($goBack));	
			}else{
				$this->redirect(array('/user/address','companyId'=>$this->companyId));
			}
		};
	}
	public function actionCupon()
	{
		$userId = Yii::app()->session['userId'];
		$cupons = WxCupon::getUserAllCupon($userId,$this->companyId);
		$this->render('cupon',array('companyId'=>$this->companyId,'cupons'=>$cupons));
	}
	public function actionGift()
	{
		$userId = Yii::app()->session['userId'];
		$gifts = WxGiftCard::getUserAvailableGift($userId,$this->companyId);
		$this->render('gift',array('companyId'=>$this->companyId,'gifts'=>$gifts));
	}
	public function actionUsedGift()
	{
		$userId = Yii::app()->session['userId'];
		$gifts = WxGiftCard::getUserUsedGift($userId,$this->companyId);
		$this->render('usedgift',array('companyId'=>$this->companyId,'gifts'=>$gifts));
	}
	public function actionExpireGift()
	{
		$userId = Yii::app()->session['userId'];
		$gifts = WxGiftCard::getUserExpireGift($userId,$this->companyId);
		$this->render('expiregift',array('companyId'=>$this->companyId,'gifts'=>$gifts));
	}
	public function actionGiftInfo()
	{
		$userId = Yii::app()->session['userId'];
		$giftId = Yii::app()->request->getParam('gid');
		
		$gift = WxGiftCard::getUserGift($this->companyId,$userId,$giftId);
		if(!$gift['qrcode']){
			$imgurl = '/uploads/company_'.$this->companyId.'/qrcode/gift/gift-'.$this->companyId.'-'.$giftId.'.png';
			$code=new QRCode($gift['code']);
			$code->create($imgurl);
			$gift['qrcode'] = $imgurl;
		}
		$this->render('giftinfo',array('companyId'=>$this->companyId,'gift'=>$gift));
	}
	public function actionAjaxSetAddress()
	{
		$lid = Yii::app()->request->getPost('lid');
		$userId = Yii::app()->request->getPost('userId');
		$dpid = $this->companyId;
		
		$addresss = WxAddress::setDefault($userId,$lid,$dpid);
		
		if($addresss){
			echo 1;
		}else{
			echo 0;
		}
		exit;
	}
	public function actionAjaxDeleteAddress()
	{
		$lid = Yii::app()->request->getPost('lid');
		$dpid = $this->companyId;
		
		$addresss = WxAddress::deleteAddress($lid,$dpid);
		
		if($addresss){
			echo 1;
		}else{
			echo 0;
		}
		exit;
	}
	private function weixinServiceAccount() {	
		$this->weixinServiceAccount = WxAccount::get($this->companyId);
	}
	private function brandUser($openId) {	
		$this->brandUser = WxBrandUser::getFromOpenId($openId);
	}
}