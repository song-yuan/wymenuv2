<?php

class TestController extends Controller
{
	public function actionIndex()
	{
		$companyId = Yii::app()->request->getParam('companyId');
		$order = WxOrder::getOrder(4778,6);
		
		new WxMessageTpl($order['dpid'],$order['user_id'],0,$order);
		exit;
	}
	public function actionQrcode(){
		$this->render('index');
	}
	
}