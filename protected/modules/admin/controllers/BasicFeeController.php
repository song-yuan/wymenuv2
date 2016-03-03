<?php
class BasicFeeController extends BackendController {
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
		$criteria->condition = 't.dpid='.$this->companyId ;
		$criteria->addCondition('t.delete_flag=0');
		$pages = new CPagination(CompanyBasicFee::model()->count($criteria));
		//	    $pages->setPageSize(1);
		$pages->applyLimit($criteria);
		$models = CompanyBasicFee::model()->findAll($criteria);
		$this->render('index',array(
				'models'=>$models,
				'pages'=>$pages,
				'companyId' => $companyId
		));
		
	}
	public function actionCreate(){
		$companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId'));
		
		$model = new CompanyBasicFee() ;
		$model->dpid = $companyId ;
		$is_sync = DataSync::getInitSync();
		if(Yii::app()->request->isPostRequest) {
			$model->attributes = Yii::app()->request->getPost('CompanyBasicFee');
			if($model->fee_type =="1"){
			$se=new Sequence("company_basic_fee");
                        $model->lid = $se->nextval();
						$model->create_at = date('Y-m-d H:i:s');
                        $model->update_at=date('Y-m-d H:i:s',time());
                        $model->delete_flag = "0";
                        $model->fee_name = "餐位费";
                        $model->is_sync = $is_sync;
//			var_dump($model->attributes);exit;
			}
			elseif($model->fee_type =="2"){
				$se=new Sequence("company_basic_fee");
				$model->lid = $se->nextval();
				$model->create_at = date('Y-m-d H:i:s');
				$model->update_at=date('Y-m-d H:i:s',time());
				$model->delete_flag = "0";
				$model->fee_name = "打包费";
				$model->is_sync = $is_sync;
				//			var_dump($model->attributes);exit;
			}
			elseif($model->fee_type =="3"){
				$se=new Sequence("company_basic_fee");
				$model->lid = $se->nextval();
				$model->create_at = date('Y-m-d H:i:s');
				$model->update_at=date('Y-m-d H:i:s',time());
				$model->delete_flag = "0";
				$model->fee_name = "送餐费";
				$model->is_sync = $is_sync;
				//			var_dump($model->attributes);exit;
			}elseif($model->fee_type =="4"){
				$se=new Sequence("company_basic_fee");
				$model->lid = $se->nextval();
				$model->create_at = date('Y-m-d H:i:s');
				$model->update_at=date('Y-m-d H:i:s',time());
				$model->delete_flag = "0";
				$model->fee_name = "外卖起步价";
				$model->is_sync = $is_sync;
				//			var_dump($model->attributes);exit;
			}
			if($model->save()){
				Yii::app()->user->setFlash('success' , yii::t('app','添加成功'));
				$this->redirect(array('basicFee/index' , 'companyId' => $companyId));
			}
		}
		$this->render('create' , array('model' => $model));
	}
	public function actionUpdate(){
		$id = Yii::app()->request->getParam('id');
		$is_sync = DataSync::getInitSync();
		$companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId'));
		$model = CompanyBasicFee::model()->find('lid=:id and dpid=:companyId' , array(':id' => $id , ':companyId' => $companyId));
		Until::isUpdateValid(array($id),$this->companyId,$this);//0,表示企业任何时候都在云端更新。
            if(Yii::app()->request->isPostRequest) {
					$model->attributes = Yii::app()->request->getPost('CompanyBasicFee');
                    $model->update_at=date('Y-m-d H:i:s',time());
                    $model->is_sync = $is_sync;
			if($model->save()){
				Yii::app()->user->setFlash('success' , yii::t('app','修改成功'));
				$this->redirect(array('basicFee/index' , 'companyId' => $companyId));
			}
		}
		$this->render('update' , array('model' => $model ));
	}
	public function actionDelete(){
		$companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId'));
		$ids = Yii::app()->request->getPost('ids');
		$is_sync = DataSync::getInitSync();
        //        Until::isUpdateValid($ids,$companyId,$this);//0,表示企业任何时候都在云端更新。
		if(!empty($ids)) {
			Yii::app()->db->createCommand('update nb_company_basic_fee set delete_flag="1", is_sync ='.$is_sync.' where lid in ('.implode(',' , $ids).') and dpid = :companyId')
			->execute(array( ':companyId' => $this->companyId));
			$this->redirect(array('basicFee/index' , 'companyId' => $companyId)) ;
		} else {
			Yii::app()->user->setFlash('error' , yii::t('app','请选择要删除的项目'));
			$this->redirect(array('basicFee/index' , 'companyId' => $companyId)) ;
		}
	}
}
