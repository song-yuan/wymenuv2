<?php
class MtpayConfigController extends BackendController
{
	public function beforeAction($action) {
		parent::beforeAction($action);
		if(!$this->companyId) {
			Yii::app()->user->setFlash('error' , yii::t('app','请选择公司'));
			$this->redirect(array('company/index'));
		}
		return true;
	}
	public function actionIndex() {
		$ty=1;
        $model = MtpayConfig::model()->find('dpid=:dpid',array(':dpid'=>$this->companyId));
        if(empty($model)){
        	$model = new MtpayConfig;
        	$se=new Sequence("mtpay_config");
        	$model->lid = $se->nextval();
        	$model->dpid = $this->companyId;
        	$model->create_at = date('Y-m-d H:i:s',time());
            $model->update_at = date('Y-m-d H:i:s',time());
            $ty = 0;
        }
        if(Yii::app()->request->isPostRequest){
        	$postData = Yii::app()->request->getPost('MtpayConfig');
        	//var_dump($postData);exit;
            $postData['update_at'] = date('Y-m-d H:i:s',time());
        	$model->attributes = $postData;
        	if($model->save()){
        		Yii::app()->user->setFlash('success' ,yii::t('app', '设置成功'));
        	}else{
        		Yii::app()->user->setFlash('error' ,yii::t('app', '失败'));
        	}
        }
		$this->render('index',array(
				'model'=>$model,
				'ty'=>$ty,
		));
	}
	
}