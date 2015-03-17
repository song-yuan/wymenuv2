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
		$companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId'));
		
		$criteria = new CDbCriteria;
		$criteria->with = 'company';
		$criteria->condition =  't.delete_flag=0 and t.dpid='.$companyId ;
		$criteria->order = ' t.tree,t.dpid asc ';
		
		$models = ProductCategory::model()->findAll($criteria);
		
		$id = Yii::app()->request->getParam('id',0);
		$expandModel = ProductCategory::model()->find('lid=:id and dpid=:companyId and delete_flag=0',array(':id'=>$id,':companyId'=>$companyId));
		$expandNode = $expandModel?explode(',',$expandModel->tree):array(0);
		
		$this->render('index',array(
				'models'=>$models,
				'expandNode'=>$expandNode
		));
	}
	public function actionCreate() {
		$parentId = Yii::app()->request->getParam('parentId',0);
		$model = new ProductCategory() ;
		$model->dpid = $this->companyId ;
		
		if($parentId) {
			$model->parent_id = $parentId;
                        $model->pid = $parentId;
		}
		if(Yii::app()->request->isPostRequest) {
			$model->attributes = Yii::app()->request->getPost('ProductCategory');
			//$model->lid = $model->getPkValue();
                        $se=new Sequence("product_category");
                        $model->lid = $se->nextval();
			if($model->save()){
				if($model->parent_id!='0'){
                                        $model->pid = $parentId;
					$parent = ProductCategory::model()->find('lid=:parentId and dpid=:dpid' , array(':parentId'=>$model->parent_id,":dpid"=>  $this->companyId));
					$model->tree = $parent->tree.','.$model->lid;
				} else {
					$model->tree = '0,'.$model->lid;
				}
				$model->save();
				Yii::app()->user->setFlash('success' , '添加成功');
                                $this->redirect(array('productCategory/index' , 'id'=>$model->lid,'companyId' => $this->companyId));
				//echo json_encode(array('status'=>1,'message'=>'添加成功'));exit;
			} else {
				//echo json_encode(array('status'=>0,'message'=>'添加失败'));exit;
                                Yii::app()->user->setFlash('error' , '添加失败');
                                $this->redirect(array('productCategory/index' , 'id'=>$model->lid,'companyId' => $this->companyId));
			}
		}
		$this->renderPartial('_form1' , array(
				'model' => $model,
				'action' => $this->createUrl('productCategory/create' , array('companyId'=>$this->companyId))
		));
	}
	public function actionUpdate() {
		$id = Yii::app()->request->getParam('id');
		$model = ProductCategory::model()->find('lid=:id and dpid=:dpid', array(':id' => $id,':dpid'=>  $this->companyId));
	
		if(Yii::app()->request->isPostRequest) {
			$model->attributes = Yii::app()->request->getPost('ProductCategory');
			if($model->save()){
				Yii::app()->user->setFlash('success' , '修改成功');
				//echo json_encode(array('status'=>1,'message'=>'修改成功'));exit;
                                $this->redirect(array('productCategory/index' , 'id'=>$model->lid,'companyId' => $this->companyId));
			} else {
				//echo json_encode(array('status'=>0,'message'=>'修改失败'));exit;
                                 Yii::app()->user->setFlash('error' , '添加失败');
                                $this->redirect(array('productCategory/index' , 'id'=>$model->lid,'companyId' => $this->companyId));
			}
		}
		$this->renderPartial('_form1' , array(
				'model' => $model,
				'action' => $this->createUrl('productCategory/update' , array(
						'companyId'=>$this->companyId,
						'id'=>$model->lid
				))
		));
	}
	public function actionDelete(){
		$id = Yii::app()->request->getParam('id');
		$model = ProductCategory::model()->find('lid=:id and dpid=:companyId' , array(':id'=>$id,':companyId'=>$this->companyId));
		//var_dump($model);exit;
		if($model) {
			$model->deleteCategory();
			Yii::app()->user->setFlash('success','删除成功！');
		}
		$this->redirect(array('productCategory/index','companyId'=>$this->companyId,'id'=>$model->parent_id));
	}
	
	
	
}