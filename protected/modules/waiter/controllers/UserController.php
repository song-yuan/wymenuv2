<?php

class UserController extends BaseWaiterController
{
	public $layout = '/layouts/loginLayout';
	public function actionIndex()
	{
		$model = new LoginForm();
		// if it is ajax validation request
		if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
		
		// collect user input data
		if(isset($_POST['LoginForm']))
		{
			$model->attributes=$_POST['LoginForm'];
			// validate user input and redirect to the previous page if valid
			if($model->validate() && $model->login()) {
				$this->redirect(Yii::app()->params['waiter_home_url']);
			}
		}
		$this->render('index',array('model' => $model));
	}
	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->params['admin_return_url']);
	}
}