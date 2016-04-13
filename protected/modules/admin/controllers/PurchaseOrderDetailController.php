<?php
class PurchaseOrderDetailController extends BackendController
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
		$criteria = new CDbCriteria;
		$criteria->with = 'material';
		$criteria->condition =  't.dpid='.$this->companyId;	
		$criteria->order = ' t.lid desc ';	
		$pages = new CPagination(PurchaseOrderDetail::model()->count($criteria));
		//	    $pages->setPageSize(1);
		$pages->applyLimit($criteria);
		
		$models = PurchaseOrderDetail::model()->findAll($criteria);
		//var_dump($categoryId);exit;
		$this->render('index',array(
				'models'=>$models,
				'pages'=>$pages,
				'categoryId'=>$categoryId
		
		));
	}
	public function actionSetMealList() {
		
	}
	public function actionCreate(){
		$model = new PurchaseOrderDetail();
		$model->dpid = $this->companyId ;

		if(Yii::app()->request->isPostRequest) {
			$model->attributes = Yii::app()->request->getPost('PurchaseOrderDetail');
                        $se=new Sequence("material_unit");
                        $model->lid = $se->nextval();
                        $model->create_at = date('Y-m-d H:i:s',time());
                        $model->update_at = date('Y-m-d H:i:s',time());
                      //  $model->delete_flag = '0';
                        $py=new Pinyin();
                        $model->unit_name = $py->py($model->unit_name);
                        //var_dump($model);exit;
			if($model->save()){
				Yii::app()->user->setFlash('success',yii::t('app','添加成功！'));
				$this->redirect(array('purchaseOrderDetail/index' , 'companyId' => $this->companyId ));
			}
		}
		$categories = $categories = PurchaseOrderDetail::model()->findAll('dpid=:companyId' , array(':companyId' => $this->companyId)) ;
		//var_dump($categories);exit;
		$this->render('create' , array(
			'model' => $model ,
			'categories' => $categories
		));
	}
	
	public function actionUpdate(){
		$id = Yii::app()->request->getParam('id');
		$model = PurchaseOrderDetail::model()->find('lid=:materialId and dpid=:dpid' , array(':materialId' => $id,':dpid'=>  $this->companyId));
		$model->dpid = $this->companyId;
		Until::isUpdateValid(array($id),$this->companyId,$this);//0,表示企业任何时候都在云端更新。
		if(Yii::app()->request->isPostRequest) {
			$model->attributes = Yii::app()->request->getPost('PurchaseOrderDetail');
                        $py=new Pinyin();
                        $model->unit_name = $py->py($model->unit_name);
			$model->update_at=date('Y-m-d H:i:s',time());
			if($model->save()){
				Yii::app()->user->setFlash('success',yii::t('app','修改成功！'));
				$this->redirect(array('purchaseOrderDetail/index' , 'companyId' => $this->companyId ));
			}
		}

		$this->render('update' , array(
				'model' => $model ,
		));
	}


}