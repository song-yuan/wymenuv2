<?php
class UserController extends BackendController
{
	public $roles ;
	public function init(){
		$this->roles = array(
			'2' => '管理员' ,
			'3' => '服务员',
		) ;
		if(Yii::app()->user->role == User::POWER_ADMIN) {
			$this->roles = array('1' => '系统管理员' ) +$this->roles;
		}
		$this->roles = array('' => '-- 请选择 --' ) +$this->roles;
	}
	public function beforeAction($action) {
		return parent::beforeAction($action);
	}
	public function actionIndex() {
		$companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId'));
		$criteria = new CDbCriteria;
		$criteria->with = 'company' ;
		$criteria->condition = (Yii::app()->user->role == User::POWER_ADMIN ? '' : 't.company_id='.Yii::app()->user->companyId.' and ').'t.status=1' ;
		
		$pages = new CPagination(User::model()->count($criteria));
		//	    $pages->setPageSize(1);
		$pages->applyLimit($criteria);
		$models = User::model()->findAll($criteria);
		
		$this->render('index',array(
				'models'=>$models,
				'pages'=>$pages,
				'companyId' => $companyId
		));
	}
	public function actionCreate() {
		$companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId'));
		$model = new UserForm() ;
		$model->company_id = $companyId ;
		$model->status = 1;
		if(Yii::app()->request->isPostRequest) {
			$model->attributes = Yii::app()->request->getPost('UserForm');
			if($model->save()){
				Yii::app()->user->setFlash('success','添加成功');
				$this->redirect(array('user/index' , 'companyId' => $companyId));
			}
		}
		$this->render('create' , array('model' => $model));
	}
	public function actionUpdate() {
		$companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId'));		
		$id = Yii::app()->request->getParam('id');
		if(Yii::app()->user->role > User::ADMIN && Yii::app()->user->userId != $id) {
			Yii::app()->user->setFlash('error' , '你没有删除权限');
			$this->redirect(array('user/index' , 'companyId' => $companyId)) ;
		}
		$model = new UserForm();
		$model->find('id=:id and status=1', array(':id' => $id));
		
		if(Yii::app()->request->isPostRequest) {
			$model->attributes = Yii::app()->request->getPost('UserForm');
			if($model->save()){
				Yii::app()->user->setFlash('success','修改成功');
				$this->redirect(array('user/index' , 'companyId' => $companyId));
			}
		}
		$this->render('update' , array('model' => $model)) ;
	}
	public function actionDelete(){
		$companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId'));
		if(Yii::app()->user->role > User::ADMIN) {
			Yii::app()->user->setFlash('error' , '你没有删除权限');
			$this->redirect(array('user/index' , 'companyId' => $companyId)) ;
		}
		$ids = Yii::app()->request->getPost('ids');
		if(!empty($ids)) {
				foreach ($ids as $id) {
					$model = User::model()->find('id=:id' , array(':id' => $id)) ;
					if($model) {
						$model->saveAttributes(array('status'=>0));
					}
				}
				$this->redirect(array('user/index' , 'companyId' => $companyId)) ;
		} else {
			Yii::app()->user->setFlash('error' , '请选择要删除的项目');
			$this->redirect(array('user/index' , 'companyId' => $companyId)) ;
		}
	}
}