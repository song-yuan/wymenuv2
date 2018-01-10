<?php
class LoginController extends BackendController
{
	public $layout = '/layouts/loginLayout';
	public function actionIndex()
	{
       $language=Yii::app()->request->getParam('language','0');
       if($language!='0')
       {
           Yii::app()->session['language']=$language;
           Yii::app()->language=$language;
        }
		$model = new LoginForm();
		// if it is ajax validation request
		if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
		{ //var_dump($language);exit;
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
		
		// collect user input data
		if(isset($_POST['LoginForm']))
		{
			$model->attributes=$_POST['LoginForm'];
			//var_dump($model);exit;
			//var_dump($model->login());exit;
			// validate user input and redirect to the previous page if valid
			if($model->validate() && $model->login()) {
                                //insert into nb_b_login
                                //echo Yii::app()->user->userId;exit;
                                $se=new Sequence("b_login");
                                $lid = $se->nextval(); 
                                $userarray= explode("_",Yii::app()->user->userId);
                                $data = array(
                                    'lid'=>$lid,
                                    'dpid'=>$userarray[1],
                                    'create_at'=>date('Y-m-d H:i:s',time()),
                                    'update_at'=>date('Y-m-d H:i:s',time()),
                                    'user_id'=>$userarray[0],
                                	'do_what'=>'login',
                                    'out_time'=>"0000-00-00 00:00:00"                                    
                                );                            
                                Yii::app()->db->createCommand()->insert('nb_b_login',$data);
                               //print_r ((array('default/index/companyId/'.Yii::app()->user->companyId)));exit;
				$this->redirect(array('welcome/list/companyId/'.Yii::app()->user->companyId));
				
			}
		}//
                //var_dump(Yii::app()->user->companyId);exit;
		$this->render('index',array('model' => $model));
	}
	public function actionLogout()
	{
                //$language=Yii::app()->session['language'];
		Yii::app()->user->logout();
                //Yii::app()->user->
		//$this->redirect(array('index','language'=>$language));
                $this->redirect('/wymenuv2/admin/login');
	}
        public function actionUnlock()
        {
                $username=Yii::app()->user->name;
                $password=Yii::app()->request->getParam('password','0');
                $identity=new UserIdentity($username,$password);
                $identity->authenticate();
		if($identity->errorCode===UserIdentity::ERROR_NONE)
		{
                    Yii::app()->end(json_encode(array('status'=>true,'msg'=>"")));
                }else{
                    Yii::app()->end(json_encode(array('status'=>false,'msg'=>"")));
                }
        }
	
	
	
	
	
	
}