<?php
class LoginController extends CController
{
	public $layout = '/layouts/loginLayout';
	public function actionIndex()
	{
       $language = Yii::app()->request->getParam('language','0');
       $oauthCallback = Yii::app()->request->getParam('oauth_callback',null);
       if($language!='0')
       {
           Yii::app()->session['language']=$language;
           Yii::app()->language=$language;
        }
		$model = new LoginForm();
		if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
		
		if(isset($_POST['LoginForm']))
		{
			$model->attributes = $_POST['LoginForm'];
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
                            'do_what'=>'login',
                            'out_time'=>"0000-00-00 00:00:00"                                    
                       );                            
                Yii::app()->db->createCommand()->insert('nb_b_login',$data);
                if($oauthCallback){
                	$this->redirect(urldecode($oauthCallback));
                }else{
                	$this->redirect(array('welcome/list/companyId/'.Yii::app()->user->companyId));
                }
			}
		}
		$this->render('index',array('model' => $model));
	}
	public function actionLogout()
	{
		Yii::app()->user->logout();
        $this->redirect('/wymenuv2/admin/login');
	}
    public function actionUnlock()
    {
        $username = Yii::app()->user->name;
        $password = Yii::app()->request->getParam('password','0');
        $identity = new UserIdentity($username,$password);
        $identity->authenticate();
		if($identity->errorCode===UserIdentity::ERROR_NONE)
		{
             Yii::app()->end(json_encode(array('status'=>true,'msg'=>"")));
        }else{
             Yii::app()->end(json_encode(array('status'=>false,'msg'=>"")));
        }
    }
	
	
	
	
	
	
}