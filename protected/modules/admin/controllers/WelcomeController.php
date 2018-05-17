<?php
class WelcomeController extends BackendController
{

    public function beforeAction($action) {
    	parent::beforeAction($action);
    	if(!$this->companyId && $this->getAction()->getId() != 'upload') {
    		Yii::app()->user->setFlash('error' , '请选择公司˾');
    		$this->redirect(array('company/index'));
    	}
    	return true;
    }

    public function actionList() {
    	//var_dump(Yii::app()->user);exit;
    	var_dump('aaaaa');exit;
    	$type = Yii::app()->request->getParam('type');
    	$this->render('list',array(
    			'companyId' => $this->companyId,
    			'type'=>$type,
    	));
    }
}
