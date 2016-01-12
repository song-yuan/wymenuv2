<?php

class ScreenController extends Controller
{
	public $companyId = 0;
	public $layout = '/layouts/mallmain';
	
	public function init() 
	{
		$companyId = Yii::app()->request->getParam('companyId');
		$this->companyId = $companyId;
	}
	
	/**
	 * 
	 * 显示视频
	 * 
	 */
	public function actionIndex()
	{
		$this->render('index',array('companyId'=>$this->companyId));
	}
	public function actionAjaxGetDiscuss(){
		$discuss = WxDiscuss::get($this->companyId);
		echo json_encode($discuss);
		exit;
	}
}