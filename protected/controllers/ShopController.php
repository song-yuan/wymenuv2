<?php

class ShopController extends Controller
{
	public $companyId = 0;
	
	public $layout = '/layouts/shopmain';
	public function init()
	{
		$companyId = Yii::app()->request->getParam('companyId');
		$type = Yii::app()->request->getParam('type',6);
		$this->companyId = $companyId;
		$this->type = $type;
	}
	public function actionIndex(){
		$children = WxCompany::getCompanyChildren($this->companyId);
		$this->render('shoplist',array('companyId'=>$this->companyId,'type'=>$this->type,'children'=>$children));
	} 
}