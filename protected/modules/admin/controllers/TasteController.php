<?php
class TasteController extends BackendController
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
		$type = Yii::app()->request->getParam('type',0);
		$criteria = new CDbCriteria;
		$criteria->addCondition('dpid=:dpid and allflae=:type and delete_flag=0');
		$criteria->order = ' lid desc ';
		$criteria->params[':dpid']=$this->companyId;
		$criteria->params[':type']=$type; 
		
		$pages = new CPagination(Taste::model()->count($criteria));
		//$pages->setPageSize(1);
		$pages->applyLimit($criteria);
		$models = Taste::model()->findAll($criteria);
		
		$this->render('index',array(
				'models'=>$models,
				'pages' => $pages,
				'type'=>$type
		));
	}
	public function actionCreate() {
		$type = Yii::app()->request->getParam('type',0);
		$model = new Taste ;
		$model->dpid = $this->companyId ;
		
		if(Yii::app()->request->isPostRequest) {
			$model->attributes = Yii::app()->request->getPost('Taste');
                        $se=new Sequence("taste");
                        $model->lid = $se->nextval();
                        $model->allflae = $type;
                        $model->create_at = date('Y-m-d H:i:s',time());
                        $model->delete_flag = '0';
//                        var_dump($model);exit;
			if($model->save()) {
				Yii::app()->user->setFlash('success' , '添加成功');
				$this->redirect(array('taste/index' , 'companyId' => $this->companyId,'type'=>$type));
			}
		}
		$this->render('create' , array(
				'model' => $model , 
				'type' => $type
		));
	}
	public function actionUpdate(){
		$lid = Yii::app()->request->getParam('lid');
		$type = Yii::app()->request->getParam('type');
		$model = Taste::model()->find('lid=:lid and dpid=:dpid', array(':lid' => $lid,':dpid'=>  $this->companyId));
		
		if(Yii::app()->request->isPostRequest) {
			$model->attributes = Yii::app()->request->getPost('Taste');
			if($model->save()){
				Yii::app()->user->setFlash('success' , '修改成功');
				$this->redirect(array('taste/index' , 'type'=>$type, 'companyId' => $this->companyId));
			}
		}
		$this->render('update' , array(
			'model'=>$model,
			'type' => $type
		));
	}
	public function actionDelete(){
		$companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId'));
		$ids = Yii::app()->request->getPost('lid');
		$type = Yii::app()->request->getParam('type',0);
		if(!empty($ids)) {
			foreach ($ids as $id) {
				$model = Taste::model()->find('lid=:id and dpid=:companyId' , array(':id' => $id , ':companyId' => $companyId)) ;
				if($model) {
					$model->saveAttributes(array('delete_flag'=>1));
				}
			}
			$this->redirect(array('taste/index' , 'companyId' => $companyId,'type'=>$type)) ;
		} else {
			Yii::app()->user->setFlash('error' , '请选择要删除的项目');
			$this->redirect(array('taste/index' , 'companyId' => $companyId,'type'=>$type)) ;
		}
	}
	public function actionProductTaste(){
		var_dump(111);exit;
	}
}