<?php
class PayMethodController extends BackendController {
	public function beforeAction($action) {
		parent::beforeAction($action);
		if(!$this->companyId && $this->getAction()->getId() != 'upload') {
			Yii::app()->user->setFlash('error' , yii::t('app','请选择公司'));
			$this->redirect(array('company/index'));
		}
		return true;
	}
	
	public function actionIndex() {
		$companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId'));
		$criteria = new CDbCriteria;
		//$criteria->with = 'company' ;
		//$criteria->condition = Yii::app()->user->role == User::POWER_ADMIN ? '' : 't.dpid='.Yii::app()->user->companyId ;
		$criteria->condition = 't.delete_flag = 0 and t.dpid='.$this->companyId ;
		$pages = new CPagination(PaymentMethod::model()->count($criteria));
		//	    $pages->setPageSize(1);
		$pages->applyLimit($criteria);
		$models = PaymentMethod::model()->findAll($criteria);
		$this->render('index',array(
				'models'=>$models,
				'pages'=>$pages,
				'companyId' => $companyId
		));
		
	}
	public function actionCreate(){
		$companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId'));
		
		$model = new PaymentMethod() ;
		$model->dpid = $companyId ;
		if(Yii::app()->request->isPostRequest) {
			$model->attributes = Yii::app()->request->getPost('PaymentMethod');
			$se=new Sequence("payment_method");
                        $model->lid = $se->nextval();
			$model->create_at = date('Y-m-d H:i:s');
                        $model->update_at=date('Y-m-d H:i:s',time());
//			var_dump($model->attributes);exit;
			if($model->save()){
				Yii::app()->user->setFlash('success' , yii::t('app','添加成功'));
				$this->redirect(array('payMethod/index' , 'companyId' => $companyId));
			}
		}
		$this->render('create' , array('model' => $model));
	}
	public function actionUpdate(){
		if(Yii::app()->user->role > User::SHOPKEEPER) {
			Yii::app()->user->setFlash('error' , yii::t('app','你没有权限'));
			$this->redirect(array('payMethod/index' , 'companyId' => $this->companyId)) ;
		}
		$id = Yii::app()->request->getParam('id');
		$companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId'));
		$model = PaymentMethod::model()->find('lid=:id and dpid=:companyId' , array(':id' => $id , ':companyId' => $companyId));
		//Until::isUpdateValid(array($id),$this->companyId,$this);//0,表示企业任何时候都在云端更新。
                if(Yii::app()->request->isPostRequest) {
			$model->attributes = Yii::app()->request->getPost('PaymentMethod');
                        $model->update_at=date('Y-m-d H:i:s',time());
			if($model->save()){
				Yii::app()->user->setFlash('success' , yii::t('app','修改成功'));
				$this->redirect(array('payMethod/index' , 'companyId' => $companyId));
			}
		}
		$this->render('update' , array('model' => $model ));
	}
	public function actionDelete(){
		if(Yii::app()->user->role > User::SHOPKEEPER) {
			Yii::app()->user->setFlash('error' , yii::t('app','你没有权限'));
			$this->redirect(array('payMethod/index' , 'companyId' => $this->companyId)) ;
		}
		$companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId'));
		$ids = Yii::app()->request->getPost('ids');
        //Until::isUpdateValid($ids,$companyId,$this);//0,表示企业任何时候都在云端更新。
		if(!empty($ids)) {
				foreach ($ids as $id) {
					$model = PaymentMethod::model()->find('lid=:id and dpid=:companyId' , array(':id' => $id , ':companyId' => $companyId)) ;
					if($model) {
						$model->delete_flag = 1;
						$model->update();
					}
				}
				$this->redirect(array('payMethod/index' , 'companyId' => $companyId)) ;
		} else {
			Yii::app()->user->setFlash('error' , yii::t('app','请选择要删除的项目'));
			$this->redirect(array('payMethod/index' , 'companyId' => $companyId)) ;
		}
	}
}
