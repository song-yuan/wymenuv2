<?php

class LoginController extends BaseYmallController
{
	public $layout = '/layouts/loginLayout';
	// public $layout = '/layouts/mainymall';
	public function actionIndex()
	{
		$model = new LoginForm();
		// p($model);
		if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}

		if(isset($_POST['LoginForm']))
		{
			$model->attributes=$_POST['LoginForm'];
			if($model->validate() && $model->login()) {
                $se=new Sequence("b_login");
                $lid = $se->nextval();
                $userarray= explode("_",Yii::app()->user->userId);
                $data = array(
                    'lid'=>$lid,
                    'dpid'=>$userarray[1],
                    'create_at'=>date('Y-m-d H:i:s',time()),
                    'update_at'=>date('Y-m-d H:i:s',time()),
                    'user_id'=>$userarray[0],
                	'do_what'=>'loginmall',
                    'out_time'=>"0000-00-00 00:00:00",
                );
                Yii::app()->db->createCommand()->insert('nb_b_login',$data);
				$this->redirect(array('product/index','companyId'=>Yii::app()->user->companyId));
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