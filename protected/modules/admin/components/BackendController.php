<?php
class BackendController extends CController
{
	public $layout = '/layouts/main_admin';
	public $companyId = 0;
	public function beforeAction($action) {
		parent::beforeAction($action);
		$controllerId = Yii::app()->controller->getId();
		$action = Yii::app()->controller->getAction()->getId();
		if(Yii::app()->user->isGuest) {
			if($controllerId != 'login' && $action != 'upload') {
				$this->redirect(Yii::app()->params['admin_return_url']);
			}
		} elseif(Yii::app()->user->role > User::ADMIN &&$controllerId != 'login'){
			$this->redirect(Yii::app()->params['admin_return_url']);
		}else {
			$this->companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId'));
		}
		return true ;
	}
}