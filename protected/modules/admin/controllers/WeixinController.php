<?php
class WeixinController extends BackendController
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
        $model = WeixinServiceAccount::model()->findByPk($this->companyId);
        if(!$model){
        	$model = new WeixinServiceAccount;
        }
        if(Yii::app()->request->isPostRequest){
        	$postData = Yii::app()->request->getPost('WeixinServiceAccount');
        	$postData['dpid'] = $this->companyId;
        	$model->attributes = $postData;
        	if($model->save()){
        		Yii::app()->user->setFlash('success' ,yii::t('app', '设置成功'));
        	}else{
        		$this->redirect(array('/admin/weixin/index','companyId'=>$this->companyId));
        	}
        }
		$this->render('index',array(
				'model'=>$model,
		));
	}
	public function actionMenu() {
		$data = ' {
				     "button":[
				     {	
				          "name":"点餐",
				           "sub_button":[
				            {
				               "type":"view",
				               "name":"堂吃",
				               "url":"http://www.baidu.com"
				            },
				            {
				               "type":"view",
				               "name":"外卖",
				               "url":"http://www.baidu.com"
				            }]
				      },
				      {
				          "type":"view",
			              "name":"我的",
			              "url":"http://www.baidu.com"
				       }]
				 }';
		$menu = new WxSdk($this->companyId);
		$result = $menu->createMenu($data);
		var_dump($result);
	}
	
}