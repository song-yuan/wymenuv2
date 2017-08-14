<?php

class TestController extends Controller
{
	public $layout = '/layouts/productmain';
	public function actionIndex()
	{
		$this->render('index');
	}
	public function actionReadLog()
	{
		$log = file_get_contents( Yii::app()->basePath."/data/log.txt");
		echo $log;
		exit;
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
		$key = 'test';
		$cache = Yii::app()->cache->get($key);
		if($cache!=false){
			var_dump($cache[0]);
		}else{
			Yii::app()->cache->set($key,array('a','b','c'),300);
		}
	}
}