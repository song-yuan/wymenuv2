<?php

class DefaultController extends BackendController
{
	public function actionIndex()
	{
		$companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId'));
		if(!$companyId) {
			
		}
		
		$this->render('index');
	}
}