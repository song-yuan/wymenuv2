<?php
class BaseWaiterController extends CController
{
	public $layout = '/layouts/mainwaiter';
	public function beforeAction($action) {
		$controllerId = Yii::app()->controller->getId();
		if(Yii::app()->user->isGuest && $controllerId != 'user') {
			$this->redirect(Yii::app()->params['waiter_return_url']);
		}
		return true ;
	}
}