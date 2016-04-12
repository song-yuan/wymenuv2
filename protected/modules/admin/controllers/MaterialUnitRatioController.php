<?php
class MaterialUnitRatioController extends BackendController
{
	public function actionIndex(){
		$categoryId = Yii::app()->request->getParam('cid',0);
		$criteria = new CDbCriteria;
		$criteria->with = 'company';
		$criteria->condition =  't.delete_flag=0 and t.dpid='.$this->companyId;	
		$criteria->order = ' t.lid desc ';	
		$pages = new CPagination(MaterialUnitRatio::model()->count($criteria));
		//	    $pages->setPageSize(1);
		$pages->applyLimit($criteria);
		$models = MaterialUnitRatio::model()->findAll($criteria);
		$this->render('index',array(
				'models'=>$models,
				'pages'=>$pages,
				'categoryId'=>$categoryId
		
		));
	}
	public function actionSetMealList() {
		
	}
	public function actionCreate(){
		$model = new MaterialUnitRatio();
		$model->dpid = $this->companyId ;
		//$stock =new MaterialUnit();
		//$stockId = Yii::app()->request->getParam('lid');
		//$stockunit = MaterialUnit::model()->findAll( 
		//	array('select' =>'unit_name','condition'=>'unit_type=0','order' => 'lid DESC',
		//	));	
		if(Yii::app()->request->isPostRequest) {
			$model->attributes = Yii::app()->request->getPost('MaterialUnitRatio');
                        $se=new Sequence("material_unit");
                        $model->lid = $se->nextval();
                        $model->create_at = date('Y-m-d H:i:s',time());
                        $model->update_at = date('Y-m-d H:i:s',time());
                        $model->delete_flag = '0';
                        $py=new Pinyin();
                        $model->unit_ratio = $py->py($model->unit_ratio);
                        //var_dump($model);exit;
			if($model->save()){
				Yii::app()->user->setFlash('success',yii::t('app','添加成功！'));
				$this->redirect(array('MaterialUnitRatio/index' , 'companyId' => $this->companyId ));
			}
		}
		$categories = MaterialUnitRatio::model()->findAll('delete_flag=0 and dpid=:companyId' , array(':companyId' => $this->companyId)) ;
		//var_dump($categories);exit;

		$this->render('create' , array(
			'model' => $model ,
			'categories' => $categories,
			//'stockunit' => $stockunit,
			//'stockId'=>$stockId,
		));
	}
	
	public function actionUpdate(){
		$id = Yii::app()->request->getParam('id');
		$model = MaterialUnitRatio::model()->find('lid=:materialId and dpid=:dpid' , array(':materialId' => $id,':dpid'=>  $this->companyId));
		$model->dpid = $this->companyId;
		Until::isUpdateValid(array($id),$this->companyId,$this);//0,表示企业任何时候都在云端更新。
		if(Yii::app()->request->isPostRequest) {
			$model->attributes = Yii::app()->request->getPost('MaterialUnitRatio');
                        $py=new Pinyin();
                        $model->unit_ratio = $py->py($model->unit_ratio);
			$model->update_at=date('Y-m-d H:i:s',time());
			if($model->save()){
				Yii::app()->user->setFlash('success',yii::t('app','修改成功！'));
				$this->redirect(array('MaterialUnitRatio/index' , 'companyId' => $this->companyId ));
			}
		}

		$this->render('update' , array(
				'model' => $model ,
		));
	}
	public function actionDelete(){
		$companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId'));
		$ids = Yii::app()->request->getPost('ids');
                Until::isUpdateValid($ids,$companyId,$this);//0,表示企业任何时候都在云端更新。
		if(!empty($ids)) {
			Yii::app()->db->createCommand('update nb_material_unit set delete_flag=1 where lid in ('.implode(',' , $ids).') and dpid = :companyId')
			->execute(array( ':companyId' => $this->companyId));
			$this->redirect(array('MaterialUnitRatio/index' , 'companyId' => $companyId)) ;
		} else {
			Yii::app()->user->setFlash('error' , yii::t('app','请选择要删除的项目'));
			$this->redirect(array('MaterialUnitRatio/index' , 'companyId' => $companyId)) ;
		}
	}

}