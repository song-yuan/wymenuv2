<?php
class PrinterController extends BackendController
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
		$criteria = new CDbCriteria;
		$criteria->condition =  't.dpid='.$this->companyId ;
		$pages = new CPagination(Printer::model()->count($criteria));
		//	    $pages->setPageSize(1);
		$pages->applyLimit($criteria);
		
		$models = Printer::model()->findAll($criteria);
		
		$this->render('index',array(
			'models'=>$models,
			'pages'=>$pages
		));
	}
	public function actionCreate(){
		$model = new Printer() ;
		$model->dpid = $this->companyId ;
		
		if(Yii::app()->request->isPostRequest) {
			$model->attributes = Yii::app()->request->getPost('Printer');
			if($model->save()) {
				Yii::app()->user->setFlash('success' , '添加成功');
				$this->redirect(array('printer/index','companyId' => $this->companyId));
			}
		}
		$this->render('create' , array(
				'model' => $model ,
		));
	}
	public function actionUpdate(){
		$id = Yii::app()->request->getParam('id');
		$model = Printer::model()->findByPk($id);
		
		if(Yii::app()->request->isPostRequest) {
			$model->attributes = Yii::app()->request->getPost('Printer');
			if($model->save()){
				Yii::app()->user->setFlash('success' , '修改成功');
				$this->redirect(array('printer/index' , 'companyId' => $this->companyId));
			}
		}
		$this->render('update' , array(
				'model'=>$model,
		));
	}
        public function actionList(){
		$model = Company::model()->findByPk($this->companyId);
		$printer = $this->getPrinters();
		if(Yii::app()->request->isPostRequest) {
			$model->attributes = Yii::app()->request->getPost('Company');
			if($model->save()){
				Yii::app()->user->setFlash('success' , '修改成功');
				//$this->redirect(array('printer/index' , 'companyId' => $this->companyId));
			}
		}
		$this->render('list' , array(
				'model'=>$model,
                                'printers'=>$printer
		));
	}
	public function actionDelete(){
		$companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId'));
		$ids = Yii::app()->request->getPost('ids');
		if(!empty($ids)) {
			foreach ($ids as $id) {
				$model = Printer::model()->find('lid=:id and dpid=:companyId' , array(':id' => $id , ':companyId' => $companyId)) ;
				if($model) {
					$model->saveAttributes(array('delete_flag'=>1));
				}
			}
			$this->redirect(array('printer/index' , 'companyId' => $companyId)) ;
		} else {
			Yii::app()->user->setFlash('error' , '请选择要删除的项目');
			$this->redirect(array('printer/index' , 'companyId' => $companyId)) ;
		}
	}
	public function actionRefresh(){
		$printers = Yii::app()->db->createCommand('select * from nb_printer where company_id=:companyId')
		->bindValue(':companyId',$this->companyId)
		->queryAll();
		
		$key = $this->companyId.'_printer';
		$list = new ARedisList($key);
		$list->clear();
		
		if(!empty($printers)) {
			foreach ($printers as $printer) {
				$list->unshift($printer['ip_address']);
			}
		}
		exit;
	}
	private function getPrinters(){
		$printers = Printer::model()->findAll('dpid=:companyId and delete_flag=0' , array(':companyId' => $this->companyId)) ;
		$printers = $printers ? $printers : array();
		return CHtml::listData($printers, 'lid', 'name');
	}
}