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
		$companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId'));
		$criteria = new CDbCriteria;
		$criteria->condition = Yii::app()->user->role == User::POWER_ADMIN ? ' delete_flag=0 ' : ' delete_flag=0 and dpid='.Yii::app()->user->companyId ;
		
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
		$model->create_at = date('Y-m-d H:i:s');
		if(Yii::app()->request->isPostRequest) {
			$model->attributes = Yii::app()->request->getPost('Company');
			if($model->save()){
				Yii::app()->user->setFlash('success',yii::t('app','创建成功'));
				$this->redirect(array('company/index'));
			} else {
				Yii::app()->user->setFlash('error',yii::t('app','创建失败'));
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
		$model = Company::model()->find('dpid=:companyId' , array(':companyId' => $companyId)) ;
		if(Yii::app()->request->isPostRequest) {
			$model->attributes = Yii::app()->request->getPost('Company');
			//var_dump($model->attributes);exit;
			if($model->save()){
				Yii::app()->user->setFlash('success',yii::t('app','修改成功'));
				$this->redirect(array('company/index'));
			} else {
				Yii::app()->user->setFlash('error',yii::t('app','修改失败'));
			}
		}
		$printers = $this->getPrinterList();
		return $this->render('update',array(
				'model'=>$model,
				'printers'=>$printers
		));
	}
	public function actionDelete(){
		$ids = Yii::app()->request->getPost('companyIds');
                
		if(!empty($ids)) {
			Yii::app()->db->createCommand('update nb_company set delete_flag=1 where dpid in ('.implode(',' , $ids).')')
			->execute();
			
		}
		$this->redirect(array('company/index'));
	}
	private function getPrinterList(){
		$printers = Printer::model()->findAll('dpid=:dpid',array(':dpid'=>$this->companyId)) ;
		return CHtml::listData($printers, 'printer_id', 'name');
	}
	
}