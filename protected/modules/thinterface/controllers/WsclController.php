<?php
class WsclController extends CController
{
	public function actionIndex()
	{
                //echo(Yii::app()->request->baseUrl.'/wsdl/wymenu.wsdl');
                echo time();
                echo '</br>';
                //sequence测试例程
                //$seq= new Sequence('data_sync');
                //echo $seq->nextval();
                //$bd=new BaseDataMsg('0000000001');
                //$bd->saveCmd('XZTC');
                echo "</br>";
		//$client = new SoapClient('http://menu.wymenu.com/wymenuv2/thinterface/wsv2');
                $client = new SoapClient('http://localhost/wymenuv2/thinterface/wsv2');
                //echo $client->getPrice('GOOGLE');
                //echo $client->dealSn('0000000001','HT','adfjalsd;fjalsjfkldsajfkdslafjkdsaf');
                //$wn=  new WMsg('0000000001');
                //$wn->XD('1111111111111111111111111111111111111');
                //$wn->JC('22222222222222222222222222222222222222');
                //echo $wn->getMsg();
                //echo $client->getNewWn('0000000001');
                echo $client->setWnResult('0000000001','XD','0000000032',1);
                echo '</br>';
                echo time();
	}
}