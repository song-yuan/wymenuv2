<?php

class TestController extends Controller
{
	public $layout = '/layouts/productmain';
	public function actionIndex()
	{
		$this->render('index');
	}
	public function actionQrcode(){
		$url = 'http://www.baidu.com';
		$code=new QRCode($url);
		$code->create();exit;
	}
	public function actionMicroPay(){
		$companyId = Yii::app()->request->getParam('companyId');
		$orderId = Yii::app()->request->getParam('orderId');
		$this->render('micropay',array('companyId'=>$companyId,'orderId'=>$orderId));
	}
	public function actionMemercache(){
		$key = 'product';
		$cache = Yii::app()->memcache->get($key);
		if($cache!=false){
			echo $cache;
		}else{
			Yii::app()->memcache->set($key,'addbdfdfdfd');
		}
	}
}