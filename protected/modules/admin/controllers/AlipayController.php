<?php
class AlipayController extends BackendController
{
	public function actions() {
		return array(
				'upload'=>array(
						'class'=>'application.extensions.swfuploadali.SWFUploadAction',
						//注意这里是绝对路径,.EXT是文件后缀名替代符号
						'filepath'=>Helper::genFileName().'.EXT',
						//'onAfterUpload'=>array($this,'saveFile'),
				)
		);
	}
	public function beforeAction($action) {
		parent::beforeAction($action);
		if(!$this->companyId && $this->getAction()->getId() != 'upload') {
			Yii::app()->user->setFlash('error' , yii::t('app','请选择公司'));
			$this->redirect(array('company/index'));
		}
		return true;
	}
	public function actionIndex() {
        $model = AlipayServiceAccount::model()->find('dpid=:dpid',array(':dpid'=>$this->companyId));
        if(!$model){
        	$model = new AlipayServiceAccount;
        	$model->dpid = $this->companyId ;
        }
        if(Yii::app()->request->isPostRequest){
        	$postData = Yii::app()->request->getPost('AlipayServiceAccount');
        	$se=new Sequence("alipay_service_account");
            $postData['lid'] = $se->nextval();
            $postData['dpid'] = $this->companyId;
            $postData['create_at'] = date('Y-m-d H:i:s',time());
            $postData['update_at'] = date('Y-m-d H:i:s',time());
        	$model->attributes = $postData;
        	//var_dump($model);exit;
        	if($model->save()){
        		Yii::app()->user->setFlash('success' ,yii::t('app', '设置成功'));
        	}else{
        		$this->redirect(array('/admin/alipay/index','companyId'=>$this->companyId));
        	}
        }
		$this->render('index',array(
				'model'=>$model,
		));
	}

}