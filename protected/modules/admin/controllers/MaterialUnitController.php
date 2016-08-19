<?php
class MaterialUnitController extends BackendController
{
	public function actions() {
		return array(
				'upload'=>array(
						'class'=>'application.extensions.swfupload.SWFUploadAction',
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
	public function actionIndex(){
		$categoryId = Yii::app()->request->getParam('cid',0);
		$type=Yii::app()->request->getParam('type',0);
		$criteria = new CDbCriteria;
		$criteria->with = 'company';
		$criteria->condition =  't.unit_type='.$type.' and t.delete_flag=0 and t.dpid='.$this->companyId;
		$criteria->order = ' t.lid desc ';	
		$pages = new CPagination(MaterialUnit::model()->count($criteria));
		//	    $pages->setPageSize(1);
		$pages->applyLimit($criteria);
		$models = MaterialUnit::model()->findAll($criteria);
		$this->render('index',array(
				'models'=>$models,
				'pages'=>$pages,
				'categoryId'=>$categoryId,
				'type'=>$type
		
		));
	}
	public function actionSetMealList() {
		
	}
	public function actionCreate(){
		$type=Yii::app()->request->getParam('type',0);
		$model = new MaterialUnit();
		$model->dpid = $this->companyId ;
		if(Yii::app()->request->isPostRequest) {
			$model->attributes = Yii::app()->request->getPost('MaterialUnit');
			$se=new Sequence("material_unit");
			$lid = $se->nextval();
			$model->lid = $lid;
			$code = new Sequence('muhs_code');
			$muhs_code = $code->nextval();
			$model->muhs_code = ProductCategory::getChscode($this->companyId,$lid, $muhs_code);
			$model->create_at = date('Y-m-d H:i:s',time());
			$model->update_at = date('Y-m-d H:i:s',time());
			$model->delete_flag = '0';
			if($model->save()){
				Yii::app()->user->setFlash('success',yii::t('app','添加成功！'));
				$this->redirect(array('materialUnit/index' , 'companyId' => $this->companyId ));
			}
		}
		$categories = $categories = MaterialUnit::model()->findAll('delete_flag=0 and dpid=:companyId' , array(':companyId' => $this->companyId)) ;
		//var_dump($categories);exit;
		$this->render('create' , array(
			'model' => $model ,
			'categories' => $categories,
			'type' => $type
		));
	}
	
	public function actionUpdate(){
		$type=Yii::app()->request->getParam('type',0);
		$id = Yii::app()->request->getParam('id');
		$model = MaterialUnit::model()->find('lid=:materialId and dpid=:dpid' , array(':materialId' => $id,':dpid'=>  $this->companyId));
		$model->dpid = $this->companyId;
		Until::isUpdateValid(array($id),$this->companyId,$this);//0,表示企业任何时候都在云端更新。
		if(Yii::app()->request->isPostRequest) {
			$model->attributes = Yii::app()->request->getPost('MaterialUnit');
            $model->update_at=date('Y-m-d H:i:s',time());
			if($model->save()){
				Yii::app()->user->setFlash('success',yii::t('app','修改成功！'));
				$this->redirect(array('materialUnit/index' , 'companyId' => $this->companyId ));
			}
		}

		$this->render('update' , array(
				'model' => $model ,
				'type' => $type
		));
	}
	public function actionDelete(){
		$companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId'));
		$ids = Yii::app()->request->getPost('ids');
                Until::isUpdateValid($ids,$companyId,$this);//0,表示企业任何时候都在云端更新。
		if(!empty($ids)) {
			Yii::app()->db->createCommand('update nb_material_unit set delete_flag=1 where lid in ('.implode(',' , $ids).') and dpid = :companyId')
			->execute(array( ':companyId' => $this->companyId));
			$this->redirect(array('materialUnit/index' , 'companyId' => $companyId)) ;
		} else {
			Yii::app()->user->setFlash('error' , yii::t('app','请选择要删除的项目'));
			$this->redirect(array('materialUnit/index' , 'companyId' => $companyId)) ;
		}
	}

}