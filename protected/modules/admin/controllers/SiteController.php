<?php
class SiteController extends BackendController
{
	public function beforeAction($action) {
		parent::beforeAction($action);
		if(!$this->companyId) {
			Yii::app()->user->setFlash('error' , '请选择公司');
			$this->redirect(array('company/index'));
		}
		return true;
	}
	public function actionIndex() {
		$typeId = Yii::app()->request->getParam('typeId');
		$siteTypes = $this->getTypes();
		if(empty($siteTypes)) {
			$models = false;
		}
		$typeKeys = array_keys($siteTypes);
		$typeId = array_search($typeId, $typeKeys) ? $typeId : $typeKeys[0] ;
		
		$criteria = new CDbCriteria;
		$criteria->with = 'siteType';
		$criteria->condition =  't.delete_flag = 0 and t.type_id = '.$typeId.' and t.company_id='.$this->companyId ;
		$criteria->order = ' t.type_id asc ';
		
		$pages = new CPagination(Site::model()->count($criteria));
		//$pages->setPageSize(1);
		$pages->applyLimit($criteria);
		$models = Site::model()->findAll($criteria);
		
		$this->render('index',array(
				'siteTypes' => $siteTypes,
				'models'=>$models,
				'typeId' => $typeId,
				'pages' => $pages
		));
	}
	public function actionCreate() {
		$typeId = Yii::app()->request->getParam('typeId',0);
		$model = new Site() ;
		$model->company_id = $this->companyId ;
		$model->type_id = $typeId;
		
		if(Yii::app()->request->isPostRequest) {
			$model->attributes = Yii::app()->request->getPost('Site');
			if($model->save()) {
				Yii::app()->user->setFlash('success' , '添加成功');
				$this->redirect(array('site/index' , 'typeId'=>$typeId,'companyId' => $this->companyId));
			}
		}
		$types = $this->getTypes();
		$this->render('create' , array(
				'model' => $model , 
				'types' => $types
		));
	}
	public function actionUpdate(){
		$id = Yii::app()->request->getParam('id');
		$model = Site::model()->find('site_id=:id', array(':id' => $id));
		
		if(Yii::app()->request->isPostRequest) {
			$model->attributes = Yii::app()->request->getPost('Site');
			if($model->save()){
				Yii::app()->user->setFlash('success' , '修改成功');
				$this->redirect(array('site/index' , 'typeId'=>$model->type_id, 'companyId' => $this->companyId));
			}
		}
		$types = $this->getTypes();
		$this->render('update' , array(
			'model'=>$model,
			'types' => $types
		));
	}
	public function actionDelete(){
		$companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId'));
		$ids = Yii::app()->request->getPost('ids');
		if(!empty($ids)) {
			foreach ($ids as $id) {
				$model = Site::model()->find('site_id=:id and company_id=:companyId' , array(':id' => $id , ':companyId' => $companyId)) ;
				if($model) {
					$model->saveAttributes(array('delete_flag'=>1));
				}
			}
			$this->redirect(array('site/index' , 'companyId' => $companyId)) ;
		} else {
			Yii::app()->user->setFlash('error' , '请选择要删除的项目');
			$this->redirect(array('site/index' , 'companyId' => $companyId)) ;
		}
	}
	private function getTypes(){
		$types = SiteType::model()->findAll('company_id=:companyId and delete_flag=0' , array(':companyId' => $this->companyId)) ;
		$types = $types ? $types : array();
		return CHtml::listData($types, 'type_id', 'name');
	}
}