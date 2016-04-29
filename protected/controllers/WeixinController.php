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
		$companyId = Yii::app()->request->getPost('dpid');
		$should_total = Yii::app()->request->getPost('pay_price');
		$auth_code = Yii::app()->request->getPost('auth_code');
		
		$this->render('singlemicropay',array('dpid'=>$companyId,'auth_code'=>$auth_code,'should_total'=>$should_total));
	}
}