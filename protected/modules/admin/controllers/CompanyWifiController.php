<?php
class CompanyWifiController extends BackendController
{
    
        public function beforeAction($action) {
		parent::beforeAction($action);
		if(!$this->companyId && $this->getAction()->getId() != 'upload') {
			Yii::app()->user->setFlash('error' , yii::t('app','请选择公司'));
			$this->redirect(array('company/index'));
		}
		return true;
	}
    
	public function actionIndex(){
		$companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId'));
		$criteria = new CDbCriteria;
		//$criteria->with = 'company' ;
		//$criteria->condition = Yii::app()->user->role == User::POWER_ADMIN ? '' : 't.dpid='.Yii::app()->user->companyId ;
		$criteria->condition = 't.dpid='.$this->companyId ;
		
		$pages = new CPagination(CompanyWifi::model()->count($criteria));
		//	    $pages->setPageSize(1);
		$pages->applyLimit($criteria);
		$models = CompanyWifi::model()->findAll($criteria);
		
                $this->render('index',array(
				'models'=>$models,
				'pages'=>$pages,
				'companyId' => $companyId
		));
	}
	public function actionCreate(){
		$companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId'));
		
		$model = new CompanyWifi() ;
		$model->dpid = $companyId ;
		if(Yii::app()->request->isPostRequest) {
			$model->attributes = Yii::app()->request->getPost('CompanyWifi');
			$se=new Sequence("company_wifi");
                        $model->lid = $se->nextval();
			$model->create_at = date('Y-m-d H:i:s');
                        $model->update_at = date('Y-m-d H:i:s');
			if($model->save()){
				Yii::app()->user->setFlash('success' , yii::t('app','添加成功'));
				$this->redirect(array('companyWifi/index' , 'companyId' => $companyId));
			}
		}
		$this->render('create' , array('model' => $model));
	}
	public function actionUpdate(){
		$id = Yii::app()->request->getParam('id');
        //Until::isUpdateValid(array($id),$this->companyId,$this);
		$companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId'));
		$model = CompanyWifi::model()->find('lid=:id and dpid=:companyId' , array(':id' => $id , ':companyId' => $companyId));
		if(Yii::app()->request->isPostRequest) {
			$model->attributes = Yii::app()->request->getPost('CompanyWifi');
                        $model->update_at = date('Y-m-d H:i:s');
			if($model->save()){
				Yii::app()->user->setFlash('success' , yii::t('app','修改成功'));
				$this->redirect(array('companyWifi/index' , 'companyId' => $companyId));
			}
		}
		$this->render('update' , array('model' => $model ));
	}
	public function actionDelete(){
		$companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId'));
		$ids = Yii::app()->request->getPost('ids');
        //Until::isUpdateValid($ids,$this->companyId,$this);
		if(!empty($ids)) {
                        foreach ($ids as $id) {
                                $model = CompanyWifi::model()->find('lid=:id and dpid=:companyId' , array(':id' => $id , ':companyId' => $companyId)) ;
                                if($model) {
                                        $model->update_at = date('Y-m-d H:i:s');
                                        $model->delete();
                                }
                        }
                        $this->redirect(array('companyWifi/index' , 'companyId' => $companyId)) ;
		} else {
			Yii::app()->user->setFlash('error' , yii::t('app','请选择要删除的项目'));
			$this->redirect(array('companyWifi/index' , 'companyId' => $companyId)) ;
		}
	}
	
}