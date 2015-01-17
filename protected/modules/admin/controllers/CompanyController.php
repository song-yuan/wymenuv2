<?php
class CompanyController extends BackendController
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
	public function actionIndex(){
		$criteria = new CDbCriteria;
		$criteria->condition = Yii::app()->user->role == User::POWER_ADMIN ? : 'company_id='.Yii::app()->user->companyId ;
		
		$pages = new CPagination(Company::model()->count($criteria));
		//	    $pages->setPageSize(1);
		$pages->applyLimit($criteria);
		$models = Company::model()->findAll($criteria);
		
		$this->render('index',array(
				'models'=> $models,
				'pages'=>$pages,
		));
	}
	public function actionCreate(){
		if(Yii::app()->user->role != User::POWER_ADMIN) {
			$this->redirect(array('company/index'));
		}
		$model = new Company();
		$model->create_time = time();
		if(Yii::app()->request->isPostRequest) {
			$model->attributes = Yii::app()->request->getPost('Company');
			if($model->save()){
				Yii::app()->user->setFlash('success','创建成功');
				$this->redirect(array('company/index'));
			} else {
				Yii::app()->user->setFlash('error','创建失败');
			}
		}
		$printers = $this->getPrinterList();
		return $this->render('create',array(
				'model' => $model,
				'printers'=>$printers
		));
	}
	public function actionUpdate(){
		$companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId'));
		$model = Company::model()->find('company_id=:companyId' , array(':companyId' => $companyId)) ;
		if(Yii::app()->request->isPostRequest) {
			$model->attributes = Yii::app()->request->getPost('Company');
			//var_dump($model->attributes);exit;
			if($model->save()){
				Yii::app()->user->setFlash('success','修改成功');
				$this->redirect(array('company/index'));
			} else {
				Yii::app()->user->setFlash('error','修改失败');
			}
		}
		$printers = $this->getPrinterList();
		return $this->render('update',array(
				'model'=>$model,
				'printers'=>$printers
		));
	}
	public function actionFreaze(){
		
	}
	private function getPrinterList(){
		$printers = Printer::model()->findAll('company_id=:companyId',array(':companyId'=>$this->companyId)) ;
		return CHtml::listData($printers, 'printer_id', 'name');
	}
	
}