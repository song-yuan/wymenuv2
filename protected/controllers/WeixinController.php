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
}