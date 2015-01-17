<?php
class ProductCategoryController extends BackendController
{
	public function beforeAction($action) {
		parent::beforeAction($action);
		if(!$this->companyId) {
			Yii::app()->user->setFlash('error' , '请选择公司');
			$this->redirect(array('company/index'));
		}
		return true;
	}
	public function actionIndex(){
		//$companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId'));
		$criteria = new CDbCriteria;
		$criteria->with = 'company';
		$criteria->condition =  't.delete_flag=0 and t.company_id='.$this->companyId ;
		$criteria->order = ' tree,category_id asc ';
		
		$models = ProductCategory::model()->findAll($criteria);
		
		$id = Yii::app()->request->getParam('id',0);
		$expandModel = ProductCategory::model()->find('category_id=:id and delete_flag=0',array(':id'=>$id));
		$expandNode = $expandModel?explode(',',$expandModel->tree):array(0);
		
		$this->render('index',array(
				'models'=>$models,
				'expandNode'=>$expandNode
		));
	}
	public function actionCreate() {
		$pid = Yii::app()->request->getParam('pid',0);
		$model = new ProductCategory() ;
		$model->company_id = $this->companyId ;
		if($pid) {
			$model->pid = $pid;
		}
		if(Yii::app()->request->isPostRequest) {
			$model->attributes = Yii::app()->request->getPost('ProductCategory');
			//var_dump($_POST['ProductCategory'],$model->attributes);exit;
		
			if($model->save()){
				if($model->pid){
					$parent = ProductCategory::model()->find('category_id=:pid' , array(':pid'=>$model->pid));
					$model->tree = $parent->tree.','.$model->category_id;
				} else {
					$model->tree = $model->tree.','.$model->category_id;
				}
				$model->save();
				Yii::app()->user->setFlash('success' , '添加成功');
				$this->redirect(array('productCategory/index' , 'id'=>$model->category_id,'companyId' => $this->companyId));
			}
		}
		$this->renderPartial('_form1' , array(
				'model' => $model,
				'action' => $this->createUrl('productCategory/create' , array('companyId'=>$this->companyId))
		));
	}
	public function actionUpdate() {
		$id = Yii::app()->request->getParam('id');
		$model = ProductCategory::model()->find('category_id=:id', array(':id' => $id));
	
		if(Yii::app()->request->isPostRequest) {
			$model->attributes = Yii::app()->request->getPost('ProductCategory');
			if($model->save()){
				Yii::app()->user->setFlash('success' , '修改成功');
				$this->redirect(array('productCategory/index' , 'id'=>$model->category_id,'companyId' => $this->companyId));
			}
		}
		$this->renderPartial('_form1' , array(
				'model' => $model,
				'action' => $this->createUrl('productCategory/update' , array(
						'companyId'=>$this->companyId,
						'id'=>$model->category_id
				))
		));
	}
	public function actionDelete(){
		$id = Yii::app()->request->getParam('id');
		$model = ProductCategory::model()->find('category_id=:id and company_id=:companyId' , array(':id'=>$id,':companyId'=>$this->companyId));
		//var_dump($model);exit;
		if($model) {
			$model->deleteCategory();
			Yii::app()->user->setFlash('success','删除成功！');
		}
		$this->redirect(array('productCategory/index','companyId'=>$this->companyId,'id'=>$model->pid));
	}
	
	
	
}