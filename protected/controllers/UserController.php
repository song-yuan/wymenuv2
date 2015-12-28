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
		if(in_array($actin->id,array('index','orderList','orderinfo','address','addAddress'))){
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
		$this->render('index',array('companyId'=>$this->companyId,'user'=>$user,'userLevel'=>$userLevel));
	}
	/**
	 * 
	 * 订单列表
	 * 
	 */
	public function actionOrderList()
	{
		$userId = Yii::app()->session['userId'];
		$orderLists = WxOrder::getUserOrderList($userId,$this->companyId);
		$this->render('orderlist',array('companyId'=>$this->companyId,'models'=>$orderLists));
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
		
		$orderId = Yii::app()->request->getParam('orderId');
		$order = WxOrder::getOrder($orderId,$this->companyId);
		$site = $site = WxSite::get($order['site_id'],$this->companyId);
		if($site){
			$siteType = WxSite::getSiteType($site['type_id'],$this->companyId);
		}
		
		$orderProducts = WxOrder::getOrderProduct($orderId,$this->companyId);
		//查找分享红包
		$redPack = WxRedPacket::getOrderShareRedPacket($this->companyId,$order['should_total']);
		
		$this->render('orderinfo',array('companyId'=>$this->companyId,'order'=>$order,'orderProducts'=>$orderProducts,'site'=>$site,'siteType'=>$siteType,'redPack'=>$redPack));
	}
	public function actionAddress()
	{
		$userId = Yii::app()->session['userId'];
		$addresss = WxAddress::get($userId,$this->companyId);
		$this->render('address',array('companyId'=>$this->companyId,'addresss'=>$addresss,'userId'=>$userId));
	}
	public function actionAddAddress()
	{
		$userId = Yii::app()->session['userId'];
	
		$this->render('addaddress',array('companyId'=>$this->companyId,'userId'=>$userId));
	}
	public function actionGenerateAddress() {
		$goBack = Yii::app()->request->getParam('goBack');
		if(Yii::app()->request->isPostRequest) {
			$post = Yii::app()->request->getPost('address');
			$post['dpid'] = $this->companyId;

            $generateAddress = WxAddress::insert($post);
            if($goBack==1){
				$this->redirect(array('/user/setDefaultAddress','companyId'=>$this->companyId,'goBackUrl'=>1));	
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
	
	private function weixinServiceAccount() {	
		$this->weixinServiceAccount = WxAccount::get($this->companyId);
	}
	private function brandUser($openId) {	
		$this->brandUser = WxBrandUser::getFromOpenId($openId);
	}
}