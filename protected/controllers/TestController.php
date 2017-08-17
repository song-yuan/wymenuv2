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
		echo '<meta charset="utf-8">';
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
		$dpid = 30;
		$sql = 'select count(*) from nb_pad_setting_status where dpid='.$dpid.' and delete_flag=0';
		$padNums = Yii::app ()->db->createCommand ( $sql )->queryScalar();
		$padNo = $padNums + 1;
		var_dump($padNo);exit;
	}
}