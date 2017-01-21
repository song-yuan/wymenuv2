<?php
class CustomerFlowController extends BackendController
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
		$dpid = Yii::app()->request->getParam('companyId');
		
		$this->render('index',array(
		));
	}
	public function actionCreate(){
		if(Yii::app()->user->role != User::POWER_ADMIN) {
			$this->redirect(array('company/index','companyId'=>  $this->companyId));
		}
		$model = new Company();
		$model->create_at = date('Y-m-d H:i:s');
		if(Yii::app()->request->isPostRequest) {
			$model->attributes = Yii::app()->request->getPost('Company');
                        $model->create_at=date('Y-m-d H:i:s',time());
                        $model->update_at=date('Y-m-d H:i:s',time());
			if($model->save()){
				Yii::app()->user->setFlash('success',yii::t('app','创建成功'));
				$this->redirect(array('company/index','companyId'=> $this->companyId));
			} else {
				Yii::app()->user->setFlash('error',yii::t('app','创建失败'));
			}
		}
		$printers = $this->getPrinterList();
		return $this->render('create',array(
				'model' => $model,
				'printers'=>$printers,
                                'companyId'=>  $this->companyId
		));
	}
	public function actionUpdate(){
		$dpid = Helper::getCompanyId(Yii::app()->request->getParam('dpid'));
		$model = Company::model()->find('dpid=:companyId' , array(':companyId' => $dpid)) ;
		if(Yii::app()->request->isPostRequest) {
        //Until::isUpdateValid(array(0),$this->companyId,$this);//0,表示企业任何时候都在云端更新。
			$model->attributes = Yii::app()->request->getPost('Company');
                        $model->update_at=date('Y-m-d H:i:s',time());
			
			//var_dump($model->attributes);exit;
			if($model->save()){
				Yii::app()->user->setFlash('success',yii::t('app','修改成功'));
				$this->redirect(array('company/index','companyId'=>$this->companyId));
			} else {
				Yii::app()->user->setFlash('error',yii::t('app','修改失败'));
			}
		}
		$printers = $this->getPrinterList();
		return $this->render('update',array(
				'model'=>$model,
				'printers'=>$printers,
                                'companyId'=>$this->companyId
		));
	}
	public function actionDelete(){
		$ids = Yii::app()->request->getPost('companyIds');
        //Until::isUpdateValid(array(0),$this->companyId,$this);//0,表示企业任何时候都在云端更新。
		if(!empty($ids)) {
			Yii::app()->db->createCommand('update nb_company set delete_flag=1,update_at="'.date('Y-m-d H:i:s',time()).'" where dpid in ('.implode(',' , $ids).')')
			->execute();
			
		}
		$this->redirect(array('company/index','companyId'=>$this->companyId));
	}
	private function getPrinterList(){
		$printers = Printer::model()->findAll('dpid=:dpid',array(':dpid'=>$this->companyId)) ;
		return CHtml::listData($printers, 'printer_id', 'name');
	}
	
}