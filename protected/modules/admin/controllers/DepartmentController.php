<?php
class DepartmentController extends BackendController
{
	public function beforeAction($action) {
		parent::beforeAction($action);
		if(!$this->companyId) {
			Yii::app()->user->setFlash('error' , yii::t('app','请选择公司'));
			$this->redirect(array('company/index'));
		}
		return true;
	}
	public function actionIndex(){
		$criteria = new CDbCriteria;
		$criteria->condition =  't.company_id='.$this->companyId ;
		$pages = new CPagination(Department::model()->count($criteria));
		//	    $pages->setPageSize(1);
		$pages->applyLimit($criteria);
		
		$models = Department::model()->findAll($criteria);
		
		$this->render('index',array(
			'models'=>$models,
			'pages'=>$pages
		));
	}
	public function actionCreate(){
		$model = new Department() ;
		$model->company_id = $this->companyId ;
		
		if(Yii::app()->request->isPostRequest) {
			$model->attributes = Yii::app()->request->getPost('Department');
                        $model->create_at=date('Y-m-d H:i:s',time());
                        $model->update_at=date('Y-m-d H:i:s',time());
			if($model->save()) {
				Yii::app()->user->setFlash('success' , yii::t('app','添加成功'));
				$this->redirect(array('department/index','companyId' => $this->companyId));
			}
		}
		$printers = $this->getPrinterList();
		$this->render('create' , array(
				'model' => $model ,
				'printers'=>$printers
		));
	}
	public function actionUpdate(){
		$id = Yii::app()->request->getParam('id');
		$model = Department::model()->findByPk($id);
		
		if(Yii::app()->request->isPostRequest) {
                        //Until::isUpdateValid(array($id),$this->companyId,$this);//0,表示企业任何时候都在云端更新。
			
			$model->attributes = Yii::app()->request->getPost('Department');
                        $model->update_at=date('Y-m-d H:i:s',time());
			if($model->save()){
				Yii::app()->user->setFlash('success' , yii::t('app','修改成功'));
				$this->redirect(array('department/index' , 'companyId' => $this->companyId));
			}
		}
		$printers = $this->getPrinterList();
		$this->render('update' , array(
				'model'=>$model,
				'printers'=>$printers
		));
	}
	public function actionDelete(){
		
	}
	private function getPrinterList(){
		$printers = Printer::model()->findAll('company_id=:companyId',array(':companyId'=>$this->companyId)) ;
		return CHtml::listData($printers, 'printer_id', 'name');
	}
}