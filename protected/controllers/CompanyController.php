<?php

class CompanyController extends Controller
{
	public $companyId = 0;
	public $layout = '/layouts/productmain';
	public function init(){
		session_start();
		$this->companyId = isset($_SESSION['companyId'])?$_SESSION['companyId']:0;
		if(!$this->companyId){
			$mac = Yii::app()->request->getParam('wuyimenusysosyoyhmac',0);
			$companyWifi = CompanyWifi::model()->find('macid=:macId',array(':macId'=>$mac));
			$this->companyId = $companyWifi?$companyWifi->company_id:0;
			$_SESSION['companyId'] = $this->companyId;
		}
	}
	public function actionIndex()
	{
		$company = Company::model()->findByPk($this->companyId);
		$this->render('index',array('company'=>$company));
	}
}