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
	public function actionQrcode()
	{
		$orderId = Yii::app()->request->getParam('orderId');
		$companyId = Yii::app()->request->getParam('companyId');
		$order = WxOrder::getOrder($orderId,$companyId);
		$this->render('qrcode',array('order'=>$order));
	}
}