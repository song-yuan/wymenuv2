<?php
class WxcashbackController extends BackendController
{
	public function beforeAction($action) {
		parent::beforeAction($action);
		if(!$this->companyId) {
			Yii::app()->user->setFlash('error' ,yii::t('app','请选择公司'));
			$this->redirect(array('company/index'));
		}
		return true;
	}
	public function actionIndex() {
		$criteria = new CDbCriteria;
		$criteria->addCondition('t.dpid=:dpid and t.delete_flag=0');
		$criteria->order = ' t.lid desc ';
		$criteria->params[':dpid']=$this->companyId;
		
		$pages = new CPagination(ConsumerCashProportion::model()->count($criteria));
		//$pages->setPageSize(1);
		$pages->applyLimit($criteria);
		$models = ConsumerCashProportion::model()->findAll($criteria);
		
		$this->render('index',array(
				'models'=>$models,
				'pages' => $pages
		));
	}
	public function actionCreate() {
		$model = new ConsumerCashProportion ;
		$model->dpid = $this->companyId ;
		$is_sync = DataSync::getInitSync();
		
		if(Yii::app()->request->isPostRequest && Yii::app()->user->role <= User::SHOPKEEPER) {
			$dateType = (int)($_POST['date_info_type']);
			if($dateType==1){
				$beginDate = $_POST['begin_timestamp'];
				$endDate = $_POST['end_timestamp'];
// 				$begin = str_replace('.','-',$_POST['begin_timestamp']);
// 				$beginDate = strtotime($begin);
// 				$end = str_replace('.','-',$_POST['end_timestamp']);
// 				$endDate = strtotime($end);
				
				$model->attributes = Yii::app()->request->getPost('ConsumerCashProportion');
				$se=new Sequence("brand_user_level");
				$model->lid = $se->nextval();
				$model->create_at=date('Y-m-d H:i:s',time());
				$model->update_at=date('Y-m-d H:i:s',time());
				$model->date_info_type=$dateType;
				$model->begin_timestamp=$beginDate;
				$model->end_timestamp=$endDate;
				$model->delete_flag = '0';
				$model->is_sync=$is_sync;
			}elseif($dateType==2){
				$beginDate = (int)$_POST['fixed_term'];
				$endDate = (int)$_POST['fixed_begin_term'];
				
				$model->attributes = Yii::app()->request->getPost('ConsumerCashProportion');
				$se=new Sequence("brand_user_level");
				$model->lid = $se->nextval();
				$model->create_at=date('Y-m-d H:i:s',time());
				$model->update_at=date('Y-m-d H:i:s',time());
				$model->date_info_type=$dateType;
				$model->fixed_term=$beginDate;
				$model->fixed_begin_term=$endDate;
				$model->delete_flag = '0';
				$model->is_sync=$is_sync;
			}
			//$end = $beginDate.'+'.$endDate;
			//var_dump($end);exit;
			
                        //var_dump($model);exit;
			if($model->save()) {
				Yii::app()->user->setFlash('success' , yii::t('app','添加成功'));
				$this->redirect(array('wxcashback/index' , 'companyId' => $this->companyId));
			}
		}else{Yii::app()->user->setFlash('error' , yii::t('app','无权限'));}
		$this->render('create' , array(
				'model' => $model
		));
	}
	public function actionUpdate(){
		$lid = Yii::app()->request->getParam('lid');
		$model = ConsumerCashProportion::model()->find('lid=:lid and dpid=:dpid', array(':lid' => $lid,':dpid'=>  $this->companyId));
		//Until::isUpdateValid(array($lid),$this->companyId,$this);//0,表示企业任何时候都在云端更新。			
		$is_sync = DataSync::getInitSync();
		
		if(Yii::app()->request->isPostRequest && Yii::app()->user->role <= User::SHOPKEEPER) {
			$dateType = (int)($_POST['date_info_type']);
			if($dateType==1){
				$beginDate = $_POST['begin_timestamp'];
				$endDate = $_POST['end_timestamp'];
				$model->attributes = Yii::app()->request->getPost('ConsumerCashProportion');
				$model->update_at=date('Y-m-d H:i:s',time());
				$model->date_info_type=$dateType;
				$model->begin_timestamp=$beginDate;
				$model->end_timestamp=$endDate;
				$model->fixed_term="0";
				$model->fixed_begin_term="0";
				$model->is_sync=$is_sync;
				if($model->save()){
					Yii::app()->user->setFlash('success' , yii::t('app','修改成功'));
					$this->redirect(array('wxcashback/index' , 'companyId' => $this->companyId));
				}
			}elseif($dateType==2){
				$beginDate = (int)$_POST['fixed_term'];
				$endDate = (int)$_POST['fixed_begin_term'];
			
				$model->attributes = Yii::app()->request->getPost('ConsumerCashProportion');
				$model->update_at=date('Y-m-d H:i:s',time());
				$model->date_info_type=$dateType;
				$model->fixed_term=$beginDate;
				$model->fixed_begin_term=$endDate;
				$model->begin_timestamp="0";
				$model->end_timestamp="0";
				$model->is_sync=$is_sync;
				if($model->save()){
					Yii::app()->user->setFlash('success' , yii::t('app','修改成功'));
					$this->redirect(array('wxcashback/index' , 'companyId' => $this->companyId));
				}
			}
			//$model->attributes = Yii::app()->request->getPost('ConsumerCashProportion');
			//$model->update_at=date('Y-m-d H:i:s',time());
                        //var_dump($model->attributes);exit;
			
		}else{Yii::app()->user->setFlash('error' , yii::t('app','无权限'));}
		$this->render('update' , array(
			'model'=>$model
		));
	}
	public function actionDelete(){
		if(Yii::app()->user->role > User::SHOPKEEPER) {
			Yii::app()->user->setFlash('error' , yii::t('app','你没有权限'));
			$this->redirect(array('wxcashback/index' , 'companyId' => $this->companyId)) ;
		}
		$companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId'));
		$ids = Yii::app()->request->getPost('lid');
		//Until::isUpdateValid($ids,$this->companyId,$this);//0,表示企业任何时候都在云端更新。			
                if(!empty($ids)) {
			foreach ($ids as $id) {
				$model = ConsumerCashProportion::model()->find('lid=:id and dpid=:companyId' , array(':id' => $id , ':companyId' => $companyId)) ;
				if($model) {
					$model->saveAttributes(array('delete_flag'=>1,'update_at'=>date('Y-m-d H:i:s',time())));
				}
			}
			$this->redirect(array('wxcashback/index' , 'companyId' => $companyId)) ;
		} else {
			Yii::app()->user->setFlash('error' , yii::t('app','请选择要删除的项目'));
			$this->redirect(array('wxcashback/index' , 'companyId' => $companyId)) ;
		}
	}
	
}