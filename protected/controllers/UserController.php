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
		if(in_array($actin->id,array('index','orderList','orderinfo','address','addAddress','setAddress','gift','usedGift','cupon','expireGift','giftInfo','setUserInfo'))){
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
		$seatingFee = 0;
		$packingFee = 0;
		$freightFee = 0;
		
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
		
		if(in_array($order['order_type'],array(1,3))){
			$seatingProducts = WxOrder::getOrderProductByType($orderId,$this->companyId,1);
			foreach($seatingProducts as $seatingProduct){
				$seatingFee += $seatingProduct['price']*$seatingProduct['amount'];
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
		
//		$orderPays = WxOrderPay::get($this->companyId,$orderId);
		//查找分享红包
		$redPack = WxRedPacket::getOrderShareRedPacket($this->companyId,$order['should_total']);
		
		$this->render('orderinfo',array('companyId'=>$this->companyId,'order'=>$order,'orderProducts'=>$orderProducts,'site'=>$site,'address'=>$address,'siteType'=>$siteType,'redPack'=>$redPack,'seatingFee'=>$seatingFee,'packingFee'=>$packingFee,'freightFee'=>$freightFee));
	}
	/**
	 * 
	 * 完善个人资料
	 * 
	 */
	public function actionSetUserInfo()
	{
		$userId = Yii::app()->session['userId'];
		$user = WxBrandUser::get($userId,$this->companyId);
		
		$this->render('updateuserinfo',array('companyId'=>$this->companyId,'user'=>$user));
	}
	/**
	 * 
	 * 保存个人资料
	 * 
	 */
	public function actionSaveUserInfo()
	{
		if(Yii::app()->request->isPostRequest){
			$userInfo = Yii::app()->request->getPost('user');
			$userInfo['dpid'] = $this->companyId;
			$result = WxBrandUser::update($userInfo);
			if($result){
				$this->redirect(array('/user/index','companyId'=>$this->companyId));
			}else{
				$this->redirect(array('/user/setUserInfo','companyId'=>$this->companyId));
			}
		}
	}
	/**
	 * 
	 * 会员地址列表
	 * 
	 */
	public function actionAddress()
	{
		$userId = Yii::app()->session['userId'];
		$addresss = WxAddress::get($userId,$this->companyId);
		$this->render('address',array('companyId'=>$this->companyId,'addresss'=>$addresss,'userId'=>$userId));
	}
	/**
	 * 
	 * 编辑地址
	 * 
	 */
	public function actionSetAddress()
	{
		$userId = Yii::app()->session['userId'];
		$url = Yii::app()->request->getParam('url');
		$type = Yii::app()->request->getParam('type',1);
		$addresss = WxAddress::get($userId,$this->companyId);
		$company = WxCompany::get($this->companyId);
		$this->render('setaddress',array('company'=>$company,'addresss'=>$addresss,'userId'=>$userId,'url'=>$url,'type'=>$type));
	}
	/**
	 * 
	 * 增加地址
	 * 
	 */
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
	/**
	 * 
	 * 保存地址
	 * 
	 */
	public function actionGenerateAddress() {
		$goBack = Yii::app()->request->getParam('url');
		if(Yii::app()->request->isPostRequest) {
			$post = Yii::app()->request->getPost('address');
			$post['dpid'] = $this->companyId;
<<<<<<< HEAD
			if($post['lid']>0){
=======
			if($post['lid'] > 0){
>>>>>>> c9ae6599caf029ad2ec37ffc3b65ab292f2f2382
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
		$cupons = WxCupon::getUserNotUseCupon($userId,$this->companyId);
		$this->render('cupon',array('companyId'=>$this->companyId,'cupons'=>$cupons));
	}
	public function actionUsedCupon()
	{
		$userId = Yii::app()->session['userId'];
		$cupons = WxCupon::getUserUseCupon($userId,$this->companyId);
		$this->render('usedcupon',array('companyId'=>$this->companyId,'cupons'=>$cupons));
	}
	public function actionExpireCupon()
	{
		$userId = Yii::app()->session['userId'];
		$cupons = WxCupon::getUserExpireCupon($userId,$this->companyId);
		$this->render('expirecupon',array('companyId'=>$this->companyId,'cupons'=>$cupons));
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
	/**
	 * 
	 * 手机报表统计
	 * 
	 */
	public function actionStatistic()
	{
		$now = time();
		$userId = Yii::app()->session['userId'];
		$day = Yii::app()->request->getParam('day',1);
		$t = Yii::app()->request->getParam('t',0);
		if($day==1){
			$yesterday = strtotime('-1 day');
		}else{
			if($day < 1){
				$day = 1;
			}
			$yesterday = strtotime('-'.$day.' day');
		}
		
		$start = date('Y-m-d',$now).' 00:00:00';
		$end = date('Y-m-d',$now).' 23:59:59';
		
		$ystart = date('Y-m-d',$yesterday).' 00:00:00';
		$yend = date('Y-m-d',$yesterday).' 23:59:59';
		
		$orderTypeStatistic = WxStatistic::getStatisticByOrderType($this->companyId,$start,$end);
		$payTypeStatistic = WxStatistic::getStatisticByOrderPayType($this->companyId,$start,$end);
		
		$yorderTypeStatistic = WxStatistic::getStatisticByOrderType($this->companyId,$ystart,$yend);
		$ypayTypeStatistic = WxStatistic::getStatisticByOrderPayType($this->companyId,$ystart,$yend);
		
		$this->render('statistic',array('companyId'=>$this->companyId,'orderTypeStatistic'=>$orderTypeStatistic,'payTypeStatistic'=>$payTypeStatistic,'yorderTypeStatistic'=>$yorderTypeStatistic,'ypayTypeStatistic'=>$ypayTypeStatistic,'day'=>$day));
	}
	/**
	 * 
	 * 
	 * 礼品券详情
	 * 
	 */
	public function actionGiftInfo()
	{
		$userId = Yii::app()->session['userId'];
		$giftId = Yii::app()->request->getParam('gid');
		
		$gift = WxGiftCard::getUserGift($this->companyId,$userId,$giftId);
		if(!$gift['qrcode']){
			$imgurl = './uploads';
			$imgurl .= '/company_'.$this->companyId;
   			if(!is_dir($imgurl)){
   				mkdir($imgurl, 0777,true);
   			}
			$imgurl .= '/qrcode';
   			if(!is_dir($imgurl)){
   				mkdir($imgurl, 0777,true);
   			}
   			$imgurl .= '/gift-'.$this->companyId.'-'.$giftId.'.png';
   			
			$code=new QRCode($gift['code']);
			$code->create($imgurl);
			WxGiftCard::updateQrcode($this->companyId,$gift['lid'],$imgurl);
			$gift['qrcode'] = $imgurl;
		}
		$this->render('giftinfo',array('companyId'=>$this->companyId,'gift'=>$gift));
	}
	/**
	 * 
	 * 取消订单
	 * 
	 */
	 public function actionAjaxCancelOrder()
	{
		$orderId = Yii::app()->request->getParam('orderId');
		$dpid = $this->companyId;
		
		$result = WxOrder::cancelOrder($orderId,$dpid);
		if($result){
			echo 1;
		}else{
			echo 0;
		}
		exit;
	}
	/**
	 * 
	 * 点击头像 更新会员信息
	 * 
	 */
	public function actionAjaxHeadIcon()
	{
		$userId = Yii::app()->request->getPost('userId');
		$dpid = $this->companyId;
		
		$pullInfo = new PullUserInfo($dpid,$userId);
		if($pullInfo->response->headimgurl){
			echo $pullInfo->response->headimgurl;
		}else{
			echo false;
		}
		exit;
	}
	/**
	 * 
	 * 设置默认地址
	 * 
	 * 
	 */
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
	/**
	 * 
	 * 删除地址
	 * 
	 */
	public function actionAjaxDeleteAddress()
	{
		$lid = Yii::app()->request->getParam('lid');
		$dpid = $this->companyId;
		
		$addresss = WxAddress::deleteAddress($lid,$dpid);
		
		if($addresss){
			echo 1;
		}else{
			echo 0;
		}
		exit;
	}
	/**
	 * 
	 * 验证手机验证码
	 * 
	 */
	 public function actionAjaxVerifyCode()
	{
		$mobile = Yii::app()->request->getParam('mobile');
		$code = Yii::app()->request->getParam('code');
		$mobile = trim($mobile);
		$code = trim($code);
		$result = WxSentMessage::getCode($this->companyId,$mobile);
		if($result && $result['code'] == $code){
			echo 1;
		}else{
			echo 0;
		}
		exit;
	}
	/**
	 * 
	 * 发送短信
	 * 
	 */
	 public function actionAjaxSentMessage()
	{
		$mobile = Yii::app()->request->getParam('mobile');
		$code = rand(1000,9999);
		if(WxSentMessage::insert($this->companyId,$mobile,$code)){
			$content = '【物易科技】您的验证码是：'.$code;
			$result = WxSentMessage::sentMessage($mobile,$content);
			$resArr = json_decode($result);
			if($resArr->returnstatus=='Success'){
				echo 1;
			}else{
				echo 0;
			}
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