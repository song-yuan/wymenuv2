<?php
class PrinterController extends BackendController
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
		$criteria->condition =  't.dpid='.$this->companyId .' and delete_flag=0';
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
                        $se=new Sequence("printer");
                        $model->lid = $se->nextval();
                        $model->create_at = date('Y-m-d H:i:s',time());
                        $model->delete_flag = '0';
			if($model->save()) {
				Yii::app()->user->setFlash('success' , yii::t('app','添加成功'));
				$this->redirect(array('printer/index','companyId' => $this->companyId));
			}
		}
		$this->render('create' , array(
				'model' => $model 
		));
	}
	public function actionUpdate(){
		$lid = Yii::app()->request->getParam('lid');
                //echo 'ddd';
		$model = Printer::model()->find('lid=:lid and dpid=:dpid', array(':lid' => $lid,':dpid'=> $this->companyId));
		//var_dump($model);exit;
		if(Yii::app()->request->isPostRequest) {
			$model->attributes = Yii::app()->request->getPost('Printer');
                        //($model->attributes);var_dump(Yii::app()->request->getPost('Printer'));exit;
			if($model->save()){
				Yii::app()->user->setFlash('success' , yii::t('app','修改成功'));
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
				Yii::app()->user->setFlash('success' , yii::t('app','修改成功'));
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
                //var_dump($ids);exit;
		if(!empty($ids)) {
			foreach ($ids as $id) {
				$model = Printer::model()->find('lid=:id and dpid=:companyId' , array(':id' => $id , ':companyId' => $companyId)) ;
				if($model) {
					$model->saveAttributes(array('delete_flag'=>1));
				}
			}
			$this->redirect(array('printer/index' , 'companyId' => $companyId)) ;
		} else {
			Yii::app()->user->setFlash('error' , yii::t('app','请选择要删除的项目'));
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