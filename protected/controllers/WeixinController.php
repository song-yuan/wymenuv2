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
	 * 刷卡支付
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
}