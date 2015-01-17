<?php
class SiteTypeController extends BackendController
{
	public function beforeAction($action){
		parent::beforeAction($action);
		if(!$this->companyId) {
			Yii::app()->user->setFlash('error' , '请选择公司');
			$this->redirect(array('company/index')) ;
		}
		return true;
	}
	public function actionIndex() {
		$criteria = new CDbCriteria;
		$criteria->with = 'company';
		$criteria->condition = 't.company_id='.$this->companyId ;
		
		$pages = new CPagination(SiteType::model()->count($criteria));
		//	    $pages->setPageSize(1);
		$pages->applyLimit($criteria);
		$models = SiteType::model()->findAll($criteria);
		
		$this->render('index',array(
				'models'=>$models,
				'pages'=>$pages,
		));
	}
	public function actionCreate() {
		$model = new SiteType() ;
		$model->company_id = $this->companyId ;
		
		if(Yii::app()->request->isPostRequest) {
			$model->attributes = Yii::app()->request->getPost('SiteType');
			if($model->save()){
				Yii::app()->user->setFlash('success' , '添加成功');
				$this->redirect(array('siteType/index' , 'companyId' => $this->companyId));
			}
		}
		$this->render('create' , array(
			'model' => $model,
		));
	}
	public function actionUpdate() {
		$id = Yii::app()->request->getParam('id');
		$model = SiteType::model()->find('type_id=:id', array(':id' => $id));
		
		if(Yii::app()->request->isPostRequest) {
			$model->attributes = Yii::app()->request->getPost('SiteType');
			if($model->save()){
				Yii::app()->user->setFlash('success' , '修改成功');
				$this->redirect(array('siteType/index' , 'companyId' => $this->companyId));
			}
		}
		$this->render('update' , array(
			'model' => $model
		));
	}
	public function actionDelete() {
		$ids = $_POST['type_id'] ;
		
		if(!empty($ids)) {
			Yii::app()->db->createCommand('update nb_site_type set delete_flag=1 where type_id in (:ids) and company_id = :companyId')
			->execute(array(':ids' => implode(',' , $ids) , ':companyId' => $this->companyId));
			
			Yii::app()->db->createCommand('update nb_site set delete_flag where type_id in (:ids) and company_id = :companyId')
			->execute(array(':ids' => implode(',' , $ids) , ':companyId' => $this->companyId));
		}
		$this->redirect(array('siteType/index' , 'companyId' => $this->companyId)) ;
	}
}