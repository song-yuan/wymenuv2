<?php

class WeixinController extends Controller
{
	public function actionIndex()
	{
		$companyId = Yii::app()->request->getParam('companyId');
		$this->render('index',array('companyId'=>$companyId));
	}
	public function actionNotify()
	{
		$this->render('notify');
	}
	/**
	 * 
	 * 生成二维码支付
	 * 
	 */
	public function actionQrcode()
	{
		$orderId = Yii::app()->request->getParam('orderId');
		$companyId = Yii::app()->request->getParam('companyId');
		$order = WxOrder::getOrder($orderId,$companyId);
		$this->render('qrcode',array('order'=>$order));
	}
	/**
	 * 
	 * 整单刷卡支付
	 * 
	 * 
	 */
	 public function actionMicroPay()
	{
		$orderId = Yii::app()->request->getParam('orderId');
		$companyId = Yii::app()->request->getParam('companyId');
		$auth_code = Yii::app()->request->getParam('auth_code');
		
		$order = WxOrder::getOrder($orderId,$companyId);
		$this->render('micropay',array('order'=>$order,'auth_code'=>$auth_code));
	}
	/**
	 * 
	 * 分单刷卡支付
	 * 
	 * 
	 */
	 public function actionMicroPaySingle()
	{
		$poscode = Yii::app()->request->getParam('poscode');
		$username = Yii::app()->request->getParam('username');
		//新加参数
		$companyId = Yii::app()->request->getParam('companyId');
		$should_total = Yii::app()->request->getParam('pay_price');
		$auth_code = Yii::app()->request->getParam('auth_code');
		$goodStr = Yii::app()->request->getParam('goods');
		
		$this->render('singlemicropay',array('dpid'=>$companyId,'auth_code'=>$auth_code,'should_total'=>$should_total,'poscode'=>$poscode,'username'=>$username));
	}
	/**
	 * 
	 * 退款
	 * 
	 */
	 public function actionRefund()
	 {
	 	$poscode = Yii::app()->request->getParam('poscode');
		$companyId = Yii::app()->request->getParam('companyId');
		$adminId = Yii::app()->request->getParam('admin_id');
		$outTradeNo = Yii::app()->request->getParam('out_trade_no');
		$totalFee = Yii::app()->request->getParam('total_fee');
		$refundFee = Yii::app()->request->getParam('refund_fee');
	
		$this->render('refund',array('poscode'=>$poscode,'dpid'=>$companyId,'admin_id'=>$adminId,'out_trade_no'=>$outTradeNo,'total_fee'=>$totalFee,'refund_fee'=>$refundFee));
	 }
	 /**
	  * 
	  * 微信授权 并跳转
	  * 
	  */
	 public function actionRedirect()
	 {
	 	$companyId = Yii::app()->request->getParam('companyId');
	 	$comdpid = Yii::app()->request->getParam('pcompanyId');
	 	$url = Yii::app()->request->getParam('url');
	 	$account = WxAccount::get($companyId);
	 	$baseInfo = new WxUserBase($account['appid'],$account['appsecret']);
	 	$userInfo = $baseInfo->getSnsapiBase();
	 	$openid = $userInfo['openid'];
	 	
	 	$brandUser = WxBrandUser::getFromOpenId($openid);
	 	if(empty($brandUser)){
	 		$newBrandUser = new NewBrandUser($openid, $account['dpid']);
	 		$brandUser = $newBrandUser->brandUser;
	 	}
	 	$userId = $brandUser['lid'];
	 	$userDpid = $brandUser['dpid'];
	 	
	 	$ouserId = Yii::app()->session['userId_'.(int)$comdpid];
	 	if($ouserId&&$ouserId!=$userId){
	 		unset(Yii::app()->session['userId_'.(int)$comdpid]);
	 	}
	 	Yii::app()->session['userId_'.(int)$comdpid] = $userId;
	 	$this->redirect(urldecode($url));
	 }
}