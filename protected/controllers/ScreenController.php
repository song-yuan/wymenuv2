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
		$screens = WxScreen::get($this->companyId);
		$this->render('index',array('companyId'=>$this->companyId,'screens'=>$screens));
	}
	/**
	 * 
	 * 显示视频
	 * 
	 */
	public function actionInfor()
	{
		$screenId = Yii::app()->request->getParam('screenId');
		$screen = WxScreen::getScreen($this->companyId,$screenId);
		$this->render('infor',array('companyId'=>$this->companyId,'screen'=>$screen));
	}
	/**
	 * 
	 * 显示评论
	 * 
	 */
	public function actionDiscuss()
	{
		$screenId = Yii::app()->request->getParam('screenId');
		$screen = WxScreen::getScreen($this->companyId,$screenId);
		$this->render('discuss',array('companyId'=>$this->companyId,'screen'=>$screen));
	}
	public function actionAjaxGetDiscuss(){
		$screenId = Yii::app()->request->getParam('screenId');
		$discuss = WxDiscuss::get($this->companyId,$screenId);
		echo json_encode($discuss);
		exit;
	}
	public function actionAjaxGetScreenTips(){
		$screenId = Yii::app()->request->getParam('screenId');
		$discuss = WxScreen::getScreen($this->companyId,$screenId);
		echo json_encode($discuss);
		exit;
	}
}