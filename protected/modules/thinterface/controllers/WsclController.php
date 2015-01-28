<?php
class WsclController extends CController
{
	public function actionIndex()
	{
                echo(Yii::app()->request->baseUrl.'/wsdl/wymenu.wsdl');
		$client = new SoapClient('http://127.0.0.1/wymenuv2/wsdl/wymenu.wsdl');
                echo $client->getPrice('GOOGLE');
	}
}