<?php

class TestController extends Controller
{
	public $layout = '/layouts/productmain';
	public function actionIndex()
	{
		$code = '60605790';
		$deviceId = '1234567890';
		echo $code;
		exit;
		$res = SqbPay::activate($code,$deviceId);
		var_dump($res);exit;
	}
	public function actionQrcode(){
		$this->render('index');
	}
	public function actionMicroPay(){
		$companyId = Yii::app()->request->getParam('companyId');
		$orderId = Yii::app()->request->getParam('orderId');
		$this->render('micropay',array('companyId'=>$companyId,'orderId'=>$orderId));
	}
	
}