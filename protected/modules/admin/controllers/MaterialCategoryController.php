<?php
class MaterialCategoryController extends BackendController
{
	public function actionIndex(){
		//$companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId'));
		$criteria = new CDbCriteria;
		$criteria->with = 'company';
		$criteria->condition =  't.delete_flag=0 and t.dpid='.$this->companyId ;
		$criteria->order = ' tree,lid asc ';

		$models = MaterialCategory::model()->findAll($criteria);

		$id = Yii::app()->request->getParam('id',0);
		$expandModel = MaterialCategory::model()->find('lid=:id and dpid=:dpid and delete_flag=0',array(':id'=>$id,':dpid'=>  $this->companyId));

                $expandNode = $expandModel?explode(',',$expandModel->tree):array(0);
		//var_dump(substr('0000000000'.$expandNode[2],-10,10));exit;
		$this->render('index',array(
				'models'=>$models,
				'expandNode'=>$expandNode
		));
	}
	public function actionCreate() {
		$this->layout = '/layouts/main_picture';
		$pid = Yii::app()->request->getParam('pid',0);
		$model = new MaterialCategory() ;
		$model->dpid = $this->companyId ;
		if($pid) {
			$model->pid = $pid;
		}
		if(Yii::app()->request->isPostRequest) {
			$model->attributes = Yii::app()->request->getPost('MaterialCategory');
			$category = MaterialCategory::model()->find('dpid=:dpid and category_name=:name and delete_flag=0' , array(':dpid'=>  $this->companyId,':name'=>$model->category_name));
			if($category){
				Yii::app()->user->setFlash('success' ,yii::t('app', '该类别已添加'));
				$this->redirect(array('materialCategory/index' , 'id'=>$category->lid,'companyId' => $this->companyId));
			}
                $se=new Sequence("material_category");
                $lid = $se->nextval();
                $model->lid = $lid;
                $code=new Sequence("mchs_code");
                $mchs_code = $code->nextval();
                $model->mchs_code = ProductCategory::getChscode($this->companyId,$lid, $mchs_code);
                $model->create_at = date('Y-m-d H:i:s',time());
                $model->delete_flag = '0';
                $model->update_at=date('Y-m-d H:i:s',time());

			if($model->save()){
                              //var_dump($model);exit;
                $self = MaterialCategory::model()->find('lid=:pid and dpid=:dpid' , array(':pid'=>$model->lid,':dpid'=>  $this->companyId));
				if($self->pid!='0'){
					$parent = MaterialCategory::model()->find('lid=:pid and dpid=:dpid' , array(':pid'=>$model->pid,':dpid'=>  $this->companyId));
					$self->tree = $parent->tree.','.$self->lid;
				} else {
					$self->tree = '0,'.$self->lid;
				}
                                //var_dump($model);exit;
				$self->update();
				Yii::app()->user->setFlash('success' ,yii::t('app', '添加成功'));
				$this->redirect(array('materialCategory/index' , 'id'=>$self->lid,'companyId' => $this->companyId));
			}else{
				Yii::app()->user->setFlash('error' ,yii::t('app', '添加失败'));
				$this->redirect(array('materialCategory/index' ,'companyId' => $this->companyId));
			}
		}
		$this->render('_form' , array(
				'model' => $model,
				'action' => $this->createUrl('materialCategory/create' , array('companyId'=>$this->companyId))
		));
	}
	public function actionUpdate() {
		$this->layout = '/layouts/main_picture';
		$id = Yii::app()->request->getParam('id');
		$model = MaterialCategory::model()->find('lid=:id and dpid=:dpid', array(':id' => $id,':dpid'=>  $this->companyId));
        //Until::isUpdateValid(array($id),$this->companyId,$this);//0,表示企业任何时候都在云端更新。
		if(Yii::app()->request->isPostRequest) {
			$model->attributes = Yii::app()->request->getPost('MaterialCategory');
                        $model->update_at=date('Y-m-d H:i:s',time());
			if($model->save()){
				Yii::app()->user->setFlash('success' ,yii::t('app', '修改成功'));
				$this->redirect(array('materialCategory/index' , 'id'=>$model->lid,'companyId' => $this->companyId));
			}
		}
		$this->render('_form' , array(
				'model' => $model,
				'action' => $this->createUrl('materialCategory/update' , array(
						'companyId'=>$this->companyId,
						'id'=>$model->lid
				))
		));
	}
	public function actionDelete(){
		$id = Yii::app()->request->getParam('id');
        //Until::isUpdateValid(array($id),$this->companyId,$this);//0,表示企业任何时候都在云端更新。
		$model = MaterialCategory::model()->find('lid=:id and dpid=:companyId' , array(':id'=>$id,':companyId'=>$this->companyId));
		//var_dump($id,  $this->companyId,$model);exit;
		if($model) {
			$model->deleteCategory();
			Yii::app()->user->setFlash('success',yii::t('app','删除成功！'));
		}
		$this->redirect(array('materialCategory/index','companyId'=>$this->companyId,'id'=>$model->pid));
	}



}