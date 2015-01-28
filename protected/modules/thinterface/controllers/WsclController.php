<?php
class WsclController extends CController
{
	public function actionIndex()
	{
                echo(Yii::app()->request->baseUrl.'/wsdl/wymenu.wsdl');
		$client = new SoapClient('http://menu.wymenu.com/wymenuv2/thinterface/wsv2');
                echo $client->getPrice('GOOGLE');
	}
}