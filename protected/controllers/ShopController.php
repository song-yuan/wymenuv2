<?php

class ShopController extends Controller
{
	public $companyId = 0;
	public $type = 0;
	
	public $layout = '/layouts/shopmain';
	public function init()
	{
		$companyId = Yii::app()->request->getParam('companyId');
		$type = Yii::app()->request->getParam('type',6);
		$this->companyId = $companyId;
		$this->type = $type;
	}
	public function actionIndex(){
		$this->render('shoplist',array('companyId'=>$this->companyId,'type'=>$this->type));
	}
	public function actionAjaxGetShop(){
		$page = Yii::app()->request->getParam('page');
		$lat = Yii::app()->request->getParam('lat');
		$lng = Yii::app()->request->getParam('lng');
		$keyword = Yii::app()->request->getParam('keyword');
		$children = WxCompany::getCompanyChildrenPage($this->companyId,$lat,$lng,$page,$keyword);
		echo json_encode($children);
		exit;
	}
}