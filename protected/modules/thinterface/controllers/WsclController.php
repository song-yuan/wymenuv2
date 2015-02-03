<?php
class WsclController extends CController
{
	public function actionIndex()
	{
                //echo(Yii::app()->request->baseUrl.'/wsdl/wymenu.wsdl');
                echo time();
                echo "</br>";
		//$client = new SoapClient('http://menu.wymenu.com/wymenuv2/thinterface/wsv2');
                $client = new SoapClient('http://localhost/wymenuv2/thinterface/wsv2');
                //echo $client->getPrice('GOOGLE');
                echo $client->baseDataDown('XZTC','adfjalsd;fjalsjfkldsajfkdslafjkdsaf');
                //echo $client->getNewWn('adfjalsd;fjalsjfkldsajfkdslafjkdsaf');
                echo '</br>';
                echo time();
	}
}